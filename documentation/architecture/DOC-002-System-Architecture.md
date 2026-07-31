# DOC-002 - System Architecture

**Project:** RajLED AI Ads OS

**Status:** Active

**Implemented Through:** Sprint S1-007

---

## 1. Purpose

This document defines the implemented system architecture of RajLED AI Ads OS through Sprint S1-007 and distinguishes it from the platform's planned future architecture.

RajLED AI Ads OS is an advertising operations platform. It is designed to acquire provider data, translate that data into stable internal models, and support future advertising decision and execution capabilities without making the business architecture dependent on a provider or runtime host.

## 2. Scope

This document covers:

- the current Core, Application, Domain, Integration, Infrastructure, and Presentation responsibilities;
- the Google Ads SDK and account-discovery boundaries;
- WordPress runtime composition and persistence;
- dependency direction, error recovery, and security boundaries;
- extension points for future providers.

Campaign retrieval, metrics retrieval, business engines, recommendations, mutations, scheduling, and automated optimization remain planned unless explicitly identified as implemented.

## 3. Architectural Vision

The platform uses provider-neutral business and application models surrounded by provider-specific integration adapters.

Google Ads is the first external provider. It establishes the integration pattern but does not define the platform's business language.

The long-term engine architecture remains the product direction. Current implementation provides the foundation and first account workflow; it does not claim that planned engines are complete.

## 4. Platform versus WordPress Runtime

WordPress is the current runtime host and presentation adapter.

WordPress provides:

- plugin lifecycle and bootstrap;
- administrator capability and nonce enforcement;
- the administration dashboard;
- option-based active-account persistence;
- REST registration for existing health functionality.

WordPress is not the business architecture of the platform. Domain and Application concepts are designed independently from WordPress and can be hosted by another adapter in the future.

## 5. High-Level System Structure

```text
WordPress Runtime / Presentation
        |
        v
Application Contracts and Services
        |
        v
Provider-Neutral Domain Values

Google Ads API
        |
        v
Google Ads SDK Boundary
        |
        v
Google Ads Integration DTOs and Mappers
        |
        v
Application Models
```

Core composes these parts through the service container and provider registry.

## 6. Core Layer

The Core Layer owns application composition and lifecycle infrastructure.

Implemented components include:

- `Kernel`;
- `ServiceContainer`;
- `ServiceProviderInterface`;
- `ProviderRegistry`;
- `CoreServiceProvider`;
- `EventDispatcher`;
- `EngineRegistry`.

`Kernel::create()` registers core and Google Ads providers, then binds WordPress adapters to Application contracts. `Kernel::boot()` registers the REST and dashboard hooks once.

Core is a composition boundary. Provider and WordPress details must not become Domain rules.

## 7. Application Layer

The Application Layer coordinates provider-neutral use cases.

The implemented account workflow contains:

- `AccountCandidate`;
- `AccountDiscoveryResult`;
- `DiscoveryCompleteness`;
- `AccountCatalogInterface`;
- `AccountSelectionService`;
- `ActiveAccountStoreInterface`.

Application contracts remain provider-neutral. They express account discovery, selection, completeness, and persistence without referencing Google Ads SDK or WordPress types.

`AccountSelectionService` always requests fresh canonical discovery before persisting a selection. It rejects missing, ambiguous, and non-selectable manager candidates.

## 8. Domain Layer

The Domain Layer contains provider-independent advertising concepts and validation.

Currently implemented Domain models are:

- common Value Objects: `Identifier`, `ResourceName`, `Currency`, `Money`, `DateRange`, and `Status`;
- `Campaign` entity;
- immutable `Metrics` object.

Domain remains independent from Google Ads, Integration, WordPress, REST, and presentation concerns.

Account discovery is currently an Application concern. No provider-specific Domain account entity has been introduced.

## 9. Integration Layer

The Integration Layer owns communication with external providers and translation into provider-neutral models.

