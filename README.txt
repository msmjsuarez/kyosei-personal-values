=== Kyosei Personal Values ===
Contributors: MJ Layasan
Tags: personal values, user profile, WordPress plugin, coaching, kyosei  
Requires at least: 5.0  
Tested up to: 6.8.1  
Requires PHP: 7.4+  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

A plugin to manage and display personal values cards for users, complete with modality tags, user test tracking, and admin settings.

== Description ==

The **Kyosei Personal Values** plugin helps administrators and coaches create, manage, and display personal value cards to users in a meaningful way. It includes:

- Admin panel for creating, editing, and deleting personal value cards with title, image, description, and long description.
- Support for modality tags, allowing categorization of cards.
- Settings for card layout and selection options.
- AJAX-powered search for quick filtering.
- Frontend support for users to take a personal values test.
- Stores each user's latest test result.
- Displays the most recent test on their WordPress profile page.

== Features ==

- Add/edit/delete personal value cards with images and rich descriptions.
- Assign modality tags to each card.
- Configure number of cards shown per row or for selection.
- WYSIWYG settings for compare page top/bottom text.
- Stores users' selected personal values and shows them on the user profile.
- Lightweight and fast, uses native WordPress features.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/kyosei-personal-values` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the ‘Plugins’ screen in WordPress.
3. Navigate to **Personal Values** in the admin menu to begin creating and managing cards.
4. Optional: Configure settings under "Cards Per Row", "Cards Selection", and "Compare Page Text".
5. Create a new page and add the shortcode "personal_value_cards" to show the test values in action.
6. Create another page and add the shortcode "display_personal_value_titles". This is where to show the user's top values. Make sure the page has slug "my-top-personal-values"

== Frequently Asked Questions ==

= Where are the user tests stored? =  
User test results are stored in the `wp_kyosei_personal_values_user` table.

= Can I display the selected values in the frontend? =  
Yes, you can create a custom shortcode or template to display the latest selected values per user.

= Can I customize the number of cards shown per row? =  
Yes, go to **Personal Values > Cards Per Row** in the admin panel.

== Screenshots ==

1. Admin dashboard with personal value cards.
2. Modal form to add/edit cards.
3. Settings page for configuring cards per row.
4. User profile view with latest test results.

== Changelog ==

= 1.0.0 =
* Initial release with card management, modality tags, and user profile integration.

== Upgrade Notice ==

= 1.0.0 =
Initial release.

== License ==

This plugin is licensed under the GPLv2 or later.

