/*
 * QR Code Tag Wordpress Plugin Javascript jQuery Handler v1.0
 * http://spreendigital.de/blog/wordpress
 *
 * Copyright (c) 2009 Dennis D. Spreen
 *
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 */;
jQuery(document).ready(function($) {
	$("span.qrcttooltip").tooltip({
	   	bodyHandler: function() {
			return $("<img/>").attr("src", this.tooltipText); 
	   	},
		track: true, showURL: false, delay: 1, id: "qrcttooltip"
	})
  });