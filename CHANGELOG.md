# Changelog

## [Unreleased]

### Added

- Generator (`bin/generate-schemas.php` + `EDDCoreTables\Generator\SchemaGenerator`) that
  introspects a live EDD install via `information_schema` and emits one `berlindb/core`
  Schema class per EDD core table into `src/Schemas/`. Captures decimal scale, `unsigned`
  (explicitly, since core defaults numeric columns to unsigned), real NULL defaults, and
  index prefix lengths - none of which core's own `Schema::from_table()` recovers.
- Generated schemas for EDD's core tables, plus a `manifest.php` (class -> table map).
- `CapabilityTest` - strict, no allowlist: for each schema, core builds a scratch table
  from it, which is re-introspected and compared against EDD's live table. Red on any
  structural difference core cannot reproduce.
- CI matrix over EDD **stable** and **master**, and a scheduled workflow that regenerates
  from EDD's latest stable release and opens a PR on any change.

### Known gaps

- **Red on [berlindb/core#244](https://github.com/berlindb/core/issues/244):** core cannot
  express `decimal(P,S)` scale, so EDD's `decimal(18,9)` money columns are recreated as
  `decimal(18,0)`. The suite goes green when core gains a scale concept.

### Notes

- Structural parity only. EDD's hand-coded BerlinDB semantics (sortable/searchable/in/
  date_query/validate/transition/cache_key flags, meta wiring, relationships) are not in
  the DDL and are not reproduced here.
