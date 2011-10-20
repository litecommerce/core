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
 * @since     1.0.9
 */

namespace XLite\View;

/**
 * Labels 
 *
 * @see   ____class_see____
 * @since 1.0.9
 */
class Labels extends \XLite\View\AView
{
    /**
     * Widget param names
     */
    const PARAM_LABELS = 'labels';

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.9
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/style.css';

        return $list;
    }

    /**
     * Get name of the working directory
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getDir()
    {
        return 'labels';
    }

    /**
     * Return widget template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getLabels();
    }

    /**
     * Alias
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function getLabels()
    {
        return $this->getParam(static::PARAM_LABELS);
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.9
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_LABELS => new \XLite\Model\WidgetParam\Collection('Labels', array()),
        );
    }
}
