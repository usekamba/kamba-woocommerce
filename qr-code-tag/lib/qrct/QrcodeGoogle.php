<?php
/**             
 * @package     Qrcode
 * @copyright   Copyright (c) 2009 Dennis D. Spreen (http://www.spreendigital.de/blog)
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Dennis D. Spreen <dennis@spreendigital.de>
 * @version     $Id: QrcodeGoogle.php 70 2009-09-18 16:14:16Z dennis.spreen $  
 */ 

require_once(dirname(__FILE__).'/Qrcode.php');

class QrcodeGoogle extends Qrcode
{
    /**
     * save image with GD conversion function, needs allow_url_fopen
     *  
     * @param  string    $url    URL image path
     * @param  string    $file   local image filename
     */
    public function saveImageGd($url, $file)
    {
        // fetch image from URL and save it
        $img = imagecreatefrompng($url);
        $this->saveImage($img, $file);
        imagedestroy($img);
    }
    
    /**
     * alternative image saving using cURL if allow_url_fopen is disabled
     *
     * @param  string    $url    URL image path
     * @param  string    $file   local image filename
     */
    public function saveImageCurl($url, $file)
    {
        // initialize cURL settings
        $ch = curl_init ($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        // fetch raw data  
        $rawdata = curl_exec($ch);
        curl_close($ch);
        
        // convert it to a GD image and save
        $img = imagecreatefromstring($rawdata);
        $this->saveImage($img, $file);
        imagedestroy($img);
    }    
    
    /**
     * Grab an Image from the given URL (with GD function or cURL)
     *  
     * @param  string    $url    URL image path
     * @param  string    $file   local image filename
     */
    public function grabImage($url, $file)
    {
        // get allow_url_fopen setting
        $allow_url_fopen = (ini_get('allow_url_fopen') == 1);
 
        if ($allow_url_fopen) { // use gd function if allowed
            return $this->saveImageGd($url, $file);
        } else { // use cURL as alternative
            return $this->saveImageCurl($url, $file);
        }
     }

    /**
     * Create the QR Code
     * 
     * @param  mixed    $content    QR Code content
     * @param  string   $file       image file name
     * @param  integer  $size       size of the image
     * @param  string   $enc        encoding of the content
     * @param  string   $ecc        error correction code type
     * @param  integer  $margin     QR Code image
     * @param  integer  $version    QR Code version (not used)
     */
    public function create($content, $file, $size, $enc, $ecc, $margin, $version)
     {
         // prepare Google Chart URL
         $url = 'http://chart.apis.google.com/chart?chs=' . $size . 'x' . $size . '&cht=qr&chl=' . urlencode($content) . '&choe=' . $enc . '&chld=' . $ecc . '|' . $margin;
        
         // grab image from URL
         $this->grabImage($url, $file);
    }
    
}