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

namespace XLite\Core;

/**
 * Translation core rutine
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Translation extends \XLite\Base\Singleton implements \XLite\Base\IREST
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
     * @var    \XLite\Core\TranslationDriver\ATranslationDriver
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
        '\XLite\Core\TranslationDriver\Gettext',
        '\XLite\Core\TranslationDriver\Db',
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
            self::$currentLanguageCode = \XLite\Core\Session::getInstance()->getLanguage()->code;
        }

        return self::$currentLanguageCode;
    }

    /**
     * Get translation (short static method)
     * 
     * @param string $name      Label name
     * @param array  $arguments Substitute arguments
     * @param string $code      Language code OPTIONAL
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
     * @param string $code      Language code OPTIONAL
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function translate($name, array $arguments = array(), $code = null)
    {
        if (!isset($code)) {
            $code = \XLite\Core\Session::getInstance()->getLanguage()->code;
        }

        $translated = $this::getInstance()->getDriver()->translate($name, $code);
        if (!isset($translated)) {
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
    protected function processSubstitute($string, array $args)
    {
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
     * @return \XLite\Core\TranslationDriver\ATranslationDriver
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDriver()
    {
        if (!isset($this->driver)) {
            $this->driver = $this->defineDriver();
        }

        return $this->driver;
    }

    /**
     * Define translation driver 
     * 
     * @return \XLite\Core\TranslationDriver\ATranslationDriver
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

        if (!isset($driver)) {
            // TODO - add throw exception
            $this->doDie('Unable to find a translation driver!');
        }

        return $driver;
    }

    /**
     * Get REST entity names 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRESTNames()
    {
        return array (
            'translation',
        );
    }

    /**
     * Get translation as REST 
     * 
     * @param string $id        Label name
     * @param array  $arguments Arguments
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTranslationREST($id, $arguments)
    {
        if (!is_array($arguments) || !$arguments) {
            $arguments = array();
        }

        return $this->translate($id, $arguments);
    }

}

