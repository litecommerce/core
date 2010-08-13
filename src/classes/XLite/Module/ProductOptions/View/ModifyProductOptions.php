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

namespace XLite\Module\ProductOptions\View;

/**
 * Modify product options 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ModifyProductOptions extends \XLite\View\AView
{
    /**
     *  Widget parameters
     */
    const PARAM_PRODUCT = 'product';


    /**
     * Option groups list (cache)
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $options;

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
            self::PARAM_PRODUCT => new \XLite\Model\WidgetParam\Object(
                'Product',
                \XLite\Core\Database::getRepo('XLite\Model\Product')->find(
                    \XLite\Core\Request::getInstance()->product_id
                ),
                false,
                '\XLite\Model\Product'
            ),
        );
    }

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/product_options.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_PRODUCT);
    }

    /**
     * Get product id 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductId()
    {
        return $this->getParam(self::PARAM_PRODUCT)->getProductId();
    }

    /**
     * Get options groups list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions()
    {
        if (!isset($this->options)) {
            $this->options = $this->getParam(self::PARAM_PRODUCT)->getOptionGroups();
            if (is_object($this->options)) {
                $this->options = $this->options->toArray();
            }
        }

        return $this->options;
    }

    /**
     * Get option group link 
     * 
     * @param \XLite\Module\ProductOptions\Model\OptionGroup $option Option group
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptionGroupLink(\XLite\Module\ProductOptions\Model\OptionGroup $option)
    {
        return $this->buildUrl(
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/ProductOptions/style.css';

        return $list;
    }

}