Implemented shared integration concepts include:

- integration registration and readiness;
- client and connection contracts;
- Campaign and Metrics mapping contracts.

Google Ads-specific configuration, credentials, DTOs, readers, mappers, and SDK adapters live under `src/Integration/GoogleAds/`.

External providers are translated into provider-neutral Application or Domain models before those models are consumed outside Integration.

## 10. Google Ads Integration

The Google Ads integration currently provides:

- configuration and required-credential validation;
- native SDK client construction;
- live read-only connectivity verification;
- directly accessible account discovery;
- account name and Manager/Client metadata discovery;
- manager hierarchy traversal;
- deterministic account deduplication;
- mapping into `AccountCandidate` objects;
- explicit partial-discovery handling.

Campaign and Metrics mappers accept normalized provider-neutral arrays, but production campaign and metrics readers are not implemented.

## 11. SDK Boundary

Official Google Ads SDK types remain inside:

```text
src/Integration/GoogleAds/Sdk/
```

This boundary contains native client creation, API requests, SDK response handling, and SDK failure classification.

`GoogleAdsSdkFactory` creates configured native clients. `GoogleAdsSdkClient` wraps the native client. Readers convert SDK responses into integration-owned objects before data crosses the boundary.

No Domain, Application contract, WordPress adapter, REST controller, or mapper contract exposes a native SDK type.

## 12. Presentation / WordPress Adapter

The WordPress dashboard is an administrator-facing presentation adapter.

It provides:

- system health display;
- an explicit Google Ads connectivity action;
- an explicit accessible-account discovery action;
- active-account selection and current-selection display.

Network actions require `manage_options`, a dedicated nonce, and a POST request. Ordinary dashboard GET requests do not construct a native client or call Google Ads.

Output is escaped and failures are reduced to bounded, sanitized information.

## 13. Dependency Direction

The intended dependency rules are:

```text
Domain <- Application
Application contracts <- Integration implementations
Application contracts <- Infrastructure implementations
Application services <- Presentation
Core -> composition of all runtime services
```

Important constraints:

- Domain depends on no provider or framework;
- Application does not depend on Google Ads SDK or WordPress;
- Integration may depend on Application contracts and Domain values to produce provider-neutral output;
- Infrastructure implements Application persistence contracts;
- Presentation receives composed services and does not create SDK clients;
- native SDK types do not leave the SDK boundary.

## 14. Service Registration and Lifecycle

`CoreServiceProvider` registers foundational services. `GoogleAdsServiceProvider` registers Google Ads configuration, credentials, SDK factories, discovery readers, mapping, connectivity, and catalog services.

The `Kernel` registers the WordPress `GoogleAdsActiveAccountStore` as the implementation of `ActiveAccountStoreInterface` and composes dashboard services.

Services are lazy where possible. The connectivity health check resolves its checker only after preflight succeeds, and dashboard network work occurs only after an authorized explicit action.

## 15. Google Ads Connectivity Flow

```text
wp-config.php constants
    -> plugin configuration bridge
    -> ConfigurationManager
    -> GoogleAdsConfiguration
    -> CredentialsManager
    -> GoogleAdsSdkFactory
    -> GoogleAdsSdkClient
    -> GoogleAdsConnectivityChecker
    -> ListAccessibleCustomers
```

The flow is read-only and returns only sanitized status plus the number of accessible resources. It does not expose resource names or customer identifiers in the health panel.

## 16. Account Discovery Flow

```text
GoogleAdsAccountDiscovery
    -> list directly accessible roots
GoogleAdsAccountDetailsReader
    -> query each root with a root-scoped client
    -> traverse manager hierarchy when applicable
GoogleAdsAccountDetailsDiscoveryResult
    -> GoogleAdsAccountCatalog
    -> deterministic deduplication
    -> GoogleAdsAccountMapper
AccountDiscoveryResult<AccountCandidate>
    -> GoogleAdsAccountPanel / AccountSelectionService
```

