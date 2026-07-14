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

### Status

**25 of 28 tables reproduce exactly** on MySQL 8. Two core fixes landed:

- **[berlindb/core#244](https://github.com/berlindb/core/issues/244)** - decimal scale is
  now expressible (`decimal(18,9)` no longer collapses to `decimal(18,0)`).
- **[berlindb/core#245](https://github.com/berlindb/core/issues/245)** - core no longer
  emits an illegal `DEFAULT ''` on NOT NULL TEXT/BLOB/spatial columns.

### Remaining (the punch-list)

- `emails`, `logs_emails`, `sessions` still differ: core supplies a `DEFAULT` on NOT NULL
  *non-LOB* columns EDD leaves defaultless (`session_key`, `session_expiry`, `email_id`).
  The tables create fine; they just are not byte-identical. Remaining item in core#245.

### Notes

- Structural parity only. EDD's hand-coded BerlinDB semantics (sortable/searchable/in/
  date_query/validate/transition/cache_key flags, meta wiring, relationships) are not in
  the DDL and are not reproduced here.
