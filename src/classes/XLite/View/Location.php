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
 * @since     1.0.0
 */

namespace XLite\View;

/**
 * Bread crumbs widget
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Location extends \XLite\View\AView
{
    /**
     * Widget param names
     */

    const PARAM_NODES = 'nodes';


    /**
     * Return breadcrumbs 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getNodes()
    {
        $list = array_values($this->getParam(self::PARAM_NODES));

        $list[count($list) - 1]->setWidgetParams(
            array(
                \XLite\View\Location\Node::PARAM_IS_LAST => true,
            )
        );

        return $list;
    }


    /**
     * Get a list of CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'location/location.css';

        return $list;
    }


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'location.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_NODES => new \XLite\Model\WidgetParam\Collection(
                'Breadcrumbs', $this->getLocationPath()
            ),
        );
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && 1 < count($this->getNodes());
    }
}
