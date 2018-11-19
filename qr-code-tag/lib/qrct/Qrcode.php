<?php
/**             
 * @package     Qrcode
 * @copyright   Copyright (c) 2009 Dennis D. Spreen (http://www.spreendigital.de/blog)
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Dennis D. Spreen <dennis@spreendigital.de>
 * @version     $Id: Qrcode.php 69 2009-09-18 16:11:08Z dennis.spreen $  
 */ 

class Qrcode
{
    public $cacheDir;
    
    private $timeStart;    // used for creation time measure
    
    const CREATIONLOG    = 'creation.log';   // name of the creation timing file
    const CACHEERRORFILE = 'cacheerror.gif'; // name of the cache error file (write permissions missing)
    
    /**
     * Generate the QR Code object and initializes the Cache directory
     */
    public function Qrcode()
    {
        // initialize cache directory
        $this->cacheDir = dirname(__FILE__).'/../../data/';
    }    
    
    /**
     * Check if file extension is as expected
     * 
     * @param  string    $filename    the filename including extension
     * @param  string    $extension   expected extension
     * @return bool
     */
    public function isExtension($filename, $extension)
    { 
        // convert to lower string and extract file extension
        return (strtolower(pathinfo($filename, PATHINFO_EXTENSION)) == strtolower($extension));
    }
    
    /**
     * Save image type based on file extension
     * 
     * @param  resource      $img    GD image
     * @param  string        $file   complete file path
     */
    public function saveImage($img, $file) 
    {
        if ($this->isExtension($file,'png')) {
            imagepng($img, $file);
        } elseif ($this->isExtension($file,'gif')) {
            imagegif($img, $file);
        } else {
            imagejpeg($img, $file);
        }
    }
    
    /**
     * Stub for QR Code creation
     * 
     * @param  mixed    $content    QR Code content
     * @param  string   $file       image file name
     * @param  integer  $size       size of the image
     * @param  string   $enc        encoding of the content
     * @param  string   $ecc        error correction code type
     * @param  integer  $margin     QR Code image
     * @param  integer  $version    QR Code version
     */
    public function create($content, $file, $size, $enc, $ecc, $margin, $version) 
    {
        // not declared as abstract because of cache usage, well this could be static.
    }

    /**
     * Start execution timer
     */
    public function startTimer()
    {
        $this->timeStart = microtime(true);
    }
    
    
    /**
     * Stop execution Timer and store execution time
     */
    public function stopTimer()
    {
        // time = end - start
        $time = microtime(true) - $this->timeStart;
        
        // if cache directory is writeable store execution time
        if (is_writable($this->cacheDir)) {
            $file = $this->cacheDir.self::CREATIONLOG;
            $fh = fopen($file, 'a') or die("can't open file");
            fwrite($fh, '<?php $creationTimes[]='.$time."; ?>\n");
            fclose($fh);
        }
    }
    
    
    /**
     * Remove whitespace from image and resize with specified margin
     *  
     * @param  resource   $image       GD image
     * @param  integer    $size        size of the resulting image
     * @param  integer    $margin      including this whitespace margin
     * @return resource    
     */
    public function cropImage($image, $size, $margin) 
    {
        // get image dimensions
        $width = imagesx($image);
        $height = imagesy($image);

        $refColor = imagecolorat($image, 0,0); // get top left pixel as reference
        
        // find top line
        $topY = -1;
        $sameColor = TRUE;
        while (($sameColor) && ($topY < $height-1)) {
            $topY++;
            $x = 0;
            while (($sameColor) && ($x < $width)) {
                $sameColor = (imagecolorat($image, $x, $topY) == $refColor);
                $x++;
            }
        }
        if ($sameColor) {
            $topY = 0;
        }

        // find bottom line
        $bottomY = $height;
        $sameColor = TRUE;
        while (($sameColor) && ($bottomY >= 0)) {
            $x = 0;
            $bottomY--;
            while (($sameColor) && ($x<$width)) {
                $sameColor = (imagecolorat($image, $x, $bottomY) == $refColor);
                $x++;
            }
        }
        if ($sameColor) {
            $bottomY = $height-1;
        }
        
        // find left border
        $leftX = -1;
        $sameColor = TRUE;
        while (($sameColor) && ($leftX < $width-1)) {
            $leftX++;
            $y = $topY;
            while (($sameColor) && ($y <= $bottomY)) {
                $sameColor = (imagecolorat($image, $leftX, $y) == $refColor);
                $y++;
            }
        }
        if ($sameColor) {
            $leftX = 0;
        }
        
        // find right border
        $rightX = $width;
        $sameColor = TRUE;
        while (($sameColor) && ($rightX >= 0)) {
            $rightX--;
            $y = $topY;
            while (($sameColor) && ($y <= $bottomY)) {
                $sameColor = (imagecolorat($image, $rightX, $y) == $refColor);
                $y++;
            }
        }
        if ($sameColor) {
            $rightX = $width-1;
        }

        // calculate dimensions
        $cropWidth = $rightX-$leftX+1;
        $cropHeight = $bottomY-$topY+1;
        $croppedWidth = $cropWidth + $margin*2;
        $croppedHeight = $cropHeight + $margin*2;
        
        // create cropped image and fill it with white
        $cropImage = ImageCreate($croppedWidth, $croppedHeight);
        imagefill($cropImage, 0, 0, imagecolorallocate($cropImage, 255, 255, 255));
        
        // crop it with margin
        imagecopy($cropImage, $image, $margin, $margin, $leftX, $topY, $cropWidth, $cropHeight);

        // if zoom mode is specified, change size
        if ($size<10) {
            $size = $croppedWidth*($size+1); 
        }
        
        // resize
        $outputImage = imagecreate($size, $size);
        imagecopyresized($outputImage, $cropImage, 0, 0, 0, 0, $size, $size, $croppedWidth, $croppedHeight);

        // destroy temporary cropImage
        imagedestroy($cropImage);
        
        return $outputImage;
    }

