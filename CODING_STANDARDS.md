# CODING_STANDARDS.md

# Coding Standards

**Project:** RajLED AI Ads OS
**Version:** 1.0
**Status:** Active

---

# Purpose

This document defines the coding standards for RajLED AI Ads OS.

The goal is to ensure that all code remains:

* readable,
* maintainable,
* testable,
* extensible,
* consistent across the entire project.

These standards apply equally to:

* human developers,
* AI coding agents,
* generated code,
* reviewed code.

---

# General Principles

Every piece of code should be:

* easy to understand,
* easy to modify,
* easy to test,
* easy to review.

Code is written for people first.

The compiler is only the second reader.

---

# Primary Standards

The project follows:

* PSR-12
* PSR-4 Autoloading
* SOLID Principles
* DRY (Don't Repeat Yourself)
* KISS (Keep It Simple)
* YAGNI (You Aren't Gonna Need It)

Where project-specific rules conflict with generic standards, project standards take precedence.

---

# WordPress Standards

The project follows WordPress Coding Standards where applicable.

Business logic must remain independent from WordPress.

WordPress should act as the application framework—not as the domain model.

Avoid placing business logic in:

* hooks,
* templates,
* callbacks,
* admin pages.

Prefer dedicated services and engines.

---

# Naming Conventions

## Classes

Use PascalCase.

Examples:

```text id="cls1"
SnapshotEngine
CampaignMemory
DecisionEngine
HealthManager
```

---

## Interfaces

Suffix:

```text id="int1"
Interface
```

Examples:

```text id="int2"
EngineInterface
RepositoryInterface
LoggerInterface
```

---

## Traits

Suffix:

```text id="tr1"
Trait
```

---

## Abstract Classes

Prefix:

```text id="ab1"
Abstract
```

Example:

```text id="ab2"
AbstractEngine
```

---

## Methods

Use camelCase.

Methods should describe an action.

Examples:

```text id="mth1"
loadSnapshot()
calculateScore()
buildRecommendation()
saveCampaign()
```

Avoid ambiguous names such as:

```text id="mth2"
process()
run()
execute()
handle()
```

unless the context is obvious.

---

## Variables

Use meaningful names.

Good:

```text id="var1"
campaignSnapshot
qualityScore
budgetRecommendation
```

Avoid:

```text id="var2"
data
temp
value
obj
result2
```

---

# File Organization

One class per file.

One responsibility per class.

Keep directory structure aligned with namespaces.

---

# Class Design

Every class should have a single responsibility.

Avoid:

* God Classes,
* utility classes with unrelated methods,
* hidden dependencies.

Prefer dependency injection.

---

# Engine Design

Every Engine should:

* expose a clear public interface,
* encapsulate business logic,
* avoid knowledge of infrastructure,
* remain independently testable.

Engines must not communicate directly with external APIs.

---

# Dependency Rules

Dependencies should point inward.

Preferred flow:

```text id="dep1"
Infrastructure

↓

Integration Layer

↓

Application

↓

Domain
```

The Domain layer must never depend on infrastructure.

---

# Error Handling

Never suppress exceptions silently.

Catch exceptions only when meaningful action can be taken.

Log important failures.

Return meaningful error messages.

---

# Logging

Every significant business operation should be logged.

Logs should include:

* timestamp,
* operation,
* context,
* outcome.

Avoid excessive logging.

---

# Comments

Write self-explanatory code.

Use comments only when necessary.

Comments should explain **why**, not **what**.

Bad:

```php
// Increment counter
$counter++;
```

Good:

```php
// Google Ads API may return duplicate campaign IDs during synchronization.
```

---

# Functions

Prefer small functions.

A function should perform one logical operation.

Avoid long methods.

If a function exceeds reasonable complexity, consider extraction.

---

# Magic Values

Avoid hardcoded values.

Use:

* constants,
* configuration,
* enumerations,
* value objects.

---

# Configuration

Configuration belongs in dedicated configuration classes.

Never hardcode:

* API endpoints,
* credentials,
* identifiers,
* business thresholds.

---

# Database Access

Keep persistence separate from business logic.

Prefer repositories.

Avoid direct SQL inside engines.

---

# REST Controllers

REST controllers should:

* validate input,
* delegate work,
* format responses.

Business logic belongs elsewhere.

---

# Security

Always:

* validate input,
* sanitize data,
* escape output,
* verify permissions,
* protect sensitive information.

Never trust external input.

---

# Performance

Optimize after correctness.

Avoid:

* duplicated queries,
* unnecessary API calls,
* repeated calculations.

Prefer caching only when justified.

---

# AI-generated Code

AI-generated code must:

* follow these standards,
* remain readable,
* avoid speculative abstractions,
* avoid unnecessary complexity.

Generated code is reviewed using the same standards as human-written code.

---

# Code Review Checklist

Every review should verify:

* readability,
* architecture compliance,
* naming consistency,
* dependency direction,
* documentation updates,
* logging,
* maintainability,
* unnecessary complexity.

---

# Definition of Good Code

Good code is:

* simple,
* explicit,
* predictable,
* testable,
* modular,
* maintainable.

The best code is not the shortest.

The best code is the easiest to understand six months later.

---

# References

Related project documents:

* ENGINEERING_STANDARDS.md
* AGENTS.md
* CONTRIBUTING.md
* ARCHITECTURE.md
* Documentation Suite

---

**Consistent code builds maintainable software.**
