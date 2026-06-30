# TESTING.md

# Testing Strategy

**Project:** RajLED AI Ads OS
**Version:** 1.0
**Status:** Active

---

# Purpose

This document defines the testing strategy for RajLED AI Ads OS.

Testing is not limited to validating source code.

The objective is to verify that the entire platform remains:

* correct,
* stable,
* maintainable,
* secure,
* architecturally consistent.

Testing is part of every Sprint and every Release.

---

# Testing Philosophy

The project follows the principle:

> **Quality is verified continuously, not only before release.**

Testing begins during design and ends after release verification.

Every feature should include an appropriate validation strategy.

---

# Testing Pyramid

The project uses a layered testing approach.

```text
                    Manual Validation
                           ▲
                    Acceptance Tests
                           ▲
                  Integration Tests
                           ▲
                     Unit Tests
```

Each higher level depends on the stability of the levels below.

---

# Testing Levels

## Unit Testing

Purpose:

Verify individual classes and methods.

Examples:

* Engine methods
* Value Objects
* Scoring algorithms
* Utility classes

Requirements:

* isolated,
* deterministic,
* fast.

---

## Integration Testing

Purpose:

Verify cooperation between components.

Examples:

* Engine interactions
* Repository access
* REST API
* Google Ads Integration Layer
* Snapshot workflow

Integration tests verify contracts rather than implementation details.

---

## Functional Testing

Purpose:

Verify business workflows.

Examples:

* Snapshot creation
* Campaign synchronization
* Recommendation generation
* Approval workflow

Functional tests focus on expected business behaviour.

---

## Acceptance Testing

Purpose:

Verify that completed Sprint objectives satisfy business expectations.

Acceptance criteria originate from Sprint Planning.

Each Sprint concludes with acceptance verification.

---

## Regression Testing

Purpose:

Ensure that previously implemented functionality continues to work.

Regression testing is mandatory before every release.

Typical regression areas include:

* REST API
* Dashboard
* Snapshot Engine
* Campaign Memory
* Google Ads synchronization

---

## Manual Verification

Not every behaviour can be automated.

Manual verification should confirm:

* WordPress integration,
* Dashboard usability,
* Settings,
* Administrative workflows,
* Release readiness.

---

# Architecture Verification

Testing also protects the architecture.

Review should verify:

* Engine independence,
* Dependency direction,
* Integration Layer isolation,
* Domain Model consistency,
* REST contract compliance.

Architecture violations are considered defects.

---

# AI-assisted Testing

AI agents may generate tests.

However:

* generated tests require review,
* tests must be deterministic,
* tests must verify behaviour rather than implementation.

AI-generated tests follow the same quality standards as manually written tests.

---

# Google Ads Integration Testing

Google Ads API testing should distinguish between:

## Contract Verification

Verify:

* request structure,
* response mapping,
* authentication,
* error handling.

---

## Live Integration

Executed only when necessary.

Should use:

* dedicated test accounts,
* controlled datasets,
* minimal API usage.

Avoid unnecessary production API calls.

---

# Test Data

Testing should use controlled datasets whenever possible.

Test data should be:

* repeatable,
* isolated,
* predictable,
* documented.

Production data must never be modified during automated testing.

---

# Sprint Validation

Every Sprint concludes with a validation checklist.

The checklist verifies:

* Sprint objectives completed,
* documentation updated,
* regression verification completed,
* architecture unchanged,
* release candidate accepted.

---

# Release Validation

Every Release requires:

* Unit Tests
* Integration Tests
* Regression Verification
* Manual Verification
* Documentation Review

A release is considered complete only after successful validation.

---

# Test Documentation

Testing activities should be documented.

Documentation may include:

* test scenarios,
* validation results,
* known limitations,
* unresolved issues.

Significant findings should be recorded in Technical Discovery documents when appropriate.

---

# Definition of Done

A feature is considered tested when:

* required validation has been completed,
* acceptance criteria are satisfied,
* no critical regressions exist,
* documentation has been updated,
* review has been completed.

Testing is part of the Definition of Done.

---

# Continuous Improvement

Testing strategy evolves together with the platform.

Lessons learned from defects should improve:

* automated tests,
* regression suites,
* engineering standards,
* development workflow.

Testing is a continuous engineering activity rather than a final project phase.

---

# References

Related project documents:

* ENGINEERING_STANDARDS.md
* CODING_STANDARDS.md
* CONTRIBUTING.md
* DOCUMENTATION_POLICY.md
* RELEASE_NOTES.md
* Documentation Suite

---

**Reliable software is built through continuous verification, not by chance.**
