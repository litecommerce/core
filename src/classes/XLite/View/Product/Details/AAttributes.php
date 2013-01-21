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

namespace XLite\View\Product\Details;

/**
 * Product attributes 
 *
 */
abstract class AAttributes extends \XLite\View\AView
{
    /**
     * Widget param names
     */
    const PARAM_GROUP = 'group';

    /**
     * Get step title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttributeGroup()
            ? $this->getAttributeGroup()->getName()
            : null;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getAttributesList(true);
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_GROUP => new \XLite\Model\WidgetParam\Object(
                'Group', null, false,  '\XLite\Model\AttributeGroup'
            ),
        );
    }

    /**
     * Get attribute group
     *
     * @return \XLite\Model\AttributeGroup
     */
    protected function getAttributeGroup()
    {
        return $this->getParam(static::PARAM_GROUP);
    }

    /**
     * Get attributes list
     *
     * @param boolean $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     */
    protected function getAttributesList($countOnly = false)
    {
        $cnd = new \XLite\Core\CommonCell;
        $cnd->attributeGroup = $this->getAttributeGroup();
        if (!$this->getAttributeGroup()) {
            $cnd->product = $this->getProduct();
        }

        return \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->search($cnd, $countOnly);
    }

}
