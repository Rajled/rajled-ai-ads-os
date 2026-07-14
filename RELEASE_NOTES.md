# RELEASE_NOTES.md

# Release Notes Policy

**Project:** RajLED AI Ads OS

**Version:** 1.0

**Status:** Active

---

# Purpose

This document defines the release documentation policy for RajLED AI Ads OS.

Every product release must have its own Release Notes document.

Release Notes provide detailed information about a specific release, while `CHANGELOG.md` provides a chronological overview of the entire project.

---

# Release Notes Location

Individual release documents are stored in:

```text
documentation/
└── releases/
```

Examples:

```text
documentation/releases/v0.1.0.md
documentation/releases/v0.2.0.md
documentation/releases/v0.3.0.md
```

---

# Release Notes Template

Each release document should contain the following sections.

---

# Release Information

Release Version

Release Date

Sprint

Milestone

Status

---

# Overview

Short summary of the release.

Explain the purpose of the release and the delivered business value.

---

# Objectives

List the planned objectives for the release.

Example:

* Google Ads Integration Layer
* Snapshot Engine
* Dashboard improvements

---

# Delivered Features

List completed features.

Examples:

* Core Framework
* Engine Registry
* REST API
* Dashboard Foundation

---

# Architecture Changes

Describe architectural modifications.

Reference related ADR documents.

Example:

* ADR-002 Integration Layer Pattern

---

# Documentation

List updated documentation.

Examples:

* Data Model
* REST API Specification
* Engine Specifications

---

# Technical Changes

Important implementation details.

Examples:

* Database schema
* REST endpoints
* API integration
* Performance improvements

---

# Breaking Changes

Document incompatible changes.

If none:

"None."

---

# Migration Notes

Describe required migration steps.

If none:

"No migration required."

---

# Known Limitations

List features intentionally postponed.

Examples:

* Recommendation Engine
* Learning Engine

---

# Testing

Describe completed verification.

Examples:

* Manual Testing
* Integration Testing
* Regression Testing

---

# Sprint Summary

Summarize completed Sprint tasks.

Example:

S1-001 Core Framework

Completed

S1-002 Google Ads Integration

Completed

...

---

# Repository

Reference:

* Release Tag
* Commit
* Branch

---

# Related Documents

Reference:

* CHANGELOG.md
* ROADMAP.md
* ADR documents
* Technical Discovery
* Documentation Suite

---

# Next Release

Describe the expected focus of the next planned release.

---

# Approval

Prepared by

Reviewed by

Approved by

Release Date

---

# Release Lifecycle

Every release follows the same workflow.

```text
Sprint
    │
    ▼
Implementation
    │
    ▼
Review
    │
    ▼
Testing
    │
    ▼
Release Notes
    │
    ▼
Release Tag
    │
    ▼
Documentation Update
```

# v0.2.0-alpha.3

## Highlights

Sprint S1-004 introduces the complete Domain Foundation.

Major additions include:

- Domain Value Objects
- Campaign Entity
- Metrics Domain Model
- Integration Mapping Contracts

The project now possesses a provider-independent business model that will become the input for future Decision Engines.


Release Notes are considered part of the Definition of Done.

A release is not complete until its Release Notes have been prepared and reviewed.


# v0.2.0-alpha.4

## Highlights

Sprint S1-005 introduced the first Integration → Domain bridge.

The system now supports provider-independent mapping from normalized integration data into the Domain model while maintaining complete isolation from the Google Ads SDK.

Implementation of the first SDK reader has intentionally been deferred until the official Google Ads SDK is installed and verified.