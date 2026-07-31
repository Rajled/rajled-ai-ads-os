# Sprint Retrospectives

---

# S1-001 — Core Framework

## Achievements

...

## Process Improvements

...

## Lessons Learned

...

---

# S1-002 — Dependency Injection

## Achievements

...

---

# S1-003 — Integration Layer Foundation

## Achievements

- Introduced Integration Framework.
- Introduced IntegrationRegistry.
- Introduced IntegrationProviderInterface.
- Isolated Google Ads SDK.
- Added Architecture Backlog.

## Process Improvements

- Sprint implementation split into TASKs.
- Architecture Review before Code Review.
- Architecture Backlog introduced.
- SDK isolation adopted.

## Lessons Learned

- Generic frameworks should be implemented before provider-specific functionality.
- Small implementation TASKs significantly improve review quality.

## Deferred Architecture

- AB-003
- AB-004
- AB-005

## Outcome

The project evolved from a Google Ads plugin into a generic integration platform.

## Sprint S1-004

### What went well

- Domain model implemented incrementally.
- Small task size significantly improved review quality.
- Codex consistently followed architectural guidance.
- Domain remained fully independent from infrastructure.

### Improvements

- Validate finite floating-point values during initial implementation.
- Continue introducing contracts before concrete implementations.

### Decisions

- Model Before Behavior.
- Contracts Before Implementations.
- Primitive types are acceptable when dedicated Value Objects add no business value.

## Sprint S1-005

### What went well

- Successfully connected the Integration Layer to the Domain Layer through provider-independent mapping.
- Mapping contracts proved sufficient without introducing DTOs or additional abstractions.
- Domain remained completely independent from Google Ads SDK.
- Architecture review identified speculative SDK code before it entered production.

### Improvements

- Avoid implementing SDK readers before a verified SDK dependency is available.
- Continue validating architectural assumptions through implementation reviews rather than assumptions.

### Decisions

- Mapping belongs to Integration.
- SDK normalization belongs to the SDK boundary.
- Domain receives only provider-neutral data.
- Real SDK integrations are implemented only against verified SDK types.

# Sprint S1-006 Retrospective

## What went well

- Composer was introduced without replacing the project SPL autoloader.
- The runtime baseline moved to PHP 8.3 before the official SDK was installed.
- The Google Ads PHP SDK remained isolated inside the Integration SDK boundary.
- The WordPress credential bridge reused the existing configuration flow.
- Production ZIP packaging made deployment independent from Composer availability on hosting.
- The Connectivity Health Panel provided a bounded, administrator-controlled live verification path.

## Lessons Learned

- The architecture moved from integration readiness to real Google Ads API communication.
- Composer and `vendor/` packaging are production requirements for external WordPress hosting.
- Live `ListAccessibleCustomers` connectivity validated the complete boundary from configuration through the native SDK.
- Local SDK availability is not enough; production hosting, OAuth, Google Cloud, MCC access, and developer-token state must all be verified together.
- Credential diagnostics must describe missing categories without exposing values.

## Decisions

- Composer manages third-party dependencies only.
- Production hosts are not required to run Composer.
- The custom project autoloader remains responsible for project classes.
- Native SDK clients and types remain inside `Integration/GoogleAds/Sdk`.
- Connectivity stays read-only and runs only after an explicit administrator action.

## Outcome

Sprint S1-006 established the first verified live Google Ads API operation while preserving provider and runtime boundaries.

# Sprint S1-007 Retrospective

## What happened

The first production tests against real Google Ads accounts exposed an architectural assumption.

ListAccessibleCustomers correctly returned seven directly accessible accounts.

Three of those accounts had previously been cancelled.

Attempting to perform root account queries against cancelled accounts produced:

PERMISSION_DENIED

The original implementation aborted discovery after the first failure.

## Root Cause

The failure was not caused by:

- OAuth,
- Google Ads SDK,
- Developer Token,
- CustomerService.ListAccessibleCustomers.

The actual cause was the fail-fast strategy applied to root account discovery.

## Resolution

The discovery pipeline was redesigned.

Recoverable root-specific permission failures are now skipped.

Active manager and client accounts continue to be discovered.

System-wide failures remain fatal.

## Lessons Learned

Production Google Ads environments may contain cancelled, suspended or otherwise inaccessible accounts.

Discovery must tolerate recoverable entity-level failures while preserving correctness.

`ListAccessibleCustomers` may return directly accessible roots that cannot be queried for account details.

The cancelled roots exposed an invalid assumption that every listed root should participate in one fail-fast discovery transaction.

Entity-level recoverable failures must be isolated, while authentication, OAuth, transport, malformed response, hierarchy, mapping, and persistence failures must remain fatal.

Immutable explicit result models made completeness and unavailable-root counts reliable without hidden mutable warning state.

Real WordPress hosting acceptance revealed provider states and authorization behavior that dependency-free local tests could not reproduce.

The platform now distinguishes between:

- recoverable account-level failures,
- fatal platform-level failures.

This significantly improves robustness for real MCC environments.

## Process Improvements

- Continue combining deterministic local validation with targeted hosting acceptance.
- Treat each independently accessible provider entity as a potential recovery boundary only after explicit failure classification.
- Keep partial outcomes visible in immutable return values rather than logs or mutable service properties.

## Outcome

Sprint S1-007 established a resilient provider-neutral account discovery and selection workflow that remains safe under partial Google Ads account availability.
