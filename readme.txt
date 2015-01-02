=== Twigify ===
Contributors: mpvanwinkle77
Donate link: http://mikevanwinkle.com/
Tags: content, templating, twig, layout
Requires at least: 4.0.1
Tested up to: 4.1
Stable tag: 1.1-beta
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin creates a templating system using TWIG that you can manage from within your WordPress admin interface. No need to create dozens of template files anymore for each content type or context. Templates can be applied to every page or post and can access all post and post metadata. You can even write a query from within a page using the TWIG exetensions.

== Description ==

This plugin creates a templating system using TWIG that you can manage from within your WordPress admin interface. No need to create dozens of template files anymore for each content type or context. Templates can be applied to every page or post and can access all post and post metadata. You can even write a query from within a page using the TWIG exetensions.

This plugin is still under development and should be considered in a "beta" state.

Current features: 
 * Create a content template that can be applied to any page or post
 * Reference all post and postmeta values from within a templatei or page/posts
 * Access terms and term lists from within templates or page/posts
 * Query posts and loop the result from within templates or page/posts
 * Template Tags: the_permalink, the_post_thumbnail, the_term_list

Coming features: 
 * Reference other templates from within templates or page/posts
 * Reference user info from within templates or page/posts
 * User access management from within templates or page/posts
 * Global template application rules 
 * is_single, is_home, is_archive, is_author, etc ...

http://github.com/mikevanwinkle/twigify

== Installation ==

1. Upload to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to the TWIGify menu and get started.

== Frequently Asked Questions ==

=	What is TWIG =

TWIG is a fast but powerful template framework developed for Symfony. It is quickly becoming "best practice" in the PHP community. For more information http://twig.sensiolabs.org/
 
== Screenshots ==

No screenshots available yet

== Changelog ==

= 1.0-beta =
* Initial release

= 1.1-beta =
* Theme Functions: get_bloginfo, get_sidebar, get_footer, the_time, comments_popup_link, the_title, the_category, the_author, the_ID, edit_post_link, wp_list_bookmarks, comments_template, wp_list_pages, wp_list_categories, get_post_meta, posts_nav_link, get_search_form, custom 
