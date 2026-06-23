=== Neon text ===
Author URI: https://www.ERALION.com
Plugin URI: https://www.ERALION.com
Donate link: https://www.ERALION.com
Contributors: freeben
Tags: Animated Counters
Requires at least: 4.1
Tested up to: 6.3.1
Stable tag: 1.6
Requires PHP: 5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Description: Plugin for Animated Counters shortcodes.
Author: ERALION.com

== Description ==

Plugin for neon text effect.
Neon text allows you to create easily shortcode to customize your pages and posts with neon text effect. The shortcode generator helps you through the options for the shortcode.

<a href="https://www.eralion.com/expert-wordpress/#plugin_wordpress_neon_text" target="_blank">DEMO</a>

This plugin uses chuckyglitch's jQuery script "novacancy" (https://github.com/chuckyglitch/novacancy.js) and makes it easy to use with shortcodes.

**Available options :**

* reblinkProbability: probability of reblink(0 to 1), Number, optional, default: (1/3)
* blinkMin: time(sec.) of minimum blink, Number, optional, default: 0.01
* blinkMax: time(sec.) of maximum blink, Number, optional, default: 0.5
* loopMin: time(sec.) of minimum trigger blink, Number, optional, default: 0.5
* loopMax: time(sec.) of maximum trigger blink, Number, optional, default: 2
* color: colors, String, optional default: 'ORANGE'
* glow: array of text-shadow colors, Array, optional, default: '0 0 80px Orange', '0 0 30px Red', '0 0 6px Yellow'
* off: amount of off chars, Number, optional, default: 0
* blink: amount of blink chars, Number, optional, default: 0, (0 means all chars)

If you have a problem, you can <a href="https://www.eralion.com/contact/" target="_blank">contact me</a>.

== Installation ==

1. Download neon-text.zip from the "download" link on the web page where you're viewing this.
2. Decompress the file contents.
3. Upload the neon-text folder to your WordPress plugins directory (/wp-content/plugins/).
4. Activate the Neon text plugin from the WordPress back-office.

= Configuration =

You can use the shortcode generator in back-office to configure your neon texts.

== Frequently Asked Questions ==

= Is it compatible with all WordPress themes? =

Compatibility with all themes is impossible, because they are too many, but generally if themes are developed according to WordPress and WooCommerce guidelines, this plugin is compatible with them.

== Screenshots ==

1. Demo effect
2. Shortcode generator

== Changelog ==

= 1.3 =
* Bug fix

= 1.2 =
* Fixed a possible XSS vulnerability (discovered by Dmitrii Ignatyev from CleanTalk inc.)

= 1.1 =
* Shortcode generator in back-office

= 1.0 =
* First public distribution version.
