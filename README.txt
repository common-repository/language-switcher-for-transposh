=== Language Switcher for Transposh ===
Contributors: codingfix
Donate link: https://www.paypal.com/paypalme/codingfix
Tags: multi-language, translation
Requires at least: 4.0.1
Tested up to: 6.6
Stable tag: 1.7.3
Requires PHP: 5.6
Requires Plugins: transposh-translation-filter-for-wordpress
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A nice language switcher to Transposh plugins. **REQUIRES TRANSPOSH PLUGIN BE INSTALLED AND CONFIGURED https://transposh.org/download**

== Description ==

**BE AWARE!** Language Switcher for Transposh **requires** [Transposh Translation Filter plugin](https://transposh.org/download): LSfT doesn't translate anything, it just adds an alternative language switcher for your website.
LSfT can't even be activated if you don't have installed Transposh plugin. So, you must follow these steps:

**first** download, install and activate [Transposh Translation Filter plugin](https://transposh.org/download); I also recommend to setup Transposh choosing the languages you want to use in your website and setting the Transposh options accordingly with your needs; 

**second**: installa and activate Language Switcher for Transposh: it will allow you to get a nice, highly customizable language switcher to use alongiside Transposh.


WARNING! Recently I've got serveral issues updating this plugin and trying to fix the new bugs. Now it looks like the users who have installed version 1.5.7 are asked to give a license key. In addition, they can’t receive automatic updates.

Unfortunately, the only way to solve this problem is

   - deactivate version 1.5.7 and uninstall it
   - download version 1.5.8 from the WordPress repository
   - upload the downloaded archive to your server
   - reinstall and reactiavte the plugin

I am very sorry about all this and I apologize again for any problems it may have caused.
  
Feel free to email me to codingfix-at-codingfix-dot-com if you need help.
I apologize for the inconvenience and for the problems it may have caused.
Thank you for your patience.


What does Language Switcher for Transposh:

* it gets default language and used languages from Transposh plugin settings

* it allows you to add as many flags as used languages are; in addition, administrators, authors and editors will see an Edit translation button as last item in the primary menu which will allow them to activate the Transposh Editor

* it allows you to choose between Transposh flags or flags provided by Language Switcher for Transposh itself

* it allows you to add to your language switcher menu item all classes you need: this allows you to make it look accordingly to your theme style using the same class your theme is using for navigation menu items

* it allows you to choose to use just simple flags or a dropdown, and if this is the case, you can choose if using a select or an unordered list to build your dropdown

* if you use an unordered list as dropdown, you can choose if the list items will show flag only, text only or both flags and text

* it provides basic stylesheets you can copy and use as a starting point to totally customize your Language Switcher

* it provides 6 shortcodes to put your language switcher everywhere you want! The shortcodes allow you to use horizontal flags, vertical flags, a native select element, a custom dropdown list with only flags, with only text or with both flags and text.
With the help of a third plugin (Shortcode in Menus), you can even put a shortcode in your menu - in this case, you're expected to disable Automode, of course! :)

* it provides 6 standard widgets to put a language switcher everywhere widgets can be placed: Like the shortcodes, the widgets allow you to use horizontal flags, vertical flags, a native select element, a custom dropdown list with only flags, with only text or with both flags and text.

* Automode: with Automode set to On, LSFT will append itself to the primary menu (and in ever location you have chosen to put it). If you set Automode to Off, then LSFT will do nothing letting you to se shortcode wherever you can put a shortcode. Obviously, Autocode and shortcodes are reciprocally compatible, so you can use them at the same time.

* it allos you to set if the user who change the used language has to be redirected to the home page or to the same page he was visiting

**An important notice about the FSE themes**. LSFT Automode doesn't work with the new Full Site Editing mode and with themes built this way. So if you use these themes you'll have to LSFT shortcodes and the block shortcode to add language switcher to your menu.

**Avada compatibility** For some reason using Avada theme the menu theme_location seems to be empty (even if this doesn't make sense and I hope to find a fix). In the meantime, if you use Avada Theme Builder, you can install [Shortcode in Menus](https://wordpress.org/plugins/shortcode-in-menus/) end add LSFT shortcode in your menu using the new option in menu page.

You can download the plugin from the WordPress Plugins Directory.

Remember to dwnload the latest version of Transposh Language Filter from the official website.

(The basic idea for this plugin comes from an article I wrote sometime ago,
How to use a custom language switcher with Transposh to build a multi-language WordPress website)

  **Notice:** in order to avoid 'Page not found' issues, be sure to check the option "Rewrite URLs to be search engine friendly, e.g. (http://transposh.org/en). Requires that permalinks will be enabled." in the Transposh Settings page.

== Installation ==

Dashboard Method:

  1. Login to your WordPress admin and go to Plugins > Add New
  2. Type “Better Search Replace” in the search bar and select this plugin
  3. Click “Install”, and then “Activate Plugin”


Upload Method:

  1. Unzip the plugin and upload the “better-search-replace” folder to your ‘wp-content/plugins’ directory
  2. Activate the plugin through the Plugins menu in WordPress


And you're done!

== Frequently Asked Questions ==

= Can I use Language Switcher for Transposh without using Transpoh? =

No. This plugin is just a small improvement of a little part of Transposh and can't be used without it.

== Screenshots ==

1. /assets/lsft.png

== Changelog ==

= 1.0.0 =
* This is the first version of this plugin so there is no changelog :)

= 1.0.1 =
* Added the options to choose the menu(s) where the language switcher will show up

= 1.0.2 =
* Improved css for menu locations

= 1.0.3 =
* Fixed bug saving custom styles

= 1.0.4 =
* Improved README file, minor bugs fixed

= 1.0.5 =
* Minor bug fixed

= 1.0.6 =
* Fixed menu locations issue which prevented to show the language switcher

= 1.0.7 =
* Fixed a bug managing default language
* Fixed a bug which prevented to change the dropdown list items depending on the current language
* Slightly improved the default.css rules for dropdown list (but this is about you depending on the theme in use)

= 1.0.8 =
* Fixed a bug managing used languages

= 1.0.9 =
* Added Settings link in the Plugins list admin page

= 1.0.10 =
* Fixed bug preventing displaying Settings link in the Plugins list admin page

= 1.0.11 =
* Fixed a couple of typos

= 1.0.12 =
* Fixed minor bugs

= 1.0.13 =
* Fixed minor bugs

= 1.0.14 =
* Fixed minor bugs

= 1.0.15 =
* Added code to show an error messages after failed activation because of Transposh is not found in plugins directory

= 1.0.16 =
* Fixed one minor bug

= 1.0.17 =
* Now switching to another language reload the page the user is viewing

= 1.0.18 =
* Fixed a bug which broke link switching language

= 1.0.19 =
* Minor bug fixed

= 1.0.20 =
* Reverted last changes because they just didn't work. Now switching language redirect to the home page. I'll still work on this but I’ll be more careful before publishing new versions

= 1.0.21 =
* Tested up to 5.8.9

= 1.0.22 =
* Tested up to 5.9
* fixed a couple of bugs which prevented to use custom dropdown swither

= 1.0.23 =
* Tested up to 5.9.1

= 1.2.1 =
* You can choose if, after having changed language, visitor must be redirected to the home page or to the same page they were visiting
* added shortcodes: now you can use Automode and let Language Switcher for Transposh add it self to the main menu or you can disable Automode and put shortcodes everywhere... Or you can use both feature at the same time. Shortcodes are available for horizontal flags, vertical flags, native select, custom list with flags only, text only or both flags and text
* custom styling capabilities have been improved
* Tested up to 5.9.2

= 1.2.2 =
Fixed a bug about flag size

= 1.2.3 =
Fixed a bug which prevented to load basic stylesheets

= 1.2.4 =
Fixed a bug which prevented to download new stylesheets

= 1.2.5 =
Added code to check plugin versions and call activation hook to update database options. It works even if the plugin is update manually.

= 1.2.6 =
Improved the way the plugin checks versions and updates options

= 1.2.7 =
Fixed wrong shortcode names for horizontal and vertical flags

= 1.2.8 =
Added the widget. Prevent activation if Transposh is not installed. Fixed the bug which prevented to download additional stylesheets.

= 1.2.9 =
Fixed a bug which prevented widget to show up.

= 1.3.0 =
Fixed a bug which prevented the shortcode for custom list with flags and names to work correctly.

= 1.3.1 =
Now Language Switcher for Transposh has its own widget you can use in the WordPress widget page. Fixed list-style-type for custom lists.

= 1.3.2 =
Fixed a bug which prevented the widget for custom list with flags and names to work correctly.

= 1.3.3 =
Added option to display original languages names; deleted obsolete css files.

= 1.3.4 =
Fixed bug in css editor which displayed an error message in browser console

= 1.3.5 =
Fixed a bug which prevented to show the original language names switcher in admin options page

= 1.3.6 =
Minor fix

= 1.3.7 =
Fixed a bug which in some cases prevented to show default language in the frontend switcher.

= 1.3.8 =
Fixed a bug which prevented other tabs to work correctly in the admin dashboard.

= 1.3.9 =
Tested up 6.0.1

= 1.4.0 =
Tested up 6.0.2
FIxed a bug which prevented to switch to Styles tab in plugin options page

= 1.4.1 =
Tested up 6.1.1

= 1.4.2 =
Fixed a bug that in some cases raised a 500 error loading stylesheets

= 1.4.3 =
Increased specificity of css rules in Settings page to avoid conflicts with other plugins' (less specific) css rules

= 1.4.4 =
* Improved Edit translations button in primary menu
* Fixed a bug which prevented to correclty load default styles in the CSS viewer
* Fixed a bug which prevented to scroll CSS viewer

= 1.4.5 =
* Fixed a bug which prevented native select to work correctly.

= 1.4.6 =
* Fixed the same bug of 1.4.5 for widgets.

= 1.4.7 =
* Fixed unexpected ouput on plugin activation bug.

= 1.4.8 =
* Added alt attribute to flag images to improve accessibility.

= 1.4.9 =
* Fixed a bug preventing to rediret to the current page after having changed the active language.

= 1.5.0 =
* Removed inline margin-bottom for the shortcode flag switcher

= 1.5.1 =
* Fixed a bug loading predefined stylesheets

= 1.5.2 =
* Fixed a bug which prevented to see correctly the text of the select

= 1.5.3 =
* Fixed minor bugs

= 1.5.4 =
* Fixed a couple of minor bugs
* Tested up to 6.4

= 1.5.5 =
* Fixed a bug that prevented to load styles in the CSS viewer in the plugin settings page

= 1.5.6 =
* Integrated Freemius platform
* Improved tab navigation in the settings page
* added a spinner while loading the selected style in the settings page
* refactored the code to copy to the clipboard the CSSeditor content: now it uses the Clipboard API and it preserves all formatting style

= 1.5.7 =
* Fixed a bug preventing correct updates

= 1.5.8 =
* Fixed a bug preventing the use of the plugin

= 1.5.9 =
* Added link to definitely dismiss notice about coupon code to buy Red Eyes Froggy Buttons

= 1.6.0 =
* Fixed vulnerability to Cross Site Scripting (XSS)

= 1.6.1 =
* Reviewed the whole code to fix errors found using Plugin Check tool. 

= 1.6.2 =
* Added important notice in the readme file about the nature and the installation of the plugin; I've also fixed some minor bug

= 1.6.3 =
* Fixed a css bug in shortcodes stylesheets that added an extra padding to the left side of the page when using RTL languages

= 1.6.4 =
* Fixed wrong link to settings page in plugins page

= 1.6.5 =
* Fixed blocked access to Styles tab
* Added option to choose between USA and British flags for english language
* Added option to use default Transposh flags

= 1.6.6 =
* Fixed a bug preventing the user from returning and using the English flag

= 1.6.7 =
* Added bold warning about requiring Transposh plugin in short description

= 1.6.8 =
* Added Requires plugins clause in Readme
* Improved activation process

= 1.6.9 =
* Fixed a bug that told to setup Transposh languages even if they were already set

= 1.7.0 =
* Fixed css rules in horizontal flags

= 1.7.1 =
* Tested up 6.6

= 1.7.2 =
* Tested up 6.6

= 1.7.3 =
* Fixed a typo in the readme file

== Upgrade Notice ==

= 1.0.0 =
This plugin is totally free, you don't need to upgrade to a Premium version because a Premium version just (still) doesn't exist!

