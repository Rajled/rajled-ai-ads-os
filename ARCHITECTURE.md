# ARCHITECTURE.md

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

The system is based on independent business engines connected through clearly defined contracts.

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

## Integration Layer

Responsible for communication with external providers.

Current provider:

* Google Ads - configuration, credentials, client and connection readiness foundation

Future providers may include:

* Microsoft Ads
* Meta Ads
* Amazon Ads
* LinkedIn Ads

Business engines never communicate directly with external APIs.

Integration providers are registered through a shared IntegrationRegistry. Provider-specific integrations expose readiness through IntegrationProviderInterface so application services can report integration health without depending on individual providers.

The current Google Ads Integration Layer foundation does not perform live API requests, GAQL queries or campaign fetching. It prepares provider-specific services that future sprints can use behind integration-layer contracts.

---

## Domain Layer

Contains the business model.

Examples:

* Campaign
* Ad Group
* Keyword
* Budget
* Recommendation
* Snapshot

The domain model is independent of infrastructure.

---

## Engine Layer

Business functionality is implemented as independent engines.

Current engine roadmap:

* Snapshot Engine
* Campaign Memory Engine
* Performance Engine
* Optimization Engine
* Decision Engine
* Recommendation Engine
* Execution Engine
* Learning Engine

Each engine owns a single responsibility.

---

## Presentation Layer

Provides administrative access to the platform.

Includes:

* Dashboard
* AI Control Center
* REST API
* Administrative Interface

---

# Documentation Structure

The complete architecture is documented within the Documentation Suite.

Primary architectural documents include:

* DOC-001 Product Vision
* DOC-002 System Architecture
* DOC-003 Domain Model
* DOC-004 Data Model
* DOC-005 Engine Specifications
* REST API Specification

Architecture decisions are documented separately as ADR.

---

# Dependency Direction

The preferred dependency flow is:

```text
Infrastructure
        │
        ▼
Integration Layer
        │
        ▼
Application
        │
        ▼
Domain
```

Business logic must never depend directly on infrastructure.

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

* README.md
* ENGINEERING_STANDARDS.md
* AGENTS.md
* Documentation Suite
* ADR
* Technical Discovery

---

**Architecture defines the system.**

**Implementation follows the architecture.**
