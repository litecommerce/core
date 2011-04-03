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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\View;

/**
 * Page header
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Header extends \XLite\View\AView
{

    /**
     * Default meta description 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $defaultMetaDescription = 'The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database with highly configurable implementation based on templates';

    /**
     * Default title 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $defaultTitle = 'Litecommerce';

    /**
     * Get meta description 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMetaDescription()
    {
        return method_exists(\XLite::getController(), 'getMetaDescription')
            ? \XLite::getController()->getMetaDescription()
            : $this->t($this->defaultMetaDescription);
    }

    /**
     * Get title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTitle()
    {
        return \XLite::getController()->getPageTitle() ?: $this->t($this->defaultTitle);
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'header.tpl';
    }

    /**
     * Get collected javascript resources 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getJSResources()
    {
        return self::getRegisteredResources(self::RESOURCE_JS);
    }

    /**
     * Get collected CSS resources 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCSSResources()
    {
        $list = array();

        foreach (self::getRegisteredResources(self::RESOURCE_CSS) as $k => $file) {
            if (!isset($file['media']) || !$file['media']) {
                $file['media'] = 'all';
            }

            $list[$k] = $file;
        }

        return $list;
    }

    /**
     * Get script 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getScript()
    {
        return \XLite::getInstance()->getScript();
    }
}

