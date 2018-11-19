<?php
//$Id: QrctWp-admin.inc.php 72 2009-09-18 16:19:35Z dennis.spreen $

    if (!is_admin()) {
        die('hacker, eh?');
    } 
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div>
    <h2><?php echo __('QR Code Tag Settings', $this->pluginDomain); ?></h2>
       
    <form method="post" name="options" target="_self">
       <h3><?php echo __('Code Generation', $this->pluginDomain); ?></h3>
    <table class="form-table">
    <tr valign="top">
    <th scope="row"><label for="qrct_generator"><?php echo __('Generator', $this->pluginDomain); ?></label></th>
    <td>
        <select name="qrct_generator" style="width:150px;">
                            <option value="google" <?php if ($options['global']['generator'] == 'google') echo 'selected="selected"'; ?> /><?php echo __('Google Chart API', $this->pluginDomain); ?></option>
                            <option value="lib" <?php if ($options['global']['generator'] == 'lib') echo 'selected="selected"'; ?> /><?php echo __('QR Code Lib', $this->pluginDomain); ?></option>
        </select>
           </td>
    </tr>
    <tr valign="top">
    <th scope="row"><label for="qrct_imagetype"><?php echo __('Image Type', $this->pluginDomain); ?></label></th>
    <td>
        <select name="qrct_imagetype" style="width:60px;">
                            <option value="gif" <?php if ($options['global']['ext'] == 'gif' ) echo 'selected="selected"'; ?> /><?php echo __('GIF', $this->pluginDomain); ?></option>
                            <option value="png" <?php if ($options['global']['ext'] == 'png' ) echo 'selected="selected"'; ?> /><?php echo __('PNG', $this->pluginDomain); ?></option>
                            <option value="jpg" <?php if ($options['global']['ext'] == 'jpg' ) echo 'selected="selected"'; ?> /><?php echo __('JPG', $this->pluginDomain); ?></option>
        </select>
           </td>
    </tr>
    </table>
       
       <h3><?php echo __('Default Options', $this->pluginDomain); ?></h3>
    <p><?php echo __('This will be the default values of the QR Code Tag Shortcode, Tooltip and Widget.', $this->pluginDomain); ?></p>
    <table width="100%" cellspacing="0" id="inactive-plugins-table" class="widefat">
      <thead><tr>
        <th width="100"><?php echo __('Field', $this->pluginDomain); ?></th>
        <th width="100"><?php echo __('Shortcode', $this->pluginDomain); ?></th>
        <th width="100"><?php echo __('Tooltip', $this->pluginDomain); ?></th>
        <th width="100"><?php echo __('Widget', $this->pluginDomain); ?></th>
        <th><?php echo __('Description', $this->pluginDomain); ?></th>
      </tr></thead>
      <tr>
        <td width="100"><?php echo __('Size', $this->pluginDomain); ?></td>
        <td width="100"><input type="text" name="qrct_sc_size" style="width:100%;" value="<?php echo $options['shortcode']['size']; ?>" /></td>
        <td width="100"><input type="text" name="qrct_tt_size" style="width:100%;" value="<?php echo $options['tooltip']['size']; ?>" /></td>
        <td width="100"><input type="text" name="qrct_wg_size" style="width:100%;" value="<?php echo $options['widget']['size']; ?>" /></td>
        <td><?php echo __('The size of the generated QRCode image (in pixels). it\'s always a square, so you only need to set one side - to enable the "Best Read Mode" <i>(QR Code Lib only)</i> specify a value lower than 10 (read help below)', $this->pluginDomain); ?></td>
      </tr>
      <tr>
        <td width="100"><?php echo __('Enc', $this->pluginDomain); ?></td>
        <td width="100"><select name="qrct_sc_enc" style="width:100%;">
                            <option value="UTF-8" <?php if ($options['shortcode']['enc'] == 'UTF-8') echo 'selected="selected"'; ?> /><?php echo __('UTF-8', $this->pluginDomain); ?></option>
                            <option value="Shift_JIS" <?php if ($options['shortcode']['enc'] == 'Shift_JIS') echo 'selected="selected"'; ?> /><?php echo __('Shift_JIS', $this->pluginDomain); ?></option>
                            <option value="ISO-8859-1" <?php if ($options['shortcode']['enc'] == 'ISO-8859-1') echo 'selected="selected"'; ?> /><?php echo __('ISO-8859-1', $this->pluginDomain); ?></option>
        </select></td>
        <td width="100"><select name="qrct_tt_enc" style="width:100%;">
                            <option value="UTF-8" <?php if ($options['tooltip']['enc'] == 'UTF-8') echo 'selected="selected"'; ?> /><?php echo __('UTF-8', $this->pluginDomain); ?></option>
                            <option value="Shift_JIS" <?php if ($options['tooltip']['enc'] == 'Shift_JIS') echo 'selected="selected"'; ?> /><?php echo __('Shift_JIS', $this->pluginDomain); ?></option>
                            <option value="ISO-8859-1" <?php if ($options['tooltip']['enc'] == 'ISO-8859-1') echo 'selected="selected"'; ?> /><?php echo __('ISO-8859-1', $this->pluginDomain); ?></option>
        </select></td>
        <td width="100"><select name="qrct_wg_enc" style="width:100%;">
                            <option value="UTF-8" <?php if ($options['widget']['enc'] == 'UTF-8') echo 'selected="selected"'; ?> /><?php echo __('UTF-8', $this->pluginDomain); ?></option>
                            <option value="Shift_JIS" <?php if ($options['widget']['enc'] == 'Shift_JIS') echo 'selected="selected"'; ?> /><?php echo __('Shift_JIS', $this->pluginDomain); ?></option>
                            <option value="ISO-8859-1" <?php if ($options['widget']['enc'] == 'ISO-8859-1') echo 'selected="selected"'; ?> /><?php echo __('ISO-8859-1', $this->pluginDomain); ?></option>
        </select></td>
        <td><?php echo __('Specifies how the output is encoded', $this->pluginDomain); ?>
            <i><?php echo __('(Google Chart API only)', $this->pluginDomain); ?></i>
        </td>
      </tr>
      
      <tr>
        <td width="100"><?php echo __('ECC', $this->pluginDomain); ?></td>
        <td width="100"><select name="qrct_sc_ecc" style="width:100%;">
                            <option value="L" <?php if ($options['shortcode']['ecc'] == 'L') echo 'selected="selected"'; ?> /><?php echo __('L', $this->pluginDomain); ?></option>
                            <option value="M" <?php if ($options['shortcode']['ecc'] == 'M') echo 'selected="selected"'; ?> /><?php echo __('M', $this->pluginDomain); ?></option>
                            <option value="Q" <?php if ($options['shortcode']['ecc'] == 'Q') echo 'selected="selected"'; ?> /><?php echo __('Q', $this->pluginDomain); ?></option>
                            <option value="H" <?php if ($options['shortcode']['ecc'] == 'H') echo 'selected="selected"'; ?> /><?php echo __('H', $this->pluginDomain); ?></option>
        </select></td>
        <td width="100"><select name="qrct_tt_ecc" style="width:100%;">
                            <option value="L" <?php if ($options['tooltip']['ecc'] == 'L') echo 'selected="selected"'; ?> /><?php echo __('L', $this->pluginDomain); ?></option>
                            <option value="M" <?php if ($options['tooltip']['ecc'] == 'M') echo 'selected="selected"'; ?> /><?php echo __('M', $this->pluginDomain); ?></option>
                            <option value="Q" <?php if ($options['tooltip']['ecc'] == 'Q') echo 'selected="selected"'; ?> /><?php echo __('Q', $this->pluginDomain); ?></option>
                            <option value="H" <?php if ($options['tooltip']['ecc'] == 'H') echo 'selected="selected"'; ?> /><?php echo __('H', $this->pluginDomain); ?></option>
        </select></td>
        <td width="100"><select name="qrct_wg_ecc" style="width:100%;">
                            <option value="L" <?php if ($options['widget']['ecc'] == 'L') echo 'selected="selected"'; ?> /><?php echo __('L', $this->pluginDomain); ?></option>
                            <option value="M" <?php if ($options['widget']['ecc'] == 'M') echo 'selected="selected"'; ?> /><?php echo __('M', $this->pluginDomain); ?></option>
                            <option value="Q" <?php if ($options['widget']['ecc'] == 'Q') echo 'selected="selected"'; ?> /><?php echo __('Q', $this->pluginDomain); ?></option>
                            <option value="H" <?php if ($options['widget']['ecc'] == 'H') echo 'selected="selected"'; ?> /><?php echo __('H', $this->pluginDomain); ?></option>
        </select></td>

        <td><?php echo __('Error Correction Level (see <a href="http://code.google.com/apis/chart/types.html#ec_level_table" target="_blank">Google Chart API</a>)<br/><strong>L</strong> allows 7% of a QR code to be restored<br/><strong>M</strong> allows 15% of a QR code to be restored<br/><strong>Q</strong> allows 25% of a QR code to be restored<br/><strong>H</strong> allows 30% of a QR code to be restored', $this->pluginDomain); ?></td>
      </tr>
      <tr>
        <td width="100"><?php echo __('Version', $this->pluginDomain); ?></td>
        <td width="100"><input type="text" name="qrct_sc_ver" style="width:100%;" value="<?php echo $options['shortcode']['version']; ?>" /></td>
        <td width="100"><input type="text" name="qrct_tt_ver" style="width:100%;" value="<?php echo $options['tooltip']['version']; ?>" /></td>
        <td width="100"><input type="text" name="qrct_wg_ver" style="width:100%;" value="<?php echo $options['widget']['version']; ?>" /></td>
        <td><?php echo __('<strong>0-40 (0=auto)</strong>. Before choosing the QR code version, consider what kind of device is used to read your code. The best QR code readers are able to read Version 40 codes, mobile devices may read only up to Version 4 ', $this->pluginDomain); ?><i><?php echo __('(QR Code Lib only)', $this->pluginDomain); ?></i></td>
      </tr>
      <tr>
        <td width="100"><?php echo __('Margin', $this->pluginDomain); ?></td>
        <td width="100"><input type="text" name="qrct_sc_margin" style="width:100%;" value="<?php echo $options['shortcode']['margin']; ?>" /></td>
        <td width="100"><input type="text" name="qrct_tt_margin" style="width:100%;" value="<?php echo $options['tooltip']['margin']; ?>" /></td>
        <td width="100"><input type="text" name="qrct_wg_margin" style="width:100%;" value="<?php echo $options['widget']['margin']; ?>" /></td>
        <td><?php echo __('Defines the margin (or blank space) around the QR code (in QR Code pixel size - not actual pixels!)', $this->pluginDomain); ?></td>
      </tr>
      <tr>
        <td width="100"><?php echo __('ImageParam', $this->pluginDomain); ?></td>
        <td width="100"><input type="text" name="qrct_sc_imageparam" style="width:100%;" value="<?php echo $options['shortcode']['imageparam']; ?>" /></td>
        <td width="100">&nbsp;</td>
        <td width="100"><input type="text" name="qrct_wg_imageparam" style="width:100%;" value="<?php echo $options['widget']['imageparam']; ?>" /></td>
        <td><?php echo __('Additional image parameters (e.g. <i>class="qrctimage"</i>)', $this->pluginDomain); ?></td>
      </tr>
      <tr>
        <td width="100"><?php echo __('Link', $this->pluginDomain); ?></td>
        <td width="100"><input type="text" name="qrct_sc_link" style="width:100%;" value="<?php echo $options['shortcode']['link']; ?>" /></td>
        <td width="100">&nbsp;</td>
        <td width="100"><input type="text" name="qrct_wg_link" style="width:100%;" value="<?php echo $options['widget']['link']; ?>" /></td>
        <td><?php echo __('Defines if the image will have a link:<br/><strong>false</strong> = no link<br/><strong>true</strong> = link to the QR code content<br/><strong>url</strong> = link to the current URL<br><strong>http://</strong> = link to some URL (e.g. <i>http://www.google.com</i>)', $this->pluginDomain); ?></td>
      </tr>
      <tr>
        <td width="100"><?php echo __('ATagParam', $this->pluginDomain); ?></td>
        <td width="100"><input type="text" name="qrct_sc_atagparam" style="width:100%;" value="<?php echo $options['shortcode']['atagparam']; ?>" /></td>
        <td width="100">&nbsp;</td>
        <td width="100"><input type="text" name="qrct_wg_atagparam" style="width:100%;" value="<?php echo $options['widget']['atagparam']; ?>" /></td>
        <td><?php echo __('Additional link parameters (e.g. <i>class="mylinkclass"</i>)', $this->pluginDomain); ?></td>
      </tr>
    </table>
     <p class="submit"><input type="submit" name="update_options" class="button-primary" value="<?php echo __('Save Changes', $this->pluginDomain); ?>" /> <input type="submit" name="reset_options" value="<?php echo __('Reset Options', $this->pluginDomain); ?>" /></p>
    </form>
    <form method="post" name="options" target="_self">
    <h3><?php echo __('QR Code Cache', $this->pluginDomain); ?></h3>
    <?php
    
        function formatBytes($bytes) 
        {
               $types = array( 'Byte', 'KB', 'MB', 'GB', 'TB' );
            for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
            return( round( $bytes, 2 ) . " " . $types[$i] );
        }
    
        require_once(dirname(__FILE__).'/Qrcode.php');
        $qrcode = new Qrcode();
        $cacheFiles = 0;
        $cacheSize = 0;
        $qrcode->cacheState($cacheFiles, $cacheSize, $avgCreationTime);
        $cacheSizeReadable = formatBytes($cacheSize);
        $diskspaceleft = formatBytes(disk_free_space (dirname(__FILE__)));
        echo sprintf(__('%1$d codes cached, using %2$s disk space (%3$s free disk space).', $this->pluginDomain),$cacheFiles,$cacheSizeReadable,$diskspaceleft).'<br>';
        echo sprintf(__('%1$01.4f sec average code creation time.', $this->pluginDomain), $avgCreationTime).'<br>';
    ?>
     <p class="submit"><input type="submit" name="clear_cache" class="button-primary" value="<?php echo __('Clear Cache', $this->pluginDomain); ?>" /></p>
    </form>
    
    <h3><?php echo __('Help', $this->pluginDomain); ?></h3>
    <p style="width: 300px;">
    <?php
        
        $langPath = dirname(__FILE__).'/../../lang/';
        $helpFile = $langPath.'help-'.WPLANG.'.html';
        if (!file_exists($helpFile)) {
            $helpFile = $langPath.'help.html';
        }
         include ($helpFile); 
    ?>
    </p>
    
    
</div>
