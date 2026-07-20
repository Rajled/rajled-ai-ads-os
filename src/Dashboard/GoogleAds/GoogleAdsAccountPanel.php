<?php
/**
 * Google Ads account dashboard panel.
 *
 * @package Rajled\AiAdsOs\Dashboard\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Dashboard\GoogleAds;

use Rajled\AiAdsOs\Application\Account\AccountCandidate;
use Rajled\AiAdsOs\Application\Account\AccountCatalogInterface;
use Rajled\AiAdsOs\Application\Account\AccountSelectionService;
use Rajled\AiAdsOs\Application\Account\ActiveAccountStoreInterface;
use Rajled\AiAdsOs\Application\Account\Exception\ActiveAccountStorageException;
use Rajled\AiAdsOs\Application\Account\Exception\InvalidAccountSelectionException;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Identifier;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsSdkException;
use Rajled\AiAdsOs\Logging\Logger;
use Throwable;

/**
 * Handles explicit account discovery and active-account selection presentation.
 */
final class GoogleAdsAccountPanel
{
    private const ACCOUNT_DISCOVERY_ACTION = 'load_google_ads_accessible_accounts';

    private const ACCOUNT_DISCOVERY_NONCE_ACTION = 'rajled_google_ads_account_discovery';

    private const ACCOUNT_DISCOVERY_NONCE_NAME = 'rajled_google_ads_account_discovery_nonce';

    private const ACCOUNT_SELECTION_ACTION = 'select_google_ads_active_account';

    private const ACCOUNT_SELECTION_NONCE_ACTION = 'rajled_google_ads_account_selection';

    private const ACCOUNT_SELECTION_NONCE_NAME = 'rajled_google_ads_account_selection_nonce';

    private AccountCatalogInterface $accountCatalog;

    private ActiveAccountStoreInterface $activeAccountStore;

    private AccountSelectionService $accountSelectionService;

    private Logger $logger;

    public function __construct(
        AccountCatalogInterface $accountCatalog,
        ActiveAccountStoreInterface $activeAccountStore,
        AccountSelectionService $accountSelectionService,
        Logger $logger
    ) {
        $this->accountCatalog = $accountCatalog;
        $this->activeAccountStore = $activeAccountStore;
        $this->accountSelectionService = $accountSelectionService;
        $this->logger = $logger;
    }

    public function render(string $formAction, bool $handleRequest): void
    {
        $activeAccountResult = $this->loadActiveAccount();
        $activeAccount = $activeAccountResult['account'];
        $accountDiscoveryResult = $handleRequest
            ? $this->handleAccountDiscoveryRequest()
            : null;
        $accountSelectionResult = $handleRequest && null === $accountDiscoveryResult
            ? $this->handleAccountSelectionRequest()
            : null;

        if (
            is_array($accountSelectionResult)
            && true === $accountSelectionResult['success']
            && $accountSelectionResult['account'] instanceof AccountCandidate
        ) {
            $activeAccount = $accountSelectionResult['account'];
        }
        ?>
        <h2><?php echo esc_html__('Accessible Google Ads Accounts', 'rajled-ai-ads-os'); ?></h2>
        <p>
            <?php
            echo esc_html__(
                'Load directly accessible accounts and their managed account hierarchy.',
                'rajled-ai-ads-os'
            );
            ?>
        </p>

        <?php if ($activeAccountResult['failed']) : ?>
            <div class="notice notice-error inline">
                <p>
                    <?php
                    echo esc_html__(
                        'The active Google Ads account could not be loaded.',
                        'rajled-ai-ads-os'
                    );
                    ?>
                </p>
            </div>
        <?php endif; ?>

        <?php $this->renderActiveAccount($activeAccount); ?>

        <?php
        if (is_array($accountSelectionResult)) {
            $this->renderAccountSelectionResult($accountSelectionResult);
        }

        if (is_array($accountDiscoveryResult)) {
            $this->renderAccessibleAccounts(
                $accountDiscoveryResult,
                $activeAccount,
                $formAction
            );
        } elseif (false === $accountDiscoveryResult) {
            $this->renderAccessibleAccounts(null, $activeAccount, $formAction);
        }
        ?>

        <form method="post" action="<?php echo esc_url($formAction); ?>">
            <?php
            wp_nonce_field(
                self::ACCOUNT_DISCOVERY_NONCE_ACTION,
                self::ACCOUNT_DISCOVERY_NONCE_NAME
            );
            ?>
            <input
                type="hidden"
                name="rajled_google_ads_account_discovery_action"
                value="<?php echo esc_attr(self::ACCOUNT_DISCOVERY_ACTION); ?>"
            >
            <?php
            submit_button(
                esc_html__('Load accessible accounts', 'rajled-ai-ads-os'),
                'secondary',
                'submit',
                false
            );
            ?>
        </form>
        <?php
    }

