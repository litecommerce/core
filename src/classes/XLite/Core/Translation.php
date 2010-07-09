<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Translation core rutine
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Core_Translation extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Current language code 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $currentLanguageCode = null;

    /**
     * Translation driver 
     * 
     * @var    XLite_Core_TranslationDriver_ATranslationDriver
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $driver = null;

    /**
     * Translation drivers query 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $driversQuery = array(
        'XLite_Core_TranslationDriver_Gettext',
        'XLite_Core_TranslationDriver_Db'
    );

    /**
     * Get current language code
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getCurrentLanguageCode($force = false)
    {
        if (!isset(self::$currentLanguageCode) || $force) {
            self::$currentLanguageCode = XLite_Model_Session::getInstance()->getLanguage()->code;
        }

        return self::$currentLanguageCode;
    }

    /**
     * Get translation (short static method)
     * 
     * @param string $name      Label name
     * @param array  $arguments Substitute arguments
     * @param string $code      Language code
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function lbl($name, array $arguments = array(), $code = null)
    {
        return self::getInstance()->translate($name, $arguments, $code);
    }

    /**
     * Reset driver cache
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function reset()
    {
        $this->getDriver()->reset();
    }

    /**
     * Get translation
     * 
     * @param string $name      Label name
     * @param array  $arguments Substitute arguments
     * @param string $code      Language code
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function translate($name, array $arguments = array(), $code = null)
    {
        if (is_null($code)) {
            $code = XLite_Model_Session::getInstance()->getLanguage()->code;
        }

        $translated = $this::getInstance()->getDriver()->translate($name, $code);
        if (is_null($translated)) {
            $translated = $name;
        }

        if ($arguments) {
            $translated = $this->processSubstitute($translated, $arguments);
        }

        return $translated;
    }

    /**
     * Process substitute 
     * 
     * @param string $string Translated label
     * @param array  $args   Substitute arguments
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processSubstitute($string, array $args) {
        $keys = array();
        $values = array();
        foreach ($args as $k => $v) {
            $keys[] = '{{' . $k . '}}';
            $values[] = $v;
        }

        return str_replace($keys, $values, $string);
    }

    /**
     * Get translation driver 
     * 
     * @return XLite_Core_TranslationDriver_ATranslationDriver
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDriver()
    {
        if (is_null($this->driver)) {
            $this->driver = $this->defineDriver();
        }

        return $this->driver;
    }

    /**
     * Define translation driver 
     * 
     * @return XLite_Core_TranslationDriver_ATranslationDriver
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineDriver()
    {
        $driver = null;

        foreach ($this->driversQuery as $class) {
            $driver = new $class();
            if ($driver->isValid()) {
                break;
            }
            $driver = null;
        }

        if (is_null($driver)) {
            // TODO - add throw exception
            $this->doDie('Can not found translation driver!');
        }

        return $driver;
    }

}

