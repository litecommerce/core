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

namespace XLite\Core\TranslationDriver;

/**
 * DB-based driver
 *
 */
class Db extends \XLite\Core\TranslationDriver\ATranslationDriver
{
    const TRANSLATION_DRIVER_NAME = 'Database';


    /**
     * Translations
     *
     * @var array
     */
    protected $translations = array();

    /**
     * Translate label
     *
     * @param string $name Label name
     * @param string $code Language code
     *
     * @return string|void
     */
    public function translate($name, $code)
    {
        if (!isset($this->translations[$code])) {
            $this->translations[$code] = $this->getRepo()->findLabelsByCode($code);
        }

        return \Includes\Utils\ArrayManager::getIndex($this->translations[$code], $name);
    }

    /**
     * Check if driver is valid or not
     *
     * @return boolean
     */
    public function isValid()
    {
        return true;
    }

    /**
     * Reset language driver
     *
     * @return void
     */
    public function reset()
    {
        $this->translations = array();
        $this->getRepo()->cleanCache();
    }
}