    /**
     * @return list<AccountCandidate>|false|null
     */
    private function handleAccountDiscoveryRequest(): array|false|null
    {
        if (! current_user_can('manage_options')) {
            return null;
        }

        if ('POST' !== $this->getRequestMethod()) {
            return null;
        }

        $submittedAction = isset($_POST['rajled_google_ads_account_discovery_action'])
            && is_string($_POST['rajled_google_ads_account_discovery_action'])
            ? sanitize_key(wp_unslash($_POST['rajled_google_ads_account_discovery_action']))
            : '';

        if (self::ACCOUNT_DISCOVERY_ACTION !== $submittedAction) {
            return null;
        }

        check_admin_referer(
            self::ACCOUNT_DISCOVERY_NONCE_ACTION,
            self::ACCOUNT_DISCOVERY_NONCE_NAME
        );

        try {
            return $this->accountCatalog->discover();
        } catch (GoogleAdsSdkException $exception) {
            $this->logFailure(
                'Google Ads account discovery failed.',
                'google_ads_account_discovery',
                'google_ads_discovery_failure',
                $exception
            );

            return false;
        } catch (Throwable $exception) {
            $this->logFailure(
                'Google Ads account discovery failed.',
                'google_ads_account_discovery',
                'unexpected_application_failure',
                $exception
            );

            return false;
        }
    }

    /**
     * @return array{success: bool, message: string, account: AccountCandidate|null}|null
     */
    private function handleAccountSelectionRequest(): ?array
    {
        if (! current_user_can('manage_options')) {
            return null;
        }

        if ('POST' !== $this->getRequestMethod()) {
            return null;
        }

        $submittedAction = isset($_POST['rajled_google_ads_account_selection_action'])
            && is_string($_POST['rajled_google_ads_account_selection_action'])
            ? sanitize_key(wp_unslash($_POST['rajled_google_ads_account_selection_action']))
            : '';

        if (self::ACCOUNT_SELECTION_ACTION !== $submittedAction) {
            return null;
        }

        check_admin_referer(
            self::ACCOUNT_SELECTION_NONCE_ACTION,
            self::ACCOUNT_SELECTION_NONCE_NAME
        );

        $customerId = isset($_POST['rajled_google_ads_customer_id'])
            && is_string($_POST['rajled_google_ads_customer_id'])
            ? trim(wp_unslash($_POST['rajled_google_ads_customer_id']))
            : '';

        if (1 !== preg_match('/\A[0-9]{10}\z/', $customerId)) {
            $this->logger->warning(
                'Google Ads account selection was rejected.',
                array(
                    'operation'        => 'google_ads_account_selection',
                    'outcome'          => 'rejected',
                    'failure_category' => 'invalid_submitted_identifier',
                )
            );

            return $this->selectionFailureResult();
        }

        try {
            $account = $this->accountSelectionService->select(
                new Identifier($customerId)
            );

            return array(
                'success' => true,
                'message' => 'The active Google Ads account was updated.',
                'account' => $account,
            );
        } catch (InvalidAccountSelectionException $exception) {
            $this->logFailure(
                'Google Ads account selection was rejected.',
                'google_ads_account_selection',
                'invalid_account_selection',
                $exception,
                true
            );
        } catch (GoogleAdsSdkException $exception) {
            $this->logFailure(
                'Google Ads account selection failed.',
                'google_ads_account_selection',
                'google_ads_discovery_failure',
                $exception
            );
        } catch (ActiveAccountStorageException $exception) {
            $this->logFailure(
                'Google Ads account selection failed.',
                'google_ads_account_selection',
                'persistence_failure',
                $exception
            );
        } catch (Throwable $exception) {
            $this->logFailure(
                'Google Ads account selection failed.',
                'google_ads_account_selection',
                'unexpected_application_failure',
                $exception
            );
        }

        return $this->selectionFailureResult();
    }

