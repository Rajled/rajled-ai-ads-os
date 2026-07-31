# RajLED AI Ads OS

> **AI-powered Digital Advertising Operations Platform**

**Version:** 0.2.0-alpha.4 (Google Ads Integration)
**Status:** In Development
**Repository Type:** Private
**License:** Proprietary (RajLED)

---

# Overview

RajLED AI Ads OS is an AI-powered operations platform designed to analyze, optimize and manage digital advertising campaigns.

Unlike traditional Google Ads management tools, AI Ads OS is **not built around the Google Ads interface**. Instead, it creates its own business model, knowledge layer and decision process, allowing the platform to remain independent from vendor-specific APIs.

The first supported advertising platform is Google Ads. 

Google Ads is currently the only implemented provider. The platform architecture is intentionally provider-neutral so additional advertising platforms can be integrated without changing the business or application layers.

The architecture has been designed to support additional advertising platforms in future releases without modifying the business engines.

Examples include:

* Microsoft Ads
* Meta Ads
* LinkedIn Ads
* Amazon Ads

---

# Vision

The long-term vision of AI Ads OS is to become an intelligent advertising operations platform capable of:

* collecting advertising data,
* building historical knowledge,
* detecting optimization opportunities,
* supporting business decisions,
* executing approved changes,
* learning from previous outcomes.

The system follows the engineering principle:

> **Data → Knowledge → Decision → Execution → Learning**

---

# Current Project Status

Current release:

**v0.2.0-alpha.4 Google Ads Integration**

Current milestone:

**M1 - Foundation Platform**

Latest accepted sprint:

**S1-007 - Google Ads Account Discovery and Selection**

Current implementation phase:

* Core service container, provider registry, REST health, and dashboard foundation
* Provider-independent Domain foundation with Campaign and Metrics models
* Provider-neutral Application account contracts and selection service
* Composer-managed production dependencies on PHP 8.3 or newer
* Official Google Ads PHP SDK v33.5.0 using API V24
* External WordPress credential bridge and native SDK client factory
* Live read-only Google Ads connectivity through `ListAccessibleCustomers`
* Accessible-account names and Manager / Client classification
* Explicit partial discovery for independently inaccessible roots
* Administrator account selection with validated WordPress persistence

The first operational account workflow is implemented and accepted. Campaign retrieval, metrics retrieval, advertising mutations, and business engines remain future work.

---

# Architecture

The platform is designed around independent business engines.

Target engine architecture (planned; the engines shown below are not yet implemented):

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

External APIs are isolated behind dedicated Integration Layers.

Business engines never communicate directly with third-party APIs.

---

# Engineering Principles

The project follows several fundamental principles.

* Architecture First
* Documentation as Code
* Domain-driven Development
* Engine-based Architecture
* Infrastructure Isolation
* Small Incremental Releases
* Quality over Speed

Detailed engineering rules are defined in:

`ENGINEERING_STANDARDS.md`

---

# Documentation

Project documentation is maintained alongside the source code.

Documentation Suite currently includes:

* [System Architecture](documentation/architecture/DOC-002-System-Architecture.md)
* [Domain Model](documentation/architecture/DOC-003-DOMAIN-MODEL.md)
* [Data Model](documentation/architecture/DOC-004-Data-Model.md)
* [Engine Specification](documentation/architecture/DOC-005-Engine-Specification.md)
* [REST API Specification](documentation/api/DOC-006-REST-API.md)
* [Sprint Documentation](documentation/sprints/)
* [Sprint Retrospectives](documentation/RETROSPECTIVES.md)

Documentation is treated as a first-class project artifact.

Code is never considered complete until the corresponding documentation has been updated.

---

# Repository Structure

```text
documentation/
src/
  Application/
  Core/
  Config/
  Dashboard/
  Domain/
  Engine/
  Health/
  Infrastructure/
  Integration/
    Contract/
    GoogleAds/
      Account/
      Mapping/
      Sdk/
  Logging/
  Rest/
vendor/

composer.json
composer.lock
rajled-ai-ads-os.php
README.md
AGENTS.md
ENGINEERING_STANDARDS.md
CONTRIBUTING.md
ROADMAP.md
CHANGELOG.md
```

