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