<?php
/**             
 * @category    WordPress Plugin
 * @package     Qrcode
 * @copyright   Copyright (c) 2009 Dennis D. Spreen (http://www.spreendigital.de/blog)
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author      Dennis D. Spreen <dennis@spreendigital.de>
 * @version     $Id: QrctWp.php 68 2009-09-18 16:06:46Z dennis.spreen $  
 */ 

class QrctWp
{
    public $pluginName = 'QR Code Tag';
    public $pluginVersion = '1.0';
  
    private $pluginDomain = 'qrctwp';          // translation domain
    private $pluginOptions = 'qrct_options';   // tag for WordPress options database
    private $shortcodeTag = 'qrcodetag';       // WordPress shortcode representation
    private $qrcode;                           // QR Code object that handles the code generation
    private $qrcodeExt = '';                   // image file extension
    private $pluginUrl = '';                   // plugin URL
    private $pluginBase = '';                  // plugin base path
     
    private $defaultOptions = array(
        'widget' => array(
            'size' => '125',
            'enc' => 'UTF-8',
            'ecc' => 'L',
            'version' => '0',
            'margin' => '4',
            'imageparam' => 'class=&quot;qrctwidget&quot;',
            'link' => 'http://en.wikipedia.org/wiki/QR_Code',
            'atagparam' => 'target=&quot;_blank&quot;',
            'tooltip' => '',
            'title' => 'QR Code',
            'content' => '',
            'before' => '<div class=&quot;qrcode&quot;>',
            'after' => '</div>'),
        'shortcode' => array(
            'size' => '125',
            'enc' => 'UTF-8',
            'ecc' => 'L',
            'version' => '0',
            'margin' => '4',
            'imageparam' => 'class=&quot;qrctimage&quot;',
            'link' => 'true',
            'atagparam' => 'target=&quot;_blank&quot;"',
            'tooltip' => ''),
        'tooltip' => array(
            'size' => '125',
            'enc' => 'UTF-8',
            'ecc' => 'L',
            'version' => '0',
            'margin' => '4'),
        'global' => array(
            'generator' => 'lib',
            'ext' => 'png'));

    /**
    * WordPress integration with Hooks, Translation and Widgets
    *   
    * @param  string  $baseFile  The full path and filename to the Creator script
    */
    public function QrctWp($baseFile)
    {
        // set initial paths - because of resolved symlinks within $baseFile 
        // we'll construct the URL and base paths using the script name and 
        // the 'guessed' plugin directory name - thus do not name the 
        // plugin directory other than the main script file! 
        // plugin_basename(__FILE__) does not work with symlinks. 
        // blame php. not wp.
        $this->pluginBase = basename($baseFile,'.'.pathinfo($baseFile, PATHINFO_EXTENSION)).
                            "/".basename($baseFile);
        $this->pluginUrl = WP_PLUGIN_URL.'/'.dirname($this->pluginBase).'/';

        // load text translation
        load_plugin_textdomain($this->pluginDomain, false, './'.dirname($this->pluginBase).'/lang');
    
        // activation and deactivation function
        register_activation_hook($baseFile, array($this, 'activate'));
        register_deactivation_hook($baseFile, array($this, 'deactivate'));

        // widget integration
        add_action('widgets_init', array($this, 'initWidget'));
    
        // shortcode integration
        add_shortcode($this->shortcodeTag, array($this, 'shortcode'));
    
        // setup an options page
        if (is_admin()) { 
            add_action('admin_menu', array($this, 'setAdminMenu')); 
        }

        // add "Settings" link in the plugins list
        add_filter('plugin_action_links_'.$this->pluginBase, array($this, 'addConfigureLink'));

        // include javascript and css styles
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-tooltip', $this->pluginUrl.'js/jquery.tooltip.min.js','jquery');
        wp_enqueue_script('qrcodetagging', $this->pluginUrl.'js/qrct.js','jquery-tooltip');
        wp_enqueue_style('qrcodetagging', $this->pluginUrl.'css/qrct.css');
    }
  
