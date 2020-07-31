=== Print Barcodes on Label Sheets for Wordpress & WooCommerce ===
Contributors: UkrSolution <https://profiles.wordpress.org/ukrsolution>
Tags: WooCommerce, barcode, SKU, EAN, UPC, Code128, Code39, QR Code, QRcode, inventory, bar code, barcode generator, print barcodes.
Requires at least: 4.0.1 
Tested up to: 5.4
Stable tag: 2.14.4
Requires PHP: 5.6 or later
License: GPLv2 or later 
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

### Create & Print barcodes from SKU, EAN, UPC & other codes. Best tool to make inventory of your stock

https://www.youtube.com/watch?v=r8SDZb_Uk1w

**✔ Creates barcodes from product SKU, UPC, EAN and other codes**
**✔ Allows to place any product data & texts on barcode labels**
**✔ Compatible with all types of printers & label sheets**

When your store is growing and you have a lot of products it could be pretty difficult to manage them effectively. If you'd like to improve your workflow by using barcodes you can find **Barcode Generator for WordPress & Woocommerce** very useful. With "Barcode Generator" you can create barcodes manually or generate barcodes for selected products or cetegories.

== Barcode Generator does: ==

* Supports WooCommerce Product Variations.
* Allows to create barcodes: manually, for selected products or for the whole WooCommerce categories.
* Along with code and product name allows to put on barcodes any additional info (e.g. Price, static text).
* By default Letter, A4, P4 & roll thermal printers are supported. Have another printer ? See "Advanced Details".
But if your printer supports some other paper type, it is not a problem at all as our plugin is extendable and allows you to work with any kind of paper. The only thing you need to do is to find out the sizes and create new "Paper type" by pressing "Add new" button.
* Built-in set of common-used label sheets. Not found the one? - Plugin allows to create a custom one easily.
* Supports the most popular types of barcodes: UPC, EAN, QRCode, Data Matrix, CODE128 and CODE39.
* Allows to create any amount of copies of the same barcode in manual mode.
* All barcodes can be read by barcode scanner or mobile app for iOS or Android
 **Customized & personalized labels.**
Plugin allows to make labels more personalized to fit your store brand. 
It allows to add logo of your store (or any other images) and add any static texts, e.g. name of your store or company.
Such customization became possible after templates system implementation into plugin. By default plugin contains 3 templates but you can create any amount of them:
 ✔ "Default template" - supports all paper types and label sheets.
 ✔ "Optimized for 2D codes" - example of label optimized for QR and DataMatrix barcodes
 ✔ "Label with images & texts" - example of label with images and static texts.

