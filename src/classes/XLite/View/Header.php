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

namespace XLite\View;

/**
 * Page header
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Header extends \XLite\View\Container
{
    /**
     * Get head prefixes 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    public static function defineHeadPrefixes()
    {
        return array();
    }

    /**
     * Get meta description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMetaDescription()
    {
        return ($result = \XLite::getController()->getMetaDescription()) 
            ? trim(strip_tags($result)) 
            : $this->getDefaultMetaDescription();
    }

    /**
     * Get default meta description
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDefaultMetaDescription()
    {
        return 'The powerful shopping cart software for web stores and e-commerce '
            . 'enabled stores is based on PHP5 with SQL database with highly '
            . 'configurable implementation based on templates';
    }

    /**
     * Get title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTitle()
    {
        return \XLite::getController()->getPageTitle() ?: $this->getDefaultTitle;
    }

    /**
     * Get default title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDefaultTitle()
    {
        return 'Litecommerce';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'header';
    }

    /**
     * Get collected javascript resources
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getJSResources()
    {
        return static::getRegisteredResources(static::RESOURCE_JS);
    }

    /**
     * Get collected CSS resources
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCSSResources()
    {
        return static::getRegisteredResources(static::RESOURCE_CSS);
    }

    /**
     * Get collected meta tags
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMetaResources()
    {
        return static::getRegisteredMetas();
    }

    /**
     * Get script
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getScript()
    {
        return \XLite::getInstance()->getScript();
    }

    /**
     * Get head tag attributes 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getHeadAttributes()
    {
        $list = array(
            'profile' => 'http://www.w3.org/1999/xhtml/vocab',
        );

        $prefixes = static::defineHeadPrefixes();
        if ($prefixes) {
            $data = array();
            foreach ($prefixes as $name => $uri) {
                $data[] = $name . ': ' . $uri;
            }
            $prefixes = implode(' ', $data);
        }

        if ($prefixes) {
            $list['prefix'] = $prefixes;
        }

        return $list;
    }

    /**
     * Return default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'header/body.tpl';
    }
}
