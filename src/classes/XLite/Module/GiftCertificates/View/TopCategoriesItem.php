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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Gift certificate item for Top categories list
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @ListChild (class="XLite_View_TopCategories", list="childs")
 */
class XLite_Module_GiftCertificates_View_TopCategoriesItem extends Xlite_View_Abstract
{
    const PARAM_IS_SUBTREE   = 'is_subtree';

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
            self::PARAM_IS_SUBTREE => new XLite_Model_WidgetParam_Bool(
                'Is subtree', false, false
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
		return 'modules/GiftCertificates/top_categories_item.tpl';
	}

	/**
	 * Assemble Gift certificate link class name 
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function assembleGiftLinkClassName()
	{
		return 'gift_certificate' == XLite_Core_Request::getInstance()->target
			? 'active'
			: '';
	}

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
		return parent::isVisible()
			&& !$this->getParam(self::PARAM_IS_SUBTREE);
	}

}

