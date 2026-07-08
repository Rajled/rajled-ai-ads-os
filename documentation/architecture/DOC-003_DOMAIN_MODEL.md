# DOC-003 — Domain Model

## Purpose

The Domain Layer defines the business language of RajLED AI Ads OS.

Its responsibility is to model advertising and marketing concepts independently from any external provider, SDK, API or infrastructure.

The Domain Layer represents the single source of truth for all business concepts used throughout the platform.

---

# Objectives

The Domain Model has four primary objectives:

- define a provider-independent business language;
- encapsulate business rules;
- isolate business logic from infrastructure;
- provide reusable models for Decision Engines and AI components.

---

# Design Principles

## Domain Owns the Language

Business terminology is defined exclusively within the Domain Layer.

External integrations translate technical data into Domain objects.

The Domain Layer never adapts its terminology to provider-specific implementations.

---

## Provider Independence

The Domain Layer has no knowledge of:

- Google Ads
- Google Ads SDK
- WordPress
- REST API
- Integration Layer
- Infrastructure Services

Business models remain stable regardless of the underlying provider.

---

## Strong Typing

Business concepts should be represented by dedicated classes rather than primitive values whenever they carry business meaning.

Examples include:

- Identifier
- ResourceName
- Money
- Currency
- DateRange
- Status

---

## Value Objects First

Reusable business values are implemented as immutable Value Objects.

Every Value Object:

- validates its own state;
- encapsulates business rules;
- is immutable;
- may be shared across multiple entities.

---

## No Primitive Obsession

Business logic should avoid passing raw strings, arrays or numeric values when a dedicated business concept exists.

Example:

Instead of:

Campaign ID → string

Use:

CampaignIdentifier

---

## Domain Before Infrastructure

Infrastructure exists to support the Domain.

The Domain never depends on infrastructure.

External providers are responsible only for translating technical data into business objects.

---

# Initial Package Structure

```text
Domain/
│
├── Common/
│   ├── Collection/
│   ├── Contract/
│   ├── Exception/
│   └── ValueObject/
│
├── Shared/
│
├── Account/
│
├── Campaign/
│
└── Metrics/
```

---

# Initial Value Objects

The first implementation introduces the following reusable business objects.

| Value Object | Description |
|--------------|-------------|
| Identifier | Generic business identifier |
| ResourceName | External provider resource identifier |
| Money | Monetary value |
| Currency | Currency representation |
| DateRange | Reporting period |
| Status | Generic business status |

---

# Initial Domain Entities

The first iteration introduces:

- Campaign
- Metrics

Additional entities will be introduced in future iterations, including:

- Account
- AdGroup
- Asset
- Keyword
- SearchTerm
- Budget
- Conversion
- Recommendation

---

# Layer Relationship

```text
Integration
      │
      ▼
Domain
      │
      ▼
Decision Engine
      │
      ▼
AI
```

External providers deliver technical data.

The Integration Layer maps provider-specific structures into Domain models.

Decision Engines operate exclusively on Domain objects.

---

# S1-004 Scope

Sprint S1-004 establishes the initial Domain Foundation.

Included:

- Domain package structure;
- Common Value Objects;
- Campaign entity;
- Metrics model;
- Mapping contracts.

Deferred:

- Repositories;
- Domain Services;
- Aggregates;
- Specifications;
- Business Workflows.

These components will be introduced in later iterations.