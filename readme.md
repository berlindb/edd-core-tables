# edd-core-tables

![EDD readiness](https://img.shields.io/endpoint?url=https://raw.githubusercontent.com/berlindb/edd-core-tables/master/.readiness/edd.json)

Easy Digital Downloads' core database tables, expressed as [`berlindb/core`](https://github.com/berlindb/core)
schemas - **auto-generated** by introspecting a live EDD install, and continuously
tested to measure whether today's shared BerlinDB can faithfully reproduce them.

The **readiness** badge above is the *behavioral* score from
[berlindb/readiness](https://github.com/berlindb/readiness): how much of EDD's fork
behavior shared `berlindb/core` can express - both its per-column flags (`sortable`,
`searchable`, `in`, `compare`, ...) and its relationships/meta (the hand-coded JOINs and
`*meta` tables, mapped in [`.readiness/capabilities.php`](.readiness/capabilities.php)).
A gap is a concrete item on the path to reunifying EDD onto shared core.

## Why this exists

EDD does not consume `berlindb/core`. Its `EDD\Database\*` layer is a hand-copied,
first-generation *fork* of BerlinDB frozen inside the plugin. A long-term goal is to
reunify EDD onto shared BerlinDB. This repo measures the distance to that goal:

- It declares each EDD core table as a `berlindb/core` schema - **generated**, never
  hand-copied, so it cannot drift from EDD.
- A **capability test** asks the only question that matters for reunification: *can
  today's `berlindb/core` recreate this table exactly?* Where it can't, that's a
  concrete gap to close in core.

## How it works

1. **Generate** (`bin/generate-schemas.php`) - boots a live EDD install, then reads each
   `edd_*` table from `information_schema` and emits a `berlindb/core` Schema class into
   [`src/Schemas/`](src/Schemas/). `information_schema` is used (not core's own
   `Schema::from_table()`) because it faithfully carries decimal scale, `unsigned`, and
   index prefix lengths.
2. **Capability test** (`tests/CapabilityTest.php`) - for each generated schema, core
   creates a scratch table from it, that table is re-introspected, and the result is
   compared column-for-column and index-for-index against EDD's live table. A match
   means core can express that table exactly.

The test is **strict**: there is no allowlist. Any column or index core cannot reproduce
turns the suite red.

### Structural parity only

Generation reads the DDL, so it captures columns and indexes. It intentionally does
**not** capture the higher-level semantics EDD hand-codes (the `sortable` / `searchable`
/ `in` / `date_query` / `validate` / `transition` / `cache_key` flags, meta wiring,
relationships) - those are invisible to `SHOW CREATE TABLE`. This proves *structural*
parity, not *behavioral* parity.

## Current status

**25 of 28 tables reproduce exactly** against a live EDD install on MySQL 8. Two core
fixes got it there:

- **[core#244](https://github.com/berlindb/core/issues/244)** - `decimal(P,S)` scale is
  now expressible (EDD's money is `decimal(18,9)`; it had been recreated as
  `decimal(18,0)`).
- **[core#245](https://github.com/berlindb/core/issues/245)** - core no longer emits an
  illegal `DEFAULT ''` on NOT NULL TEXT/BLOB/spatial columns (it had blocked *every*
  EDD table from creating).

The **3 remaining** reds (`emails`, `logs_emails`, `sessions`) are one specific,
non-fatal behavior: core supplies a `DEFAULT` (`''` or `0`) on NOT NULL *non-LOB*
columns that EDD leaves defaultless (e.g. `session_key`, `session_expiry`, `email_id`).
The tables still create; they just aren't byte-identical. This is the remaining item in
core#245 (let a column express "no default"), and is the current reunification
punch-list.

## Staying current

A scheduled workflow polls EDD's latest **stable release** and **master**, regenerates
the schemas, and opens a PR when EDD's schema changes - so this repo tracks EDD
automatically and flags the day EDD adds something core must accommodate. CI runs the
capability test against both EDD stable and master.

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
