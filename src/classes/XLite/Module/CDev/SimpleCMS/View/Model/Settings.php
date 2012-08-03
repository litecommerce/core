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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\SimpleCMS\View\Model;

/**
 * Settings dialog model widget
 *
 */
abstract class Settings extends \XLite\View\Model\Settings implements \XLite\Base\IDecorator
{
    /**
     * Populate model object properties by the passed data
     *
     * @param array $data Data to set
     *
     * @return void
     */
    protected function setModelProperties(array $data)
    {
        $options = $this->getOptions();
        $dir = LC_DIR_SKINS . \XLite\Core\Layout::PATH_COMMON . LC_DS;

        if ('CDev\SimpleCMS' == $options[0]->category) {
            if (
                $_FILES
                && $_FILES['logo']
                && $_FILES['logo']['name']
            ) {
                $path = \Includes\Utils\FileManager::moveUploadedFile('logo', $dir);

                if ($path) {
                    if ($options[0]->value) {
                        \Includes\Utils\FileManager::deleteFile($dir . $options[0]->value);
                    }

                    $data['logo'] = basename($path);;
                }

            } elseif (\XLite\Core\Request::getInstance()->useDefaultLogo) {
                $data['logo'] = '';
                if ($options[0]->value) {
                   \Includes\Utils\FileManager::deleteFile($dir . $options[0]->value);
                }

            } else {
                $data['logo'] = $options[0]->value;
            }
        }

        parent::setModelProperties($data);
    }
}

