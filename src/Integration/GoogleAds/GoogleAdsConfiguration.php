<?php
/**
 * Google Ads configuration.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

use Rajled\AiAdsOs\Config\ConfigurationManager;

/**
 * Reads Google Ads integration settings from application configuration.
 */
final class GoogleAdsConfiguration
{
    public const CONFIG_KEY = 'google_ads';

    public const DEVELOPER_TOKEN = 'developer_token';

    public const CLIENT_ID = 'client_id';

    public const CLIENT_SECRET = 'client_secret';

    public const REFRESH_TOKEN = 'refresh_token';

    public const LOGIN_CUSTOMER_ID = 'login_customer_id';

    private const REQUIRED_FIELDS = array(
        self::DEVELOPER_TOKEN,
        self::CLIENT_ID,
        self::CLIENT_SECRET,
        self::REFRESH_TOKEN,
    );

    /**
     * @var array<string, mixed>
     */
    private array $values;

    /**
     * @param array<string, mixed> $values Google Ads configuration values.
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function fromConfigurationManager(ConfigurationManager $configuration): self
    {
        $values = $configuration->get(self::CONFIG_KEY, array());

        if (! is_array($values)) {
            $values = array();
        }

        return new self($values);
    }

    public function getDeveloperToken(): string
    {
        return $this->getString(self::DEVELOPER_TOKEN);
    }

    public function getClientId(): string
    {
        return $this->getString(self::CLIENT_ID);
    }

    public function getClientSecret(): string
    {
        return $this->getString(self::CLIENT_SECRET);
    }

    public function getRefreshToken(): string
    {
        return $this->getString(self::REFRESH_TOKEN);
    }

    public function getLoginCustomerId(): string
    {
        return $this->getString(self::LOGIN_CUSTOMER_ID);
    }

    public function hasRequiredValues(): bool
    {
        return array() === $this->getMissingRequiredFields();
    }

    /**
     * Return missing required Google Ads configuration fields.
     *
     * @return array<int, string>
     */
    public function getMissingRequiredFields(): array
    {
        $missingFields = array();

        foreach (self::REQUIRED_FIELDS as $field) {
            if (! $this->hasValue($field)) {
                $missingFields[] = $field;
            }
        }

        return $missingFields;
    }

    public function hasValue(string $key): bool
    {
        return '' !== $this->getString($key);
    }

    public function getValue(string $key): string
    {
        return $this->getString($key);
    }

    private function getString(string $key): string
    {
        $value = $this->values[$key] ?? '';

        if (! is_scalar($value)) {
            return '';
        }

        return trim((string) $value);
    }
}
