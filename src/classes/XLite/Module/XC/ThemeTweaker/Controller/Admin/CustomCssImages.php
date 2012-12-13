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

namespace XLite\Module\XC\ThemeTweaker\Controller\Admin;

/**
 * Custom CSS images controller
 *
 */
class CustomCssImages extends \XLite\Module\XC\ThemeTweaker\Controller\Admin\ThemeTweaker
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t('Images');
    }

    /**
     * Update action 
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $dir = \XLite\Module\XC\ThemeTweaker\Main::getThemeDir() . 'images' . LC_DS;
 
        if (
            $_FILES
            && $_FILES['new_image']
            && $_FILES['new_image']['name']
        ) {
            if (!\Includes\Utils\FileManager::isExists($dir)) {
                \Includes\Utils\FileManager::mkdir($dir);
            }

            \Includes\Utils\FileManager::moveUploadedFile('new_image', $dir);
        }

        $delete = \XLite\Core\Request::getInstance()->delete;

        if (
            $delete
            && is_array($delete)
        ) {
            foreach ($delete as $file => $del) {
                if ($del) {
                    \Includes\Utils\FileManager::DeleteFile($dir . $file);
                }
            }
        }
    }
}
