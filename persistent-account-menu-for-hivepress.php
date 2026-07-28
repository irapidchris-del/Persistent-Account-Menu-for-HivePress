<?php
/**
 * Plugin Name: Persistent Account Menu for HivePress
 * Plugin URI: https://github.com/irapidchris-del/Persistent-Account-Menu-for-HivePress
 * Description: Keeps HivePress account menu items visible even when they are empty, and replaces each empty page with a helpful notice, icon and button.
 * Version: 1.5.1
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

/**
 * Loads the plugin translations.
 *
 * Translations are loaded from the standard locations, so translation
 * plugins like Loco Translate can save custom wordings for any of the
 * plugin's texts under the `persistent-account-menu-for-hivepress`
 * text domain.
 *
 * @return void
 */
function load_textdomain() {
	load_plugin_textdomain( 'persistent-account-menu-for-hivepress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

add_action( 'init', __NAMESPACE__ . '\\load_textdomain', 1 );

/**
 * Gets the default managed menu items.
 *
 * Routes, orders, display conditions, empty-page redirects and page
 * block names are source-verified against HivePress 1.7.26,
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
				'text'   => __( "You haven't added any listings to your favorites yet. Once you click the heart icon on a listing, you can return to this page to find the listing more easily next time.", 'persistent-account-menu-for-hivepress' ),
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

	// Add the WooCommerce items that HivePress core links into the account
	// menu. These mirror the exact item shape core uses, and their pages
	// already render native WooCommerce empty states, so no notice is set.
	if ( function_exists( 'wc_get_endpoint_url' ) && function_exists( 'wc_get_page_permalink' ) && function_exists( 'wc_get_account_menu_items' ) ) {
		$items['orders_view'] = [
			'title'  => __( 'Orders (WooCommerce)', 'persistent-account-menu-for-hivepress' ),
			'label'  => hp\get_array_value( wc_get_account_menu_items(), 'orders', __( 'Orders', 'persistent-account-menu-for-hivepress' ) ),
			'url'    => wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ),
			'_order' => 40,
		];

		if ( class_exists( 'WC_Subscriptions' ) ) {
			$items['subscriptions_view'] = [
				'title'  => __( 'Subscriptions (WooCommerce)', 'persistent-account-menu-for-hivepress' ),
				'label'  => hp\get_array_value( wc_get_account_menu_items(), 'subscriptions', __( 'Subscriptions', 'persistent-account-menu-for-hivepress' ) ),
				'url'    => wc_get_endpoint_url( 'subscriptions', '', wc_get_page_permalink( 'myaccount' ) ),
				'_order' => 42,
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
 * are left completely untouched and keep the stock behavior.
 *
 * @return array<string, array<string, mixed>>
 */
function get_items() {
	static $items = null;

	if ( null !== $items ) {
		return $items;
	}

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

	$settings['persistent_menu'] = [
		'title'    => __( 'Default Menu Items', 'persistent-account-menu-for-hivepress' ),
		'_order'   => 200,

		'sections' => [
			'items' => [
				'description' => __( 'Choose the account menu items that stay visible even when they are empty. Unchecked items are left untouched and keep the default behavior, appearing only once there is something to show.', 'persistent-account-menu-for-hivepress' ),
				'_order'      => 10,

				'fields'      => [
					'hppam_items' => [
						'label'   => __( 'Menu Items', 'persistent-account-menu-for-hivepress' ),
						'type'    => 'checkboxes',
						'options' => $options,
						'default' => array_keys( $options ),
						'_order'  => 10,
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
				? __( 'Customize the button on this empty page. Leave a field blank to keep the default.', 'persistent-account-menu-for-hivepress' )
				: __( 'This page has no button by default. Set both a label and a URL to add one.', 'persistent-account-menu-for-hivepress' ),

			'_order'      => $order,

			'fields'      => [
				'hppam_button_label_' . $name => [
					'label'       => __( 'Button Label', 'persistent-account-menu-for-hivepress' ),
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
 * Checks if the current user has a published vendor profile.
 *
 * Used to force vendor-only items for vendors regardless of whether they
 * have data yet. Core's `vendor_id` request context is capability-gated,
 * so the vendor profile is queried directly and cached per request.
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
		} elseif ( isset( $item['url'] ) ) {
			$menu['items'][ $name ] = [
				'label'  => $item['label'],
				'url'    => $item['url'],
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
 * chain. The notice only renders when the extension itself left the item
 * out of the native menu, meaning the page is empty.
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

		$blocks = [
			'hppam_empty_notice' => [
				'type'    => 'content',
				'content' => render_notice( $item['notice'] ),
				'_order'  => 5,
			],
		];

		// Blank the page's own output so the default "Nothing found"
		// message is not shown alongside the notice.
		foreach ( hp\get_array_value( $item['notice'], 'blank', [] ) as $block_name ) {
			$blocks[ $block_name ] = [
				'type'    => 'content',
				'content' => '',
			];
		}

		$template = hp\merge_trees(
			$template,
			[
				'blocks' => [
					'page_content' => [
						'blocks' => $blocks,
					],
				],
			]
		);

		break;
	}

	return $template;
}

add_filter( 'hivepress/v1/templates/user_account_page', __NAMESPACE__ . '\\alter_account_page', 200 );

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
			$output .= '<a href="' . esc_url( $url ) . '" class="hppam-empty__button button button--primary alt">' . esc_html( $label ) . '</a>';
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

	wp_add_inline_style(
		'hppam-frontend',
		'.hppam-empty{display:flex;flex-direction:column;align-items:center;text-align:center;padding:3rem 1rem;gap:1rem}
		.hppam-empty__icon::before{content:attr(data-icon);font-family:"Font Awesome 5 Free","Font Awesome 6 Free","Font Awesome 7 Free";font-weight:900;font-size:2.75rem;line-height:1;opacity:.25}
		.hppam-empty__text{max-width:26rem;margin:0}
		.hppam-empty__button{margin-top:.25rem}'
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
 * Gets the latest GitHub release details, cached for 6 hours.
 *
 * @param bool $force Bypass the cache.
 * @return array<string, string>|null
 */
function get_latest_release( $force = false ) {
	$release = $force ? false : get_site_transient( UPDATE_CACHE_KEY );

	if ( ! is_array( $release ) ) {
		$release = fetch_latest_release();

		// Failures are cached briefly so the API is not queried repeatedly.
		set_site_transient( UPDATE_CACHE_KEY, $release, $release ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	}

	return $release ? $release : null;
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
			'timeout' => 10,
			'headers' => [ 'Accept' => 'application/vnd.github+json' ],
		]
	);

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
		'sections'      => [
			'description' => wpautop( esc_html( $plugin_data['Description'] ) ),
			'changelog'   => $release['notes'] ? wpautop( esc_html( $release['notes'] ) ) : '<p>' . esc_html__( 'See the GitHub releases page for the changelog.', 'persistent-account-menu-for-hivepress' ) . '</p>',
		],
	];
}

add_filter( 'plugins_api', __NAMESPACE__ . '\\get_plugin_information', 10, 3 );

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

	$release = get_latest_release( true );

	wp_clean_plugins_cache();
	wp_update_plugins();

	$status = 'none';

	if ( ! $release ) {
		$status = 'error';
	} elseif ( version_compare( $release['version'], get_version(), '>' ) ) {
		$status = 'available';
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
	if ( ! isset( $_GET['hppam_checked'] ) || ! current_user_can( 'update_plugins' ) ) {
		return;
	}

	$status = sanitize_key( wp_unslash( $_GET['hppam_checked'] ) );

	if ( 'available' === $status ) {
		$release = get_latest_release();

		/* translators: %s: new version number. */
		$message = sprintf( __( 'A new version of Persistent Account Menu for HivePress (%s) is available.', 'persistent-account-menu-for-hivepress' ), $release ? $release['version'] : '' );
		$class   = 'notice-success';
	} elseif ( 'none' === $status ) {
		$message = __( 'Persistent Account Menu for HivePress is up to date.', 'persistent-account-menu-for-hivepress' );
		$class   = 'notice-success';
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
