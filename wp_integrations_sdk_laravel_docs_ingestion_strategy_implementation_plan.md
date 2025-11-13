# Overview

This document specifies the strategy and end‑to‑end implementation plan for:

1) **WordPress Integrations SDK (execution-only)**
2) **Laravel App (discovery + ingestion + documentation)**

We will follow a **Manifest‑First** architecture: the WordPress SDK produces a deterministic, versioned JSON manifest of all integrations, triggers, and actions during build time. The Laravel app consumes this manifest to store integration metadata, generate human‑friendly documentation (optionally via LLM), expose searchable UIs (Filament + Livewire), and provide an API for internal/front-end use.

---

## Goals & Non‑Goals

**Goals**
- Single source of truth for integrations and their triggers/actions.
- Zero runtime coupling: Laravel does not execute or load SDK code; it only imports metadata.
- Deterministic, reproducible manifests with strong validation and versioning.
- Fast developer ergonomics: local and CI builds always regenerate manifests.
- Elegant admin UI for browsing integrations & endpoints, with doc previews.

**Non‑Goals**
- Executing integrations in Laravel.
- Booting or depending on a WordPress runtime in Laravel.

---

## Architecture at a Glance

- **SDK (WP Plugin)**
  - Metadata lives in PHP 8 Attributes (or static registries as a fallback).
  - Build script scans source, validates, writes `dist/manifest.json` + `dist/integrations/*.json`.
  - CI publishes artifacts (commit to repo, release assets, or push to S3).

- **Laravel**
  - Importer reads the manifest URL/path, validates, versions, and upserts DB records.
  - LLM Docs Generator creates/updates endpoint documentation entries per checksum change.
  - Filament Resources + Livewire Catalog for search, inspect, and regenerate docs.
  - Public/internal API endpoints to deliver the catalog to other systems.

---

# Part A — WordPress Integrations SDK (Execution‑Only)

## A1. Source Layout

```
src/
  Attributes/
    Integration.php
    Trigger.php
    Action.php
  Integrations/
    WooCommerce/
      WooCommerce.php          # #[Integration(...)]
      Triggers/
        OrderCreated.php       # #[Trigger(...)]
        ...
      Actions/
        UpdateOrderStatus.php  # #[Action(...)]
        ...
scripts/
  generate-manifest.php        # entry point
  src/Manifest/
    Generator.php
    Schema.php
    Support/ClassDiscovery.php
composer.json
.distignore (optional)
dist/
  manifest.json
  integrations/{slug}.json
```

> **Naming policy**: namespaces should include the integration ID (e.g., `Integrations\WooCommerce\...`) so the generator can infer ownership.

## A2. Metadata Contracts (Attributes)

- `#[Integration(id, name, slug?, since?, homepage?, tags?)]`
- `#[Trigger(id, label, description?, payloadSchema?, examples?, tags?, since?)]`
- `#[Action(id, label, description?, inputSchema?, outputSchema?, examples?, tags?, since?)]`

Use JSON‑Schema‑like arrays for schemas. Keep IDs globally unique per integration.

## A3. Manifest Format

**File:** `dist/manifest.json`

```json
{
  "plugin": "acme/wp-integrations-sdk",
  "name": "ACME Integrations SDK",
  "version": "1.4.2",
  "generated_at": "2025-11-03T09:12:33Z",
  "checksum": "sha256:...",
  "integrations": [
    {
      "id": "woocommerce",
      "name": "WooCommerce",
      "slug": "woocommerce",
      "since": "1.0.0",
      "homepage": "https://woocommerce.com",
      "tags": ["commerce"],
      "triggers": [
        {
          "id": "woocommerce.order.created",
          "label": "Order Created",
          "description": "Fires when a new order is created",
          "payloadSchema": { "type": "object", "required": ["order_id"] },
          "examples": [{ "order_id": 123 }],
          "tags": ["orders"],
          "since": "1.0.0"
        }
      ],
      "actions": [
        {
          "id": "woocommerce.order.update_status",
          "label": "Update Order Status",
          "description": "Set the status of an order",
          "inputSchema": { "type": "object", "required": ["order_id", "status"] },
          "outputSchema": { "type": "object", "properties": { "success": {"type": "boolean"} } },
          "examples": [{ "order_id": 123, "status": "completed" }],
          "tags": ["orders"],
          "since": "1.0.0"
        }
      ]
    }
  ]
}
```

Also write **per‑integration files**: `dist/integrations/{slug}.json` (same content as each `integrations[]` item).

## A4. Generator Behavior