Direct roots preserve a null access-account identifier. Managed clients preserve their canonical manager access identifier.

## 17. Partial Discovery Flow

`ListAccessibleCustomers` can return roots that reject later detail queries. A recoverable root-level permission failure is isolated so independent roots can continue.

`GoogleAdsAccountDiscoveryFailurePolicy` allows recovery only for a `GoogleAdsSdkException` classified as a root-account-query permission failure.

The integration result carries successful details, a bounded unavailable-root count, and explicit completeness. The catalog maps those values into `AccountDiscoveryResult`.

Completeness is never inferred by Presentation. No hidden mutable recovery state, global warning state, or side channel is allowed.

If all returned roots are unavailable, discovery fails. Zero returned roots produces a successful empty result.

## 18. Active Account Selection and Persistence

The dashboard accepts a submitted account identifier only through a capability-checked, nonce-protected POST action.

`AccountSelectionService` performs fresh canonical discovery and selects only one currently accessible, unambiguous client candidate. Manager accounts remain visible but unselectable.

`GoogleAdsActiveAccountStore` persists validated non-secret selection data in the non-autoloaded WordPress option:

```text
rajled_ai_ads_os_google_ads_active_account
```

Malformed stored payloads are rejected without being treated as trusted application state.

## 19. Error Classification and Recovery

SDK exceptions are translated into `GoogleAdsSdkException` with bounded diagnostic metadata.

Systemic failures fail fast. These include initialization, authentication, OAuth, accessible-customer listing, query validity, transport, malformed response, manager hierarchy, mapping, deduplication, persistence, and unexpected failures.

Recoverable root-level access failures may produce an explicit partial result when at least one usable account remains.

Sanitized logs may contain approved categories and bounded counts. They must not contain credentials, account identifiers from failed roots, request payloads, raw responses, or stack traces.

## 20. Security Boundaries

Credentials are defined outside the repository, normally in `wp-config.php`, and mapped into configuration during bootstrap.

The system does not store OAuth secrets, developer tokens, or refresh tokens in WordPress options. Developer tools and credential files are excluded from release packages.

WordPress actions use administrator capability checks, nonces, strict input validation, escaped output, and safe persistence handling.

Release ZIP packages include locked production dependencies because production hosting is not required to provide Composer.

## 21. Extension Model for Future Providers

A future provider should:

- create its own Integration package and SDK boundary;
- normalize provider responses into integration-owned data;
- implement provider-neutral Application contracts;
- map business data into existing Domain models;
- provide Infrastructure adapters only where platform persistence is required;
- register services through a provider without changing Domain language.

Shared abstractions should be added only after a concrete cross-provider requirement exists.

## 22. Current Limitations

The implemented system does not yet provide:

- production campaign discovery;
- production metrics retrieval;
- snapshots or historical campaign memory;
- optimization, decision, recommendation, execution, or learning engines;
- Google Ads mutations;
- scheduling or background account synchronization;
- multi-provider account selection;
- a credential settings UI.

Current implementation is intentionally limited to provider connectivity, account discovery, and active-account selection. These capabilities establish the platform foundation on which future campaign, metrics, optimization, and AI engines will be built.

These capabilities remain planned future architecture.

## 23. Related Documents

- [Architecture Overview](../../ARCHITECTURE.md)
- [Project README](../../README.md)
- [Engineering Standards](../../ENGINEERING_STANDARDS.md)
- [Domain Model](DOC-003-DOMAIN-MODEL.md)
- [Data Model](DOC-004-Data-Model.md)
- [Engine Specification](DOC-005-Engine-Specification.md)
- [REST API](../api/DOC-006-REST-API.md)
- [Sprint S1-006](../sprints/SPR-006.md)
- [Sprint S1-007](../sprints/SPR-007.md)
- [Sprint Retrospectives](../RETROSPECTIVES.md)
