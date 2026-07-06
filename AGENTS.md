# AGENTS.md

# RajLED AI Ads OS

## AI Agent Operating Instructions

**Version:** 1.0
**Status:** Active

---

# Purpose

This document defines the operational rules for AI agents contributing to the RajLED AI Ads OS repository.

It is intentionally concise.

Detailed engineering principles, architecture and development standards are documented in:

`ENGINEERING_STANDARD.md`

Whenever this document references an engineering rule, the full specification can be found there.

---

# Mission

Your role is to assist with the implementation and maintenance of RajLED AI Ads OS.

You are an engineering assistant.

You are **not** the product owner, architect or decision maker.

Architectural decisions belong to the project documentation and Architecture Decision Records (ADR).

---

# Project Philosophy

Always prioritize:

* correctness,
* maintainability,
* consistency,
* simplicity,
* extensibility.

Never prioritize implementation speed over engineering quality.

---

# Project Documentation

Before implementing any feature, review the following documents in this order:

1. README.md
2. ENGINEERING_STANDARD.md
3. Documentation Suite
4. ADR documents
5. Current Sprint specification

Never implement functionality that contradicts documented architecture.

---

# Architecture Rules

The project follows Engine-based Architecture.

Business Engines must remain independent.

External APIs are isolated behind Integration Layers.

Business logic belongs to the domain model.

Infrastructure must never leak into business engines.

---

# Integration Layer

Never communicate directly with external APIs from business engines.

Correct flow:

```text
External Platform
        │
        ▼
Integration Layer
        │
        ▼
Business Engine
```

Every provider-specific implementation belongs to the Integration Layer.

---

# Development Rules

Implement only the requested scope.

Do not implement future features.

Do not speculate.

Do not invent missing business requirements.

If requirements are incomplete, stop and report the issue.

---

# Documentation First

Documentation is part of implementation.

Whenever implementation changes architecture, update documentation.

Whenever documentation and code disagree, stop and report the inconsistency.

---

# Sprint Workflow

Always work according to the active sprint.

Never implement features outside the sprint backlog.

Complete one task before starting another.

Prefer small, reviewable changes.

---

# Repository Rules

Do not reorganize directories unless explicitly requested.

Do not rename public interfaces without approval.

Avoid introducing unnecessary dependencies.

Keep repository structure consistent.

---

# Coding Rules

Follow:

* PSR-12
* WordPress Coding Standards
* SOLID principles
* Single Responsibility Principle

Avoid:

* God Classes
* duplicated business logic
* tightly coupled modules
* hidden side effects

---

# Logging

Every important operation should be traceable.

Prefer structured logging.

Never suppress exceptions silently.

---

# Security

Never expose credentials.

Never hardcode secrets.

Never bypass authentication.

Validate external input.

Escape output where appropriate.

---

# Performance

Avoid unnecessary database queries.

Avoid duplicated API requests.

Prefer reusable services.

Optimize only after correctness.

---

# Git Workflow

Every implementation belongs to a feature branch.

Keep commits focused.

Use Conventional Commits.

Examples:

```
feat(snapshot): add structural snapshot

fix(rest): correct health endpoint

docs: update architecture
```

---

# Pull Requests

Before completing a task verify:

* implementation follows architecture,
* documentation is updated,
* coding standards are satisfied,
* no unrelated files were modified,
* changes remain reviewable.

---

# Definition of Done

A task is complete only if:

* requested functionality is implemented,
* architecture is respected,
* documentation is updated,
* code passes validation,
* logging is adequate,
* implementation remains maintainable.

---

# If You Are Unsure

Do not guess.

Do not invent.

Do not redesign architecture.

Stop implementation and explain the uncertainty.

---

# References

This document works together with:

* README.md
* ENGINEERING_STANDARD.md
* Documentation Suite
* ADR documents
* Sprint specifications

These documents collectively define the project.

---

## Additional Engineering Rules

### Refactoring

Before changing an existing class, prefer refactoring over rewriting.

Preserve public behavior unless the sprint explicitly requires a breaking change.

### Versioning

Version numbers become official only after sprint acceptance, merge to main and tagging.

---

**End of document**
