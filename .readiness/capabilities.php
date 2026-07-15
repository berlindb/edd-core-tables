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
 *   edd_order_transactions, edd_order_adjustments): EDD's QUERIES port fine - object_id and
 *   object_type are two ordinary column filters core supports. But core relationships are
 *   pure column->column with no value condition (Kern/Relationship.php has no where/scope),
 *   so this ownership cannot be modeled as ONE core has_many/belongs_to for get_related()
 *   traversal (a join on object_id alone would cross object types). Recorded below as a
 *   relationship-MODELING limitation, not a behavioral gap - a candidate core feature
 *   (conditioned relationships). See the `relationship.conditioned` entry.
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
	 * Relationship-MODELING limitation (kept as an entry so it is scored/tracked, not
	 * hidden). Polymorphic ownership is expressible as core column filters TODAY, so this
	 * is not a behavioral gap; but modeling it as one relationship for traversal needs a
	 * value condition core relationships do not have. `requires` points at a feature core
	 * does NOT currently provide, so it scores as a gap - the one open reunification item.
	 */
	array(
		'name'     => 'polymorphic ownership traversal (object_id + object_type)',
		'kind'     => 'relationship-modeling',
		'requires' => 'relationship.conditioned',
		'scope'    => 'modeling',
		'note'     => 'notes/logs/logs_emails/order_transactions/order_adjustments; queries port as column filters (behavioral: fine), but core relationships have no value condition (Relationship.php) to scope object_type - modeling gap, candidate core feature',
	),
);
