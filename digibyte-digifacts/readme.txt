=== Plugin Name ===
Contributors: saltedlolly
Donate link: https://github.com/saltedlolly/DigiByte-DigiFacts-Wordpress-Plugin
Tags: digibyte, digifacts, crypto, blockchain
Requires at least: 4.7
Tested up to: 6.4.1
Stable tag: 4.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Display a random DigiFact - a fact about DigiByte - every 60 seconds using AJAX and jquery.

== Description ==

Plugin to display a random DigiFact every 60 seconds using AJAX and jquery. Learn more about DigiByte DigiFacts [here](https://github.com/saltedlolly/DigiByte-DigiFacts-JSON).

## Usage

User the [digifacts] shortcode or included gutenberg block to insert the DigiFacts into your web page.

## How this works

* The current set of DigiFacts are retrieved from the remote web service (digifacts.digibyte.help) and cached on the Wordpress server once per hour
* When a user visits the Wordpress site, the current set of DigiFacts are cached in the browser's local storage for 60 minutes. A random DigiFact is displayed.
* Every 60 seconds the DigiFact displayed on the screen is refreshed with another random one.
* If more than 60 minutes have passed, new DigiFacts are fetched via an AJAX call to the web server.
* If less than 60 minutes have passed, the DigiFacts from localStorage are used.

## Donate to the DigiFacts Translation Fund!

Please help DigiByte reach more people around the world by donating to the DigiFacts Translation Fund:

* For every 12,500 DGB that is donated, DigiFacts will be translated into five additional languages.
* Anyone who donates 2500 DGB or more gets to choose a language to translate to. (see below)
* Your donations support the ongoing development and hosting costs for the DigiFacts web service. Thank you for your support.

Donate here: **dgb1qrgmuy24pj738tuc64wl30us9u8g2ywq3tclsjv**

![DigiFact Translation Fund](digibyte-digifacts/qrcode.png)

You can monitor the current donations [here](https://digibyteblockexplorer.com/address/dgb1qrgmuy24pj738tuc64wl30us9u8g2ywq3tclsjv).

== Screenshots ==

1. DigiFacts settings screen - choose language and whether the DigiFact is enclosed in a box.
2. DigiFacts can be embedded in your website and refresh every 60 seconds.

== Changelog ==

= 1.1 =
* Initial release

== Upgrade Notice ==

= 1.1 =
Please upgrade - this version reduces the number of remote queries required.