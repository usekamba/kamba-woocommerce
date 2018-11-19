<?php
/**             
 * @package     Qrcode
 * @copyright   Copyright (c) 2009 Dennis D. Spreen (http://www.spreendigital.de/blog)
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Dennis D. Spreen <dennis@spreendigital.de>
 * @version     $Id: QrcodeLib.php 70 2009-09-18 16:14:16Z dennis.spreen $  
 */ 

require_once(dirname(__FILE__).'/Qrcode.php');

class QrcodeLib extends Qrcode
{
    /**
     * Create the QR Code
     * 
     * @param  mixed    $content    QR Code content
     * @param  string   $file       image file name
     * @param  integer  $size       size of the image
     * @param  string   $enc        encoding of the content (not used)
     * @param  string   $ecc        error correction code type
     * @param  integer  $margin     QR Code image
     * @param  integer  $version    QR Code version
     */
    public function create($content, $file, $size, $enc, $ecc, $margin, $version)
    {
        // prepare library variables
        $qrcode_data_string = $content;
        $qrcode_error_correct = $ecc;
        $qrcode_module_size = $size;
        $qrcode_version = $version;
        
        // include library and execute
        require(dirname(__FILE__).'./../qr_img/qr_img.php');
       
        // redefine whitespace margin and save file 
        $base_image = $this->cropImage($base_image, $size, $margin);
        $this->saveImage($base_image, $file);
        
        // remove lib image 
        imagedestroy($base_image);
    }
}