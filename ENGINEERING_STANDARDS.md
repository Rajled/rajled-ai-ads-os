# ENGINEERING_STANDARDS.md

# RajLED Engineering Standards (RES)

Version: 1.0

Status: Active

---

# Introduction

RajLED Engineering Standards (RES) define the mandatory engineering practices, architectural principles and development methodology for every software project developed within the RajLED ecosystem.

These standards apply equally to:

* human developers,
* AI coding agents,
* architecture reviews,
* code reviews,
* technical documentation,
* release management.

Whenever implementation conflicts with these standards, these standards take precedence.

---

# RES-001 — Architecture First

Architecture is designed before implementation.

Implementation must never redefine architecture.

If implementation reveals an architectural problem, the architecture must be updated first.

Never modify architecture implicitly.

---

# RES-002 — Documentation as Code

Documentation is part of the software.

Implementation is incomplete until documentation has been updated.

Architecture documents are version-controlled.

Documentation participates in code review.

---

# RES-003 — Domain Before Technology

Technology serves the domain.

Business concepts must never depend on:

* APIs,
* frameworks,
* vendors,
* infrastructure.

The Domain Model is the primary source of business truth.

---

# RES-004 — Infrastructure Isolation

Infrastructure must be isolated.

External APIs communicate only through Integration Layers.

Business Engines never communicate directly with external services.

---

# RES-005 — Engine Independence

Every Engine represents a single business capability.

Every Engine:

* owns its responsibility,
* communicates through contracts,
* is independently testable.

Cross-engine coupling should be minimized.

---

# RES-006 — Small Incremental Releases

Every Sprint produces a working release.

Every Release is potentially deployable.

Large features are divided into incremental deliveries.

---

# RES-007 — One Source of Truth

Every concept has exactly one authoritative source.

Examples:

Business Rules

↓

Domain Model

REST Endpoints

↓

REST Specification

Architecture

↓

Architecture Documentation

---

# RES-008 — Decision Transparency

Important engineering decisions are documented.

Architecture changes require ADR.

Major technical discoveries require TD documents.

---

# RES-009 — Review Before Merge

Every implementation must pass review.

Review includes:

* architecture,
* code,
* documentation,
* tests.

---

# RES-010 — Quality Before Speed

Engineering quality has higher priority than implementation speed.

Temporary shortcuts become permanent technical debt.

Avoid them.

---

# RES-011 — Unified Project Version

One project version represents one complete engineering state.

Source code, documentation, architecture, ADR, REST specification and release documentation evolve together and share the same version.

---

# Scope

These standards apply to every repository developed under RajLED Engineering.

Current projects include:

* AI Ads OS
* AI Visibility OS

Future projects automatically inherit these standards unless explicitly documented otherwise.

---

# Continuous Evolution

RajLED Engineering Standards are living documents.

Every completed project may improve these standards.

Lessons learned become new standards.

The engineering process evolves together with the software.
