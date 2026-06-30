# DOCUMENTATION_POLICY.md

# Documentation Policy

**Project:** RajLED AI Ads OS
**Version:** 1.0
**Status:** Active

---

# Purpose

This document defines the documentation policy for RajLED AI Ads OS.

Documentation is treated as a first-class engineering artifact.

It is developed, reviewed and version-controlled together with the source code.

Documentation is not optional.

Documentation is part of the Definition of Done.

---

# Documentation Philosophy

The project follows the principle:

> **Documentation as Code**

Documentation evolves together with the software.

Every architectural decision, engineering standard and business concept must be documented.

Documentation is considered part of the product.

---

# Documentation Objectives

Documentation should:

* explain architecture,
* preserve engineering decisions,
* document business concepts,
* support future development,
* assist AI agents,
* reduce knowledge loss.

Documentation should never become outdated.

---

# Documentation Layers

RajLED AI Ads OS maintains documentation on multiple levels.

---

## Repository Governance

Purpose:

Defines how the repository is managed.

Documents:

* README.md
* AGENTS.md
* ENGINEERING_STANDARDS.md
* CONTRIBUTING.md
* ROADMAP.md
* CHANGELOG.md
* RELEASE_NOTES.md
* GIT_WORKFLOW.md
* DOCUMENTATION_POLICY.md

---

## Documentation Suite

Purpose:

Describes the system itself.

Examples:

* Product Vision
* System Architecture
* Domain Model
* Data Model
* Engine Specifications
* REST API Specification

---

## Architecture Decisions

Purpose:

Documents important engineering decisions.

Format:

ADR (Architecture Decision Record)

Examples:

```text id="adr001"
ADR-001 Engine-based Architecture

ADR-002 Integration Layer Pattern

ADR-003 Snapshot Strategy
```

---

## Technical Discovery

Purpose:

Documents technical research and implementation findings.

Examples:

```text id="td001"
TD-001 Google Ads API Capabilities

TD-002 OAuth Strategy

TD-003 Snapshot Synchronization
```

---

## Release Documentation

Purpose:

Documents every released version.

Location:

```text id="reldocs"
documentation/releases/
```

---

# Documentation Ownership

Every implementation is responsible for updating related documentation.

The contributor who changes the software is also responsible for updating the documentation.

Documentation ownership follows implementation ownership.

---

# Documentation Workflow

Every engineering change follows the same process.

```text id="docflow"
Idea
    │
    ▼
Architecture
    │
    ▼
Documentation
    │
    ▼
Implementation
    │
    ▼
Review
    │
    ▼
Release
```

Documentation begins before implementation.

Documentation ends after release.

---

# Documentation Review

Documentation is reviewed together with code.

Review verifies:

* correctness,
* completeness,
* consistency,
* readability,
* synchronization with implementation.

Documentation review is mandatory.

---

# Documentation Lifecycle

Every document progresses through the following states:

```text id="doclife"
Draft

↓

Review

↓

Accepted

↓

Versioned

↓

Maintained

↓

Archived
```

Documentation never skips review.

---

# Versioning

Documentation follows the software lifecycle.

Major architecture changes require documentation updates.

Minor implementation improvements may require documentation revisions.

Historical versions remain available through Git.

---

# One Knowledge — Three Perspectives

Every significant feature should be documented from three complementary perspectives.

## 1. Business Perspective

Purpose:

Explain why the feature exists.

Audience:

* Product Owner
* Business Stakeholders
* AI Planning

---

## 2. Technical Perspective

Purpose:

Explain how the feature works.

Audience:

* Developers
* Architects
* AI Coding Agents

---

## 3. Operational Perspective

Purpose:

Explain how the feature is used.

Audience:

* Administrators
* Operators
* End Users

---

This principle ensures that knowledge is complete and reusable.

---

# Documentation Triggers

Documentation must be updated whenever:

* architecture changes,
* public interfaces change,
* REST endpoints change,
* domain model changes,
* data model changes,
* new engine is introduced,
* release is published,
* ADR is created,
* Technical Discovery is completed.

---

# AI-assisted Documentation

AI agents may generate documentation.

However:

* documentation must remain accurate,
* architecture takes precedence over generated text,
* implementation must never contradict documentation.

AI-generated documentation is reviewed using the same standards as human-written documentation.

---

# Documentation Quality

Documentation should be:

* accurate,
* concise,
* version-controlled,
* searchable,
* maintainable,
* architecture-oriented,
* implementation-independent whenever possible.

Avoid duplication.

Prefer references over repeated content.

---

# Repository Structure

Recommended documentation structure:

```text id="docstructure"
documentation/
│
├── architecture/
├── adr/
├── api/
├── product/
├── technical-discovery/
├── releases/
└── manuals/
```

Repository governance documents remain in the repository root.

---

# Definition of Done

A feature is not complete until:

* documentation has been updated,
* related ADR references exist,
* release documentation is prepared when applicable,
* repository documentation remains consistent.

Documentation is part of every Sprint.

Documentation is part of every Release.

---

# Continuous Improvement

Documentation evolves together with the product.

Lessons learned from implementation should improve:

* engineering standards,
* architecture documentation,
* contribution guidelines,
* AI instructions.

The documentation process itself is continuously improved.

---

**Good documentation preserves knowledge.**

**Great documentation enables future engineering.**
