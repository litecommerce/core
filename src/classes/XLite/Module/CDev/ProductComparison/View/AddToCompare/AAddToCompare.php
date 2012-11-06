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

namespace XLite\Module\CDev\ProductComparison\View\AddToCompare;

/**
 * Add to compare widget
 *
 *
 */
abstract class AAddToCompare extends \XLite\View\Container
{
    /**
     * Checkbox id 
     *
     * @var string
     */
    protected $checkboxId;

    /**
     * Product id 
     *
     * @var string
     */
    protected $productId;

    /**
     * Get checkbox id
     *
     * @param integer $productId Product id
     *
     * @return string
     */
    public function getCheckboxId($productId)
    {
        if (
            !isset($this->checkboxId)
            || $productId != $this->productId
        ) {
            $this->checkboxId = 'product' . rand() . $productId;
        };
        $this->productId = $productId;

        return $this->checkboxId;
    }

    /**
     * Register JS files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/script.js';
        $list[] = 'modules/CDev/ProductComparison/compare/script.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/style.css';
        $list[] = 'modules/CDev/ProductComparison/compare/style.css';

        return $list;
    }

    /**
     * Return default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/body.tpl';
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t(
            'X products selected',
            array(
                'count' => \XLite\Module\CDev\ProductComparison\Core\Data::getInstance()->getProductsCount()
            )
        );
    }
}
