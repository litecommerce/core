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

namespace XLite\Module\CDev\SimpleCMS\View\FormField\Input\Text;

/**
 * Clean URL
 *
 */
class CleanURL extends \XLite\View\FormField\Input\Text
{
    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return '/clean_url.tpl';
    }

    /**
     * getDir
     *
     * @return string
     */
    protected function getDir()
    {
        return 'modules/CDev/SimpleCMS/page/parts';
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/clean_url.js';

        return $list;
    }

    /**
     * Check field validity
     *
     * @return boolean
     */
    protected function checkFieldValidity()
    {
        $result = parent::checkFieldValidity();

        if (
            $result
            && $this->getValue()
        ) {
            $validator = new \XLite\Core\Validator\String\CleanURL(
                false,
                null,
                '\XLite\Module\CDev\SimpleCMS\Model\Page',
                \XLite\Core\Request::getInstance()->id
            );
            try {
                $validator->validate($this->getValue());
            } catch (\XLite\Core\Validator\Exception $exception) {
                $message = static::t($exception->getMessage(), $exception->getLabelArguments());
                $result = false;
                $this->errorMessage = \XLite\Core\Translation::lbl(
                    ($exception->getPublicName() ? static::t($exception->getPublicName()) . ': ' : '') . $message,
                    array(
                        'name' => $this->getLabel(),
                    )
                );
            }
        }

        return $result;
    }

}
