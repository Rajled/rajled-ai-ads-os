# ARCHITECTURE_BACKLOG.md

# Architecture Backlog

**Project:** RajLED AI Ads OS

---

# Purpose

This document tracks architectural improvements that have been intentionally postponed.

Items in this backlog:

- are **not defects**,
- do **not block the current sprint**,
- represent future architectural refinements,
- are reviewed before starting major new modules.

Architecture Backlog is independent from the Product Backlog.

Product Backlog contains new functionality.

Architecture Backlog contains improvements to the system architecture.

---

# Status Values

| Status | Meaning |
|----------|---------|
| Deferred | Accepted for future implementation |
| Planned | Scheduled for a future sprint |
| In Progress | Currently being implemented |
| Completed | Implemented |

---

# Items

## AB-001

**Title**

Separate Connection Status from Client Access

**Status**

Deferred

**Priority**

Low

**Area**

Integration Layer

**Description**

ConnectionManager currently exposes the Google Ads client through `getClient()`.

In the future, consider limiting ConnectionManager to connection readiness and health reporting only.

Client creation and access may be moved to a dedicated integration service.

**Reason**

Improves Single Responsibility Principle (SRP).

No impact on current functionality.

---

## AB-002

**Title**

Repository Line Ending Policy

**Status**

Deferred

**Priority**

Low

**Area**

Repository

**Description**

Introduce `.gitattributes` to enforce consistent line endings across Windows, Linux and CI environments.

Example:

```text
* text=auto

*.php text eol=lf
*.md text eol=lf
```

**Reason**

Avoid recurring CRLF/LF warnings.

Improve repository consistency.

---

# Notes

Architecture Backlog items should normally be implemented only when:

- they simplify future development,
- they reduce architectural complexity,
- they improve maintainability,
- or they are required by a future sprint.

Architecture Backlog items should not delay sprint completion unless explicitly approved.

## AB-003

**Title**

Replace Provider Health Arrays with Value Object

**Status**

Deferred

**Priority**

Low

**Area**

Integration Layer

**Introduced**

Sprint S1-003 / TASK-003.2

**Description**

Integration providers currently expose their health information as associative arrays through the `getHealthStatus()` method.

As the Integration Layer grows, consider replacing these arrays with a dedicated immutable `ProviderHealth` Value Object.

Example:

```php
ProviderHealth
```

instead of:

```php
array<string, mixed>
```

**Reason**

Using a dedicated Value Object would:

- provide stronger typing,
- improve IDE support,
- simplify validation,
- reduce array key duplication,
- make future extensions easier without breaking existing consumers.

**Current Decision**

Associative arrays are sufficient for the current project stage.

Introducing a Value Object would increase complexity without providing immediate practical benefits.

The current implementation remains accepted.

**Future Trigger**

Revisit this item when:

- multiple integration providers exist,
- provider health payloads become more complex,
- health information is consumed by multiple subsystems.

**Related Documents**

- ADR-002 Integration Layer Pattern
- DOC-002 System Architecture

**Notes**

This item is an architectural refinement only.

It does not represent a defect and should not delay sprint completion.

## AB-004

**Title**

Introduce Provider Boot Phase

**Status**

Deferred

**Priority**

Medium

**Area**

Integration Layer

**Introduced**

Sprint S1-003 / TASK-003.2

**Description**

Separate service registration from runtime provider registration.

...