# DOC-004 — Data Model

## Purpose

The Data Model defines how business information is represented within the Domain Layer.

Unlike database schemas or external API structures, the Data Model describes provider-independent business objects and their relationships.

The purpose of this document is to ensure that all Decision Engines operate on a consistent and stable representation of marketing data.

---

# Design Principles

## Domain-Centric

The Data Model belongs to the Domain Layer.

It is independent from:

- Google Ads API
- Google Ads SDK
- WordPress
- REST API
- database implementation

---

## Strong Typing

Business information should be represented by dedicated Domain objects rather than primitive values whenever possible.

Example:

Instead of:

```
budget = 250.00
```

Use:

```
Budget
└── Money
    ├── Amount
    └── Currency
```

---

## Composition Over Arrays

Collections of related information should be represented by Domain objects instead of associative arrays.

Example:

Instead of:

```
campaign['metrics']
```

Use:

```
Campaign
└── Metrics
```

---

## Immutable Value Objects

Value Objects never change their internal state after creation.

Whenever a business value changes, a new Value Object is created.

---

# Initial Domain Model

```
Campaign
│
├── Identifier
├── ResourceName
├── Name
├── Status
├── Budget
│     └── Money
│
└── Metrics
      ├── Impressions
      ├── Clicks
      ├── CTR
      ├── Average CPC
      ├── Cost
      ├── Conversions
      └── Conversion Value
```

---

# Entity Relationships

## Campaign

Represents a marketing campaign independently from any advertising platform.

Owns:

- Identifier
- ResourceName
- Status
- Budget
- Metrics

---

## Metrics

Represents measured campaign performance.

Contains:

- impressions
- clicks
- ctr
- averageCpc
- cost
- conversions
- conversionValue

Metrics do not contain business logic.

They represent measured business facts.

---

## Money

Represents a monetary value.

Contains:

- amount
- currency

Money is implemented as a reusable Value Object.

---

## Currency

Represents a valid business currency.

Examples include:

- PLN
- EUR
- USD

The implementation validates supported currency codes.

---

## Identifier

Represents an internal business identifier.

Identifiers are immutable.

---

## ResourceName

Represents the provider-specific identifier.

The Domain Layer stores the identifier but does not interpret its structure.

---

## Status

Represents the business state of an entity.

Typical values may include:

- Active
- Paused
- Removed

The Domain Layer defines business semantics independently from provider-specific implementations.

---

# Integration Mapping

External providers never populate Decision Engines directly.

The flow is always:

```
External Provider
        │
        ▼
Integration Layer
        │
        ▼
Mapper
        │
        ▼
Domain Objects
        │
        ▼
Decision Engines
```

This guarantees that business logic remains independent from technical integrations.

---

# Future Expansion

The Data Model will be extended in future iterations with additional entities, including:

- Account
- AdGroup
- Asset
- Keyword
- SearchTerm
- Audience
- Budget
- Recommendation
- Decision
- Insight

Each new entity must follow the same Domain-first principles established in S1-004.

---

# Relationship with Other Documents

This document complements:

- ARCHITECTURE.md
- DOC-003 — Domain Model
- DOC-005 — Engine Specification

The Domain Model defines business concepts.

The Data Model defines how those concepts are represented.

The Engine Specification defines how those objects are processed.