    /**
     * @return array{account: AccountCandidate|null, failed: bool}
     */
    private function loadActiveAccount(): array
    {
        try {
            return array(
                'account' => $this->activeAccountStore->get(),
                'failed'  => false,
            );
        } catch (Throwable $exception) {
            $this->logFailure(
                'Google Ads active account loading failed.',
                'google_ads_active_account_load',
                'active_account_store_failure',
                $exception
            );

            return array(
                'account' => null,
                'failed'  => true,
            );
        }
    }

    private function renderActiveAccount(?AccountCandidate $activeAccount): void
    {
        ?>
        <h3><?php echo esc_html__('Active Google Ads Account', 'rajled-ai-ads-os'); ?></h3>
        <?php if (null === $activeAccount) : ?>
            <p><?php echo esc_html__('No active account is selected.', 'rajled-ai-ads-os'); ?></p>
        <?php else : ?>
            <table class="widefat striped" style="max-width: 720px; margin-bottom: 16px;">
                <tbody>
                    <tr>
                        <th scope="row"><?php echo esc_html__('Account Name', 'rajled-ai-ads-os'); ?></th>
                        <td><?php echo esc_html($this->getAccountDisplayName($activeAccount)); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__('Customer ID', 'rajled-ai-ads-os'); ?></th>
                        <td>
                            <?php
                            echo esc_html(
                                $this->formatCustomerId(
                                    $activeAccount->getIdentifier()->getValue()
                                )
                            );
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
        <?php
    }

    /**
     * @param array{success: bool, message: string, account: AccountCandidate|null} $result
     */
    private function renderAccountSelectionResult(array $result): void
    {
        $noticeClass = $result['success']
            ? 'notice notice-success inline'
            : 'notice notice-error inline';
        ?>
        <div class="<?php echo esc_attr($noticeClass); ?>">
            <p><?php echo esc_html($result['message']); ?></p>
        </div>
        <?php
    }