- Discover classes using Composer ClassMap on configured `src/` paths and namespace prefixes.
- Identify Integrations via `#[Integration]` at class level.
- Attach Triggers/Actions to the nearest Integration by inspecting namespace segments.
- Validate shapes (`Schema.php`), sort deterministically, compute checksum.
- Fail build if:
  - Duplicate trigger/action IDs under the same integration.
  - Required fields missing or invalid.
  - Output directory cannot be written.

## A5. Build Integration

**Composer scripts**
```json
{
  "scripts": {
    "generate-manifest": "php scripts/generate-manifest.php",
    "post-autoload-dump": [
      "@generate-manifest"
    ]
  },
  "autoload-dev": {
    "psr-4": { "Acme\\WpSdk\\Build\\": "scripts/src/" }
  },
  "require-dev": {
    "composer/class-map-generator": "^1.0",
    "justinrainbow/json-schema": "^5.2"
  }
}
```

**GitHub Actions** (`.github/workflows/build.yml`)
```yaml
name: Build & Publish Manifest
on: { push: { branches: [ main ] }, workflow_dispatch: {} }
jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with: { php-version: '8.2', coverage: none }
      - run: composer install --no-interaction --prefer-dist
      - run: composer generate-manifest
      - uses: actions/upload-artifact@v4
        with:
          name: sdk-manifest
          path: |
            dist/manifest.json
            dist/integrations/*.json
      # Optional: commit/attach to releases or push to S3 here
```

**Local DX**
- Running `composer install` or `composer dump-autoload` regenerates the manifest.
- `composer generate-manifest` to run manually.

## A6. Testing

- Unit tests for Attribute presence and ID uniqueness.
- Snapshot tests for `dist/manifest.json` (after sorting) to catch accidental changes.
- CI fails on schema violations or duplicate IDs.

---

# Part B — Laravel App (Ingestion + Docs)

## B1. Requirements & Packages

- Laravel 10+
- **Recommended packages**
  - `spatie/laravel-data` — DTO validation/parsing for manifest.
  - `spatie/laravel-markdown` or `league/commonmark` — render Markdown docs.
  - `orchestra/testbench` — if extracting importer as a package.
  - `spatie/laravel-translatable` (optional) — multilingual docs.
  - `spatie/laravel-schemaless-attributes` (optional) — flexible metadata.
  - Filament 3 + Livewire 3 for admin UI.

## B2. Data Model

**Tables**
- `integrations` (id, slug, name, homepage, since, current_version_id FK, timestamps)
- `integration_versions` (id, integration_id, source_version, checksum, imported_at, timestamps)
- `endpoints` (id, integration_version_id, type enum[`trigger`,`action`], key, label, description nullable, tags json, input_schema json, output_schema json, payload_schema json, examples json)
- `endpoint_docs` (id, endpoint_id, language, model, quality_score nullable, body_markdown, generated_at, timestamps)

**Indexes**
- `endpoints(integration_version_id, type, key)` unique
- `integration_versions(integration_id, checksum)` unique

## B3. Importer Flow

1. **Fetch** manifest (local path, S3, or HTTP). Configurable in `config/integrations.php`.
2. **Parse/validate** with `spatie/laravel-data` DTOs (strict mode; fail fast).
3. **Versioning**
   - Compute/compare checksum.
   - If checksum **new** for an integration: create new `integration_versions` and related `endpoints`.
   - If unchanged: skip endpoint re‑create.
4. **Idempotency** ensured via unique keys and checksum checks.

**Artisan commands**
- `integrations:import --path=/path/or/url` (or use config default)
- `integrations:rebuild-docs --integration=woocommerce --force` (see B5)

## B4. DTOs (Sketch)

- `ManifestData { plugin, name, version, generated_at, checksum, integrations: IntegrationData[] }`
- `IntegrationData { id, name, slug?, since?, homepage?, tags[], triggers: TriggerData[], actions: ActionData[] }`
- `TriggerData { id, label, description?, payloadSchema?, examples?, tags?, since? }`
- `ActionData { id, label, description?, inputSchema?, outputSchema?, examples?, tags?, since? }`

Validate `id`, `label` non‑empty; arrays default to `[]`.

## B5. LLM Docs Generation

- **When**: after a new `integration_version` is created (checksum change) or on demand with `--force`.
- **How**: queue a job per endpoint that builds a structured prompt:
  - Context: integration name, endpoint type (trigger/action), purpose.
  - Schemas: fields, enums, constraints.
  - Examples: inputs/payloads.
  - Style: accessible, professional, actionable; include usage steps, caveats, rate limits if relevant.
- **Storage**: persist Markdown to `endpoint_docs` with `language`, `model`, and optional `quality_score`.
- **Regeneration policy**: only if no docs exist for that endpoint+version+language or `--force`.

## B6. Filament + Livewire UI

