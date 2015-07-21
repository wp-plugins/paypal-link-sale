=== Paypal Sell Link Ads ===

Contributors: sunnyverma1984
Donate link: http://99webtools.com/blog/contribute/
Tags: paypal, link sale, link, sale, ipn, subscription, widget, ads, sell ads, monetize, recurring payment, recurring payments, Recurring Subscription, Recurring Subscriptions,dashboard widget, dashboard, paypal ipn, subscription, payment, orders
Requires at least: 2.8
Tested up to: 4.2.2
Stable tag: 1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Monetize your blog by selling text links using paypal subscriptions


== Description ==

**Paypal Link Sale** plugin allows to monetize WordPress blog by selling text link in sidebar widget. Anyone can buy text link using paypal subscription.
Paypal Link Sale plugin is fully automated which automatically manage orders using paypal IPN.

= Plugin Features =

* Easy installation and integration
* Fully automated using paypal IPN
* Multi Currency
* Multi payment terms
* Custom anchor text size
* Supports Paypal Sanbox
* Custom order form
* Admin settings menu
* Sidebar Widget
* Dashboard widget
* Cross Browser capability
* Translation ready



== Installation ==

= Using the Plugin Manager =

1. Click Plugins
2. Click Add New
3. Search for `paypal-link-sale`
4. Click Install
5. Click Install Now
6. Click Activate Plugin
7. Add `Paypal Link Sale` widget to sidebar

= Manually =

1. Upload `paypal-link-sale` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Add `Paypal Link Sale` widget to sidebar

== Screenshots ==

1. Admin menu Orders List page demo
2. Admin menu settings page demo
3. Sidebar widget demo
4. Order Form demo
5. Payment Page demo
6. Dashboard widget demo

== Frequently Asked Questions ==

= What is paypal sandbox? =

The PayPal Sandbox is a self-contained, virtual testing environment that mimics the live PayPal production environment. It provides a shielded space where you can initiate and watch your application process the requests you make to the PayPal APIs without touching any live PayPal accounts.

= Paypal duplicate invoice ID and how to solve it? =

Paypal by default does not allow duplicate invoices. When you try to pay for a duplicate invoice id, paypal will produce the following error:

`The transaction was refused as a result of a duplicate invoice ID supplied. Attempt with a new invoice ID`

If this were to happen, one of the reasons could be that the configuration in Paypal is set to not accept duplicate invoices. you may receive orders from various places and if the invoice numbers are the same, Paypal recognises there is an invoice duplication.

**Solution**

1. Log-in to your Paypal sandbox account
2. Click Profile
3. Under Selling Preferences, click Payment Receiving Preferences
4. Under Block Accidental Payments choose "No, allow multiple payments per invoice ID".
Save.

== Changelog ==

=1.1=
* Fix some database issues

= 1.0 =
* Initial release