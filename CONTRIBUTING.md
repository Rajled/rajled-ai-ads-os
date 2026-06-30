# CONTRIBUTING.md

# Contributing to RajLED AI Ads OS

Thank you for contributing to RajLED AI Ads OS.

This project follows an architecture-first and documentation-first development methodology.

Every contribution—whether made by a human developer or an AI agent—must follow the engineering standards defined for the project.

---

# Before You Start

Before implementing any feature, review the following documents in order:

1. README.md
2. AGENTS.md
3. ENGINEERING_STANDARDS.md
4. Documentation Suite
5. Architecture Decision Records (ADR)
6. Current Sprint specification

Do not begin implementation without understanding the current architecture.

---

# Development Philosophy

The project is developed according to the following principles:

* Architecture First
* Documentation as Code
* Domain-driven Development
* Incremental Delivery
* Small Reviewable Changes
* Continuous Improvement

Implementation should extend the architecture—not redefine it.

---

# Development Workflow

Every contribution follows the same lifecycle.

```text
Idea
    │
    ▼
Architecture
    │
    ▼
Documentation
    │
    ▼
Sprint Planning
    │
    ▼
Implementation
    │
    ▼
Review
    │
    ▼
Testing
    │
    ▼
Merge
    │
    ▼
Release
```

Skipping steps is not permitted.

---

# Branch Strategy

All development takes place on feature branches.

Examples:

```text
feature/S1-001-core-framework
feature/S1-002-google-ads-integration
feature/S1-003-gaql-query-layer

bugfix/BUG-014-health-endpoint

hotfix/HF-001-api-timeout
```

Direct development on the `main` branch is prohibited.

---

# Commit Convention

The project follows Conventional Commits.

Examples:

```text
feat(core): implement kernel bootstrap

feat(snapshot): add structural snapshot

fix(rest): correct health endpoint

refactor(memory): simplify repository

docs: update architecture

test(snapshot): add integration tests

chore: initialize repository
```

Each commit should represent one logical change.

---

# Pull Request Guidelines

Every Pull Request should:

* address a single feature or fix,
* remain small and reviewable,
* include related documentation updates,
* reference the corresponding Sprint task,
* avoid unrelated changes.

Large Pull Requests should be split into smaller units whenever possible.

---

# Code Review

Every implementation is reviewed before merge.

The review verifies:

* architecture compliance,
* domain consistency,
* coding standards,
* documentation updates,
* logging,
* test coverage,
* maintainability.

Review is considered part of implementation.

---

# Documentation Policy

Documentation is maintained alongside the code.

Whenever implementation changes:

* architecture,
* public interfaces,
* REST API,
* data model,
* engine behaviour,

the relevant documentation must also be updated.

Code and documentation must always remain synchronized.

---

# Testing

Every new feature should include appropriate validation.

Testing levels may include:

* Unit Tests
* Integration Tests
* Regression Tests
* Manual Verification

The required level depends on the scope of the change.

---

# Architecture Changes

Architecture must never change implicitly.

Significant architectural modifications require an Architecture Decision Record (ADR).

Technical discoveries should be documented as Technical Discovery (TD).

---

# AI-assisted Development

AI agents are treated as engineering assistants.

AI-generated code must:

* follow project standards,
* remain understandable,
* avoid speculative implementation,
* respect documented architecture.

Generated code is reviewed using the same process as human-written code.

---

# Definition of Done

A contribution is considered complete only when:

* implementation satisfies the requested scope,
* architecture remains consistent,
* documentation has been updated,
* tests have been completed,
* review has been performed,
* no known regressions have been introduced.

---

# Communication

If requirements are unclear:

* stop implementation,
* document the uncertainty,
* request clarification.

Never assume undocumented business rules.

---

# Engineering Standards

This repository follows the RajLED Engineering Standards.

The standards evolve together with the project.

Contributors are encouraged to improve both the software and the engineering process through documented proposals and Architecture Decision Records.

---

Thank you for helping build RajLED AI Ads OS.
