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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\ItemsList\Module;

/**
 * Abstract product list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AModule extends \XLite\View\ItemsList\AItemsList
{
    /**
     * Allowed sort criterions
     */

    const SORT_BY_MODE_NAME    = 'm.moduleName';
    const SORT_BY_MODE_POPULAR = 'm.downloads';
    const SORT_BY_MODE_RATING  = 'm.rating';
    const SORT_BY_MODE_DATE    = 'm.date';
    const SORT_BY_MODE_ENABLED = 'm.enabled';

    /**
     * Widget param names 
     */

    const PARAM_SUBSTRING    = 'substring';
    const PARAM_TAG          = 'tag';
    const PARAM_PRICE_FILTER = 'priceFilter';
    const PARAM_STATUS       = 'status';

    /**
     * Return name of the base widgets list
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.module';
    }

    /**
     * Get widget templates directory
     * NOTE: do not use "$this" pointer here (see "get[CSS/JS]Files()")
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return parent::getDir() . LC_DS . 'module';
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPageBodyDir()
    {
        return null;
    }

    /**
     * Return list of the modes allowed by default
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModes()
    {
        $list = parent::getDefaultModes();

        return $list;
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        $result->{\XLite\Model\Repo\Module::P_ORDER_BY} = array($this->getSortBy(), $this->getSortOrder());
        $result->{self::PARAM_STATUS} = \XLite\Model\Module::EXISTS;

        return $result;
    }

    /**
     * getJSHandlerClassName
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getJSHandlerClassName()
    {
        return 'ModulesList';
    }

    /**
     * Check if the module can be enabled
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function canEnable(\XLite\Model\Module $module)
    {
        return $module->getEnabled()
            || $module->canEnable();
    }

    /**
     * Check if the module can be installed
     * 
     * @param \XLite\Model\Module $module Module
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function canInstall(\XLite\Model\Module $module)
    {
        return !$module->getInstalled() && ($module->getPurchased() || $module->isFree());
    }

    /**
     * Check if the module can be installed
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function canPurchase(\XLite\Model\Module $module)
    {
        return !$module->getInstalled() && !$module->getPurchased() && !$module->isFree();
    }

    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array())
    {
        $this->sortByModes += array(
            self::SORT_BY_MODE_NAME      => 'Name',
            self::SORT_BY_MODE_POPULAR   => 'Popular',
            self::SORT_BY_MODE_RATING    => 'Most rated',
            self::SORT_BY_MODE_DATE      => 'Newest',
            self::SORT_BY_MODE_ENABLED   => 'Enabled',
        );

        parent::__construct($params);
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();
    
        $this->widgetParams += array(
            self::PARAM_SUBSTRING    => new \XLite\Model\WidgetParam\String(
                'Substring', ''
            ),
            self::PARAM_TAG          => new \XLite\Model\WidgetParam\String(
                'Tag', ''
            ),
            self::PARAM_PRICE_FILTER => new \XLite\Model\WidgetParam\String(
                'Price filter', ''
            ),
        );
    }

    /**
     * Return modules list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')
            ->search($cnd, $countOnly);
    }

    /**
     * Return installed module property
     *
     * @param \XLite\Model\Module $module   Module
     * @param string              $property Module property
     *
     * @return string|array
     * @access protected
     * @since  3.0.0
     */
    protected function getInstalledProperty(\XLite\Model\Module $module, $property = '')
    {
        return $module->__call('get' . \XLite\Core\Converter::convertToCamelCase($property));
    }

}
