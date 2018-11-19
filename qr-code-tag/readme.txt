=== QR Code Tag ===
Contributors: dspreen
Donate link: http://www.spreendigital.de/blog/
Tags: qrcode, widget, shortcode, function, qr code, mobile, google, barcode, scan, tooltip, popup
Requires at least: 2.8
Tested up to: 4.8
Stable tag: trunk

Use QR Codes (Google API or QR Code Lib) anywhere in your blog, as a Widget, Shortcode, Tooltip or with a PHP function.

== Description ==

The QR Code Tag plugin creates QRCodes for your blog. 

Features:

* Choose your QR Code generator: Google Chart API (online connection required) or QR Code Lib (included)
* Uses cURL if `allow_url_fopen` is disabled (Google Chart API)  
* GIF, PNG or JPEG image output
* All QR Code images are cached
* Use as a Sidebar Widget
* Use the Shortcode `[qrcodetag]content[/qrcodetag]` within your posts
* Use the Tooltip mode `[qrcodetag tooltip="content"]some text[/qrcodetag]` within your posts
* Use the PHP function inside your own template
* "Best Read Mode" for optimized QR Code image size
* Works with PHP 5.3 as well
* Works on symlinked plugin folders
* Available plugin admin interface languages: English, German

== Installation ==

1. Upload the full directory into your /wp-content/plugins/ directory, or install it through the admin interface
2. Set write permissions for `/wp-content/plugins/qr-code-tag/data` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Go to the settings page and change the default values (optional)

Requirements:

* PHP5 with GD Lib
* WordPress 2.8+


== Frequently Asked Questions ==

= What is a QR Code? =
Read <a href="http://en.wikipedia.org/wiki/QR_Code">Wikipedia QR Code article</a>.


= Will there be a PHP4 version? =

No. This plugin is based on OOP sytle which is not available in PHP4.
Please upgrade your PHP installation.

= Does it work with PHP 5.3? =

Yes it does.

= Does it work with PHP 6? =

I don't know. Not tested yet.

= Which image type should I choose? =

PNG is the preferred one. If you're concerned about very old browser, use GIF instead. Or JPEG.

= Which code generator should I choose? =

If you're on a webserver that disallows online connection from within php scripts, you should switch over to the QR Code Lib.

= There is a red image instead of the QR Code image. What's wrong? =

You're `/qr-code-tag/data/` directory is _not writeable_. Please adjust
your permissions. See <a href="http://codex.wordpress.org/Changing_File_Permissions">Changing File Permissions</a>.

= How to use the tooltip mode? =

See Plugin Help (below Plugin settings in your WordPress administration area).

= There is only a blank page for large posts with your plugin! Why? =

This is a PHP / WordPress problem. See <a href="http://www.undermyhat.org/blog/2009/07/sudden-empty-blank-page-for-large-posts-with-wordpress/">Sudden empty / blank page for large posts with WordPress</a> for problem description and solutions.

= The margin with the Google API differs from that one created by the QR Code Lib. Why? =

Google Chart API creates a different margin. I can't tell you why - ask Google.

= How I can check the generated QR Code? =

You can use the <a href="http://zxing.org/w/decode.jspx">Google ZXing online service</a> 

= Where I can download a barcode reader for my mobile device? =

* <a href="http://zxing.org/">http://zxing.org/</a>
* <a href="http://www.quickmark.com.tw/">http://www.quickmark.com.tw/</a>
* <a href="http://www.i-nigma.mobi/">http://www.i-nigma.mobi/</a>
* <a href="http://reader.kaywa.com/">http://reader.kaywa.com/ </a>
* <a href="http://get.neoreader.com/">http://get.neoreader.com/</a>

= The plugin is not available in (_put a language in here_). Why? =

Because no one translated it yet. How about you? See Plugin Help for translation hints!

= There source code format does not follow WordPress standards. Why? =

This plugin obeys the <a href="http://framework.zend.com/manual/en/coding-standard.html">Zend Framework Coding Standard for PHP</a> 
because the <a href="http://codex.wordpress.org/WordPress_Coding_Standards">WordPress Coding Standard</a> does not fit OOP, IMHO. Sorry Matt.

== Screenshots ==

1. Tooltip mode
2. Widget options
3. Admin interface

== Changelog ==

= 1.0 =
* Initial Release

== Demo ==

See <a href="http://www.spreendigital.de/blog/2009/09/18/essential-android-applications/">this blog entry</a> for a tooltip mode demo.
Move your mouse over the application links.

== Acknowledgements ==

This Wordpress QR Code Tag Plugin is partly based on <a href="http://wordpress.org/extend/plugins/super-cool-qrcode/" target="_blank">Super Cool QRCode Widget</a> by <a href="http://www.incerteza.org/blog/">Matias S.</a>.

It uses:

* <a href="http://code.google.com/intl/de/apis/chart/">Google Chart API</a> for online code creation
* Y. Swetakes <a href="http://www.swetake.com/qr/index-e.html">QR Code Library</a> for code offline creation
* J. Zaefferers <a href="http://docs.jquery.com/Plugins/Tooltip">jQuery Tooltip Plugin</a> 


