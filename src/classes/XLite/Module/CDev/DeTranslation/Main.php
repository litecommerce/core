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

namespace XLite\Module\CDev\DeTranslation;

/**
 * German translation module
 *
 */
abstract class Main extends \XLite\Module\AModule
{
    /**
     * Language code
     */
    const LANG_CODE = 'de';

    /**
     * Author name
     *
     * @return string
     */
    public static function getAuthorName()
    {
        return 'Creative Development LLC';
    }

    /**
     * Module name
     *
     * @return string
     */
    public static function getModuleName()
    {
        return 'Translation: German';
    }

    /**
     * Get module major version
     *
     * @return string
     */
    public static function getMajorVersion()
    {
        return '1.1';
    }

    /**
     * Module version
     *
     * @return string
     */
    public static function getMinorVersion()
    {
        return '0';
    }

    /**
     * Module description
     *
     * @return string
     */
    public static function getDescription()
    {
        return 'German translation pack';
    }

    /**
     * Decorator run this method at the end of cache rebuild
     *
     * @return void
     */
    public static function runBuildCacheHandler()
    {
        $language = \XLite\Core\Database::getRepo('\XLite\Model\Language')->findOneByCode(static::LANG_CODE);

        if (isset($language)) {
            if (!$language->getEnabled()) {
                $language->setEnabled(true);

                \XLite\Core\Database::getRepo('\XLite\Model\Language')->update($language);
                
                \XLite\Core\TopMessage::addInfo(
                    'The X language has been added and enabled successfully',
                    array('language' => $language->getName()),
                    $language->getCode()
                );
            }

            \XLite\Core\Translation::getInstance()->reset();

        } else {
            \XLite\Core\TopMessage::addError('The language you want to add has not been found');
        }
    }
}
