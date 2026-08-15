<?php
/**
 * Plugin Name: Persistent Account Menu for HivePress
 * Plugin URI: https://github.com/irapidchris-del/Persistent-Account-Menu-for-HivePress
 * Description: Keeps HivePress account menu items visible even when they are empty, and replaces each empty page with a helpful notice, icon and button.
 * Version: 1.6.1
 * Author: Chris B
 * Author URI: https://community.hivepress.io/u/chrisb
 * Text Domain: persistent-account-menu-for-hivepress
 * Domain Path: /languages
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * Requires Plugins: hivepress
 * Update URI: https://github.com/irapidchris-del/Persistent-Account-Menu-for-HivePress
 *
 * @package PersistentAccountMenu
 */

namespace PersistentAccountMenu;

use HivePress\Helpers as hp;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*
 * Translations load through WordPress's just-in-time mechanism from the
 * system location (`wp-content/languages/plugins/`), the same as HivePress
 * core and every official extension: none of them calls
 * `load_plugin_textdomain()`, and bundled `.mo` files would not be
 * auto-loaded anyway. Only the POT template ships in `languages/`.
 */

/**
 * Gets the default managed menu items.
 *
 * Routes, orders, display conditions, empty-page redirects and page
 * block names are source-verified against HivePress 1.7.27,
 * Favorites 1.2.2, Messages 1.4.0, Bookings 1.5.5, Marketplace 1.3.15,
 * Memberships 2.2.0, Requests 1.2.5 and Search Alerts 1.1.3. Items
 * whose route is not registered (extension inactive) are skipped
 * automatically. Titles are only used for the settings screen.
 *
 * @return array<string, array<string, mixed>>
 */
