# Persistent Account Menu for HivePress

Keeps HivePress account menu items visible even when they are empty, and replaces each empty page with a helpful notice, icon and button.

By default, HivePress and its extensions only show an account menu item once there is something to list — no favorites means no Favorites link, no bookings means no Bookings link. That keeps menus tidy, but it also hides features from new users. This plugin keeps the menu items in place and turns each empty page into a friendly empty state with a call to action.

## Managed menu items

Items are only forced when the matching extension is active, and vendor-only items only appear for users with a vendor profile:

| Item | Provided by |
| --- | --- |
| Listings | HivePress core |
| Favorites | Favorites |
| Requests, Offers | Requests |
| Calendar (vendors), Bookings | Bookings |
| Saved Searches | Search Alerts |
| Messages | Messages (when message storage is enabled) |
| Membership | Memberships |
| Orders, Placed Orders, Payouts | Marketplace / WooCommerce |
| Subscriptions | WooCommerce Subscriptions |

Internals are source-verified against HivePress 1.7.26, Favorites 1.2.2, Messages 1.4.0, Bookings 1.5.5, Marketplace 1.3.15, Memberships 2.2.0, Requests 1.2.5 and Search Alerts 1.1.3.

## Requirements

- WordPress 5.8 or newer
- PHP 7.4 or newer
- [HivePress](https://hivepress.io/)

## Installation

1. Download the latest release: [persistent-account-menu-for-hivepress.zip](https://github.com/irapidchris-del/Persistent-Account-Menu-for-HivePress/releases/latest/download/persistent-account-menu-for-hivepress.zip)
2. In your WordPress dashboard, go to **Plugins → Add New Plugin → Upload Plugin** and upload the zip.
3. Activate **Persistent Account Menu for HivePress**.

## Updates

The plugin checks this GitHub repository for new releases and shows updates in your WordPress dashboard just like any other plugin — including one-click and automatic updates. You can also click **Check for updates** in the plugin's row on the Plugins screen to check immediately.

## Customization

Two filters are provided:

- `hppam/v1/items` — add, remove or adjust the managed items, including notice text, icon codepoint and button.
- `hppam/v1/notice_html` — filter the rendered empty-state notice HTML.

For example, to stop forcing the Messages item:

```php
add_filter( 'hppam/v1/items', function ( $items ) {
	unset( $items['messages_thread'] );

	return $items;
} );
```

## Releasing (maintainer notes)

Publish a GitHub release with a tag matching the plugin version (for example `v1.2.0` or `1.2.0`). A workflow then builds `persistent-account-menu-for-hivepress.zip` (with the correct inner directory name and no version number in the file name) and attaches it to the release automatically. Installed copies pick the new version up within a few hours, or immediately via **Check for updates**.

## License

GPLv3 — see the [LICENSE](LICENSE) file. This matches the license used across the HivePress ecosystem.
