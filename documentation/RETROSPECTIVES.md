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