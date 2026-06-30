# DEVELOPMENT.md

# Development Environment Guide

**Project:** RajLED AI Ads OS
**Version:** 1.0
**Status:** Active

---

# Purpose

This document describes the recommended development environment for RajLED AI Ads OS.

The objective is to ensure that every contributor works in a consistent, reproducible and maintainable environment.

This guide applies to both human developers and AI-assisted development workflows.

---

# Supported Platform

Current primary development platform:

* Windows 11
* Visual Studio Code
* Git
* GitHub
* Codex
* PHP 8.x
* WordPress
* Composer

Future Linux and macOS support may be documented separately.

---

# Required Software

The following software is required:

| Software                  | Purpose                    |
| ------------------------- | -------------------------- |
| Git                       | Version control            |
| GitHub Desktop (optional) | Repository management      |
| Visual Studio Code        | Primary IDE                |
| PHP 8.x                   | Runtime                    |
| Composer                  | Dependency management      |
| Local WordPress           | Plugin development         |
| Google Chrome / Edge      | Testing                    |
| Codex                     | AI-assisted implementation |

---

# Recommended VS Code Extensions

Recommended extensions include:

* PHP Intelephense
* PHP Debug
* EditorConfig
* GitLens
* Markdown All in One
* Error Lens
* Docker (optional)

Extensions should improve productivity without changing project behavior.

---

# Repository Setup

Clone the repository:

```bash
git clone <repository-url>
```

Open the project:

```bash
cd rajled-ai-ads-os
```

Install dependencies:

```bash
composer install
```

---

# Local Development Workflow

Recommended workflow:

```text
Open Repository
        │
        ▼
Pull Latest Changes
        │
        ▼
Create Feature Branch
        │
        ▼
Review Documentation
        │
        ▼
Implement
        │
        ▼
Test
        │
        ▼
Commit
        │
        ▼
Push
        │
        ▼
Pull Request
```

---

# Local WordPress Environment

The plugin should be developed using a local WordPress installation.

Recommended configuration:

* PHP 8.x
* Latest stable WordPress
* Debug mode enabled
* Pretty Permalinks enabled
* Local database
* HTTPS preferred

Development should never be performed directly on production systems.

---

# WordPress Configuration

Recommended settings:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('SCRIPT_DEBUG', true);
```

These settings improve diagnostics during development.

---

# Composer

Composer manages project dependencies.

Recommended commands:

```bash
composer install
composer update
composer dump-autoload
```

Do not manually edit the `vendor/` directory.

---

# Git Workflow

Development follows the project Git workflow.

Typical session:

```bash
git checkout main
git pull
git checkout -b feature/S1-001-core-framework
```

After implementation:

```bash
git add .
git commit -m "feat(core): implement kernel bootstrap"
git push
```

See `GIT_WORKFLOW.md` for details.

---

# Working with Codex

Codex is used as an implementation assistant.

Before requesting implementation:

1. Update the repository.
2. Verify documentation.
3. Confirm active Sprint.
4. Ensure `AGENTS.md` is up to date.

Codex should work on one task at a time.

Large implementations should be divided into smaller tasks.

---

# Debugging

Preferred debugging tools:

* WordPress Debug Log
* Browser Developer Tools
* PHP logs
* Structured application logs

Always investigate the root cause rather than masking errors.

---

# Project Documentation

Before implementing any feature, review:

1. README.md
2. AGENTS.md
3. ENGINEERING_STANDARDS.md
4. Documentation Suite
5. Current Sprint

Documentation is part of the development environment.

---

# Testing Before Commit

Before committing:

* verify implementation,
* review changed files,
* update documentation,
* execute required tests,
* confirm no unrelated files were modified.

---

# Repository Structure

Typical repository layout:

```text
rajled-ai-ads-os/
│
├── documentation/
├── src/
├── tests/
├── vendor/
│
├── README.md
├── AGENTS.md
├── ENGINEERING_STANDARDS.md
├── CONTRIBUTING.md
├── ROADMAP.md
└── ...
```

---

# Development Principles

Every development session should begin with:

* pulling the latest changes,
* reviewing active Sprint,
* reviewing relevant documentation.

Every session should end with:

* documentation updates,
* testing,
* commit,
* push,
* Pull Request.

---

# Troubleshooting

If unexpected behavior occurs:

1. Verify the current branch.
2. Pull the latest changes.
3. Check project documentation.
4. Review debug logs.
5. Reproduce the issue.
6. Document findings if necessary.

Significant discoveries should be recorded as Technical Discovery documents.

---

# Continuous Improvement

The development environment evolves together with the project.

New tools, workflows and recommendations should be documented here.

This document should remain the primary reference for setting up and maintaining the RajLED AI Ads OS development environment.

---

# References

Related documents:

* README.md
* AGENTS.md
* ENGINEERING_STANDARDS.md
* CONTRIBUTING.md
* GIT_WORKFLOW.md
* DOCUMENTATION_POLICY.md

---

**A consistent development environment enables consistent engineering.**