    /**
     * @param list<AccountCandidate>|null $accounts
     */
    private function renderAccessibleAccounts(
        ?array $accounts,
        ?AccountCandidate $activeAccount,
        string $formAction
    ): void {
        ?>
        <table class="widefat striped" style="max-width: 720px;">
            <thead>
                <tr>
                    <th scope="col"><?php echo esc_html__('Account Name', 'rajled-ai-ads-os'); ?></th>
                    <th scope="col"><?php echo esc_html__('Customer ID', 'rajled-ai-ads-os'); ?></th>
                    <th scope="col"><?php echo esc_html__('Resource Name', 'rajled-ai-ads-os'); ?></th>
                    <th scope="col"><?php echo esc_html__('Account Type', 'rajled-ai-ads-os'); ?></th>
                    <th scope="col"><?php echo esc_html__('Selection', 'rajled-ai-ads-os'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (null === $accounts) : ?>
                    <tr>
                        <td colspan="5">
                            <?php
                            echo esc_html__(
                                'Accessible Google Ads accounts could not be loaded.',
                                'rajled-ai-ads-os'
                            );
                            ?>
                        </td>
                    </tr>
                <?php elseif (array() === $accounts) : ?>
                    <tr>
                        <td colspan="5">
                            <?php
                            echo esc_html__(
                                'No accessible Google Ads accounts were found.',
                                'rajled-ai-ads-os'
                            );
                            ?>
                        </td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($accounts as $account) : ?>
                        <tr>
                            <td><?php echo esc_html($this->getAccountDisplayName($account)); ?></td>
                            <td>
                                <?php
                                echo esc_html(
                                    $this->formatCustomerId(
                                        $account->getIdentifier()->getValue()
                                    )
                                );
                                ?>
                            </td>
                            <td><?php echo esc_html($account->getResourceName()->getValue()); ?></td>
                            <td>
                                <?php
                                echo esc_html(
                                    $account->canManageAccounts()
                                        ? __('Manager', 'rajled-ai-ads-os')
                                        : __('Client', 'rajled-ai-ads-os')
                                );
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($account->canManageAccounts()) {
                                    echo esc_html__(
                                        'Manager accounts cannot be selected.',
                                        'rajled-ai-ads-os'
                                    );
                                } elseif ($this->isActiveAccount($account, $activeAccount)) {
                                    echo esc_html__('Active account', 'rajled-ai-ads-os');
                                } else {
                                    $this->renderAccountSelectionForm($account, $formAction);
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }

    private function renderAccountSelectionForm(
        AccountCandidate $account,
        string $formAction
    ): void {
        ?>
        <form method="post" action="<?php echo esc_url($formAction); ?>">
            <?php
            wp_nonce_field(
                self::ACCOUNT_SELECTION_NONCE_ACTION,
                self::ACCOUNT_SELECTION_NONCE_NAME
            );
            ?>
            <input
                type="hidden"
                name="rajled_google_ads_account_selection_action"
                value="<?php echo esc_attr(self::ACCOUNT_SELECTION_ACTION); ?>"
            >
            <input
                type="hidden"
                name="rajled_google_ads_customer_id"
                value="<?php echo esc_attr($account->getIdentifier()->getValue()); ?>"
            >
            <?php
            submit_button(
                esc_html__('Select', 'rajled-ai-ads-os'),
                'secondary small',
                'submit',
                false
            );
            ?>
        </form>
        <?php
    }

    private function isActiveAccount(
        AccountCandidate $account,
        ?AccountCandidate $activeAccount
    ): bool {
        return null !== $activeAccount
            && $account->getIdentifier()->getValue()
                === $activeAccount->getIdentifier()->getValue();
    }

    private function getAccountDisplayName(AccountCandidate $account): string
    {
        return $account->getDisplayName()
            ?? __('Unnamed account', 'rajled-ai-ads-os');
    }

    private function formatCustomerId(string $customerId): string
    {
        if (1 !== preg_match('/\A[0-9]{10}\z/', $customerId)) {
            return $customerId;
        }

        return substr($customerId, 0, 3)
            . '-'
            . substr($customerId, 3, 3)
            . '-'
            . substr($customerId, 6, 4);
    }

    private function getRequestMethod(): string
    {
        return isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD'])
            ? strtoupper(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])))
            : '';
    }

    /**
     * @return array{success: bool, message: string, account: null}
     */
    private function selectionFailureResult(): array
    {
        return array(
            'success' => false,
            'message' => 'The Google Ads account could not be selected.',
            'account' => null,
        );
    }

    private function logFailure(
        string $message,
        string $operation,
        string $failureCategory,
        Throwable $exception,
        bool $warning = false
    ): void {
        $context = array(
            'operation'        => $operation,
            'outcome'          => 'failed',
            'failure_category' => $failureCategory,
            'exception_class'  => get_class($exception),
        );

        if ($warning) {
            $this->logger->warning($message, $context);

            return;
        }

        $this->logger->error($message, $context);
    }
}
