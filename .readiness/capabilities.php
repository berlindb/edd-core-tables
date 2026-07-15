<?php
/**
 * EDD's relationship / meta capability matrix (curated, deepened).
 *
 * EDD's fork expresses cross-table access imperatively, so this is the curated inventory
 * of the patterns that need shared berlindb/core's RELATIONSHIP or META systems, each
 * mapped to the core feature that would express it. berlindb/readiness scores each entry
 * against the installed core; a dropped core feature turns a mapped entry into a gap.
 *
 * SCOPE - what is and is NOT an entry here (so the score is honest, not padded):
 *
 * - Only patterns that need the relationship/meta SYSTEM are entries. The many EDD
 *   "belongs_to" links (order.customer_id, notes.object_id, order_items.product_id,
 *   *.user_id, self-refs via `parent`, ...) are queried as scalar / `IN` WHERE filters on
 *   the child's FK column - i.e. they are core COLUMN filters (the `in` / `not_in` flags),
 *   already counted in the flag score. They are NOT re-listed here (no double-counting).
 *
 * - POLYMORPHIC OWNERSHIP (object_id + object_type on edd_notes, edd_logs, edd_logs_emails,
 *   edd_order_transactions, edd_order_adjustments): a child table shared across parent types
 *   via an object_id + object_type pair. Now EXPRESSIBLE by core: a relationship may carry a
 *   fixed `condition` (e.g. object_type => 'order'), so this ownership models as ONE
 *   conditioned has_many/belongs_to whose join/EXISTS is scoped to the right type - shipped in
 *   berlindb/core eedada8 (relationship.conditioned). See the entry below. (EDD's raw queries
 *   already ported via column filters, so this was a MODELING gap, never a behavioral one.)
 *
 * - EDD's fork CANNOT orderby a meta key (Query::parse_orderby has no meta_value branch);
 *   core's store meta path CAN (get_store_meta_orderby_sql, #204 Phase C) - core exceeds
 *   the fork here, so it is not a gap.
 *
 * `requires` values are berlindb/readiness CoreFeatures keys:
 *   relationship.belongs_to | relationship.has_many | relationship.many_to_many |
 *   relationship.get_related | meta.store | meta.preset
 *
 * Sources are the EDD fork Query/Schema classes (src/Database/Queries|Schemas/*).
 *
 * @package EDDCoreTables
 */

return array(

	/*
	 * Parent -> child collections that the fork realizes as INNER JOINs (the only
	 * genuine JOIN-based relationships in EDD; wired in Order/Order_Item query-clause
	 * filters). These need core has_many.
	 */
	array(
		'name'     => 'order -> order_items',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php:317 INNER JOIN edd_order_items ON order_id',
	),
	array(
		'name'     => 'order -> order_adjustments',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php:369 INNER JOIN edd_order_adjustments ON object_id (type=order)',
	),
	array(
		'name'     => 'order -> order_transactions',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php:342 INNER JOIN edd_order_transactions ON object_id',
	),
	array(
		'name'     => 'order -> order_addresses',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php:254 INNER JOIN edd_order_addresses ON order_id (1:1 in practice)',
	),

	// The owning inverse the fork also joins (order_item -> order for date/order filters).
	array(
		'name'     => 'order_item -> order (order_query join)',
		'kind'     => 'belongs_to',
		'requires' => 'relationship.belongs_to',
		'note'     => 'Order_Item.php:222 INNER JOIN edd_orders; supports a nested date_query on the parent',
	),

	// Read-time traversal of a relationship (fork walks relations via bespoke methods).
	array(
		'name'     => 'relationship traversal (get_related)',
		'kind'     => 'accessor',
		'requires' => 'relationship.get_related',
		'note'     => 'Core exposes get_related(); the fork hand-rolls per-parent accessors',
	),

	/*
	 * Self-referential parent chains (refund->order, bundled item->bundle, adjustment
	 * parent). The fork FILTERS these as a `parent` column, but modeling the chain for
	 * traversal is a belongs_to back to the same table.
	 */
	array(
		'name'     => 'self-reference (orders/order_items/adjustments .parent)',
		'kind'     => 'belongs_to',
		'requires' => 'relationship.belongs_to',
		'note'     => 'Schemas: Orders.php:46, Order_Items.php:47, Adjustments.php:46 - parent FK to same table',
	),

	/*
	 * WordPress-style metadata: eleven *meta sibling tables, queried entirely through
	 * WP_Meta_Query (EDD\Database\Queries\Meta is an empty WP_Meta_Query subclass). Core's
	 * store meta path reproduces exactly this shape ({object}_id/meta_key/meta_value) and
	 * is proven by core's own tests/Database/Kern/Query/MetaQueryRelationshipTest.php - the
	 * full surface (AND/OR, EXISTS/NOT EXISTS, CAST, LIKE/IN/BETWEEN/REGEXP, compare_key,
	 * IS NULL, fail-closed). Its EXISTS semi-join is structurally immune to the OR-meta
	 * duplicate-rows bug that hit the legacy JOIN engine (fixed 5159ca1).
	 */
	array(
		'name'     => 'metadata (11 *meta tables, WP_Meta_Query semantics)',
		'kind'     => 'meta',
		'requires' => 'meta.store',
		'note'     => 'ordermeta, order_itemmeta, order_adjustmentmeta, customermeta, adjustmentmeta, emailmeta, logmeta, logs_api_requestmeta, logs_file_downloadmeta, notemeta, logs_emailmeta; proven by core MetaQueryRelationshipTest',
	),

	/*
	 * Relationship-MODELING dimension: modeling polymorphic ownership as ONE relationship
	 * (for get_related traversal / declarative filters) rather than raw column filters. Now
	 * supported - core relationships carry a fixed `condition` (relationship.conditioned,
	 * shipped eedada8) that scopes the join/EXISTS to the right object_type. Kept as its own
	 * modeling-scope entry so a regression (core dropping the feature) reopens it.
	 */
	array(
		'name'     => 'polymorphic ownership traversal (object_id + object_type)',
		'kind'     => 'relationship-modeling',
		'requires' => 'relationship.conditioned',
		'scope'    => 'modeling',
		'note'     => 'notes/logs/logs_emails/order_transactions/order_adjustments; modeled as a conditioned has_many/belongs_to (condition: object_type => <type>) - berlindb/core eedada8',
	),
);
