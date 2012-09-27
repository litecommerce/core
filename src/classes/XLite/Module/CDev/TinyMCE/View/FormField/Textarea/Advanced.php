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

namespace XLite\Module\CDev\TinyMCE\View\FormField\Textarea;

/**
 * TinyMCE textarea widget
 *
 */
class Advanced extends \XLite\View\FormField\Textarea\Advanced implements \XLite\Base\IDecorator
{
    /**
     * getJSFiles
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/js/tinymce/tiny_mce.js';
        $list[] = $this->getDir() . '/js/tinymce/jquery.tinymce.js';
        $list[] = $this->getDir() . '/js/script.js';

        return $list;
    }

    /**
     * Return CSS files for this widget
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/css/style.css';

        return $list;
    }


    /**
     * getFieldTemplate
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return '/form_field/textarea.tpl';
    }


    /**
     * getDir
     *
     * @return string
     */
    protected function getDir()
    {
        return 'modules/CDev/TinyMCE';
    }

    /**
     * Return structure of configuration for JS TinyMCE library
     *
     * @return array
     */
    protected function getTinyMCEConfiguration()
    {
        // Base is the web path to the tinymce library directory
        return array(
            'shopURL' => \XLite::getInstance()->getShopURL(),
            'shopURLRoot' => \XLite\Model\Category::WEB_LC_ROOT,
            'base' => dirname(\XLite\Singletons::$handler->layout->getResourceWebPath(
                $this->getDir() . '/js/tinymce/tiny_mce.js',
                \XLite\Core\Layout::WEB_PATH_OUTPUT_URL
            )) . '/',
        );
    }
}
