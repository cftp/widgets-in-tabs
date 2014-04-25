=== Widgets In Tabs ===
Contributors: ahspw
Tags: tabs, tabbed, widget, tabbed widget, theme, sidebar, widget area
Requires at least: 3.5
Tested up to: 3.9
Stable tag: 0.7
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Group any number of widgets into one tabbed, light, and beautiful widget.

== Description ==

Widgets In Tabs is an awesome plugin, that lets you group as many awesome widgets as you want, in one tabbed and beautiful widget, with dynamic styles, and a sleek transition between tabs.

= Examples =

* You can use WIT to show Recent Posts Widget and Recent Comments Widget, as one widget with two tabs.
* You might want to include a third tab to show Tag Cloud widget, or Categories widget.
* Or perhabs you have a Facebook like box widget, and a Google badge widget, and you want them to be displayed as one widget with two tabs.
* and the list goes on...

Widgets In Tabs (WIT for short) adds a special widgets area (sidebar) and a new widget.
This widgets area is special, because it will not appear anywhere in your site. It's just for the WIT plugin.
You can add as many widgets as you want to the special widgets area, just like any other widget area.
WIT widget will take all of those widgets, and turn them into one tabbed widget, light and beautiful.

= Features: =

* Dynamic Styles
    * WIT uses the styles of your current theme. That means WIT doesn't have its own set of styles. Instead, it blends in with your theme's styles. You might want to check the screenshots tab!
* Nice Scrollable Titles
    * Instead of stacking widgets' titles, you get a one-line, scrollable titles.
    * An option is also available to make all the tabs visible.
* Animated Transition between Widgets
    * You can even make it automatic, by choosing how many seconds to wait before switching to the next tab, with a sleek transition animation.
* A shortcode to display WIT widget anywhere in a post or a page.
    * The shortcode is simply `[wit]`. Add it anywhere inside your post or page, and WIT widget will magically appear there!
    * See FAQ tab for more tips on this.
* RTL support and Translation Ready
    * It already has Arabic!

= Spread The Word =

WIT is released for FREE, and it will always be. If you like this plugin, share it and spread the wrod. This helps very much, Thanks.

* [Facebook](//www.facebook.com/sharer.php?u=https%3A%2F%2Fwordpress.org%2Fplugins%2Fwidgets-in-tabs%2F)
* [Twitter](//twitter.com/intent/tweet?text=Widgets%20In%20Tabs%20WordPress%20Plugin&url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fwidgets-in-tabs%2F)
* [Google+](//plus.google.com/share?url=https%3A%2F%2Fwordpress.org%2Fplugins%2Fwidgets-in-tabs%2F)
* Or on your favorite social network!

== Installation ==

1. Install Widgets In Tabs either via the WordPress.org plugin directory, or by uploading the files to your server.
2. Activate Widgets In Tabs through the 'Plugins' menu in WordPress.
3. That's it. You're ready to go!

== Frequently Asked Questions ==

= How to use the WIT shortcode? =

Simply, type `[wit]` wherever you want WIT widget to appear inside your post or page. This will add a WIT instance with default options.

You can also use the WIT button for more options. Here are some examples:

* `[wit interval='0']`
* `[wit interval='3']`
* `[wit tab_style='scroll']`
* `[wit tab_style='show_all']`
* `[wit interval='3' tab_style='show_all']`

The interval attribute only accepts integer values that are equal to or larger than zero.
The tab_style attribute only accepts 'show_all' or 'scroll'.
If an inavlid value is provided, WIT will revert back to the default one.

== WIT Next ==

Some of the features to expect in the next releases:

* More animations
* More options

== Credits ==

WIT uses the following plugins:

* [prefect-scrollbar](http://noraesae.github.io/perfect-scrollbar)
* [jquery-mousewheel](http://github.com/brandonaaron/jquery-mousewheel)

== Screenshots ==

1. WIT in 2012 default theme
2. WIT in 2013 default theme
3. WIT in 2014 default theme

== Changelog ==

= 0.7 =

* Bugfix: each WIT instance should have its own unique options
* New: Option to show all tabs instead of a scrollbar
* New: Shortcode to display WIT widget anywhere inside a page or a post
* New: Editor button for WIT shortcode
* Known issues
    * scrollbar disappears on RTL websites on non-webkit browsers
    * the sidebar to which WIT is added will have a long height depending on how many tabs WIT has

= 0.5 =

* Bugfix: when animation is disabled, clicking on a tab causes crazy animation
* Dependencies upgraded
* Code reviewed and some parts rewritten
* WIT widget in admin area is now unique!
* Known issues: scrollbar disappears on RTL websites on non-webkit browsers

= 0.1 = 

initial release

== Upgrade Notice ==

= 0.5 =

This version fixes a bug, upgrades dependencies, and improves performance. See Changelog for details.

= 0.7 =

This version fixes a bug and introduces new features. See Changelog for details.