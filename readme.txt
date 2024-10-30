=== CNN News ===
Contributors: Olav Kolbu
Donate link: http://www.kolbu.com/donations/
Tags: widget, plugin, cnn, news, cnn news, rss, feed
Requires at least: 2.3.3
Tested up to: 3.1
Stable tag: trunk

Displays news items from selectable CNN News RSS feeds, 
inline, as a widget or in a theme. Multiple feeds allowed. 
Caching.

== Description ==

CNN has a number of RSS feeds with current news available, on a 
number of topics. This widget allows the WP admin to select which
feed, how many items to show from that feed and optionally set a 
widget title. If no title is selected, the name of the feed is 
used. The feed is fetched for every view, so users are guaranteed
up to date information. No local storage of feed is done.
Clicking on a news item will of course take you straight to the
relevant article at CNN, as per CNN Terms of Use.

This plugin works both as a widget, as inline content
replacement and can be called from themes. Any number of 
inline replacements or theme calls allowed, but only one 
widget instance is supported in this release.

For widget use, simply use the widget as any other after
selecting which feed it should display. For inline content
replacement, insert the one or more of the following strings in 
your content and they will be replaced by the relevant news feed.
For theme use, add the do_action function call described below.

1. **`<!--cnn-news-->`** for the default feed
1. **`<!--cnn-news#feedname-->`**

Shortcodes can be used if you have WordPress 2.5 or above,
in which case these replacement methods are also available.

1. **`[cnn-news]`** for the default feed
1. **`[cnn-news name="feedname"]`**

Calling the plugin from a theme is done with the WP do_action()
system. This will degrade gracefully and not produce errors
or output if plugin is disabled or removed.

1. **`<?php do_action('cnn_news'); ?>`** for the default feed
1. **`<?php do_action('cnn_news', 'feedname'); ?>`**

Enable plugin, go to the CNN News page under 
Dashboard->Settings and read the initial information. Then 
go to the CNN News page under Dashboard->Manage and 
configure one or more feeds. Then use a widget or insert
relevant strings in your content or theme. 

Additional information:

The available options are as follows. 

**Name:** Optional feed name, that can be used in the 
widget or the inline replacement string to reference
a specific feed. Any feed without a name is considered
"default" and will be used if the replacement strings do
not reference a specific feed. If there are more than
one feed with the same name, a random of these is picked
every time it is used. This also applies to the default
feed(s). 

**Title:** Optional, which when set will be used in the
widget title or as a header above the news items when 
inline. If the title is empty, then a default title
of "CNN News : &lt;region&gt; : &lt;feed type&gt;" is used. Note
that as per CNN Terms of Service it is a requirement
to state that the news come from CNN.

**Feed:** A dropdown list of the current feeds provided
by CNN. This list is hard coded into the plugin, presumably
CNN does not change the list too often. The [INTL], [MONEY]
and [SI] after the feed name indicate that the feed is from 
CNN International, CNN Money or Sports Illustrated respectively.

**News item length:** Short or long. The short version is really just 
the news item title as a one liner but probably the one most 
WP admins will use. The long version is the title followed by
a 3-4 line teaser. For the short version, the long text is 
available as a mouse rollover/tooltip.

**Max items to show:** As the title says, if the feed has
sufficient entries to fulfil the request. 

**Cache time:** The feeds are now fetched using WordPress 
builtin MagpieRSS system, which allows for caching of feeds
a specific number of seconds. Cached feeds are stored in
the backend database.

Clicking on a news item will of course take you to the 
relevant article at CNN, as per CNN Terms of Use.

If you want to change the look&feel, the inline table is 
wrapped in a div with the id "cnn-news-inline" and the
widget is wrapped in an li with id "cnn-news". Let me 
know if you need more to properly skin it.

Note that if you get the message "CNN News unavailable" then
the WordPress internal RSS feed fetcher failed to get the
feed. Reasons for this are firewalls blocking outbound traffic,
CNN RSS being down or feed missing/wrong URL. Try again with
a different feed to see if that helps.

**[Download now!](http://downloads.wordpress.org/plugin/cnn-news.zip)**

[Support](http://www.kolbu.com/2008/04/03/cnn-news-plugin/)

[Donate](http://www.kolbu.com/donations/)


== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Unzip into the `/wp-content/plugins/` directory
1. Activate the plugin through the Dashboard->Plugins admin menu.
1. See configuation pages under Dashboard->Settings, Dashboard->Tools and on the widget page.

== Frequently Asked Questions ==

= Is this an irrelevant test question? =

Absolutely!

== Screenshots ==

1. Widget in action under the Prosumer theme. Note the mouseover showing additional text from the news item.
2. Small part of the admin Manage page for the plugin.
3. Inline example under the Prosumer theme, replacing `<!--cnn-news-->` in content.

== Changelog ==

= 2.6 =
* Fixed critical typos on settings page
* Feeds updated, now over 100 feeds in total!
* Some old feeds renamed to conform with new CNN names.
= 2.5.1 =
* Converted to new ChangeLog syntax
* Synced version numbers with Google News plugin
* No other changes since 2.4.1
= 2.4.1 =
* Fixed minor markup glitch
= 2.4 =
* Fixed WP 2.7 compat problems
= 2.3 =
* Fixed a bug when plugin used directly in a theme.
= 2.2 =
* Copied all the new functionality from my Google News plugin, and synced the version numbers.
* Removed dependency on PHP 5 functionality.
* Fixed UTF8-related bugs. 
* Uses a class to avoid name space pollution.
* Better best practice plugin writing.
* Widget, inline replacement and theme calls.
* Multiple feeds allowed. 
* Using WP builtin RSS fetching and caching system. 
* Shortcodes are supported. 
= 1.0 =
* Initial release

== Upgrade Notice ==

= 2.6 =
Updated feeds list, now over 100 CNN feeds!
