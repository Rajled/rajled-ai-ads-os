# GIT_WORKFLOW.md

# Git Workflow

**Project:** RajLED AI Ads OS
**Version:** 1.0
**Status:** Active

---

# Purpose

This document defines the Git workflow for RajLED AI Ads OS.

The goal is to keep the repository history clean, reviewable and aligned with the project’s sprint-based development process.

Git is not used only for storing code.

Git is used to track:

* source code,
* documentation,
* architecture decisions,
* technical discoveries,
* release history,
* engineering standards.

---

# Core Principles

The Git workflow follows these principles:

* small changes,
* clear history,
* feature branches,
* review before merge,
* documentation included with code,
* no direct work on `main`,
* every release is tagged.

---

# Main Branch

The `main` branch represents the stable project state.

Rules:

* `main` must always be deployable,
* direct commits to `main` are not allowed,
* only reviewed and accepted changes are merged into `main`,
* release tags are created from `main`.

---

# Feature Branches

Sprint branches use the following convention:

sprint/<sprint-id>-<short-description>

Examples:

sprint/S1-003-google-ads-integration-foundation
sprint/S1-004-snapshot-engine

---

# Bugfix Branches

Bug fixes use the following format:

```text
bugfix/<bug-id>-<short-description>
```

Examples:

```text
bugfix/BUG-014-health-endpoint
bugfix/BUG-021-snapshot-metadata
```

---

# Hotfix Branches

Critical production fixes use:

```text
hotfix/<hotfix-id>-<short-description>
```

Examples:

```text
hotfix/HF-001-oauth-token-refresh
hotfix/HF-002-rest-auth-check
```

---

# Documentation Branches

Documentation-only changes may use:

```text
docs/<short-description>
```

Examples:

```text
docs/update-roadmap
docs/add-adr-002
docs/release-notes-v0.1.0
```

---

# Commit Convention

The project follows Conventional Commits.

Format:

```text
<type>(<scope>): <description>
```

Examples:

```text
feat(core): implement kernel bootstrap
feat(snapshot): add structural snapshot engine
fix(rest): correct health endpoint response
docs: add README v1.0
docs(adr): add ADR-002 integration layer pattern
test(snapshot): add snapshot engine tests
chore(repo): initialize repository structure
```

---

# Commit Types

Allowed commit types:

```text
feat      new feature
fix       bug fix
docs      documentation only
style     formatting only
refactor  code restructuring without behavior change
test      tests
chore     maintenance
build     build system or dependencies
ci        CI/CD configuration
perf      performance improvement
security  security-related change
```

---

# Commit Rules

Each commit should:

* represent one logical change,
* have a clear message,
* avoid unrelated modifications,
* include documentation updates when required,
* be easy to review.

Avoid commits such as:

```text
update
changes
fixes
misc
final
new version
```

---

# Pull Request Workflow

Every feature branch should be merged through a Pull Request.

Pull Request checklist:

* task ID is referenced,
* scope is clear,
* documentation is updated,
* tests or verification steps are included,
* no unrelated files are changed,
* architecture rules are respected.

---

# Merge Policy

Preferred merge strategy:

```text
Squash and merge
```

Reason:

* keeps `main` history clean,
* groups task work into one logical commit,
* simplifies release history.

Merge commit message should follow Conventional Commits.

Example:

```text
feat(core): implement S1-001 core framework
```

---

# Release Tags

Every release must be tagged.

Tag format:

```text
vMAJOR.MINOR.PATCH
```

Examples:

```text
v0.1.0
v0.2.0
v1.0.0
```

Release tags are created only from `main`.

---

# Sprint Branches

Sprint branches are not required by default.

The project uses feature branches directly from `main`.

This keeps the workflow simple and suitable for a small team supported by AI agents.

If the project grows, sprint branches may be introduced later.

---

# Working with AI Agents

AI agents must work on one task at a time.

AI-generated changes should be:

* small,
* scoped,
* reviewable,
* aligned with AGENTS.md,
* aligned with ENGINEERING_STANDARDS.md.

AI agents must not:

* push directly to `main`,
* implement unrelated features,
* rewrite large parts of the repository without approval,
* change architecture without ADR.

---

# Local Workflow

Recommended local workflow:

```bash
git status
git checkout main
git pull
git checkout -b feature/S1-001-core-framework
```

After changes:

```bash
git status
git add .
git commit -m "feat(core): implement kernel bootstrap"
git push -u origin feature/S1-001-core-framework
```

Then open a Pull Request on GitHub.

---

# First Repository Commit

The first commit should initialize repository governance and project structure.

Recommended commit message:

```text
chore(repo): initialize repository structure
```

Initial commit may include:

* README.md
* AGENTS.md
* ENGINEERING_STANDARDS.md
* CONTRIBUTING.md
* ROADMAP.md
* CHANGELOG.md
* RELEASE_NOTES.md
* GIT_WORKFLOW.md
* documentation directories
* .gitignore

---

# Protected Branches

When GitHub repository is created, `main` should be protected.

Recommended protections:

* require Pull Request before merge,
* require status checks when available,
* prevent force pushes,
* prevent deletion,
* restrict direct commits.

---

# Documentation and Git

Documentation changes follow the same workflow as code changes.

Documentation is version-controlled because it represents project knowledge.

Architecture, ADR and Technical Discovery documents must never live outside the repository.

---

# Emergency Changes

Emergency hotfixes are allowed only for production-critical issues.

Hotfix workflow:

```text
main
  ↓
hotfix/HF-xxx-description
  ↓
review
  ↓
main
  ↓
tag patch release
```

Every hotfix requires release notes.

---

# Summary

This workflow is designed to keep RajLED AI Ads OS:

* stable,
* traceable,
* reviewable,
* easy to maintain,
* suitable for human and AI-assisted development.

All contributors and AI agents must follow this Git workflow.

---

# Sprint Completion Workflow

After all sprint tasks have been completed, the sprint follows the workflow below.

```text
TASK Completion
        ↓
Architecture Review
        ↓
Documentation Review
        ↓
Code Review
        ↓
Acceptance Tests
        ↓
Commit
        ↓
Push
        ↓
Sprint Retrospective
        ↓
Sprint Acceptance
        ↓
Merge to main
        ↓
Tag
        ↓
Release Notes
```

Sprint Retrospective must be completed before Sprint Acceptance.

Sprint Acceptance confirms that:

* all planned sprint tasks have been completed,
* architecture review has passed,
* documentation review has passed,
* code review has passed,
* acceptance tests have passed,
* release documentation has been updated,
* deferred architectural improvements have been recorded in `ARCHITECTURE_BACKLOG.md`.


### Sprint Documentation

After Sprint Acceptance update only:

1. CHANGELOG.md
2. RELEASE_NOTES.md
3. documentation/RETROSPECTIVES.md
4. documentation/sprints/SPR-XXX.md

Update architecture documentation only if the accepted sprint changes the project architecture.

Update governance documentation only when the project workflow or development standards change.### Sprint Documentation

After Sprint Acceptance update only:

1. CHANGELOG.md
2. RELEASE_NOTES.md
3. documentation/RETROSPECTIVES.md
4. documentation/sprints/SPR-XXX.md

Update architecture documentation only if the accepted sprint changes the project architecture.

Update governance documentation only when the project workflow or development standards change.