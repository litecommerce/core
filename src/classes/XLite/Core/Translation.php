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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Core;

/**
 * Translation core rutine
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Translation extends \XLite\Base\Singleton implements \XLite\Base\IREST
{
    /**
     * Current language code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $currentLanguageCode;

    /**
     * Translation driver
     *
     * @var   \XLite\Core\TranslationDriver\ATranslationDriver
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $driver;

    /**
     * Translation drivers query
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $driversQuery = array(
        '\XLite\Core\TranslationDriver\Gettext',
        '\XLite\Core\TranslationDriver\Db',
    );

    /**
     * Get current language code
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getCurrentLanguageCode($force = false)
    {
        if (!isset(static::$currentLanguageCode) || $force) {
            static::$currentLanguageCode = \XLite\Core\Session::getInstance()->getLanguage()->getCode();
        }

        return static::$currentLanguageCode;
    }

    /**
     * Get translation (short static method)
     *
     * @param string $name      Label name
     * @param array  $arguments Substitute arguments OPTIONAL
     * @param string $code      Language code OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function lbl($name, array $arguments = array(), $code = null)
    {
        return static::getInstance()->translate($name, $arguments, $code);
    }

    /**
     * Reset driver cache
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function reset()
    {
        $this->getDriver()->reset();
    }

    /**
     * Get translation
     *
     * @param string $name      Label name
     * @param array  $arguments Substitute arguments OPTIONAL
     * @param string $code      Language code OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function translate($name, array $arguments = array(), $code = null)
    {
        $result = '';

        if (!isset($code)) {
            $code = \XLite\Core\Session::getInstance()->getLanguage()->getCode();
        }

        if (!empty($name)) {
            $result = $this->getDriver()->translate($name, $code);

            if (!isset($result)) {
                $result = $name;
            }

            if (!empty($arguments)) {
                $result = $this->processSubstitute($result, $arguments);
            }
        }

        return $result;
    }


    /**
     * Get REST entity names
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTranslationREST($id, $arguments)
    {
        if (!is_array($arguments) || !$arguments) {
            $arguments = array();
        }

        return $this->translate($id, $arguments);
    }


    /**
     * Process substitute
     *
     * @param string $string Translated label
     * @param array  $args   Substitute arguments
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineDriver()
    {
        $driver = null;

        $translationDriver = \XLite::getInstance()->getOptions(array('other', 'translation_driver'));
        if ($translationDriver && 'auto' != $translationDriver) {
            $class = '\XLite\Core\TranslationDriver\\'
                . \XLite\Core\Converter::convertToCamelCase($translationDriver);
            if (in_array($class, $this->driversQuery)) {
                $driver = new $class();
                if (!$driver->isValid()) {
                    $driver = null;
                }
            }
        }

        if (!$driver) {
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
        }

        return $driver;
    }
}
