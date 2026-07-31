# ARCHITECTURE.md

> This document serves as the master architecture specification for RajLED AI Ads OS.
>
> Detailed technical specifications are maintained in the documentation/architecture directory.

# Architecture Overview

**Project:** RajLED AI Ads OS
**Version:** 1.0
**Status:** Active

---

# Purpose

This document provides a high-level overview of the RajLED AI Ads OS architecture.

It is intended as the entry point for developers and AI agents who need to understand the overall system before working with the codebase.

Detailed architectural specifications are maintained separately within the Documentation Suite.

---

# Architectural Vision

RajLED AI Ads OS is designed as an AI-powered Digital Advertising Operations Platform.

The system is evolving toward independent business engines connected through provider-neutral contracts. The current implementation provides the platform foundation, Domain models, Google Ads connectivity, and account discovery and selection workflows.

The architecture prioritizes:

* modularity,
* maintainability,
* scalability,
* provider independence,
* long-term evolution.

The project follows the principle:

> **Architecture First**

---

# High-Level Architecture

The following diagram represents the planned engine architecture. The engines shown are roadmap components and are not yet implemented.

```text
External Advertising Platform
            │
            ▼
Integration Layer
            │
            ▼
Snapshot Engine
            │
            ▼
Campaign Memory Engine
            │
            ▼
Performance Engine
            │
            ▼
Optimization Engine
            │
            ▼
Decision Engine
            │
            ▼
Recommendation Engine
            │
            ▼
Execution Engine
            │
            ▼
Learning Engine
            │
            ▼
AI Control Center
```

Each engine is responsible for a single business capability.

---

# Architectural Principles

The platform follows these core principles:

* Engine-based Architecture
* Domain-driven Design
* Integration Layer Pattern
* Infrastructure Isolation
* Documentation as Code
* Architecture Decision Records
* Incremental Evolution

Detailed engineering rules are defined in:

`ENGINEERING_STANDARDS.md`

---

# Layers

The system is organized into multiple logical layers.

## Core Layer

Core owns application composition and lifecycle through the Kernel, service container, provider registry, health services, and engine registry.

## Application Layer

Application owns provider-neutral account discovery and selection contracts and services.

Implemented account components include:

* `AccountCandidate`
* `AccountDiscoveryResult`
* `DiscoveryCompleteness`
* `AccountCatalogInterface`
* `AccountSelectionService`
* `ActiveAccountStoreInterface`

## Integration Layer

Responsible for communication with external providers.

Current provider:

* Google Ads - configuration, native SDK client construction, live read-only connectivity, account discovery, account details, hierarchy traversal, mapping, and partial discovery

Future providers may include:

* Microsoft Ads
* Meta Ads
* Amazon Ads
* LinkedIn Ads

Business engines must never communicate directly with external APIs.

Integration providers are registered through a shared IntegrationRegistry. Provider-specific integrations expose readiness through IntegrationProviderInterface so application services can report integration health without depending on individual providers.

Google Ads uses Composer and `googleads/google-ads-php` v33.5.0 with API V24. SDK-specific code and official SDK types are isolated under `src/Integration/GoogleAds/Sdk/`.

The integration performs explicit administrator-initiated read-only operations for connectivity and account discovery. It translates provider data into integration-owned DTOs and provider-neutral Application models. Ordinary dashboard GET requests do not perform network operations.

Campaign retrieval, metrics retrieval, and Google Ads mutations are not implemented.

---

## Domain Layer

Contains the provider-independent business model.

Implemented models:

* Campaign
* Metrics
* Common Value Objects

The Domain model is independent of Google Ads, WordPress, Integration, REST, and infrastructure. Future entities are introduced only when their business requirements are implemented.

---

## Engine Layer

Future business functionality will be implemented as independent engines.

Planned engine roadmap:

* Snapshot Engine
* Campaign Memory Engine
* Performance Engine
* Optimization Engine
* Decision Engine
* Recommendation Engine
* Execution Engine
* Learning Engine

No roadmap engine is described as completed in the current release.

---

## Infrastructure Layer

Infrastructure contains runtime-specific implementations of Application contracts. The current `GoogleAdsActiveAccountStore` persists validated, non-secret active-account data in a non-autoloaded WordPress option.

---

## Presentation Layer

WordPress is the current runtime host and presentation adapter. It is not the business architecture of the platform.

Currently implemented presentation includes:

* Dashboard
* REST health endpoint
* Google Ads Connectivity panel
* Google Ads Account panel

Network operations require explicit administrator POST actions protected by capability and nonce checks. Presentation receives composed services and does not create native SDK clients.

---

# Documentation Structure

The complete architecture is documented within the Documentation Suite.

Primary architectural documents include:

* [DOC-002 System Architecture](documentation/architecture/DOC-002-System-Architecture.md)
* [DOC-003 Domain Model](documentation/architecture/DOC-003-DOMAIN-MODEL.md)
* [DOC-004 Data Model](documentation/architecture/DOC-004-Data-Model.md)
* [DOC-005 Engine Specification](documentation/architecture/DOC-005-Engine-Specification.md)
* [DOC-006 REST API](documentation/api/DOC-006-REST-API.md)

Architecture decisions are documented separately as ADR.

---

# Dependency Direction

The dependency rules are:

```text
Domain <- Application
Application contracts <- Integration implementations
Application contracts <- Infrastructure implementations
Application services <- Presentation
Core -> runtime composition
```

Domain depends on no provider or framework. Application remains provider-neutral. Integration and Infrastructure depend inward on stable contracts. Native SDK types remain inside the Google Ads SDK boundary.

---

# Architectural Governance

Architecture evolves through controlled engineering decisions.

Major architectural changes require:

* Architecture review
* Architecture Decision Record (ADR)
* Documentation update
* Implementation review

Architecture is considered part of the released product.

---

# References

Related documents:

* [README](README.md)
* [System Architecture](documentation/architecture/DOC-002-System-Architecture.md)
* [Engineering Standards](ENGINEERING_STANDARDS.md)
* [Agent Instructions](AGENTS.md)
* [Sprint S1-006](documentation/sprints/SPR-006.md)
* [Sprint S1-007](documentation/sprints/SPR-007.md)

---

**Architecture defines the system.**

**Implementation follows the architecture.**
