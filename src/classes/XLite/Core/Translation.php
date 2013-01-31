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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Core;

/**
 * Translation core rutine
 *
 */
class Translation extends \XLite\Base\Singleton implements \XLite\Base\IREST
{
    /**
     * Default language
     */
    const DEFAULT_LANGUAGE = 'en';

    /**
     * Translation driver
     *
     * @var \XLite\Core\TranslationDriver\ATranslationDriver
     */
    protected $driver;

    /**
     * Translation drivers query
     *
     * @var array
     */
    protected $driversQuery = array(
        '\XLite\Core\TranslationDriver\Gettext',
        '\XLite\Core\TranslationDriver\Db',
    );

    /**
     * Get translation (short static method)
     * TODO: to remove
     *
     * @param string $name      Label name
     * @param array  $arguments Substitute arguments OPTIONAL
     * @param string $code      Language code OPTIONAL
     *
     * @return string
     */
    public static function lbl($name, array $arguments = array(), $code = null)
    {
        return static::getInstance()->translate($name, $arguments, $code);
    }

    /**
     * Get language query 
     * 
     * @param string $code Specified code OPTIONAL
     *  
     * @return array
     */
    public static function getLanguageQuery($code = null)
    {
        $list = array(
            $code ?: \XLite\Core\Session::getInstance()->getLanguage()->getCode(),
            static::getDefaultLanguage(),
            static::DEFAULT_LANGUAGE
        );

        return array_unique($list);
    }

    /**
     * Reset driver cache
     *
     * @return void
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
     */
    public function getTranslationREST($id, $arguments)
    {
        if (!is_array($arguments) || !$arguments) {
            $arguments = array();
        }

        return $this->translate($id, $arguments);
    }

    /**
     * Get translation driver
     *
     * @return \XLite\Core\TranslationDriver\ATranslationDriver
     */
    public function getDriver()
    {
        if (!isset($this->driver)) {
            $this->driver = $this->defineDriver();
        }

        return $this->driver;
    }


    /**
     * Process substitute
     *
     * @param string $string Translated label
     * @param array  $args   Substitute arguments
     *
     * @return string
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
     * Define translation driver
     *
     * @return \XLite\Core\TranslationDriver\ATranslationDriver
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
