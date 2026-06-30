# VERSIONING.md

# Versioning Policy

**Project:** RajLED AI Ads OS
**Version:** 1.0
**Status:** Active

---

# Purpose

This document defines the versioning policy for RajLED AI Ads OS.

Versioning applies to:

* source code,
* releases,
* documentation,
* public APIs,
* architecture milestones.

The objective is to ensure that every version is predictable, traceable and reproducible.

---

# Versioning Principles

The project follows:

* Semantic Versioning (SemVer)
* Incremental Development
* Sprint-based Releases
* Milestone-driven Product Evolution

Every published version represents a stable and reviewable state of the project.

---

# Semantic Versioning

The version format is:

```text
MAJOR.MINOR.PATCH
```

Example:

```text
1.4.2
```

Where:

* **MAJOR** – incompatible architectural or functional changes
* **MINOR** – new backward-compatible functionality
* **PATCH** – bug fixes, optimizations and documentation corrections

---

# Major Version

A major version is released when:

* architecture changes significantly,
* public interfaces become incompatible,
* migration is required,
* the product reaches a major maturity milestone.

Examples:

```text
1.0.0
2.0.0
```

---

# Minor Version

A minor version introduces new capabilities without breaking compatibility.

Examples:

* new Engine,
* new REST endpoint,
* new Integration Layer,
* additional Dashboard module,
* new reporting capability.

Examples:

```text
0.2.0
0.5.0
0.8.0
```

---

# Patch Version

Patch releases include:

* bug fixes,
* performance improvements,
* security updates,
* documentation corrections,
* non-breaking refactoring.

Examples:

```text
0.5.1
0.5.2
0.5.3
```

---

# Development Lifecycle

The project evolves through the following lifecycle:

```text
Vision
    │
    ▼
Roadmap
    │
    ▼
Milestone
    │
    ▼
Sprint
    │
    ▼
Release Candidate
    │
    ▼
Release
```

Each Sprint produces a release candidate.

Accepted release candidates become official versions.

---

# Milestones and Versions

Milestones group related releases.

Example:

| Milestone | Target Version   | Objective                  |
| --------- | ---------------- | -------------------------- |
| M0        | Repository Setup | Engineering foundation     |
| M1        | v0.1.0           | Core Framework             |
| M2        | v0.2.0           | Google Ads Integration     |
| M3        | v0.3.0           | Snapshot Platform          |
| M4        | v0.4.0           | Campaign Memory            |
| M5        | v0.5.0           | Performance Intelligence   |
| M6        | v0.6.0           | Optimization Intelligence  |
| M7        | v0.7.0           | Decision Engine            |
| M8        | v0.8.0           | Recommendation & Execution |
| M9        | v0.9.0           | Learning Platform          |
| M10       | v1.0.0           | Production Release         |

---

# Version Tags

Every release is tagged in Git.

Format:

```text
vMAJOR.MINOR.PATCH
```

Examples:

```text
v0.1.0
v0.4.2
v1.0.0
```

Tags are created only from the `main` branch.

---

# Release Candidates

Before publication, releases may use Release Candidates.

Format:

```text
v0.5.0-rc1
v0.5.0-rc2
```

Release Candidates are used for final verification before stable release.

---

# Documentation Versioning

Documentation evolves together with the software.

Major architectural changes require:

* documentation updates,
* ADR entries,
* release documentation.

Documentation should never lag behind implementation.

---

# API Versioning

Public REST endpoints should remain backward compatible whenever possible.

Breaking API changes require:

* new endpoint version,
* migration documentation,
* release notes.

Example:

```text
/api/v1/...
/api/v2/...
```

---

# Database Versioning

Database schema changes should be:

* incremental,
* reversible whenever practical,
* documented.

Migration scripts must accompany schema changes.

---

# Release Policy

A version may be released only if:

* Sprint objectives are completed,
* architecture review is finished,
* documentation is synchronized,
* testing has passed,
* release notes have been prepared.

---

# Version Status

Possible development states:

* Planned
* In Development
* Release Candidate
* Stable
* Deprecated
* Archived

The current status should be reflected in project documentation.

---

# Backward Compatibility

Backward compatibility should be preserved whenever possible.

Breaking changes require:

* clear justification,
* ADR documentation,
* migration guidance.

---

# Long-term Support

Stable major releases may receive maintenance updates.

Example:

```text
1.0.1
1.0.2
1.0.3
```

Only bug fixes and security improvements are included in maintenance releases.

---

# Unified Project Version

RajLED AI Ads OS follows the principle:

> **One Version = One Complete Project State**

A project version represents the complete and reproducible state of the entire project—not only its source code.

Every released version includes synchronized:

* source code,
* architecture,
* documentation,
* REST API specification,
* Architecture Decision Records (ADR),
* Technical Discovery documents (TD),
* Release Notes,
* engineering standards.

Checking out a Git tag (for example `v0.6.0`) should reproduce the complete engineering state of the project at the moment of release.

This principle guarantees long-term traceability, reproducibility and maintainability of the platform.

Documentation and architecture are versioned together with the implementation and are considered part of the released product.

---

# References

Related documents:

* ROADMAP.md
* CHANGELOG.md
* RELEASE_NOTES.md
* GIT_WORKFLOW.md
* DOCUMENTATION_POLICY.md
* Documentation Suite

---

**Version numbers communicate engineering maturity—not only software age.**
