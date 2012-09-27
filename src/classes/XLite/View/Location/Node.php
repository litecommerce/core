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

namespace XLite\View\Location;

/**
 * Node
 *
 */
class Node extends \XLite\View\AView
{
    /**
     * Widget param names
     */
    const PARAM_NAME     = 'name';
    const PARAM_LINK     = 'list';
    const PARAM_SUBNODES = 'subnodes';
    const PARAM_IS_LAST  = 'last';

    /**
     * Static method to create nodes in controller classes
     *
     * @param string $name     Node title
     * @param string $link     Node link OPTIONAL
     * @param array  $subnodes Node subnodes OPTIONAL
     *
     * @return object
     */
    public static function create($name, $link = null, array $subnodes = null)
    {
        return new static(
            array(
                self::PARAM_NAME     => $name,
                self::PARAM_LINK     => $link,
                self::PARAM_SUBNODES => $subnodes,
            )
        );
    }

    /**
     * Check - node is last in nodes list or not
     *
     * @return boolean
     */
    public function isLast()
    {
        return $this->getParam(self::PARAM_IS_LAST);
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'location/node.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_NAME     => new \XLite\Model\WidgetParam\String('Name', ''),
            self::PARAM_LINK     => new \XLite\Model\WidgetParam\String('Link', ''),
            self::PARAM_SUBNODES => new \XLite\Model\WidgetParam\Collection('Subnodes', array()),
            self::PARAM_IS_LAST  => new \XLite\Model\WidgetParam\Bool('Is last', false),
        );
    }

    /**
     * Get node name
     *
     * @return string
     */
    protected function getName()
    {
        return $this->getParam(self::PARAM_NAME);
    }

    /**
     * Get link URL
     *
     * @return string
     */
    protected function getLink()
    {
        return $this->getParam(self::PARAM_LINK);
    }

    /**
     * Get subnodes
     *
     * @return array
     */
    protected function getSubnodes()
    {
        return $this->getParam(self::PARAM_SUBNODES);
    }
}