    /**
    * WordPress Filter for adding a "Settings" links in the plugins List 
    * 
    * @param  $links
    * @return unknown_type
    */
    public function addConfigureLink($links) 
    { 
        $settings_link = '<a href="options-general.php?page='.$this->pluginBase.'">'.
                        __('Settings',$this->pluginDomain).'</a>';
        array_unshift($links, $settings_link ); 
        return $links; 
    }
    
    /**
    * WordPress Admin SubMenu Page setting
    */
    public function setAdminMenu() 
    {
        add_submenu_page('options-general.php', __('QR Code Tag'), __('QR Code Tag'), 6, $this->pluginBase, array($this, 'adminSettingsPage'));
    }
  
    /**
    * Action on Plugin Activation
    * 
    * @return boolean TRUE
    */
    public function activate()
    {
        if (!get_option($this->pluginOptions)) { // if options not set before, set it now
            add_option($this->pluginOptions, $this->defaultOptions);
        } else { // else reset to default options
            update_option($this->pluginOptions, $this->defaultOptions);
        }
        return TRUE;
    } 
  
    /**
    * Action on Plugin Deactivation
    * 
    * @return boolean TRUE
    */
    public function deactivate()
    {
        remove_action('widgets_init',array($this, 'initWidget')); // remove widget
        remove_shortcode('qrcodetag'); //remove shortcode
        delete_option($this->pluginOptions); // delete plugin options
        return TRUE;
    }
  
    /**
    * WordPress Widget initialization
    */
    public function initWidget()
    {
        // register widget and control page
        wp_register_sidebar_widget('widget_qrct', $this->pluginName, array($this, 'widget'));
        register_widget_control('widget_qrct', array($this, 'widgetControl'));  
    }

