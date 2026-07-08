# DOC-006 REST API Specification

**Project:** RajLED AI Ads OS
**Version:** 0.2.0-alpha.1
**Status:** Initial

---

# Scope

This document describes the REST API surface implemented for the foundation platform.

This version exposes framework health and version endpoints.

S1-003 extends the health payload with Google Ads integration readiness only. Integration readiness is provided through the IntegrationRegistry. It does not expose Google Ads campaign data or execute live Google Ads API requests.

TASK-003.3 isolates future Google Ads PHP SDK usage behind an SDK adapter layer. REST response shapes are unchanged.

Out of scope:

* Google Ads campaign data endpoints
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
  "engines": 0,
  "google_ads": {
    "status": "not_configured",
    "configured": false,
    "missing_credentials": [
      "developer_token",
      "client_id",
      "client_secret",
      "refresh_token"
    ]
  }
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

Unreleased

S1-003 Google Ads Integration Foundation. Health response includes Google Ads readiness status.

S1-003 TASK-003.2 Integration Provider Framework. Health response shape is unchanged and provider readiness is resolved through IntegrationRegistry.

S1-003 TASK-003.3 Google Ads SDK Isolation. REST response shape is unchanged.

0.2.0-alpha.1

Infrastructure layer release. Endpoint contract unchanged.

0.1.0-alpha.1

Initial REST API foundation.
