<?php
/**
 * EDD's relationship / meta capability matrix (curated).
 *
 * EDD's fork expresses relationships IMPERATIVELY - hand-coded SQL JOINs in its Query
 * classes - and metadata as WordPress-style sibling tables, so (unlike column flags)
 * there is nothing to auto-scan. This file is the curated inventory of those patterns,
 * each mapped to the shared berlindb/core feature that would express it after
 * reunification. berlindb/readiness scores each entry against the installed core, so a
 * dropped core feature turns a mapped entry into a gap.
 *
 * `requires` values are berlindb/readiness CoreFeatures keys:
 *   relationship.belongs_to | relationship.has_many | relationship.many_to_many |
 *   relationship.get_related | meta.store | meta.preset
 *
 * Sources are the EDD fork Query classes (src/Database/Queries/*) and its meta tables.
 *
 * @package EDDCoreTables
 */

return array(

	// Parent -> child collections, hand-coded as INNER JOINs in EDD\Database\Queries\Order.
	array(
		'name'     => 'order -> order_items',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php INNER JOIN {$wpdb->edd_order_items} ON order_id',
	),
	array(
		'name'     => 'order -> order_adjustments',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php INNER JOIN {$wpdb->edd_order_adjustments}',
	),
	array(
		'name'     => 'order -> order_transactions',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php INNER JOIN {$wpdb->edd_order_transactions}',
	),
	array(
		'name'     => 'order -> order_addresses',
		'kind'     => 'has_many',
		'requires' => 'relationship.has_many',
		'note'     => 'Order.php INNER JOIN {$wpdb->edd_order_addresses}',
	),

	// The inverse (child points at its parent), e.g. order_item.order_id -> order.
	array(
		'name'     => 'order_item / adjustment / transaction / address -> order',
		'kind'     => 'belongs_to',
		'requires' => 'relationship.belongs_to',
		'note'     => 'Foreign key columns on the child tables reference edd_orders.id',
	),

	// Read-time traversal of a relationship.
	array(
		'name'     => 'relationship traversal (get_related)',
		'kind'     => 'accessor',
		'requires' => 'relationship.get_related',
		'note'     => 'Fork walks relations via bespoke query methods; core exposes get_related()',
	),

	// WordPress-style metadata: ten *meta sibling tables via EDD\Database\Queries\Meta.
	array(
		'name'     => 'metadata (10 *meta tables)',
		'kind'     => 'meta',
		'requires' => 'meta.store',
		'note'     => 'ordermeta, order_itemmeta, order_adjustmentmeta, customermeta, adjustmentmeta, emailmeta, logmeta, logs_api_requestmeta, logs_file_downloadmeta, notemeta',
	),
);
