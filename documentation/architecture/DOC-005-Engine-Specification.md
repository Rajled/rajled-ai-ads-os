# DOC-005 — Engine Specification

## Purpose

The Engine Layer is responsible for transforming business data into business decisions.

Engines do not communicate directly with external providers.

Instead, they operate exclusively on Domain objects produced by the Domain Layer.

The Engine Layer represents the decision-making core of RajLED AI Ads OS.

---

# Responsibilities

The Engine Layer is responsible for:

- evaluating business data;
- applying business rules;
- calculating scores;
- generating insights;
- producing recommendations;
- supporting AI components.

The Engine Layer never communicates directly with external APIs.

---

# Design Principles

## Domain-Driven

Engines consume Domain objects only.

They never process:

- Google Ads SDK objects;
- REST requests;
- WordPress objects;
- provider-specific models.

---

## Stateless

An Engine should not maintain internal state.

All required information must be provided through its inputs.

This makes Engines deterministic, reusable and easily testable.

---

## Single Responsibility

Each Engine should solve one specific business problem.

Examples include:

- Budget analysis
- Performance scoring
- Recommendation generation
- Opportunity detection
- Trend analysis

---

## Independent Execution

Engines should not depend on one another unless explicitly orchestrated.

This enables:

- isolated testing;
- parallel execution;
- future scalability.

---

# Processing Flow

```
Integration
      │
      ▼
Domain Objects
      │
      ▼
Decision Engine
      │
      ▼
Decision
      │
      ▼
Recommendation
      │
      ▼
Presentation / AI
```

---

# Engine Inputs

Every Engine receives Domain objects.

Examples:

- Campaign
- Metrics
- Budget
- Account
- DateRange

No provider-specific objects may enter the Engine Layer.

---

# Engine Outputs

Engines may produce:

- Decision
- Recommendation
- Insight
- Warning
- Score

Outputs are represented by Domain models.

---

# Decision Pipeline

The decision-making process follows a common pipeline.

```
Input
   │
   ▼
Validation
   │
   ▼
Analysis
   │
   ▼
Evaluation
   │
   ▼
Decision
   │
   ▼
Recommendation
```

Each stage should remain independent and testable.

---

# Engine Categories

The initial architecture defines the following Engine categories.

## Analysis Engines

Responsible for analysing business data.

Examples:

- Performance Engine
- Budget Engine
- Conversion Engine

---

## Evaluation Engines

Responsible for interpreting analytical results.

Examples:

- Opportunity Engine
- Risk Engine
- Health Engine

---

## Recommendation Engines

Responsible for proposing business actions.

Examples:

- Budget Recommendation Engine
- Keyword Recommendation Engine
- Campaign Recommendation Engine

---

## AI Support Engines

Responsible for preparing structured information for AI components.

Examples:

- Context Builder
- Summary Builder
- Evidence Builder

---

# Future Architecture

Future versions may introduce:

- Engine Registry
- Engine Orchestrator
- Execution Pipeline
- Rule Engine
- Scoring Framework
- Confidence Framework

These components are intentionally outside the scope of S1-004.

---

# Relationship with Other Documents

This document complements:

- ARCHITECTURE.md
- DOC-003 — Domain Model
- DOC-004 — Data Model

The Domain Layer defines business concepts.

The Data Model defines how business information is represented.

The Engine Layer defines how business information is transformed into business decisions.

---

# S1-004 Scope

Sprint S1-004 establishes only the architectural foundation of the Engine Layer.

No concrete Decision Engines are implemented during this sprint.

The sprint defines:

- Engine responsibilities;
- execution principles;
- processing flow;
- architectural boundaries.

Concrete Engine implementations will be introduced in future iterations.