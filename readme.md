# edd-core-tables

[![behavioral](https://img.shields.io/endpoint?url=https://raw.githubusercontent.com/berlindb/edd-core-tables/master/.readiness/edd.json "Behavioral readiness: can shared berlindb/core RUN EDD's queries? Column flags plus the relationship/meta patterns a query needs.")![modeling](https://img.shields.io/endpoint?url=https://raw.githubusercontent.com/berlindb/edd-core-tables/master/.readiness/edd-modeling.json "Modeling readiness: can core MODEL EDD's schema with faithful relationships? Adds patterns like polymorphic-ownership traversal that queries don't need but a faithful schema does.")](https://github.com/berlindb/readiness)

Easy Digital Downloads' core database tables, expressed as [`berlindb/core`](https://github.com/berlindb/core)
schemas - **auto-generated** by introspecting a live EDD install, and continuously
tested to measure whether today's shared BerlinDB can faithfully reproduce them.

The **readiness** chips above (from [berlindb/readiness](https://github.com/berlindb/readiness),
hover for details) score how much of EDD's fork shared `berlindb/core` can express:

- **behavioral** - can core *run* EDD's queries? Its per-column flags (`sortable`,
  `searchable`, `in`, `compare`, ...) plus the relationship/meta patterns a query needs.
- **modeling** - can core *model* EDD's schema with faithful relationships? Same, plus
  patterns queries don't need but a faithful schema does - e.g. polymorphic-ownership
  traversal, which core's relationships can't yet scope (no value condition), the one
  open reunification item.

The relationship/meta patterns are the curated, cited inventory in
[`.readiness/capabilities.php`](.readiness/capabilities.php).

## Why this exists

EDD does not consume `berlindb/core`. Its `EDD\Database\*` layer is a hand-copied,
first-generation *fork* of BerlinDB frozen inside the plugin. A long-term goal is to
reunify EDD onto shared BerlinDB. This repo measures the distance to that goal:

- It declares each EDD core table as a `berlindb/core` schema generated from a live
  install. A live-inventory test catches tables missing from the committed manifest.
- A **capability test** asks the only question that matters for reunification: *can
  today's `berlindb/core` recreate this table exactly?* Where it can't, that's a
  concrete gap to close in core.

## How it works

1. **Generate** (`bin/generate-schemas.php`) - boots a live EDD install, then reads each
   `edd_*` table from `information_schema` and emits a `berlindb/core` Schema class into
   [`src/Schemas/`](src/Schemas/). `information_schema` is used (not core's own
   `Schema::from_table()`) because it faithfully carries decimal scale, `unsigned`, and
   index prefix lengths.
2. **Capability test** (`tests/CapabilityTest.php`) - first compares the manifest with
   the live `edd_*` table inventory. For each table, it generates a schema from the
   live definition, asks core to create a scratch table, then compares the two live
   structures. A match means core can express that table exactly.

The test is **strict**: there is no allowlist. Any column or index core cannot reproduce
turns the suite red.

### Structural parity only

Generation reads the DDL, so it captures columns and indexes. It intentionally does
**not** capture the higher-level semantics EDD hand-codes (the `sortable` / `searchable`
/ `in` / `date_query` / `validate` / `transition` / `cache_key` flags, meta wiring,
relationships) - those are invisible to `SHOW CREATE TABLE`. This proves *structural*
parity, not *behavioral* parity.

## Current status

With EDD 3.7.0, all **30 live EDD core tables** reproduce exactly in CI on MySQL 8.0
against core `master` (PHP 8.1 and 8.3). The local MariaDB 10.2 suite also passes
against [core PR #259](https://github.com/berlindb/core/pull/259). Structural parity
does not establish behavioral parity with EDD's fork.

## Staying current

A scheduled workflow regenerates schemas from EDD's latest **stable release** and opens
a PR when they change. CI tests stable as a gate and EDD's `main` branch as an
informational early warning. The live-inventory assertion prevents a newly added
table from silently falling outside the capability suite.

## Running locally

```bash
composer install
# point core at a local checkout while developing (do not commit):
composer config repositories.berlindb-core path ../path/to/berlindb-core && composer update berlindb/core
bin/install-wp-tests.sh wordpress_test root '' 127.0.0.1 latest
composer test
```

Regenerate the schemas against a WordPress install that has EDD active:

```bash
wp eval-file bin/generate-schemas.php
```
