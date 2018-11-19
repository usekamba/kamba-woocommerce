<?php
/*
Plugin Name:    QR Code Tag
Plugin URI:     http://www.spreendigital.de/blog/wordpress/qr-code-tag
Description:    QR Code tag generator with widget, shortcode and tooltip support
Version:        1.0
Author:         Dennis D. Spreen
Author URI:     http://blog.spreendigital.de/2009/09/19/qr-code-tag-wordpress-plugin-v1-0/

Copyright 2009  Dennis D. Spreen <dennis@spreendigital.de>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
 * Plugin Coding Standard:
 *  This plugin obeys the Zend Framework Coding Standard for PHP 
 *  (http://framework.zend.com/manual/en/coding-standard.html)
 *  because the WordPress Coding Standard does not fit OOP programming 
 *  (http://codex.wordpress.org/WordPress_Coding_Standards), sorry Matt.
 * 
 * $Id: qr-code-tag.php 76 2009-09-19 07:53:22Z dennis.spreen $
 */
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);
*/
// create global object for template use, etc.
global $qrcodetag;

// load class file
require_once (dirname(__FILE__).'/lib/qrct/QrctWp.php');

// create & initialize QR Code Tag plugin for WordPress
$qrcodetag = new QrctWp(__FILE__);