    /**
    * Get current script URL
    * 
    * @return  string  Current URL
    */
    public function currentUrl() 
    {
        $pageURL = 'http';
        if ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] == 'on')) { 
            $pageURL .= "s"; 
        }
        $pageURL .= '://';
     
        if ($_SERVER['SERVER_PORT'] != '80') { // add port if not standard
            $pageURL .= $_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI'];
        } else {
            $pageURL .= $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
        }
        return $pageURL;
    }
  
    /**
    * Get the URL for a QR Code based on given parameters 
    * 
    * @param  string    $content  content to be encoded  
    * @param  integer   $size     image size
    * @param  string    $enc      encoding format
    * @param  string    $ecc      error correction type
    * @param  integer   $margin   image margin
    * @param  integer   $version  QR Code version
    * @return string
    */
    public function getQrcodeUrl($content, $size, $enc, $ecc, $margin, $version) 
    {
        // if qr code object not created yet, do now
        if (!$this->qrcode) {
      
            // load global options
            $options = get_option($this->pluginOptions);
            $options = $options['global'];

            // set QR Code file extension for later re-use
            $this->qrcodeExt = $options['ext'];
      
            // based on generator setting use appropriate class
            $generator = $options['generator']; 
            if ($generator == 'google') {
                require_once(dirname(__FILE__).'/QrcodeGoogle.php');
                $this->qrcode = new QrcodeGoogle();
            } else {
                require_once(dirname(__FILE__).'/QrcodeLib.php');
                $this->qrcode = new QrcodeLib();
            }
        }
    
        // get QR Code and return URL
        $file = $this->qrcode->get($content, $this->qrcodeExt, $size, $enc, $ecc, $margin, $version);
        return $this->pluginUrl.'data/'.$file;
    }
  
    /**
    * WordPress widget call
    * 
    * @param $args
    */
    public function widget($args)
    {
        // import variables into the current symbol table
        extract($args);
    
        // load widget options
        $pluginOptions = get_option($this->pluginOptions);
        $options = $pluginOptions['widget'];
    
        // load content
        $content = $options['content'];
    
        // Display the widget  
        echo $before_widget;
        echo $before_title;
        echo $options['title'];
        echo $after_title;
        echo htmlspecialchars_decode($options['before']);
        echo $this->createTag($content, $options, NULL);
        echo htmlspecialchars_decode($options['after']);
        echo $after_widget;
    }

    /**
    * WordPress widget administration call 
    */
    public function widgetControl()
    {
        // load widget options
        $options = get_option($this->pluginOptions);
  
        // check if user submitted changes
        if ($_POST['widget_qrct-submit']) {

            // update widget options array
            $options['widget']['title'] = htmlspecialchars(stripslashes($_POST['widget_qrct-title']));
            $options['widget']['before'] = htmlspecialchars(stripslashes($_POST['widget_qrct-before']));
            $options['widget']['text'] = htmlspecialchars(stripslashes($_POST['widget_qrct-text']));
            $options['widget']['after'] = htmlspecialchars(stripslashes($_POST['widget_qrct-after']));
    
            // we also update the options in the WordPress database
            update_option($this->pluginOptions, $options);
        }
     
        echo '<p><label><input id="widget_qrct-title" class="widefat" type="text" value="'.$options['widget']['title'].'" name="widget_qrct-title"></p>';
        echo '<p><label>'.__('Before QR Code:', $this->pluginDomain).'<input id="widget_qrct-before" class="widefat" type="text" value="'.$options['widget']['before'].'" name="widget_qrct-before"></p>';
        echo '<p><label>'.__('Content (blank for current URL):', $this->pluginDomain).'<input id="widget_qrct-text" class="widefat" type="text" value="'.$options['widget']['text'].'" name="widget_qrct-text"></p>';
        echo '<p><label>'.__('After QR Code:', $this->pluginDomain).'<input id="widget_qrct-after" class="widefat" type="text" value="'.$options['widget']['after'].'" name="widget_qrct-after"></p>';
        echo '<input type="hidden" id="widget_qrct-submit" name="widget_qrct-submit" value="1" />';
    }

    /**
    * Create a wrap if the option is specified else use default string
    * 
    * @param  string  $option   option name
    * @param  string  $name     wrap name
    * @param  string  $default  default value if option is empty
    * @return string
    */
    public function optionWrap($option, $name, $default)
    {
        // if option isn't empty wrap it
        if ($option != '') {
            return ' '.$name.'="'.$option.'"';
        } else { // else return default value
            return $default;
        }
    }
  
    /**
    * Return a QR Code, this is subfunction used by Widget and Shortcode
    * 
    * @param  string   $content  QR Code content
    * @param  array    $options  options array    
    * @param  mixed    $atts     param attributes    
    * @return string
    */
    public function createTag($content, $options, $atts)
    {
        // extract attributes into the current symbol table, with default settings if not specified
        extract(shortcode_atts(array(  
            'size' => $options['size'],
            'enc' => $options['enc'],
            'ecc' => $options['ecc'],
            'margin' => $options['margin'],
            'version' => $options['version'],
            'imageparam' => $options['imageparam'],
            'link' => $options['link'],
            'atagparam' => $options['atagparam'],
            'tooltip' => $options['tooltip']), 
        $atts));
    
        // tooltip mode check
        $tooltipMode = ($tooltip != '');
    
        // convert html entities back to characters  
        $atagparam = htmlspecialchars_decode($atagparam);
        $imageparam = htmlspecialchars_decode($imageparam);
        
        // if no or empty content then use current url as content
        if ((is_null($content)) || ($content == '') || ($content =='_')) {
            $content = $this->currentUrl();
        } else {
            $content = do_shortcode($content); // be sure to resolve other shortcodes as well
        }

        // if tooltip mode is enabled, switch content and visible Text, then load tooltip options
        if ($tooltipMode) {
            // switch content
            $tooltipText = $content;
            $content = $tooltip;
      
            // load tooltip options
            $pluginOptions = get_option("qrct_options");
            $options = $pluginOptions['tooltip'];
      
            // apply tooltip options
            extract(shortcode_atts(array(  
                'size' => $options['size'],
                'enc' => $options['enc'],
                'ecc' => $options['ecc'],
                'margin' => $options['margin'],
                'version' => $options['version']),
            $atts));
        }
    
        // create QR Code URL
        $url = $this->getQrcodeUrl($content, $size, $enc, $ecc, $margin, $version);

        // check for styling options
        if ($atagparam) {
            $atagparam = ' '.$atagparam;
        }
    
        // check for image styling options
        if ($imageparam) {
            $imageparam = ' '.$imageparam;
        }
    
        // create image tag
        $img = '<img src="'.$url.'"'.$imageparam.' />';
    
        // check for link options and set linkTarget
        $linkTarget = '';
        if ($link == 'true') {
            $linkTarget = $content;
        } elseif ($link == 'url') {
            $linkTarget = $this->currentUrl();
        } elseif (($link != '') && ($link != 'false')) {
            $linkTarget = $link;
        }
    
        // if there is a link then create html link wrap
        if ($linkTarget != '') {
            $linkWrap = '<a href="'.$linkTarget.'"'.$atagparam.'>';
            $linkWrapEnd = '</a>';
        } else {
            $linkWrap ='';
            $linkWrapEnd = '';
        }

        // if tooltip mode enabled, wrap with span class for jquery-tooltip
        if ($tooltip != '') {
            return '<span class="qrcttooltip" title="'.$url.'">'.$tooltipText.'</span>';
        } else {
            return $linkWrap.$img.$linkWrapEnd;
        }
    }
  
    /**
    * WordPress shortcode call, return QR Code HTML image string
    * 
    * @param  mixed   $atts     shortcode attributes
    * @param  string  $content  QR Code content
    * @return string  
    */
    public function shortcode($atts, $content = NULL) 
    {
        // load shortcode options
        $pluginOptions = get_option($this->pluginOptions);
        $options = $pluginOptions['shortcode'];

        // call QR Code creation subfunction
        return $this->createTag($content, $options, $atts);
    }

    /**
    * Update a list value if exists in allowedEntries, subfunction for AdminSetting
    * 
    * @param  string  $updateValue      value that needs to be updated in the options
    * @param  array   $allowedEntries   allowed values for updateValue
    * @param  string  &$options         reference to updated option
   */
    public function updateValueList($updateValue, $allowedEntries, &$options) 
    {
        // update only if value is allowed
        if (in_array($updateValue, $allowedEntries)) {
            $options = $updateValue;        
        }
    }

    /**
    * Update an integer value if exists in specified bounds, subfunction for AdminSetting
    * 
    * @param  string  $updateValue   value that needs to be updated in the options
    * @param  integer $min           minimum valid value
    * @param  integer $max           maximum valid value
    * @param  string  &$options      reference to updated option
    */
    public function updateValueInteger($updateValue, $min, $max, &$options)
    {
        // update only if value is numeric and inbetween bounds
        if ((is_numeric($updateValue)) && ($updateValue >= $min) && ($updateValue <= $max)) {
            $options = $updateValue;        
        }
    }

    /**
    * Update a string value if exists in specified bounds, subfunction for AdminSetting
    * 
    * @param  string   $updateValue  value that needs to be updated in the options
    * @param  string   &$options     reference to updated option
    * @param  integer  $maxLength    (optional) maximum valid value (defaults to 1024)
    * @param  boolean  $allowEmpty   (optional) empty value allowed (defaults to TRUE)
    */
    public function updateValueString($updateValue, &$options, $maxLength = 1024, $allowEmpty = TRUE)
    {
        // if value is empty
        if ($updateValue == '') {
            if ($allowEmpty) { // update only if empty allowed
                $options = $updateValue;
            }
        } elseif (strlen($updateValue<=$maxLength)) { // only if string length is within bounds 
            // and replace < and > for security reasons
            $options = str_replace(array('>','<'),array('',''),$updateValue);
        }     
    }
  
    /**
    * WordPress Admin configuration page for the plugin call 
    */
    public function adminSettingsPage()
    {
        // load options
        $options = get_option($this->pluginOptions);
    
        if (isset($_POST['update_options'])) // save changes 
        {
            // global, code generation
            $this->updateValueList($_POST['qrct_generator'], array('google','lib'), $options['global']['generator']); 

            // image type
            $this->updateValueList($_POST['qrct_imagetype'], array('gif','png','jpg'), $options['global']['ext']); 
      
            // default options, size
            $this->updateValueInteger($_POST['qrct_sc_size'], 0, 1400, $options['shortcode']['size']); 
            $this->updateValueInteger($_POST['qrct_tt_size'], 0, 1400, $options['tooltip']['size']); 
            $this->updateValueInteger($_POST['qrct_wg_size'], 0, 1400, $options['widget']['size']); 
      
            // encoding
            $this->updateValueList($_POST['qrct_sc_enc'], array('UTF-8','Shift_JIS','ISO-8859-1'), $options['shortcode']['enc']); 
            $this->updateValueList($_POST['qrct_tt_enc'], array('UTF-8','Shift_JIS','ISO-8859-1'), $options['tooltip']['enc']); 
            $this->updateValueList($_POST['qrct_wg_enc'], array('UTF-8','Shift_JIS','ISO-8859-1'), $options['widget']['enc']); 

            // error corrtion
            $this->updateValueList($_POST['qrct_sc_ecc'], array('L','M','Q','H'), $options['shortcode']['ecc']); 
            $this->updateValueList($_POST['qrct_tt_ecc'], array('L','M','Q','H'), $options['tooltip']['ecc']); 
            $this->updateValueList($_POST['qrct_wg_ecc'], array('L','M','Q','H'), $options['widget']['ecc']); 
      
            // version
            $this->updateValueInteger($_POST['qrct_sc_ver'], 0, 40, $options['shortcode']['version']); 
            $this->updateValueInteger($_POST['qrct_tt_ver'], 0, 40, $options['tooltip']['version']); 
            $this->updateValueInteger($_POST['qrct_wg_ver'], 0, 40, $options['widget']['version']); 
      
            // margin
            $this->updateValueInteger($_POST['qrct_sc_margin'], 0, 10, $options['shortcode']['margin']); 
            $this->updateValueInteger($_POST['qrct_tt_margin'], 0, 10, $options['tooltip']['margin']); 
            $this->updateValueInteger($_POST['qrct_wg_margin'], 0, 10, $options['widget']['margin']);

            // imageparam
            $this->updateValueString(htmlspecialchars(stripslashes($_POST['qrct_sc_imageparam'])),$options['shortcode']['imageparam']);
            $this->updateValueString(htmlspecialchars(stripslashes($_POST['qrct_wg_imageparam'])),$options['widget']['imageparam']);
      
            // link
            $this->updateValueString($_POST['qrct_sc_link'],$options['shortcode']['link']);
            $this->updateValueString($_POST['qrct_wg_link'],$options['widget']['link']);
      
            // atagparam
            $this->updateValueString(htmlspecialchars(stripslashes($_POST['qrct_sc_atagparam'])),$options['shortcode']['atagparam']);
            $this->updateValueString(htmlspecialchars(stripslashes($_POST['qrct_wg_atagparam'])),$options['widget']['atagparam']);

            // update options in the WordPress options database
            update_option($this->pluginOptions, $options);
      
            // write out header message
            echo '<div id="message" class="updated fade"><p><strong>' . __('Options saved.', $this->pluginDomain) . '</strong></p></div>';
  
        } elseif (isset($_POST['reset_options'])) {  // if Reset options pressed

            // write default options to WordPress options database
            update_option($this->pluginOptions, $this->defaultOptions);

            // and reload it afterwards
            $options = get_option($this->pluginOptions);

            // write out header message
            echo '<div id="message" class="updated fade"><p><strong>' . __('Default options loaded.', $this->pluginDomain) . '</strong></p></div>';

        } elseif (isset($_POST['clear_cache'])) { // if Clear Cache button pressed

            // create dummy QR Code object
            require_once(dirname(__FILE__).'/Qrcode.php');
            $qrcode = new Qrcode();
            $qrcode->clearCache();

            // write out header message
            echo '<div id="message" class="updated fade"><p><strong>' . __('Cache cleared.', $this->pluginDomain) . '</strong></p></div>';
        }
    
        // include default settings page
        require_once(dirname(__FILE__).'/QrctWp-admin.inc.php');
    }
  
}