Additional directories and modules will be introduced as the platform evolves.

---

# Development Workflow

Development follows an iterative, sprint-based workflow.

```
Product Vision
        │
        ▼
Product Backlog
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
Release
        │
        ▼
Documentation Update
```

Every sprint produces a working software release.

---

# Technology Stack

Current technology stack:

* PHP 8.3 or newer
* WordPress plugin runtime and administration adapter
* Composer production dependency management
* Google Ads PHP SDK v33.5.0
* Google Ads API V24
* WordPress REST API health endpoint
* Git
* GitHub
* Codex-assisted development

Planned Technology Stack: 

* Additional advertising provider SDKs
* Advertising data storage and scheduled processing
* AI model integrations

Future integrations may include:

* BigQuery
* Google Analytics 4
* Microsoft Ads
* Meta Ads
* AI model providers

---

# Repository Standards

The project follows:

* Semantic Versioning
* Conventional Commits
* Pull Request Reviews
* Architecture Decision Records (ADR)
* Documentation Review
* Incremental Releases

---

# Infrastructure Layer

The infrastructure layer provides dependency management for the WordPress plugin foundation.

Current infrastructure services:

* ServiceContainer
* ServiceProviderInterface
* ProviderRegistry
* CoreServiceProvider
* EventDispatcher
* IntegrationProviderInterface
* IntegrationRegistry

Google Ads Integration Layer foundation services:

* GoogleAdsConfiguration
* CredentialsManager
* ClientInterface
* ClientFactory
* ConnectionInterface
* ConnectionManager
* GoogleAdsServiceProvider
* GoogleAdsSdkFactory
* GoogleAdsSdkClient
* GoogleAdsSdkException
* GoogleAdsConnectivityChecker
* GoogleAdsConnectivityHealthCheck
* GoogleAdsAccountDiscovery
* GoogleAdsAccountDetailsReader
* GoogleAdsAccountCatalog
* GoogleAdsAccountMapper

Core services are registered through service providers and resolved through the ServiceContainer.

The Kernel loads providers and connects WordPress adapters such as REST endpoints, the dashboard, and active-account persistence. Integration providers register themselves in IntegrationRegistry so health reporting remains provider-neutral.

Google Ads SDK-specific logic is isolated under `src/Integration/GoogleAds/Sdk/`. The integration performs explicit, administrator-initiated read-only connectivity and account-discovery requests. Provider responses are translated into integration-owned DTOs and provider-neutral Application models before reaching selection and persistence workflows.

Ordinary dashboard GET requests do not call Google Ads. Campaign retrieval, metrics retrieval, and mutate operations are not implemented.

---

# AI-assisted Development

AI is considered an engineering assistant.

Every AI agent contributing to this repository must follow:

* AGENTS.md
* ENGINEERING_STANDARDS.md

Generated code must comply with project architecture and engineering rules.

Implementation convenience must never override architectural consistency.

---

# Getting Started

Repository setup:

1. Clone the repository.
2. Install project dependencies.
3. Review the engineering documentation.
4. Configure the development environment.
5. Begin implementation according to the active sprint.

Detailed instructions are provided in:

* DEVELOPMENT.md
* CONTRIBUTING.md
* AGENTS.md

---

# Roadmap

Current roadmap:

* v0.1.0 Foundation
* v0.2.0 Google Ads Integration
* v0.3.0 Snapshot Engine
* v0.4.0 Campaign Memory
* v0.5.0 Performance Intelligence
* v0.6.0 Optimization Intelligence
* v0.7.0 Decision Engine
* v0.8.0 Recommendation Engine
* v0.9.0 Execution & Learning
* v1.0.0 Production Release

The roadmap will evolve as the platform matures.

---

# Project Status

RajLED AI Ads OS is an actively developed proprietary platform.

The project follows a documentation-first and architecture-first development methodology.

Every architectural decision, implementation milestone and engineering standard is version-controlled and maintained alongside the source code.

---

© RajLED. All rights reserved.
