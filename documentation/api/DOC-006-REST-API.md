# DOC-006 REST API Specification

**Project:** RajLED AI Ads OS
**Version:** 0.2.0-alpha.1
**Status:** Initial

---

# Scope

This document describes the REST API surface implemented for S1-001 Core Framework.

This version only exposes framework health and version endpoints.

Out of scope:

* Google Ads API integration
* Snapshot Engine
* Campaign Memory
* business recommendations
* execution workflows

---

# Namespace

```text
rajled-ai-ads/v1
```

WordPress base path:

```text
/wp-json/rajled-ai-ads/v1
```

---

# Response Format

All successful responses use the same envelope:

```json
{
  "success": true,
  "data": {},
  "meta": {},
  "errors": []
}
```

---

# Endpoints

## GET /wp-json/rajled-ai-ads/v1/health

Returns basic system health.

Response data:

```json
{
  "status": "ok",
  "version": "0.2.0-alpha.1",
  "engines": 0
}
```

## GET /wp-json/rajled-ai-ads/v1/version

Returns the current plugin version.

Response data:

```json
{
  "version": "0.2.0-alpha.1"
}
```

---

# Access

The S1-001 health and version endpoints are public read endpoints.

They expose only framework status and version metadata.

Version History

0.2.0-alpha.1

Infrastructure layer release. Endpoint contract unchanged.

0.1.0-alpha.1

Initial REST API foundation.
