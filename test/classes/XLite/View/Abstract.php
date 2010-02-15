<?php

/**
 * XLite_View_Abstract 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
class XLite_View_Abstract extends XLite_Core_Handler
{
	 /**
     * Widget template filename
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = null;

	/**
	 * By default, widget is visible
	 * 
	 * @var    bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $visible = true;

	/**
	 * List of named widgets; FIXME - backward compatibility
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $widgets = array();

	/**
	 * Widget params (for exported widgets)
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected $widgetParams = null;


	/**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        $result = $this->visible;

        if ($result && !empty($this->mode) && isset(XLite::$controller)) {
            $result = in_array(XLite::$controller->mode, explode(',', $this->mode));
        }

        return ($result && isset($this->parentWidget)) ? $this->parentWidget->isVisible() : $result;
    }

    /**
     * Return template file base name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getTemplateFile()
    {
        return XLite_Model_Layout::getInstance()->getLayout($this->get('template'));
    }

    /**
     * Return template file full name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDisplayFile()
    {
        return LC_COMPILE_DIR . $this->getTemplateFile() . '.php';
    }

	/**
	 * Check if template is up-to-date
	 * 
	 * @param string $original original template
	 * @param string $compiled compiled one
	 *  
	 * @return bool
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function checkTemplateStatus($original, $compiled)
	{
		return file_exists($compiled) && (filemtime($compiled) == filemtime($original));
	}

	/**
	 * Return URL of the skin images folder
	 * 
	 * @return string
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getImagesURL()
	{
		return XLite::getInstance()->shopURL(XLite_Model_Layout::getInstance()->getPath() . 'images');
	}

	/**
     * Compile and display a template
     *
     * @param string $includeFile template to display
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function includeCompiledFile($includeFile)
    {
        if (is_null($this->get('template'))) {
            $this->_die("template is not set");
        }

        $templateFile = LC_ROOT_DIR . $this->getTemplateFile();

        if (!$this->checkTemplateStatus($templateFile, $includeFile)) {

            $fc = new XLite_Core_FlexyCompiler();
            $fc->set('source', file_get_contents($templateFile));
            $fc->set('url_rewrite', array('images' => $this->getImagesURL()));
            $fc->set('file', $templateFile);

            $file = $this->getDisplayFile();
            $dir  = dirname($file);

            if (!file_exists($dir)) {
                mkdirRecursive($dir, 0755);
            }

            file_put_contents($file, $fc->parse());
            touch($file, filemtime($templateFile));
        }

        include $includeFile;
    }

	/**
     * Return widget object; FIXME - backward compatibility
     *
     * @param array  $attrs widget attributes
     * @param string $class widget class
     * @param string $name  widget class
     *
     * @return XLite_View_Abstract
     * @access protected
     * @since  3.0.0 EE
     */
    protected function _getWidget(array $attrs = array(), $class = null, $name = null)
    {
        if (isset($name)) {
            if (!isset($this->widgets[$name])) {
                $this->$name = $this->widgets[$name] = isset($class) ? new $class() : clone $this;
            }
            $widget = $this->widgets[$name];
        } else {
            $widget = isset($class) ? new $class() : clone $this;
        }

        if (!empty($attrs)) {
            $widget->setAttributes($attrs);
        }

		$this->init();

        return $widget;
    }

	/**
	 * FIXME - backward compatibility 
	 * 
	 * @return XLite_View_Abstract
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getWidget()
    {
        return $this;
    }

	/**
	 * FIXME - backward compatibility
	 * 
	 * @return XLite_View_Abstract
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function getDialog()
    {
        return isset(XLite::$controller) ? XLite::$controller : $this;
    }

	/**
     * Called before the display()
     *
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function initView()
    {
    }


	/**
     * FIXME - backward compatibility
     *
     * @param string $name property name
     *
     * @return mixed
     * @access public
     * @since  3.0.0 EE
     */
	public function get($name)
    {
		$value = parent::get($name);

        return isset($value) ? $value : XLite::$controller->get($name);
    }

	/**
	 * FIXME - backward compatibility
	 * 
	 * @param string $name property name
	 *  
	 * @return mixed
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function __get($name)
	{
		$value = parent::__get($name);

		return isset($value) ? $value : XLite::$controller->$name;
	}

	/**
	 * FIXME - backward compatibility 
	 * 
	 * @param string $method method name
	 * @param array  $args   call arguments
	 *  
	 * @return mixed
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function __call($method, array $args = array())
    {
		return call_user_func_array(array(XLite::$controller, $method), $args);
    }

    /**
     * Attempts to display widget using its template 
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function display()
    {
		$this->initView();

        if ($this->isVisible()) {
            $this->includeCompiledFile($this->getDisplayFile());
        }
    }

	/**
     * Return widget parameters list
     *
     * @return array
     * @access public
     * @since  1.0.0
     */
    public function getWidgetParams()
    {
        if (is_null($this->widgetParams)) {
            $this->defineWidgetParams();
        }

        return $this->widgetParams;
    }

	/**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        $this->widgetParams = array();
    }

	/**
     * Check passed attributes
     *
     * @param array $attributes attributes to check
     *
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attributes)
    {
        return array();
    }




    /** 
    * Compares two values.
    *
    * @return boolean True if value are equals /false otherwise
    */
    function isSelected($val1, $val2, $val3 = null)
    {
        if (isset($val3)) {
            return $val1->get($val2)==$val3;
        }
        return $val1 == $val2;
    }

    /**
    * Truncates the Base object property value to specified length.
    * 
    * @access public
    * @param Base $baseObject Base instance to get field value from
    * @param string $field The filed to get value
    * @param integer $length The filed length to truncate to
    * @param string $etc The string to add to truncated field value
    * @return string The truncated string
    */
    function truncate($baseObject, $field, $length = 0, $etc = "...", $break_words = false)
    {
        if (is_scalar($baseObject)) {
            $string = $baseObject;
            $length = $field;
        } else {
        	if (is_object($baseObject)) {
            	$string = $baseObject->get($field);
            }
        }
        if ($length == 0) return '';
        if (strlen($string) > $length) {
            $length -= strlen($etc);
            if (!$break_words) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            }
            return substr($string, 0, $length) . $etc;
        } else {
            return $string;
        }
    }

    /**
    * Formats date.
    */
    function date_format($base, $field = null, $format = null)
    {
        if (is_null($format)) {
            $format = $this->config->getComplex('General.date_format');
        }
        if (!is_object($base)) {
            return strftime($format,$base);
        }
        return strftime($format, $base->get($field));
    }

    /**
    * Formats timestamp.
    */
    function time_format($base, $field = null, $format = null)
    {
        if (is_null($format)) {
            $format = $this->config->getComplex('General.date_format') . ' ' . $this->config->getComplex('General.time_format');
        }
        if (!is_object($base)) {
            return strftime($format,$base);
        }
        return strftime($format, $base->get($field));
    }

    function _price_format($value)
    {
        $price = sprintf("%.02f", round(doubleval($value), 2));
        return $price;
    }

    function price_format($base, $field = "", $thousand_delim = null, $decimal_delim = null)
    {
        if (is_null($thousand_delim)) {
            $thousand_delim = $this->config->getComplex('General.thousand_delim');
        }
        if (is_null($decimal_delim)) {
            $decimal_delim = $this->config->getComplex('General.decimal_delim');
        }
        if (is_Object($base)) {
            $price = $base->get($field);
        } else {
            $price = $base;
        }    

		if (substr($price, 0, 1) == '-' || substr($price, 0, 1) == '+') {
            $sign  = substr($price, 0, 1);
            $price = substr($price, 1);
        } else {
            $sign = '';
        }

        $price = $this->_price_format($price);
        $pos = false;
        if ($decimal_delim) {
            $price = str_replace(".", $decimal_delim, $price);
            $pos = strpos($price, $decimal_delim);
        } else {
            // no fractional part
            $price = substr($price, 0, strpos($price, "."));
        }
        if (!$pos) $pos = strlen($price);
        for ($i = $pos -3; $i > 1; $i -= 3) {
            $price = substr($price, 0, $i).$thousand_delim.substr($price, $i);
        }
        return sprintf($this->config->getComplex('General.price_format'), $sign.$price);
    }

    function addSlashes($base, $field = null)
    {
        if (is_object($base)) {
            return addslashes($base->get($field));
        } else {
            return addslashes($base);
        }
    }

    function isEqual($base1, $base2, $field) {
        return $base1->get($field) == $base2->get($field);
    }

    function isEmpty($data)
    {	
        return empty($data);
    }

    function ini_get($name)
    {
        return ini_get($name);
    }

    function split($array, $count)
    {
        $result = array_chunk($array, $count);
        // get last chunk, if any
        if ($result) {
            while(count($result[count($result)-1]) < $count) {
                $result[count($result)-1][] = null;
            }
        }
        return $result;
    }
    function split3($array) 
    {
        return $this->split($array, 3);
    }
    function split4($array) 
    {
        return $this->split($array, 4);
    }

    function isArrayPointerNth($arrayPointer, $arrayPointerCheck)
    {
    	$arrayPointerCheck = intval($arrayPointerCheck);

    	if ($arrayPointerCheck <= 0) {
    		return false;
    	}

    	return (($arrayPointer % $arrayPointerCheck) == 0) ? true : false;
    }

    function isArrayPointerEven($arrayPointer)
    {
    	return ($this->isArrayPointerNth($arrayPointer, 2)) ? true : false;
    }

    function isArrayPointerOdd($arrayPointer)
    {
    	return ($this->isArrayPointerNth($arrayPointer, 2)) ? false : true;
    }

    function percent($count)
    {
        return round(100/$count);
    }

    function inc($val, $inc = 1)
    {
        return $val + $inc;
    }
    
    function rand()
    {
        return rand();
    }

	function isOddRow($row)
	{
		return (($row % 2) == 0) ? true : false;
	}

	function getRowClass($row, $odd_css_class, $even_css_class = null)
	{
		return ($this->isOddRow($row)) ? $odd_css_class : $even_css_class;
	}

    function wrap(XLite_Base $object, $prop, $width)
    {
        if ($prop) {
            $text = $object->get($prop);
        } else {
            $text = $object;
        }
        $startPos = 0;
        $breaks = array(0);
        for ($i=0; $i<strlen($text); $i++) {
            $c = $text{$i};
            if ($c=='-' || $c==' ') {
                $startPos = $i;
            } else if ($c=='.' || $c==',' || $c=='@') {
                $nextWord = strcspn(substr($text, $i+1), '.,@- ');
                if ($i+$nextWord-$startPos >= $width) {
                    // break here
                    $breaks[] = $i+1;
                    $startPos = $i;
                }
            }
        }
        $breaks[] = strlen($text);
        // finally break text with \n
        $text1 = '';
        for ($i=0; $i<count($breaks)-1; $i++) {
            if ($i) {
                $text1 .= "\n";
            }
            $text1 .= substr($text, $breaks[$i], $breaks[$i+1]-$breaks[$i]);
        }
        return $text1;
    }

	public function isDisplayRequired(array $target)
	{
		return in_array($this->target, $target);
	}


    /*function addWidget($w) 
    {
        $this->widgets[] = $w;
        $w->parentWidget = $this;
    }*/

    function getCurrentYear()
    {
        return date("Y", time());
    }

    /*function strMD5($string)
    {
    	return strtoupper(md5(strval($string)));
    }*/

    /*function getSidebarBoxStatus($boxHead = null)
    {
		if ($this->xlite->is("adminZone")) 
		{
    		$dialog = new XLite_Controller_Admin_Sbjs();
    	}
        $dialog->sidebar_box_id = $this->strMD5($boxHead);

        return $dialog->getSidebarBoxStatus();
    }*/

	function getXliteFormID()
	{
		if (is_null($this->xlite->_xlite_form_id)) {
			$this->xlite->_xlite_form_id = $this->generateXliteFormID();
		}
		return $this->xlite->_xlite_form_id;
	}

	function generateXliteFormID()
	{
		$form = new XLite_Model_XliteForm();
		$form_id = md5(uniqid(rand(0,time())));
		$session_id = $this->xlite->session->getID();
		$form->set("form_id", $form_id);
		$form->set("session_id", $session_id);
		$form->set("date", time());
		$form->create();
		$form->collectGarbage($session_id);
		return $form_id;
	}

	/*function isIgnoredTarget()
    {
		$ignoreTargets = array
		(
        	"image" => array("*"),
            "callback" => array("*"),
			"upgrade" => array("version", "upgrade")
		);

		
                            
        if (
			isset($ignoreTargets[$_REQUEST['target']]) 
			&& (
				in_array("*", $ignoreTargets[$_REQUEST['target']]) 
				|| (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $ignoreTargets[$_REQUEST['target']]))
			)
		) { 
            return true;
        }

        $specialIgnoreTargets = array
        (
            "db" => array("backup", "delete"),
            "files" => array("tar", "tar_skins", "untar_skins"),
            "wysiwyg" => array("export", "import")
        );

        if(
			isset($specialIgnoreTargets[$_REQUEST['target']]) 
			&& (
				in_array("*", $specialIgnoreTargets[$_REQUEST['target']]) 
				|| (isset($_REQUEST['action']) && in_array($_REQUEST['action'], $specialIgnoreTargets[$_REQUEST['target']]))
			) 
			&& (
				isset($_POST['login']) && isset($_POST['password'])
			)
		) {
            $login = $this->xlite->auth->getComplex('profile.login');
            $post_login = $_POST['login'];
            $post_password = $_POST['password'];

            if($login != $post_login)
                return false;

            if(!empty($post_login) && !empty($post_password)){
                $post_password = $this->xlite->auth->encryptPassword($post_password);
                $profile = new XLite_Model_Profile();
                if ($profile->find("login='".addslashes($post_login)."' AND ". "password='".addslashes($post_password)."'")) {
                    if ($profile->get("enabled") && $profile->is("admin")) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    } 
					
	function checkXliteForm()
	{
		if (!isset($_REQUEST['action'])) {
            return true;
        }    
			  
		if ($this->isIgnoredTarget()) {
            return true;
        }
				 
		if (!$this->xlite->is("adminZone")) {
            return true;
        }

		return $this->isXliteFormValid();
	}

	function isXliteFormValid()
	{
		if (!$this->xlite->config->getComplex('Security.form_id_protection')) {
			return true;
		}

		if ($_REQUEST['target'] == 'payment_method' && $_REQUEST['action']=='callback') {
			return true;
		}

		$form_id = addslashes($_REQUEST['xlite_form_id']);
		$session_id = $this->xlite->session->getID();
		$form = new XLite_Model_XliteForm();
		if (!$form->find("form_id='$form_id' AND session_id='$session_id'")) {
			return false;
		}
		$form->collectGarbage();
		return true;
	}*/

    function isGDLibLoaded()
    {
    	// PHP 4 >= 4.3.0
    	// !!!
        return extension_loaded('gd') && function_exists("gd_info");
    }

    function getCaptchaPages()
    {
        return array(
                "on_contactus" => "",
                "on_register" => "",
                "on_add_giftcert" => "GiftCertificates",
                "on_partner_register" => "Affiliate"
                );
    }

    function getEnabledCaptchaPages()
    {
        $pages = (array) $this->get("captchaPages");
        foreach($pages as $widget_id => $dependency){
            if($dependency == "") continue;

            $module = $dependency;
            if(!$this->get("xlite.mm.activeModules." . $module)){
                unset($pages[$widget_id]);
            }
        }

        return $pages;
    }

    function isActiveCaptchaPage($widget_id)
    {
        $pages = $this->getComplex('config.Captcha.active_captcha_pages');

        return (isset($pages[$widget_id]) && $this->getComplex('xlite.config.Security.captcha_protection_system') == "Y");
    }
    
}