- **Filament Resources**
  - `IntegrationResource` (list: name, slug, homepage, current version; show: tabs for Versions, Endpoints, Docs preview).
  - `EndpointResource` (filters: type, tags; action buttons: View Docs, Regenerate Docs).
- **Livewire Catalog**
  - Search across `id`, `label`, `tags`.
  - Facets by integration, type, tag.
  - Inline Markdown preview of docs.

## B7. Public/Internal API Endpoints

- `GET /api/integrations` — list w/ pagination & filters.
- `GET /api/integrations/{slug}` — detail; includes current version + endpoint counts.
- `GET /api/integrations/{slug}/endpoints` — filterable by type & tag.
- `GET /api/endpoints/{id}/docs` — latest docs (by language).

Use simple Transformers or `spatie/laravel-query-builder`.

## B8. Config

`config/integrations.php`
```
return [
  'manifest_source' => env('INTEGRATIONS_MANIFEST_SOURCE', 'local'), // local|s3|http
  'manifest_path'   => env('INTEGRATIONS_MANIFEST_PATH', base_path('manifests/sdk/manifest.json')),
  'http_timeout'    => 8,
  'llm' => [
    'provider' => env('DOCS_LLM_PROVIDER', 'openai'),
    'model'    => env('DOCS_LLM_MODEL', 'gpt-4.1-mini'),
    'language' => env('DOCS_LLM_LANG', 'en'),
  ],
];
```

## B9. Migrations (Sketch)

- Create tables above with appropriate JSON columns (`input_schema`, `output_schema`, `payload_schema`, `examples`, `tags`).
- Use enums or check constraints for `type`.

## B10. Jobs & Queues

- `ImportIntegrationsJob` — wraps importer; supports `--path` and source selection.
- `GenerateEndpointDocsJob` — one per endpoint; retries on transient LLM errors; stores Markdown.
- `BackfillDocsJob` — regenerate missing language variants.

## B11. Testing

- Unit tests for DTO validation and importer idempotency.
- Feature tests for Filament resources.
- API tests for list/detail endpoints.

## B12. Security & Governance

- Manifests are data‑only; no code execution. Validate shapes strictly.
- If fetching over HTTP/S3, verify checksum (match header/metadata) before import.
- Access control: Filament admin behind proper roles/permissions.

---

# Part C — Delivery Plan & Milestones

**Milestone 1 — SDK Manifest Generator (2–3 days)**
- Attributes, generator, schema checks, Composer script, CI workflow.
- Output deterministic `dist/` artifacts. Basic unit tests + snapshots.

**Milestone 2 — Laravel Importer (2–3 days)**
- DTOs, migrations, importer command, checksum versioning.
- Seed with sample manifest; verify idempotency.

**Milestone 3 — Docs Generator + UI (3–4 days)**
- LLM prompt builder, jobs, storage; Filament resources; Livewire catalog.
- Regenerate docs on checksum change; preview Markdown.

**Milestone 4 — API + Polish (1–2 days)**
- Public/internal endpoints; filters; rate limits; documentation.

---

# Part D — Risks & Mitigations

- **Drift between code and docs** → Manifest generated on every build; docs regenerated on checksum change; snapshot tests.
- **Duplicate IDs** → hard fail CI; add unit test for uniqueness.
- **Schema sprawl** → use JSON‑Schema patterns and shared helpers; add schema validator in generator.
- **Breaking changes** → semantic versioning in SDK; Laravel stores per‑version records.

---

# Part E — Developer Runbooks

**SDK Dev**
- Add a new integration class with `#[Integration(...)]`.
- Add triggers/actions with attributes; run `composer dump-autoload`.
- Verify `dist/manifest.json` + per‑integration files updated; run tests.

**Laravel Dev**
- `php artisan integrations:import` to pull latest manifest (path/URL).
- Review `Integration` in Filament; ensure endpoints and docs appear.
- `php artisan integrations:rebuild-docs --integration=woocommerce` if needed.

---

# Part F — Extension Points

- **Localization**: duplicate docs in `endpoint_docs` with different `language` values.
- **Doc quality scoring**: store evaluation metadata (`quality_score`) and allow manual overrides.
- **Changelogs**: diff consecutive versions per integration and auto‑generate release notes in Laravel.
- **SDK Multi‑package**: if integrations split into multiple Composer packages, the generator can merge their outputs into one manifest.

---

## Done‑Definition Checklist

- [ ] SDK builds produce deterministic `dist/manifest.json` and per‑integration files.
- [ ] CI fails on schema/ID issues.
- [ ] Laravel imports manifest idempotently and versions by checksum.
- [ ] Filament shows integrations, versions, endpoints, and docs.
- [ ] Docs regenerate automatically on manifest checksum change.
- [ ] Public/internal API delivers searchable catalog.