function get_default_items() {
	static $items = null;

	if ( null !== $items ) {
		return $items;
	}

	$items = [
		'listings_edit'      => [
			'title'  => __( 'Listings', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'listings_edit_page',
			'_order' => 10,
			'notice' => [
				'icon'   => 'f03a',
				'text'   => __( "You haven't added any listings yet. Once you add your first listing, you can return to this page to view, edit and manage it.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Add listing', 'persistent-account-menu-for-hivepress' ),
					'route' => 'listing_submit_page',
				],
				'blank'  => [ 'listings' ],
			],
		],

		'requests_edit'      => [
			'title'  => __( 'Requests', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'requests_edit_page',
			'_order' => 10,
			'notice' => [
				'icon'   => 'f0ae',
				'text'   => __( "You haven't posted any requests yet. Once you post a request, you can return to this page to manage it and review offers.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Post a request', 'persistent-account-menu-for-hivepress' ),
					'route' => 'request_submit_page',
				],
				'blank'  => [ 'requests' ],
			],
		],

		'offers_view'        => [
			'title'  => __( 'Offers', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'offers_view_page',
			'_order' => 15,
			'notice' => [
				'icon'   => 'f02c',
				'text'   => __( "You haven't made any offers yet. When you make an offer on a request, it will appear here.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Browse requests', 'persistent-account-menu-for-hivepress' ),
					'route' => 'requests_view_page',
				],
				'blank'  => [ 'offers' ],
			],
		],

		'listings_favorite'  => [
			'title'  => __( 'Favorites', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'listings_favorite_page',
			'_order' => 20,
			'notice' => [
				'icon'   => 'f004',
				'text'   => __( "You haven't added any listings to your favourites yet. Once you click the heart icon on a listing, you can return to this page to find the listing more easily next time.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Browse listings', 'persistent-account-menu-for-hivepress' ),
					'route' => 'listings_view_page',
				],
				'blank'  => [ 'listings' ],
			],
		],

		'vendor_calendar'    => [
			'title'  => __( 'Calendar (vendors)', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'vendor_calendar_page',
			'_order' => 25,
			'vendor' => true,
			'notice' => [
				'icon'   => 'f073',
				'text'   => __( 'Your calendar shows the bookings made for your listings. Add a listing to get started.', 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Add listing', 'persistent-account-menu-for-hivepress' ),
					'route' => 'listing_submit_page',
				],
				'blank'  => [],
			],
		],

		'search_alerts_view' => [
			'title'  => __( 'Saved Searches', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'search_alerts_view_page',
			'_order' => 25,
			'notice' => [
				'icon'   => 'f002',
				'text'   => __( "You haven't saved any searches yet. Save a search to be notified when new matching listings are added.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Browse listings', 'persistent-account-menu-for-hivepress' ),
					'route' => 'listings_view_page',
				],
				'blank'  => [ 'search_alerts' ],
			],
		],

		'bookings_view'      => [
			'title'  => __( 'Bookings', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'bookings_view_page',
			'_order' => 27,
			'notice' => [
				'icon'   => 'f274',
				'text'   => __( "You don't have any bookings yet. When you make or receive a booking, the details will appear here.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Browse listings', 'persistent-account-menu-for-hivepress' ),
					'route' => 'listings_view_page',
				],
				'blank'  => [ 'bookings' ],
			],
		],

		'messages_thread'    => [
			'title'   => __( 'Messages', 'persistent-account-menu-for-hivepress' ),
			'route'   => 'messages_thread_page',
			'_order'  => 30,
			'enabled' => __NAMESPACE__ . '\\is_message_storage_enabled',
			'notice'  => [
				'icon'   => 'f086',
				'text'   => __( "You haven't exchanged any messages yet. When you send or receive a message, the conversation will appear here.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'Browse listings', 'persistent-account-menu-for-hivepress' ),
					'route' => 'listings_view_page',
				],
				'blank'  => [ 'messages' ],
			],
		],

		'memberships_view'   => [
			'title'  => __( 'Membership', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'memberships_view_page',
			'_order' => 35,
			'notice' => [
				'icon'   => 'f2c2',
				'text'   => __( "You don't have a membership yet. Choose a plan to get started.", 'persistent-account-menu-for-hivepress' ),
				'button' => [
					'label' => __( 'View plans', 'persistent-account-menu-for-hivepress' ),
					'route' => 'membership_plans_view_page',
				],
				'blank'  => [ 'memberships' ],
			],
		],

		'orders_edit'        => [
			'title'  => __( 'Received Orders (vendors)', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'orders_edit_page',
			'_order' => 35,
			'vendor' => true,
			'notice' => [
				'icon'  => 'f07a',
				'text'  => __( "You haven't received any orders yet. When a customer places an order with you, it will appear here.", 'persistent-account-menu-for-hivepress' ),
				'blank' => [ 'orders' ],
			],
		],

		'payouts_view'       => [
			'title'  => __( 'Payouts (vendors)', 'persistent-account-menu-for-hivepress' ),
			'route'  => 'payouts_view_page',
			'_order' => 45,
			'vendor' => true,
			'notice' => [
				'icon'  => 'f0d6',
				'text'  => __( "You don't have any payouts yet. Once you request a payout, its status will appear here.", 'persistent-account-menu-for-hivepress' ),
				'blank' => [ 'payouts' ],
			],
		],
	];

	/*
	 * The WooCommerce items that HivePress core links into the account
	 * menu. Their pages already render native WooCommerce empty states,
	 * so no notice is set - and, deliberately, NO WooCommerce function
	 * is called here. This function first runs from the routes filter on
	 * `init`, before `wp_loaded`, and `wc_get_account_menu_items()`
	 * loads the available payment gateways to decide its own items -
	 * on a real site a gateway-conditions plugin then touches the cart,
	 * and WooCommerce logs a doing-it-wrong for every request (found on
	 * the freestylr clone: ~50 KB of `get_cart` notices per page view,
	 * chain alter_routes > wc_get_account_menu_items >
	 * get_available_payment_gateways > WC_Cart::get_cart). Only the
	 * endpoint slug is recorded; the live label and URL are resolved at
	 * menu-build time in `alter_account_menu()`, which is when core's
	 * own WooCommerce component calls these functions too.
	 */
	if ( function_exists( 'wc_get_endpoint_url' ) && function_exists( 'wc_get_page_permalink' ) && function_exists( 'wc_get_account_menu_items' ) ) {
		$items['orders_view'] = [
			'title'       => __( 'Orders (WooCommerce)', 'persistent-account-menu-for-hivepress' ),
			'label'       => __( 'Orders', 'persistent-account-menu-for-hivepress' ),
			'wc_endpoint' => 'orders',
			'_order'      => 40,
		];

		if ( class_exists( 'WC_Subscriptions' ) ) {
			$items['subscriptions_view'] = [
				'title'       => __( 'Subscriptions (WooCommerce)', 'persistent-account-menu-for-hivepress' ),
				'label'       => __( 'Subscriptions', 'persistent-account-menu-for-hivepress' ),
				'wc_endpoint' => 'subscriptions',
				'_order'      => 42,
			];
		}
	}

	return $items;
}

/**
 * Gets the managed menu items.
 *
 * Applies the admin selection from HivePress > Settings > Default Menu
 * Items, then the developer filter. Items the admin chose not to force
 * are left completely untouched and keep the stock behaviour.
 *
 * Deliberately not cached: `reconcile_items()` can update the selection
 * options mid-request (while the routes are being built, no less), and a
 * static here would serve the pre-reconciliation list for the rest of
 * that request. The inputs are autoloaded options and the statically
 * cached defaults, so rebuilding is cheap.
 *
 * @return array<string, array<string, mixed>>
 */
function get_items() {
	$items = get_default_items();

	// Keep only the items enabled in the settings. Until the setting is
	// saved for the first time, every item is managed.
	$enabled = get_option( 'hp_hppam_items', null );

	if ( null !== $enabled ) {
		$items = array_intersect_key( $items, array_flip( array_filter( (array) $enabled ) ) );
	}

	// Apply the button customizations from the settings. A custom URL
	// replaces the default route link, and setting both a label and a
	// URL adds a button to pages that have none by default.
	foreach ( $items as $name => $item ) {
		if ( ! isset( $item['notice'] ) ) {
			continue;
		}

		$label = get_option( 'hp_hppam_button_label_' . $name );

		if ( $label ) {
			$items[ $name ]['notice']['button']['label'] = $label;
		}

		$url = get_option( 'hp_hppam_button_url_' . $name );

		if ( $url ) {
			$items[ $name ]['notice']['button']['url'] = $url;

			unset( $items[ $name ]['notice']['button']['route'] );
		}
	}

	/**
	 * Filters the menu items managed by Persistent Account Menu.
	 *
	 * Unset an item here to stop forcing it, or adjust its notice text,
	 * icon codepoint and button. The admin selection from the settings
	 * is already applied at this point.
	 *
	 * @hook hppam/v1/items
	 */
	$items = apply_filters( 'hppam/v1/items', $items );

	return $items;
}

/**
 * Gets the menu item choices for the settings field.
 *
 * Only items whose extension is currently active are offered.
 *
 * @return array<string, string>
 */
function get_item_options() {
	$options = [];

	foreach ( get_default_items() as $name => $item ) {

		// Skip items whose extension is inactive.
		if ( isset( $item['route'] ) && ! hivepress()->router->get_route( $item['route'] ) ) {
			continue;
		}

		$options[ $name ] = hp\get_array_value( $item, 'title', $name );
	}

	return $options;
}

/**
 * Reconciles the saved menu items with the currently offered choices.
 *
 * A `checkboxes` setting stores only the ticked list, which freezes the
 * set of choices that existed when it was saved. A choice that appears
 * later, because an extension was activated or a plugin update added an
 * item, is absent from the stored value, and absent reads as deliberately
 * unticked, so the new item would stay off with nothing saying a feature
 * arrived. A separate record of every choice already offered tells the
 * two apart: anything offered but not recorded is new, so it is switched
 * on and written into both options, keeping the settings screen, the
 * record and the behaviour in agreement.
 *
 * Runs on `admin_init`, deliberately, rather than on every request. The
 * check has to write two options when it finds something new, and a
 * front-end hook would put that write behind an unauthenticated request:
 * on a busy site every visitor arriving after an extension is activated
 * would race the same read-modify-write. It is also a settings-screen
 * concern, so wp-admin is where it belongs. Until an admin loads any
 * admin page, a newly offered item simply behaves as unticked, which is
 * the stock HivePress behaviour rather than a fault.
 *
 * @return void
 */
function reconcile_items() {
	if ( ! function_exists( 'hivepress' ) ) {
		return;
	}

	$enabled = get_option( 'hp_hppam_items', null );

	// Until the setting is saved every item is forced, so there is
	// nothing to reconcile; the record starts with the first save.
	if ( null === $enabled ) {
		return;
	}

	$offered = array_keys( get_item_options() );
	$known   = get_option( 'hp_hppam_known_items', null );

	if ( null === $known ) {

		// Seed the record from the current choices, so nothing the
		// admin already unticked is switched back on.
		update_option( 'hp_hppam_known_items', $offered );

		return;
	}

	$new_items = array_diff( $offered, (array) $known );

	if ( ! $new_items ) {
		return;
	}

	update_option( 'hp_hppam_items', array_values( array_unique( array_merge( array_filter( (array) $enabled ), $new_items ) ) ) );
	update_option( 'hp_hppam_known_items', array_values( array_unique( array_merge( (array) $known, $new_items ) ) ) );
}

add_action( 'admin_init', __NAMESPACE__ . '\\reconcile_items' );

/**
 * Gets the managed pages whose template the admin has customised.
 *
 * `Blocks\Template::render()` has two paths: when any `hp_template` post
 * is published and one matches this template's name, the page renders
 * that saved editor content and the template class's own block tree is
 * never used (`blocks/class-template.php:47-92`). Our notice is injected
 * into that block tree, so on those pages it cannot appear, with nothing
 * in the logs to say why. The menu item and the empty-page bounce
 * suppression are unaffected, since neither goes through the template.
 *
 * The template name equals the route name for these pages (core builds
 * the class as `\HivePress\Templates\{route}`,
 * `components/class-template.php:220`).
 *
 * @return array<string> Item titles, for the settings screen.
 */
function get_overridden_pages() {
	$titles = [];

	if ( ! function_exists( 'hivepress' ) ) {
		return $titles;
	}

	$counts = wp_count_posts( 'hp_template' );

	// Cheap gate, exactly the one core uses before querying.
	if ( ! $counts || ! $counts->publish ) {
		return $titles;
	}

	$templates = [];

	foreach ( get_items() as $item ) {
		if ( isset( $item['notice'], $item['route'] ) ) {
			$templates[ $item['route'] ] = hp\get_array_value( $item, 'title', $item['route'] );
		}
	}

	if ( ! $templates ) {
		return $titles;
	}

	$overridden = get_posts(
		[
			'post_type'        => 'hp_template',
			'post_status'      => 'publish',
			'post_name__in'    => array_keys( $templates ),
			'posts_per_page'   => count( $templates ),
			'fields'           => 'ids',
			'suppress_filters' => false,
		]
	);

	foreach ( $overridden as $post_id ) {
		$name = get_post_field( 'post_name', $post_id );

		if ( isset( $templates[ $name ] ) ) {
			$titles[] = $templates[ $name ];
		}
	}

	return $titles;
}

/**
 * Adds the plugin settings tab.
 *
 * The tab is rendered and saved by HivePress itself, with the field
 * stored as the `hp_hppam_items` option.
 *
 * @param array<string, mixed> $settings Settings configuration.
 * @return array<string, mixed>
 */
function alter_settings( $settings ) {
	$options = get_item_options();

	$description = __( 'Choose the account menu items that stay visible even when they are empty. Unticked items are left untouched and keep the default behaviour, appearing only once there is something to show.', 'persistent-account-menu-for-hivepress' );

	// Warn about pages whose template has been customised, where the
	// notice cannot be shown. Silence here would read as a plugin fault.
	$overridden = get_overridden_pages();

	if ( $overridden ) {
		$description .= ' ' . sprintf(
			/* translators: %s: comma-separated list of page names. */
			__( 'Note: you have customised these pages under HivePress > Templates, so they show your own layout instead of the notice below: %s. The menu items themselves still stay visible. To use the notice again, delete that template in HivePress > Templates.', 'persistent-account-menu-for-hivepress' ),
			implode( ', ', $overridden )
		);
	}

	$settings['persistent_menu'] = [
		'title'    => __( 'Default Menu Items', 'persistent-account-menu-for-hivepress' ),
		'_order'   => 200,

		'sections' => [
			'items' => [
				'title'       => __( 'Menu Items', 'persistent-account-menu-for-hivepress' ),
				'description' => $description,
				'_order'      => 10,

				'fields'      => [
					'hppam_items' => [
						'label'       => __( 'Visible Items', 'persistent-account-menu-for-hivepress' ),
						'description' => __( 'Tick an item to keep it visible even when its page is empty.', 'persistent-account-menu-for-hivepress' ),
						'type'        => 'checkboxes',
						'options'     => $options,
						'default'     => array_keys( $options ),
						'_order'      => 10,
					],
				],
			],
		],
	];

	// Add the per-page button settings.
	$order = 20;

	foreach ( get_default_items() as $name => $item ) {
		if ( ! isset( $item['notice'] ) || ! isset( $options[ $name ] ) ) {
			continue;
		}

		$button = hp\get_array_value( $item['notice'], 'button' );

		$settings['persistent_menu']['sections'][ 'button_' . $name ] = [
			'title'       => $item['title'],

			'description' => $button
				? __( 'Customise the button on this empty page. Leave a field blank to keep the default.', 'persistent-account-menu-for-hivepress' )
				: __( 'This page has no button by default. Set both a label and a URL to add one.', 'persistent-account-menu-for-hivepress' ),

			'_order'      => $order,

			'fields'      => [
				'hppam_button_label_' . $name => [
					'label'       => __( 'Button Label', 'persistent-account-menu-for-hivepress' ),
					'description' => __( 'Enter the text shown on the button, for example "Add listing".', 'persistent-account-menu-for-hivepress' ),
					'type'        => 'text',
					'placeholder' => $button ? hp\get_array_value( $button, 'label', '' ) : '',
					'_order'      => 10,
				],

				'hppam_button_url_' . $name   => [
					'label'       => __( 'Button URL', 'persistent-account-menu-for-hivepress' ),
					'description' => __( 'Enter a full URL or a relative path like /listings.', 'persistent-account-menu-for-hivepress' ),
					'type'        => 'text',
					'_order'      => 20,
				],
			],
		];

		$order += 10;
	}

	/*
	 * Its own section, deliberately last.
	 *
	 * The section description answers a question WordPress itself
	 * creates: the delete-confirmation screen prints "(will also delete
	 * its data)" whenever an uninstall.php exists at all
	 * (`wp-admin/plugins.php:376-380`), whatever that file does, and
	 * ours keeps everything unless this box is ticked.
	 */
	$settings['persistent_menu']['sections']['removal'] = [
		'title'       => __( 'Removing the Plugin', 'persistent-account-menu-for-hivepress' ),
		'description' => __( 'Your settings on this page are kept if you delete this plugin, so you can reinstall it and carry on where you left off. WordPress shows its own warning on the delete screen saying the data goes too, but that warning is the same for every plugin and does not apply here unless you tick the box below. Switching the plugin off never removes anything.', 'persistent-account-menu-for-hivepress' ),
		'_order'      => $order,

		'fields'      => [
			'hppam_delete_data' => [
				'label'       => __( 'Delete All Data', 'persistent-account-menu-for-hivepress' ),
				'caption'     => __( 'Delete everything when this plugin is deleted', 'persistent-account-menu-for-hivepress' ),
				'description' => __( 'Leave this unticked unless you are certain. With it ticked, deleting the plugin also removes your choice of which menu items stay visible and every button label and button URL you have set on this page. It cannot be undone and nothing asks you to confirm at the time, so copy down anything you want to keep first. Your listings, bookings, messages and every other piece of HivePress content are never touched either way, because this plugin does not create any.', 'persistent-account-menu-for-hivepress' ),
				'type'        => 'checkbox',
				'_order'      => 10,
			],
		],
	];

	return $settings;
}

add_filter( 'hivepress/v1/settings', __NAMESPACE__ . '\\alter_settings' );

/**
 * Checks if message storage is enabled.
 *
 * The Messages page route redirects away unconditionally when storage is
 * disabled, so the item is only forced when storage is on.
 *
 * @return bool
 */
function is_message_storage_enabled() {
	return (bool) get_option( 'hp_message_enable_storage' );
}

/**
 * Checks if the current user has a pending or published vendor profile.
 *
 * Used to force vendor-only items for vendors regardless of whether they
 * have data yet. Draft profiles are excluded on purpose, since those are
 * abandoned registration attempts rather than real vendors. Core's
 * `vendor_id` request context is capability-gated, so the vendor profile
 * is queried directly and cached per request.
 *
 * @return bool
 */
function is_vendor() {
	static $is_vendor = null;

	if ( null === $is_vendor ) {
		$is_vendor = is_user_logged_in() && class_exists( '\HivePress\Models\Vendor' ) && \HivePress\Models\Vendor::query()->filter(
			[
				'status__in' => [ 'pending', 'publish' ],
				'user'       => get_current_user_id(),
			]
		)->get_first_id();
	}

	return (bool) $is_vendor;
}

/**
 * Gets the probe flag, optionally setting it.
 *
 * While the flag is set, the menu filter skips adding forced items so the
 * native menu state can be inspected.
 *
 * @param bool|null $set Flag value.
 * @return bool
 */
function is_probing( $set = null ) {
	static $probing = false;

	if ( null !== $set ) {
		$probing = $set;
	}

	return $probing;
}

/**
 * Checks if an item is present in the native account menu.
 *
 * Extensions only add their item when there is data to show, so a missing
 * item means the page is empty. The native menu is built once per request
 * with forcing suppressed. Guarded against third-party route title
 * callables that are unsafe outside their own context.
 *
 * @param string $name Menu item name.
 * @return bool
 */
function is_native_item( $name ) {
	static $native_items = null;

	if ( null === $native_items ) {
		is_probing( true );

		try {
			$native_items = ( new \HivePress\Menus\User_Account() )->get_items();
		} catch ( \Throwable $e ) {
			$native_items = null;
		} finally {
			is_probing( false );
		}

		if ( ! is_array( $native_items ) ) {

			// Fail safe: treat every item as populated.
			$native_items = array_fill_keys( array_keys( get_items() ), true );
		}
	}

	return isset( $native_items[ $name ] );
}

/**
 * Forces the managed items into the account menu.
 *
 * Runs at priority 500, after every stock condition filter (core at 10,
 * Marketplace at 100), so items added natively are left untouched and
 * only the missing ones are forced.
 *
 * @param array<string, mixed> $menu Menu arguments.
 * @return array<string, mixed>
 */
function alter_account_menu( $menu ) {

	// Never force items in the admin area or while probing the native menu.
	if ( is_admin() || is_probing() || ! is_user_logged_in() ) {
		return $menu;
	}

	foreach ( get_items() as $name => $item ) {

		// Skip items added natively.
		if ( isset( $menu['items'][ $name ] ) ) {
			continue;
		}

		// Skip items disabled by their own condition.
		if ( isset( $item['enabled'] ) && ! call_user_func( $item['enabled'] ) ) {
			continue;
		}

		// Skip vendor items for non-vendors.
		if ( hp\get_array_value( $item, 'vendor' ) && ! is_vendor() ) {
			continue;
		}

		if ( isset( $item['route'] ) ) {

			// Skip items whose extension is inactive.
			if ( ! hivepress()->router->get_route( $item['route'] ) ) {
				continue;
			}

			$menu['items'][ $name ] = [
				'route'  => $item['route'],
				'_order' => $item['_order'],
			];
		} elseif ( isset( $item['wc_endpoint'] ) && function_exists( 'wc_get_account_menu_items' ) ) {

			// WooCommerce label and URL are resolved HERE, at menu-build
			// time, never during the routes filter - see the comment in
			// get_default_items() for the early-cart trap this avoids.
			$menu['items'][ $name ] = [
				'label'  => hp\get_array_value( wc_get_account_menu_items(), $item['wc_endpoint'], hp\get_array_value( $item, 'label', $item['title'] ) ),
				'url'    => wc_get_endpoint_url( $item['wc_endpoint'], '', wc_get_page_permalink( 'myaccount' ) ),
				'_order' => $item['_order'],
			];
		}
	}

	// Mirror the Marketplace label when both order lists are present.
	if ( isset( $menu['items']['orders_edit'] ) && isset( $menu['items']['orders_view'] ) ) {
		$menu['items']['orders_view']['label'] = esc_html__( 'Placed Orders', 'hivepress-marketplace' );
	}

	return $menu;
}

add_filter( 'hivepress/v1/menus/user_account', __NAMESPACE__ . '\\alter_account_menu', 500 );

/**
 * Neutralises the empty-page bounce on the managed routes.
 *
 * HivePress account pages redirect back to the account page when they have
 * nothing to show (verified in core and in every managed extension list
 * page), which would
 * make the forced menu links unusable. Each managed route's redirect
 * callbacks are wrapped so that, for logged-in users, a redirect
 * targeting the account page is suppressed while every other redirect
 * (authentication, feature gates, verification) passes through untouched.
 *
 * @param array<string, array<string, mixed>> $routes Route arguments.
 * @return array<string, array<string, mixed>>
 */
function alter_routes( $routes ) {
	foreach ( get_items() as $item ) {
		$name = hp\get_array_value( $item, 'route' );

		if ( ! $name || ! isset( $routes[ $name ]['redirect'] ) ) {
			continue;
		}

		$callbacks = $routes[ $name ]['redirect'];

		// Normalise the callbacks the same way core does.
		if ( count( $callbacks ) === 2 && is_object( hp\get_first_array_value( $callbacks ) ) ) {
			$callbacks = [
				[
					'callback' => $callbacks,
					'_order'   => 5,
				],
			];
		}

		$callbacks = array_filter(
			array_map(
				function ( $args ) {
					return hp\get_array_value( $args, 'callback' );
				},
				hp\sort_array( $callbacks )
			)
		);

		$routes[ $name ]['redirect'] = [
			[
				'callback' => function () use ( $callbacks, $item ) {
					return filter_redirect( $callbacks, $item );
				},

				'_order'   => 5,
			],
		];
	}

	// Pair the received-orders title with the forced placed-orders item.
	// Marketplace only titles the page "Received Orders" once the vendor
	// has placed orders of their own, because natively the "Placed
	// Orders" item cannot appear before then. Since the placed-orders
	// item is always forced next to it here, the plain "Orders" fallback
	// would make the two items ambiguous, so it is upgraded while custom
	// and already-distinct titles pass through untouched.
	if ( isset( $routes['orders_edit_page']['title'] ) && isset( get_items()['orders_view'] ) ) {
		$title = $routes['orders_edit_page']['title'];

		$routes['orders_edit_page']['title'] = function () use ( $title ) {
			$title = is_callable( $title ) ? call_user_func( $title ) : $title;

			if ( is_user_logged_in() && hivepress()->translator->get_string( 'orders' ) === $title ) {
				$title = esc_html__( 'Received Orders', 'hivepress-marketplace' );
			}

			return $title;
		};
	}

	return $routes;
}

add_filter( 'hivepress/v1/routes', __NAMESPACE__ . '\\alter_routes', 500 );

/**
 * Runs the original redirect callbacks, suppressing the empty bounce.
 *
 * The bounce is only suppressed for users the item is actually forced
 * for, so gated pages keep their native behaviour for everyone else.
 *
 * @param array<callable>      $callbacks Original redirect callbacks.
 * @param array<string, mixed> $item Item arguments.
 * @return mixed
 */
function filter_redirect( $callbacks, $item ) {
	$account_url = untrailingslashit( (string) hivepress()->router->get_url( 'user_account_page' ) );

	// Check the item conditions.
	$forcible = is_user_logged_in();

	if ( $forcible && isset( $item['enabled'] ) && ! call_user_func( $item['enabled'] ) ) {
		$forcible = false;
	}

	if ( $forcible && hp\get_array_value( $item, 'vendor' ) && ! is_vendor() ) {
		$forcible = false;
	}

	foreach ( $callbacks as $callback ) {
		$redirect = call_user_func( $callback );

		// Falsy results mean no redirect, the same as in core.
		if ( ! $redirect ) {
			continue;
		}

		// Honour boolean redirects (feature gates) and every redirect
		// for users the item is not forced for.
		if ( is_bool( $redirect ) || ! $forcible ) {
			return $redirect;
		}

		// Suppress the empty bounce back to the account page.
		if ( untrailingslashit( (string) $redirect ) === $account_url ) {
			continue;
		}

		return $redirect;
	}

	return false;
}

/**
 * Adds the empty-state notice to the managed account pages.
 *
 * Hooked on the base account page template, which fires for every child
 * template because HivePress applies template filters for the whole class
 * chain. The vendor calendar is the one managed page outside that chain,
 * as Bookings' `Vendor_Calendar_Page` extends `Page_Wide` rather than
 * `User_Account_Page`, so its template filter is hooked directly. The
 * notice only renders when the extension itself left the item out of the
 * native menu, meaning the page is empty.
 *
 * @param array<string, mixed> $template Template arguments.
 * @return array<string, mixed>
 */
function alter_account_page( $template ) {
	$route = hivepress()->router->get_current_route_name();

	if ( ! $route ) {
		return $template;
	}

	foreach ( get_items() as $name => $item ) {
		if ( hp\get_array_value( $item, 'route' ) !== $route || ! isset( $item['notice'] ) ) {
			continue;
		}

		// Skip populated pages.
		if ( is_native_item( $name ) ) {
			break;
		}

		// Add the notice above the page content. Blocks are merged with
		// `merge_blocks` rather than the soon-deprecated `merge_trees`,
		// in two separate calls: `_merge_blocks` never descends into a
		// block it has just matched, so the notice (added under
		// `page_content`) and the blanks (children of `page_content`)
		// cannot be merged in one pass.
		hivepress()->template->merge_blocks(
			$template,
			[
				'page_content' => [
					'blocks' => [
						'hppam_empty_notice' => [
							'type'    => 'content',
							'content' => render_notice( $item['notice'] ),
							'_order'  => 5,
						],
					],
				],
			]
		);

		// Blank the page's own output so the default "Nothing found"
		// message is not shown alongside the notice.
		$blanks = [];

		foreach ( hp\get_array_value( $item['notice'], 'blank', [] ) as $block_name ) {
			$blanks[ $block_name ] = [
				'type'    => 'content',
				'content' => '',
			];
		}

		if ( $blanks ) {
			hivepress()->template->merge_blocks( $template, $blanks );
		}

		break;
	}

	return $template;
}

add_filter( 'hivepress/v1/templates/user_account_page', __NAMESPACE__ . '\\alter_account_page', 200 );
add_filter( 'hivepress/v1/templates/vendor_calendar_page', __NAMESPACE__ . '\\alter_account_page', 200 );

/**
 * Renders the empty-state notice.
 *
 * The icon is rendered from a Font Awesome codepoint via CSS, matching
 * the solid style bundled with HivePress and compatible with self-hosted
 * Font Awesome 5, 6 and 7.
 *
 * @param array<string, mixed> $notice Notice arguments.
 * @return string
 */
function render_notice( $notice ) {
	$output = '<div class="hppam-empty">';

	// Icon.
	$icon = hp\get_array_value( $notice, 'icon' );

	if ( $icon ) {
		$output .= '<span class="hppam-empty__icon" data-icon="&#x' . esc_attr( $icon ) . ';" aria-hidden="true"></span>';
	}

	// Text.
	$output .= '<p class="hppam-empty__text">' . esc_html( hp\get_array_value( $notice, 'text', '' ) ) . '</p>';

	// Button.
	$button = hp\get_array_value( $notice, 'button' );

	if ( $button ) {
		$url   = hp\get_array_value( $button, 'url' );
		$route = hp\get_array_value( $button, 'route' );

		if ( ! $url && $route ) {
			$url = hivepress()->router->get_url( $route );
		}

		$label = hp\get_array_value( $button, 'label', '' );

		if ( $url && $label ) {

			// `hp-button` is core's structural button class and
			// `button button--primary` the appearance pair every official
			// theme styles. `alt` is inert outside WooCommerce pages (all
			// six themes scope their `.button.alt` rules to
			// `.woocommerce`), but every official extension CTA carries
			// it, so it is kept for convention rather than effect.
			$output .= '<a href="' . esc_url( $url ) . '" class="hppam-empty__button hp-button button button--primary alt">' . esc_html( $label ) . '</a>';
		}
	}

	$output .= '</div>';

	/**
	 * Filters the rendered empty-state notice.
	 *
	 * @hook hppam/v1/notice_html
	 */
	return apply_filters( 'hppam/v1/notice_html', $output, $notice );
}

/**
 * Enqueues the notice styles on the managed account pages.
 *
 * @return void
 */
function enqueue_styles() {
	if ( ! function_exists( 'hivepress' ) ) {
		return;
	}

	$route = hivepress()->router->get_current_route_name();

	if ( ! $route || ! in_array( $route, array_filter( array_column( get_items(), 'route' ) ), true ) ) {
		return;
	}

	wp_register_style( 'hppam-frontend', false, [], get_version() );
	wp_enqueue_style( 'hppam-frontend' );

	// Spacing sticks to core's rem scale and the icon size is a percentage
	// so type scales with the theme, per the native look-and-feel rules.
	wp_add_inline_style(
		'hppam-frontend',
		'.hppam-empty{display:flex;flex-direction:column;align-items:center;text-align:center;padding:3rem 1rem;gap:1rem}
		.hppam-empty__icon::before{content:attr(data-icon);font-family:"Font Awesome 5 Free","Font Awesome 6 Free","Font Awesome 7 Free";font-weight:900;font-size:275%;line-height:1;opacity:.25}
		.hppam-empty__text{max-width:26rem;margin:0}'
	);
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_styles' );

/*
 * -------------------------------------------------------------------------
 * Updates
 *
 * The plugin is distributed via GitHub releases rather than wp.org, so
 * update checks go through the native `update_plugins_{$hostname}` API
 * introduced in WordPress 5.8, keyed off the Update URI header above.
 * The update package is the release asset named `*.zip`, which must
 * contain a single `persistent-account-menu-for-hivepress` directory.
 * -------------------------------------------------------------------------
 */

const UPDATE_REPO = 'irapidchris-del/Persistent-Account-Menu-for-HivePress';

const UPDATE_SLUG = 'persistent-account-menu-for-hivepress';

const UPDATE_CACHE_KEY = 'hppam_github_release';

/**
 * Gets the installed plugin version.
 *
 * @return string
 */
function get_version() {
	static $version = null;

	if ( null === $version ) {
		$data = get_file_data( __FILE__, [ 'Version' => 'Version' ] );

		$version = $data['Version'];
	}

	return $version;
}

/**
 * Gets the cached lookup result, whatever it says.
 *
 * Three shapes are possible: a full release array, `[ 'none' => '1' ]`
 * when the repository has no published release yet, and an empty array
 * when the lookup failed.
 *
 * @param bool $force Bypass the cache.
 * @return array<string, string>
 */
function get_release_data( $force = false ) {
	$release = $force ? false : get_site_transient( UPDATE_CACHE_KEY );

	if ( ! is_array( $release ) ) {
		$release = fetch_latest_release();

		// Failures are cached briefly so the API is not queried
		// repeatedly; the unauthenticated limit is 60 requests an hour.
		set_site_transient( UPDATE_CACHE_KEY, $release, $release ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	}

	return (array) $release;
}

/**
 * Gets the latest GitHub release details, cached for 6 hours.
 *
 * Returns null unless a real release was found, so callers can never
 * read a version out of a "no releases yet" or failed lookup.
 *
 * @param bool $force Bypass the cache.
 * @return array<string, string>|null
 */
function get_latest_release( $force = false ) {
	$release = get_release_data( $force );

	return isset( $release['version'] ) ? $release : null;
}

/**
 * Fetches the latest release details from the GitHub API.
 *
 * Draft and pre-release entries are excluded by the endpoint itself, so
 * publishing a pre-release never triggers an update notice.
 *
 * @return array<string, string>
 */
function fetch_latest_release() {
	$response = wp_remote_get(
		'https://api.github.com/repos/' . UPDATE_REPO . '/releases/latest',
		[
			'timeout'    => 10,
			'headers'    => [ 'Accept' => 'application/vnd.github+json' ],

			/*
			 * Set deliberately. Left out, WordPress fills the header in
			 * with "WordPress/{version}; {site url}"
			 * (`wp-includes/class-wp-http.php:211`), which would tell
			 * GitHub the site's address and its exact WordPress version
			 * on every check. GitHub only asks that the header identify
			 * something, so the plugin and its version satisfy it while
			 * saying nothing about the site.
			 */
			'user-agent' => UPDATE_SLUG . '/' . get_version(),
		]
	);

	// A 404 means no release has been published yet, which is the normal
	// state of a new repository rather than a connectivity failure. It is
	// reported separately so the manual check can say so.
	if ( ! is_wp_error( $response ) && 404 === wp_remote_retrieve_response_code( $response ) ) {
		return [ 'none' => '1' ];
	}

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		return [];
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );

	if ( ! is_array( $data ) ) {
		return [];
	}

	// The version is read from the release tag, with or without a "v" prefix.
	$version = ltrim( (string) ( isset( $data['tag_name'] ) ? $data['tag_name'] : '' ), 'vV' );

	if ( ! $version ) {
		return [];
	}

	// The update package is the first release asset named `*.zip`.
	$package = '';

	foreach ( (array) ( isset( $data['assets'] ) ? $data['assets'] : [] ) as $asset ) {
		$name = strtolower( (string) ( isset( $asset['name'] ) ? $asset['name'] : '' ) );

		if ( '.zip' === substr( $name, -4 ) && ! empty( $asset['browser_download_url'] ) ) {
			$package = (string) $asset['browser_download_url'];

			break;
		}
	}

	if ( ! $package ) {
		return [];
	}

	return [
		'version'   => $version,
		'package'   => $package,
		'url'       => (string) ( isset( $data['html_url'] ) ? $data['html_url'] : 'https://github.com/' . UPDATE_REPO ),
		'notes'     => (string) ( isset( $data['body'] ) ? $data['body'] : '' ),
		'published' => (string) ( isset( $data['published_at'] ) ? $data['published_at'] : '' ),
	];
}

/**
 * Provides the update details to the WordPress update system.
 *
 * WordPress matches the plugin to this filter via the Update URI header
 * hostname and compares the versions itself, filing the result under
 * either the available updates or the up-to-date list.
 *
 * @param array<string, mixed>|false $update Update data.
 * @param array<string, string>      $plugin_data Plugin headers.
 * @param string                     $plugin_file Plugin basename.
 * @return array<string, mixed>|false
 */
function check_for_update( $update, $plugin_data, $plugin_file ) {
	if ( plugin_basename( __FILE__ ) !== $plugin_file ) {
		return $update;
	}

	$release = get_latest_release();

	if ( ! $release ) {
		return $update;
	}

	return [
		'id'      => 'https://github.com/' . UPDATE_REPO,
		'slug'    => UPDATE_SLUG,
		'plugin'  => $plugin_file,
		'version' => $release['version'],
		'url'     => $release['url'],
		'package' => $release['package'],
	];
}

add_filter( 'update_plugins_github.com', __NAMESPACE__ . '\\check_for_update', 10, 3 );

/**
 * Renders the GitHub release body as HTML for the details popup.
 *
 * Release notes are written in Markdown, and WordPress prints the
 * changelog tab as HTML, so passing the body straight through shows
 * literal `**bold**` asterisks and runs bullet lists together.
 *
 * The body is remote content, so it is escaped FIRST and only then given
 * the small set of tags below; the result goes through `wp_kses()` as a
 * second belt. Only the constructs release notes actually use are
 * handled: headings, bullet and numbered lists, fenced and inline code,
 * bold (`**` and `__`), italics (guarded `*` and `_`) and http(s) links.
 * Code spans and URLs are tokenised out before the emphasis rules run
 * and restored afterwards - see the comment at the tokenising step.
 *
 * @param string $notes Release body in Markdown.
 * @return string
 */
function format_release_notes( $notes ) {
	$text = esc_html( trim( (string) $notes ) );

	/*
	 * The `/u` flag is not cosmetic here. Without it `\R` also matches the
	 * single byte 0x85 (NEL), which occurs INSIDE ordinary UTF-8 emoji -
	 * U+2705 "white heavy check mark" encodes as E2 9C 85 - so a tick in
	 * the release notes was split mid-character, corrupting the glyph and
	 * breaking the line in two. Every pattern below is therefore UTF-8
	 * aware, and a failed match falls back rather than returning null.
	 */
	$lines = preg_split( '/\R/u', $text );

	if ( ! is_array( $lines ) ) {

		// Invalid UTF-8, or a PCRE limit on a very large body. Show
		// something readable rather than an empty changelog.
		return wpautop( $text );
	}

	$output   = '';
	$list_tag = '';
	$in_fence = false;

	foreach ( $lines as $line ) {
		$line = rtrim( $line );

		// Fenced code blocks are passed through verbatim, with no inline
		// formatting applied, so a snippet containing asterisks or
		// underscores survives intact.
		if ( preg_match( '/^\s*```/u', $line ) ) {
			if ( $in_fence ) {
				$output .= '</code></pre>';
			} else {
				$output  .= close_list( $list_tag ) . '<pre><code>';
				$list_tag = '';
			}

			$in_fence = ! $in_fence;

			continue;
		}

		if ( $in_fence ) {
			$output .= $line . "\n";

			continue;
		}

		/*
		 * Tokenise BEFORE transforming, transform, then restore. The
		 * emphasis patterns must never see the inside of a code span, a
		 * link URL or a bare URL: an adversarial review proved (by
		 * execution) that running them over the whole line first turned
		 * `/docs/_v2_/` into `/docs/emv2/em/` inside an href - esc_url()
		 * strips only the angle brackets of an injected tag and keeps its
		 * letters as path text - and chewed `__FILE__` inside backticks
		 * into straddled, unclosed tags that wp_kses does not rebalance
		 * (neither pass calls force_balance_tags). Running the link pass
		 * FIRST is not a fix either: the emphasis rules then eat the
		 * emitted href markup itself. Placeholders sidestep both orders.
		 * The token delimiter is a control character that esc_html leaves
		 * alone and legitimate notes never contain; any real occurrence
		 * is stripped first so remote content cannot address the token
		 * table.
		 */
		$tokens = [];
		$line   = str_replace( "\x1a", '', $line );

		// Inline code spans first, held verbatim so their asterisks and
		// underscores survive.
		$line = tokenise(
			'/`([^`]+)`/u',
			$line,
			$tokens,
			function ( $matches ) {
				return '<code>' . $matches[1] . '</code>';
			}
		);

		// Markdown links next. Only http(s) targets are matched at all;
		// the text was escaped above, so the URL is decoded before
		// esc_url() sees it. The URL part refuses the token delimiter, so
		// a backtick pair inside a URL (already lifted out as a code
		// span, which is also CommonMark's precedence) stops the link
		// forming rather than producing an anchor with a corrupted
		// target. Link text is kept verbatim, and MAY contain a code
		// token - a code span inside link text is legal Markdown.
		$line = tokenise(
			'/\[(.+?)\]\((https?:\/\/[^\s)\x1a]+)\)/u',
			$line,
			$tokens,
			function ( $matches ) {
				return '<a href="' . esc_url( html_entity_decode( $matches[2], ENT_QUOTES ) ) . '">' . $matches[1] . '</a>';
			}
		);

		// Bare URLs, kept as plain text but shielded from the emphasis
		// rules, so an underscore in a pasted URL is never eaten. This
		// pattern must also refuse the token delimiter: restoration is
		// single-pass, so a token swallowed into another token would
		// come back as raw control bytes instead of its content.
		$line = tokenise(
			'/https?:\/\/[^\s\x1a]+/u',
			$line,
			$tokens,
			function ( $matches ) {
				return $matches[0];
			}
		);

		// Emphasis, on prose only. Both double markers run before the
		// single ones so `__FILE__` reads as GitHub renders it (bold
		// FILE) rather than shedding stray underscores, and the single
		// rules require non-space, non-marker characters at BOTH ends -
		// the closing guard is what keeps "*.php and *.js" or "5 * 3"
		// from italicising half the sentence. The `<>` exclusions stop a
		// match crossing an already-emitted tag.
		$line = replace_safely( '/\*\*\*(.+?)\*\*\*/u', '<strong><em>$1</em></strong>', $line );
		$line = replace_safely( '/\*\*(.+?)\*\*/u', '<strong>$1</strong>', $line );
		$line = replace_safely( '/__(.+?)__/u', '<strong>$1</strong>', $line );
		$line = replace_safely( '/\*([^\s*<>](?:[^*<>]*[^\s*<>])?)\*/u', '<em>$1</em>', $line );
		$line = replace_safely( '/(?<![a-z0-9_])_([^_<>]+?)_(?![a-z0-9_])/iu', '<em>$1</em>', $line );

		// Restore the held-out spans.
		$line = restore_tokens( $line, $tokens );

		// Bullet and numbered lists, closing the other kind if it changes.
		$tag  = '';
		$item = [];

		if ( preg_match( '/^\s*[-*]\s+(.*)$/u', $line, $item ) ) {
			$tag = 'ul';
		} elseif ( preg_match( '/^\s*\d+\.\s+(.*)$/u', $line, $item ) ) {
			$tag = 'ol';
		}

		if ( $tag !== $list_tag ) {
			$output  .= close_list( $list_tag ) . ( $tag ? '<' . $tag . '>' : '' );
			$list_tag = $tag;
		}

		if ( $tag ) {
			$output .= '<li>' . $item[1] . '</li>';
		} elseif ( preg_match( '/^\s*#{1,6}\s+(.*)$/u', $line, $heading ) ) {
			$output .= '<h4>' . $heading[1] . '</h4>';
		} elseif ( '' !== trim( $line ) ) {
			$output .= '<p>' . $line . '</p>';
		}
	}

	$output .= close_list( $list_tag );

	if ( $in_fence ) {
		$output .= '</code></pre>';
	}

	return wp_kses(
		$output,
		[
			'p'      => [],
			'h4'     => [],
			'ul'     => [],
			'ol'     => [],
			'li'     => [],
			'pre'    => [],
			'strong' => [],
			'em'     => [],
			'code'   => [],
			'a'      => [ 'href' => [] ],
		]
	);
}

/**
 * Closes an open list, if there is one.
 *
 * @param string $tag Currently open list tag, or an empty string.
 * @return string
 */
function close_list( $tag ) {
	return $tag ? '</' . $tag . '>' : '';
}

/**
 * Runs a replacement, keeping the original when the pattern fails.
 *
 * `preg_replace()` returns null on a PCRE error, such as the backtrack
 * limit on a very long line or malformed UTF-8 under the `/u` flag.
 * Assigning that straight back would silently blank the line.
 *
 * @param string $pattern Regular expression.
 * @param string $replacement Replacement string.
 * @param string $subject Subject string.
 * @return string
 */
function replace_safely( $pattern, $replacement, $subject ) {
	$result = preg_replace( $pattern, $replacement, $subject );

	return null === $result ? $subject : $result;
}

/**
 * Replaces every match with a placeholder, storing the rendered HTML.
 *
 * The placeholder is `\x1A{index}\x1A`; the caller strips any literal
 * `\x1A` from the line first, so remote content can never collide with
 * or address the token table.
 *
 * @param string   $pattern Regular expression.
 * @param string   $line Subject line.
 * @param array    $tokens Token store, passed by reference.
 * @param callable $render Renders a match into final HTML.
 * @return string
 */
function tokenise( $pattern, $line, &$tokens, $render ) {
	$result = preg_replace_callback(
		$pattern,
		function ( $matches ) use ( &$tokens, $render ) {
			$tokens[] = call_user_func( $render, $matches );

			return "\x1a" . ( count( $tokens ) - 1 ) . "\x1a";
		},
		$line
	);

	return null === $result ? $line : $result;
}

/**
 * Restores tokenised spans into the transformed line.
 *
 * @param string $line Transformed line.
 * @param array  $tokens Token store.
 * @return string
 */
function restore_tokens( $line, $tokens ) {
	$result = preg_replace_callback(
		"/\x1a(\\d+)\x1a/",
		function ( $matches ) use ( $tokens ) {
			return isset( $tokens[ (int) $matches[1] ] ) ? $tokens[ (int) $matches[1] ] : '';
		},
		$line
	);

	return null === $result ? $line : $result;
}

/**
 * Provides the plugin details for the update information popup.
 *
 * Without this the "View version x.x.x details" link on the Plugins
 * screen would open an empty modal, since the plugin is not on wp.org.
 *
 * @param object|array|false $result Result object.
 * @param string             $action API action.
 * @param object             $args API arguments.
 * @return object|array|false
 */
function get_plugin_information( $result, $action, $args ) {
	if ( 'plugin_information' !== $action || ! is_object( $args ) || UPDATE_SLUG !== ( isset( $args->slug ) ? $args->slug : '' ) ) {
		return $result;
	}

	$release = get_latest_release();

	if ( ! $release ) {
		return $result;
	}

	$plugin_data = get_file_data(
		__FILE__,
		[
			'Name'        => 'Plugin Name',
			'Description' => 'Description',
			'Author'      => 'Author',
			'AuthorURI'   => 'Author URI',
			'RequiresWP'  => 'Requires at least',
			'RequiresPHP' => 'Requires PHP',
		]
	);

	return (object) [
		'name'          => $plugin_data['Name'],
		'slug'          => UPDATE_SLUG,
		'version'       => $release['version'],
		'author'        => '<a href="' . esc_url( $plugin_data['AuthorURI'] ) . '">' . esc_html( $plugin_data['Author'] ) . '</a>',
		'homepage'      => 'https://github.com/' . UPDATE_REPO,
		'requires'      => $plugin_data['RequiresWP'],
		'requires_php'  => $plugin_data['RequiresPHP'],
		'last_updated'  => $release['published'],
		'download_link' => $release['package'],

		// WordPress renders this as "Donate to this plugin" for free.
		// With no `contributors` returned it lands in the sidebar link
		// list (`wp-admin/includes/plugin-install.php:705-706`).
		'donate_link'   => get_support_url(),
		'sections'      => [
			'description' => wpautop( esc_html( $plugin_data['Description'] ) ),
			'changelog'   => $release['notes'] ? format_release_notes( $release['notes'] ) : '<p>' . esc_html__( 'See the GitHub releases page for the changelog.', 'persistent-account-menu-for-hivepress' ) . '</p>',
		],
	];
}

add_filter( 'plugins_api', __NAMESPACE__ . '\\get_plugin_information', 10, 3 );

/**
 * Shows a notice when HivePress is missing.
 *
 * None of the plugin's filters fire without HivePress, so it would
 * otherwise sit silently inactive. WordPress 6.5+ blocks activation via
 * the `Requires Plugins` header; this notice covers older versions and
 * the case where HivePress is deactivated later.
 *
 * @return void
 */
function show_missing_hivepress_notice() {
	if ( function_exists( 'hivepress' ) || ! current_user_can( 'activate_plugins' ) ) {
		return;
	}

	// Dismissible, because an undismissable notice on every admin screen
	// is admin hijacking even when the thing it says is true. WordPress
	// only hides it for the current page load, so it returns until
	// HivePress is actually activated.
	echo '<div class="notice notice-warning is-dismissible"><p>' . wp_kses(
		sprintf(
			/* translators: %s: link to the HivePress plugin. */
			__( 'Persistent Account Menu for HivePress requires the %s plugin to be installed and active. Until then, this plugin does nothing.', 'persistent-account-menu-for-hivepress' ),
			'<a href="https://wordpress.org/plugins/hivepress/" target="_blank">HivePress</a>'
		),
		[
			'a' => [
				'href'   => [],
				'target' => [],
			],
		]
	) . '</p></div>';
}

add_action( 'admin_notices', __NAMESPACE__ . '\\show_missing_hivepress_notice' );

/**
 * Gets the author's support page.
 *
 * One place, so the settings tab, the Plugins row and the View details
 * popup can never drift apart.
 *
 * @return string
 */
function get_support_url() {
	return 'https://ko-fi.com/chrisbathivepresscommunity';
}

/**
 * Adds a quiet Donate link to this plugin's row meta.
 *
 * WordPress fires `plugin_row_meta` for EVERY plugin on the screen and
 * joins the items with a pipe, so without the basename test the link
 * would appear on every row on the site. The star is a Dashicon rather
 * than Font Awesome, which wp-admin does not load. The label matches the
 * wording WordPress itself uses in the details popup ("Donate to this
 * plugin"), so the two placements read as one ask rather than two.
 *
 * @param array<string> $meta Row meta links.
 * @param string        $plugin_file Plugin file the row belongs to.
 * @return array<string>
 */
function add_row_meta( $meta, $plugin_file ) {
	if ( plugin_basename( __FILE__ ) === $plugin_file ) {
		$meta[] = '<a href="' . esc_url( get_support_url() ) . '" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-star-filled" style="font-size:14px;line-height:1.3;"></span>' . esc_html__( 'Donate', 'persistent-account-menu-for-hivepress' ) . '</a>';
	}

	return $meta;
}

add_filter( 'plugin_row_meta', __NAMESPACE__ . '\\add_row_meta', 10, 2 );

/**
 * Prints the support line under the settings form.
 *
 * A HivePress settings section carrying only a description would be the
 * tidier home for this, but `Admin::register_settings` skips any section
 * with no fields (`components/class-admin.php:288`), so a
 * description-only section renders nothing at all. Appending to the form
 * from `admin_footer` is the supported way, and inventing a stored
 * option purely to make a section appear is not.
 *
 * @return void
 */
function print_support_link() {

	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- Read-only screen check that changes nothing; the capability test below is the gate.
	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';
	$tab  = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';
	// phpcs:enable WordPress.Security.NonceVerification.Recommended

	if ( 'hp_settings' !== $page || 'persistent_menu' !== $tab || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<script>
	( function() {
		var form = document.querySelector( 'form[action*="options.php"]' );

		if ( ! form ) {
			return;
		}

		var p = document.createElement( 'p' );

		p.className = 'description';
		p.style.marginTop = '1.5rem';

		// Built as text plus one anchor, never innerHTML: the wording is translatable and a
		// translation is not trusted markup.
		p.appendChild( document.createTextNode( <?php echo wp_json_encode( __( 'If this plugin saved you time or money, consider ', 'persistent-account-menu-for-hivepress' ) ); ?> ) );

		var a = document.createElement( 'a' );

		a.href = <?php echo wp_json_encode( esc_url( get_support_url() ) ); ?>;
		a.target = '_blank';
		a.rel = 'noopener noreferrer';
		a.appendChild( document.createTextNode( <?php echo wp_json_encode( __( 'buying me a coffee', 'persistent-account-menu-for-hivepress' ) ); ?> ) );

		p.appendChild( a );
		p.appendChild( document.createTextNode( <?php echo wp_json_encode( __( '. Sharing these tools for free takes a lot of time and resources, and your support helps me keep doing it for the community. Thank you!', 'persistent-account-menu-for-hivepress' ) ); ?> ) );

		form.appendChild( p );
	}() );
	</script>
	<?php
}

add_action( 'admin_footer', __NAMESPACE__ . '\\print_support_link' );

/**
 * Adds the settings link to the plugin row.
 *
 * The link is only shown while HivePress is active, since the settings
 * tab does not exist without it.
 *
 * @param array<string> $links Plugin action links.
 * @return array<string>
 */
function add_settings_link( $links ) {
	if ( current_user_can( 'manage_options' ) && function_exists( 'hivepress' ) ) {
		array_unshift( $links, '<a href="' . esc_url( admin_url( 'admin.php?page=hp_settings&tab=persistent_menu' ) ) . '">' . esc_html__( 'Settings', 'persistent-account-menu-for-hivepress' ) . '</a>' );
	}

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), __NAMESPACE__ . '\\add_settings_link' );

/**
 * Adds the manual update check link to the plugin row.
 *
 * @param array<string> $links Plugin action links.
 * @return array<string>
 */
function add_update_check_link( $links ) {
	if ( current_user_can( 'update_plugins' ) ) {
		$links[] = '<a href="' . esc_url( wp_nonce_url( self_admin_url( 'plugins.php?hppam_check_updates=1' ), 'hppam_check_updates' ) ) . '">' . esc_html__( 'Check for updates', 'persistent-account-menu-for-hivepress' ) . '</a>';
	}

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), __NAMESPACE__ . '\\add_update_check_link' );
add_filter( 'network_admin_plugin_action_links_' . plugin_basename( __FILE__ ), __NAMESPACE__ . '\\add_update_check_link' );

/**
 * Handles the manual update check.
 *
 * Refreshes the cached release, re-runs the update check and redirects
 * back to the Plugins screen with the result.
 *
 * @return void
 */
function handle_update_check() {
	if ( ! isset( $_GET['hppam_check_updates'] ) || ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	check_admin_referer( 'hppam_check_updates' );

	$data = get_release_data( true );

	wp_clean_plugins_cache();
	wp_update_plugins();

	$status = 'none';

	if ( isset( $data['version'] ) ) {
		if ( version_compare( $data['version'], get_version(), '>' ) ) {
			$status = 'available';
		}
	} elseif ( isset( $data['none'] ) ) {

		// A 404 from the releases endpoint is an answer, not a failure to
		// get one: it is what every repository says before its first
		// release. Reporting it as a connectivity error sends people
		// hunting a network fault that does not exist.
		$status = 'unreleased';
	} else {
		$status = 'error';
	}

	wp_safe_redirect( add_query_arg( 'hppam_checked', $status, self_admin_url( 'plugins.php' ) ) );

	exit;
}

add_action( 'admin_init', __NAMESPACE__ . '\\handle_update_check' );

/**
 * Shows the manual update check result.
 *
 * @return void
 */
function show_update_check_notice() {

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only flag that selects one of three fixed notices; the state-changing request is nonce-checked in handle_update_check().
	if ( ! isset( $_GET['hppam_checked'] ) || ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- See above; the value is sanitised and only compared against fixed strings.
	$status = sanitize_key( wp_unslash( $_GET['hppam_checked'] ) );

	if ( 'available' === $status ) {
		$release = get_latest_release();

		/* translators: %s: new version number. */
		$message = sprintf( __( 'A new version of Persistent Account Menu for HivePress (%s) is available.', 'persistent-account-menu-for-hivepress' ), $release ? $release['version'] : '' );
		$class   = 'notice-success';
	} elseif ( 'none' === $status ) {
		$message = __( 'Persistent Account Menu for HivePress is up to date.', 'persistent-account-menu-for-hivepress' );
		$class   = 'notice-success';
	} elseif ( 'unreleased' === $status ) {
		$message = __( 'No releases have been published for this plugin yet, so there is nothing to update to. Your copy is working normally.', 'persistent-account-menu-for-hivepress' );
		$class   = 'notice-info';
	} elseif ( 'error' === $status ) {
		$message = __( 'Could not reach GitHub to check for updates. Please try again later.', 'persistent-account-menu-for-hivepress' );
		$class   = 'notice-error';
	} else {
		return;
	}

	echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
}

add_action( 'admin_notices', __NAMESPACE__ . '\\show_update_check_notice' );
add_action( 'network_admin_notices', __NAMESPACE__ . '\\show_update_check_notice' );

/**
 * Keeps updates installing into the current plugin directory.
 *
 * The extracted release folder is renamed to match the directory the
 * plugin is installed in, so an update can never end up in a differently
 * named folder even if the release zip is packaged unexpectedly.
 *
 * @param string               $source Extracted update source.
 * @param string               $remote_source Remote source directory.
 * @param object               $upgrader Upgrader instance.
 * @param array<string, mixed> $hook_extra Extra hook arguments.
 * @return string|\WP_Error
 */
function fix_update_directory( $source, $remote_source, $upgrader, $hook_extra = [] ) {
	global $wp_filesystem;

	if ( plugin_basename( __FILE__ ) !== ( isset( $hook_extra['plugin'] ) ? $hook_extra['plugin'] : '' ) || ! $wp_filesystem ) {
		return $source;
	}

	$directory = dirname( plugin_basename( __FILE__ ) );

	if ( '.' === $directory ) {
		return $source;
	}

	$target = trailingslashit( $remote_source ) . $directory . '/';

	if ( trailingslashit( $source ) === $target ) {
		return $source;
	}

	if ( ! $wp_filesystem->move( untrailingslashit( $source ), untrailingslashit( $target ) ) ) {
		return new \WP_Error( 'hppam_rename_failed', __( 'Could not rename the update directory.', 'persistent-account-menu-for-hivepress' ) );
	}

	return $target;
}

add_filter( 'upgrader_source_selection', __NAMESPACE__ . '\\fix_update_directory', 10, 4 );
