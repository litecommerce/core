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

namespace XLite\Module\CDev\ProductOptions\View;

/**
 * Modify product options
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ModifyProductOptions extends \XLite\View\AView
{
    /**
     * Option groups list (cache)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $options;

    /**
     * Get product id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProductId()
    {
        return $this->getProduct()->getProductId();
    }

    /**
     * Get options groups list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptions()
    {
        if (!isset($this->options)) {
            $this->options = $this->getProduct()->getOptionGroups();
            if (is_object($this->options)) {
                $this->options = $this->options->toArray();
            }
        }

        return $this->options;
    }

    /**
     * Get option group link
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\OptionGroup $option Option group
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptionGroupLink(\XLite\Module\CDev\ProductOptions\Model\OptionGroup $option)
    {
        return $this->buildURL(
            'product',
            '',
            array(
                'page'       => 'product_options',
                'product_id' => $this->getProductId(),
                'groupId'    => $option->getGroupId(),
                'language'   => \XLite\Core\Request::getInstance()->language,
            )
        );
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules/CDev/ProductOptions/style.css';

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
        return 'modules/CDev/ProductOptions/product_options.tpl';
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
            && $this->getProduct();
    }
}
