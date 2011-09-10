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

namespace XLite\Module\CDev\FeaturedProducts\View\Customer;

/**
 * Featured products widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center.bottom", zone="customer", weight="300")
 */
class FeaturedProducts extends \XLite\View\ItemsList\Product\Customer\Category
{

    /**
     * Featured products
     *
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $featuredProducts = null;

    /**
     * Initialize widget (set attributes)
     *
     * @param array $params Widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setWidgetParams(array $params)
    {
        parent::setWidgetParams($params);

        $this->widgetParams[\XLite\View\Pager\APager::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR]->setValue(false);
        $this->widgetParams[\XLite\View\Pager\APager::PARAM_ITEMS_COUNT]->setValue(5);
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Featured products';
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagerClass()
    {
        return '\XLite\Module\CDev\FeaturedProducts\View\Pager\Pager';
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

        $this->widgetParams[self::PARAM_DISPLAY_MODE]
            ->setValue(\XLite\Core\Config::getInstance()->CDev->FeaturedProducts->featured_products_look);

        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);

        unset($this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]);
        unset($this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]);
    }

    /**
     * Return products list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        if (!isset($this->featuredProducts)) {

            $products = array();

            $fp = \XLite\Core\Database::getRepo('XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct')
                ->getFeaturedProducts($this->getRequestParamValue(self::PARAM_CATEGORY_ID));

            foreach ($fp as $product) {
                $products[] = $product->getProduct();
            }

            $this->featuredProducts = $products;
        }

        return true === $countOnly
            ? count($this->featuredProducts)
            : $this->featuredProducts;
    }

}