    /**
     * Get a QR Code file name (either create one or load from cache)
     * 
     * @param  mixed    $content    QR Code content
     * @param  string   $fileExt    image file extension    
     * @param  integer  $size       (optional) size of the image (defaults to 125)
     * @param  string   $enc        (optional) encoding of the content (defaults to UTF-8)
     * @param  string   $ecc        (optional) error correction code type (defaults to L)
     * @param  integer  $margin     (optional) QR Code image (defaults to 4)
     * @param  integer  $version    (optional) QR Code version (defaults to 0=auto)
     * @return string
     */
    public function get($content, $fileExt, $size = '125', $enc = 'UTF-8', $ecc = 'L', $margin = '4', $version = 0) 
    {
        // create hash parameters        
        $params = array('d'=>$content, 's'=>$size, 'e'=>$enc, 'c'=>$ecc, 'm'=>$margin, 'v'=>$version);
        
        // create cacheId file name based on hash parameters
        $cacheId = 'qrct-'.md5(serialize($params)).'.'.$fileExt;

        // prepare cache directory
        $cacheFilePath = $this->cacheDir.$cacheId;
        $cacheWriteable = is_writable($this->cacheDir);
        
        if (file_exists($cacheFilePath)) { // if file alreas exists, return filename
            return $cacheId;
        } elseif (!is_writable($this->cacheDir)) { // if cache directory isn't writeable return error file
            return self::CACHEERRORFILE;
        } else { // create new code and return filename
            $this->startTimer();
            $this->create($content, $cacheFilePath, $size, $enc, $ecc, $margin, $version);
            $this->stopTimer();
            return $cacheId;
        }
    }
    
    /**
     * Clear QR Code Cache
     */
    public function clearCache()
    {
        // if cache is writeable then scan directory for files
        if (is_writable($this->cacheDir)) {
            $files = scandir($this->cacheDir);
            foreach($files as $file) {
                if ( (($this->isExtension($file,'gif')) ||
                      ($this->isExtension($file,'png')) ||
                      ($this->isExtension($file,'jpg'))) && ($file != self::CACHEERRORFILE)) {
                          // delete if it's an image but not the cache Error File
                         unlink($this->cacheDir.$file);
                }
            }
            // if creation log exists, delete this also
            $creationLog = $this->cacheDir.self::CREATIONLOG;
            if (file_exists($creationLog)) {
                unlink ($creationLog);
            }
        }
    }
    
    /**
     * Return current QR Code Cache state
     * 
     * @param integer    &$cacheFiles        number of files in the cache
     * @param integer    &$cacheSize         cacheSize in Bytes 
     * @param float      &$avgCreationTime   average QR Code creation time
     */
    public function cacheState(&$cacheFiles, &$cacheSize, &$avgCreationTime)
    {
        // reset values
        $cacheFiles = 0;
        $cacheSize = 0;
        $avgCreationTime = 0;
        
        // scan cache directory and count files and size
        $files = scandir($this->cacheDir);
        foreach($files as $file) {
                if ( (($this->isExtension($file,'gif')) ||
                      ($this->isExtension($file,'png')) ||
                      ($this->isExtension($file,'jpg'))) && ($file != self::CACHEERRORFILE)) {
                         $cacheFiles++;
                         $cacheSize += filesize($this->cacheDir.$file);
                }
        }
        
        // read creation log
        $creationLog = $this->cacheDir.self::CREATIONLOG;
        
        // only if exists and calculate average creation time
        if (file_exists($creationLog)) {
            $creationTimes = array();
            include($creationLog);
            foreach($creationTimes as $creationTime) {
                $avgCreationTime += $creationTime;
            }
            if (count($creationTimes)>0) {            
                $avgCreationTime = $avgCreationTime / count($creationTimes);
            }
        }
    }
    
}