Please, check out [this page](https://www.ukrsolution.com/Joomla/A4-BarCode-Generator-For-Wordpress#details) for more information.


== How to: ==

### Create Barcodes Manually:

* Click at "Barcode Generator" -> "Create Manually" menu - popup will appear.
* Fill in "Code" field. This field value will be used for generating barcode. This is usually some unique value like SKU or UPC.
* You could also fill optional fields "Name" and "Text" that will be printed on barcode label.
* Select "Type" of barcode - read more about barcode types [here](https://www.ukrsolution.com/Joomla/A4-BarCode-Generator-For-Wordpress#details).
* Click "Add to Page" button and new label will appear at page.
* You can add any amount of barcodes/pages before printing them.
* Select label format with "Labels format" dropdown and preview how it will look like.

### Create Barcodes for Selected Products:

* Go to WooCommerce Products page (or any other page with products list).
* Select/check products for barcode creation.
* Open menu "Barcode Generator" -> "Import Selected Items". Popup will open with similar settings like when you created barcodes manually.
* Match fields "Code", "Name", "Text1/2" with proper columns, select "Type" of barcode and click "Import".
* You can repeat above actions many times and then print all added barcodes later by clicking "Print" button.
* Select label format with "Labels format" dropdown and preview how it will look like.


### Create Barcodes for Selected WooCommerce Categories:

* Open menu "Barcode Generator" -> "Import Categories".
* Select categories you need.
* Match "Code", "Name" and "Text" fields, select "Type" of barcode.
* Click "Import".
* Select label format with "Labels format" dropdown and preview how it will look like.

### How to Add new label sheet:

* Add at least one barcode and page will appear at the right side of you screen.
* Click "+ Add new" button below preview page.
* In popup "New Custom Format", please enter sizes of labels and margins - you can find all these sizes on paper box.
* Click "Add Format".


**Please note**: This is Demo version of Barcode Generator. It's free to use and you can check all features of this product except printing.
For printing you'll need to buy a license, download full version and install it.
[Get Full Version](https://www.ukrsolution.com/Joomla/A4-BarCode-Generator-For-Wordpress)  

More information about Barcode plugin check here:

* [Barcode Generator](https://www.ukrsolution.com/Joomla/A4-BarCode-Generator-For-Wordpress)
* [FAQ](https://www.ukrsolution.com/Joomla/A4-BarCode-Generator-For-Wordpress)
* [User Manual](https://www.ukrsolution.com/Joomla/A4-BarCode-Generator-For-Wordpress)

== Installation ==

### Installing Demo version
* Go to WordPress admin -> Plugins menu.
* Add New-> search for plugin by entering plugin name “Barcode Generator” -> choose plugin  “Barcode Generator” -> install now.
* Activate the plugin.

### Installing Full version
* Download the zipped plugin file to your local machine by this link: [BarCode Generator](https://www.ukrsolution.com/Joomla/A4-BarCode-Generator-For-Wordpress).
* Go to WordPress Admin -> Plugins menu.
* Add New->Upload->browse the zipped plugin.
* Upload and Activate the plugin.

== Frequently Asked Questions == 

= I have label sheets and I am not sure if this plugin can support it. =

By default barcode generator contains the most popular A4 label sheets that allows to print 12, 14, 16, 21, 24, 40, 44, 56, 65, 120, 189, 230 or 324 barcodes per page.
If you have label sheet with different amount of labels or with different sizes - you can easily create your own custom format - just specify sizes of labels, margins, label gaps and new format will be ready for work.

= How to put attributes or custom fields on barcode ? =

By default plugin allows to display on barcode 2 attributes/custom fields. For this you need to match "Text1" and "Text2" fields on "Import" popup with specific attribute or custom field. To use 3 or more attributes/custom fields on barcode you will have to create new barcode template and by using shortcodes like **[attr=gcolor]** or **[cf=_regular_price]** specify where they should be placed. The value for shortcode might look different, like "tshirt_size", "upc_code" or "my_custom_field". Usually name of custom field or attribute is specified in plugin/place where it was created.

= How do I know that my printer supports margin-free printing ? =

Before printing it's very important to get to know your printer a little bit better, especially we need to know if printer can print without page margins.

The right way to do it is to read technical documentation about your printer (or find it in google) but if you lost documentation or can't find in google there is another way. 

Print [this PDF page](https://www.ukrsolution.com/pdf/Barcodes/Check-Printer-Margins.pdf) and compare if it looks the same as on PDF. If page border doesn't look the same - your printer might not suport border-free printing or you didn't check this option in print settings popup.

= My printer doesn't support margin-free printing, what's next ? =

It's ok, actually most of printers can't do it. You just need to use label sheets with page margins. 

[Here is example](https://www.ukrsolution.com/pdf/Barcodes/label-sheet-40-margins.pdf) of label sheet (40 labels per page) with page margins.

= What types of barcodes do you support ? =

Our **Barcode Generator** supports most popular types of codes: **CODE128**, **UPC**, **EAN**, **EAN8**, **CODE39**, **EAN13**, **QRCode** and **DataMatrix**.

= Can I save/download barcodes on my MAC or PC? =

There are 2 ways: 
1. You can save each code as PNG image.
2. You can save all pages as PDF document, so you will be able to print them later.

= How much barcodes I can  put on page ? =

As much as you need :)
Our barcode generator contains buil-in set of the most popular A4 label sheets which allows to add 12, 14, 16, 21, 24, 56 or 65 barcodes per page.
And you can create any amount of your own label sheet formats.

= How can I generate barcodes for all products in particular category or categories ? =

Please follow these steps:
1. Click at "Barcode Generator" -> "Import categories" menu.
2. Check categories for which you'd like to create barcodes, specify barcode type and fields you'd like to use for barcode generation.
3. Click "Import" button.
 

= Can I add  product name on barcode ? =

Sure, one barcode can contain and code and product name.

= I have found a bug, how can it be fixed ? =

You need to send email to us and provide us with details:
How to reproduce bug
Screenshots of bug
Best way to describe the problem is to create video (you can use software like Jing)

= Can I add a prefix to a barcode code ? = 

Sure, this option available on the settings page.

= Can I combine values for generating code ?= 

Yes, our plugin supports this option which you can find on the "custom template" page (the [code] value tab).

== Screenshots ==

1. Create barcodes manually.
2. Create QR code with Import Selected Products option.
3. Create barcodes with with custom teplate.
4. Create barcodes from WooCommerce Categories.
5. The "Custom template" page.
6. Example of Roll thermal printer.
7. Example of a4 printer.

== Changelog =

= 2.14.4 - 14st, Jul 2020 =
* Hide currency symbol if a price field is empty.
* Added support PHP 7.4

= 2.14.2 - 22th, May 2020 =
* Fixed issue with deleting barcode image on the preview page.

= 2.14.1 - 20th, May 2020 = 
* Added message "This browser is not fully supported, please use "Google Chrome" browser to print bar codes. 

= 2.14.0 - 18th, May 2020 = 
* Added possibility to add more then one value from custom fields via comma separator.

= 2.13.0 - 27th, April 2020 =
* Added support of colors for custom label templates.
* UI improvements.

= 2.12.3 - 21th, February 2020 =
* Improvements with default uol settings.
* Improvements for compatibility with the custom website theme.
* Fixed issue with custom fields.

= 2.12.2 - 11th, February 2020 =
* Corrected issue with logo icon.

= 2.12.1 - 10th, February 2020 = 
* Corrected issue with label sheet size configuration.

= 2.12.0 - 6th, February 2020 = 
* Added option to specify any prefix for generating barcode.
* Added ability match code value with any Woocommerce custom field (s) for single and variables products.
* Added the "Custom Fields & Attributes" template.
* Added ability to set on the label the "Currency position" which set at WooCommerce general settings.
* Added new shortcodes to the "Custom template" page.
* Added support of "mm" and "inch" values instead of "px" for the "Base padding" field on the custom template page.
* Removed an option for deleting the "Roll Thermal Printer" type from the Paper type list.
* Corrected issue with PHP version 7.4.

= 2.11.6 - 11th, December 2019 = 
* Fixed issue with dialog windows.

= 2.11.5.3 - 25nd, November 2019 = 
* Added support to display price with tax; 
* UI improvements;
* The custom field doesn't work for product attributes - fixed.

= 2.11.5.2 - 18nd, November 2019 =

* Improvements with layout.

= 2.11.5.1 - 12nd, November 2019 = 

* Added ability for output all specified attributes with a custom template;
* Added ability for generating barcodes for products with multiple variations.
* Added ability for generating barcodes for Private Products.

= 2.11.4 - 18th, October 2019 = 
* Fixed issue with updates.

= 2.11.3.2 -8th, October 2019 = 
* Added support of inches.
* Added a switch-mode for printing with Receipt and Roll Printer.
* Fixed cache issue.

= 2.11.3 - 1st, September 2019 = 
* Added possibility creates an empty label.
* Added padding setting.
* Added supports of decimals (inches).
* Fixed issue with move gaps on the top and bottom on the page.
* Fixed issue with displaying zeros. 

= 2.11.2 - 19th, August 2019 = 
* Add support parent product image.
 
= 2.11.1.1 - August 1st, 2019 = 
* Print link wasn't working on some WordPress configurations - corrected.

= 2.11.1 - July 4th, 2019 = 
* Added improvements for a custom template.

= 2.11.0 - July 1st, 2019 = 
* Added possibility add a product image on a barcode label.
* Added custom template with supporting product image.
* UI improvements.
* Roll printer improvements.

= 2.10.0 - June 13th, 2019 =
* Fixed attributes value issue.
* New template added.

= 2.9.0.1 - June 5th, 2019 = 
* Fixed attributes issue.

= 2.9.0 - May 17th, 2019 =
* UI improvements.

= 2.8.0 - April 26th, 2019 = 
* Added support of Custom Templates for Labels.
* Corrected issue with empty folder.
* UI improvements.
* Fixed issue with cache.

= 2.7.0.3 - April 25th, 2019 = 
* Corrected - ajax request with sub-folder.
* Improvements for variations that have stock quantity 0.

= 2.7.0.2 - April 16th, 2019 =
* Corrected Preview Page disappearance.
* Barcode labels sorting stopped working – fixed.
* Major improvements.

= 2.7.0.1 - April 3rd, 2019 =
* JavaScript files now loaded after click at Barcode Generator menu.
* Import Categories popup improvements.
* СODE128 throws error when created out of number - corrected.
* UPC-A validation - corrected.

= 2.7.0 - March 26th, 2019 =
* Added WooCommerce "Attributes" support.
* Added support of WooCommerce "Stock quantity". If enabled amount of created barcodes will be equal as the amount specified in "Stock quantity" field.
* Added other languages support. Now you can add translations for your language.
* Quality of barcodes improved by using SVG instead of images.

= 2.6.0 - March 15th, 2019 =
* Added support of variations to "Selected Items" option.
* Product Link and Admin Product Link added. When creating barcodes you can use Product Links - useful for QRCodes.
* Corrections for Roll Label Printers

= 2.5.0.1 - March 6th, 2019 =
* CODE39 wasn't detected by some barcode scanners - corrected.
* Printing of Barcodes didn't work in Safari - corrected.

= 2.5.0 - March 1st, 2019 =
* Other plugins caused printing issues. Printing proccess has been rewritten to prevent these issues.

= 2.4.0 - January 31st, 2019 =
* Design enhancements

= 2.3.0 - January 28th, 2019 =
* "DataMatrix" barcode type is added.
* "Letter" paper type added into pre-installed list.

= 2.2.3.1 - January 24th, 2019 =
* Corrected issue that some EAN13 codes were detected as invalid.

= 2.2.3 - January 15th, 2019 =
* Text on labels were cut off - corrected.

= 2.2.2.1 - December 21st, 2018 =
* UPC-A validation issue corrected.
* Added error message for websites that use old PHP version (less than PHP 5.6).

= 2.2.2 - December 6th, 2018 =
* Add "X Copies" feature for "Create Manually" popup to create any amount of the same barcode.
* Custom Paper Formats added. You can create any page size you need including support of roll printers.
* Custom Fields support added. Now you can use any custom field to create barcode.
* Import Selected Items popup. Improved error log.
* EAN13 format - improved validations & errors handling.
* Check for the new version is optimized. Now you won't miss new releases.
* Responsive improvements for small devices.
* Correction for Euro prices in "Import Categories" popup.
* When printing user got empty page - fixed.

= 2.2.1 - November 6th, 2018 =
* Speed optimization improved.
* Feature "Static text" added.
* Added option to show/hide "Code" on barcode label for "Import selected items" and "Import Categories" popups.

= 2.2.0.1 - November 2nd, 2018 =
* Price currency hasn't been printed in some cases - corrected.

= 2.2.0 - October 31st, 2018 =
* Error messages improved.
* Сategory hierarchy is displayed.
* Bug Reporter improved.
* Preloader added while categories list is loading, and while new format is being created.
* New version checker is improved.
* Responsive fixes made.
* Import categories fixed.

= 2.1.0.2 - October 24th, 2018 =
* JavaScript errors fixed.
* CSS improvements made.

= 2.1.0.1 - October 22th, 2018 =
* Added hot fixes for php notices.

= 2.1.0 - October 20th, 2018 =
* Access to Barcode Generator added to "Shop Manager" user role. This is one of built-in roles of WooCommerce.
* Added "Bug Reporter" that helps to detect errors.
* "Add to Page" button is disabled till Barcode is generated - prevents adding empty Barcodes to page.
* Preloader in "Import Categories" popup never stops when empty categories selected - fixed.
* Auto-check for a newer version fixed.

= 2.0.0.1 - October 18th, 2018 =
* Fix for servers with allow_url_fopen settings disabled.
* Added fix for "Import Categories" popup when there are many categories.

= 2.0.0 - October 17th, 2018 =
* Created from scratch the core of plugin
* Support of WooCommerce Product Variations added
* "Text" field is added in "Create Manually" popup

= 0.4.18 - August 13th, 2018 =
* Small corrections to layout

= 0.4.16 - August 8th, 2018 =
* Added "Import Categories" feature. Allows to generate barcodes for products in selected categories.
* Added support of 120 barcodes per A4 page
* Redesign

= 0.3.3 - Marth 23th, 2017 =
* Show/Hide barcode text

= 0.2.9 - January 13th, 2016 =
* Added Additional field in Import

= 0.2.8 - November 9th, 2016 =
* Added QRCode

= 0.2.7 - October 31th, 2016 =
* Added new features

= 0.2.6 - September 22nd, 2016 =
* Added new features

= 0.1.6 - September 15th, 2016 =
* Added new features

= 0.1.2 =
* Fist version

== Upgrade Notice ==
* Added new features
