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

namespace XLite\View\LanguagesModify;

/**
 * Confirm language deletion dialog
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ConfirmDeletion extends \XLite\View\AView
{
    /**
     * Widgets parameters 
     */
    const PARAM_LNG_ID = 'lng_id';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'languages/confirm_deletion.tpl';
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
            self::PARAM_LNG_ID => new \XLite\Model\WidgetParam\Int('Language id', null),
        );

        $this->requestParams[] = self::PARAM_LNG_ID;
    }

    /**
     * Get confirm language 
     * 
     * @return \XLite\Model\Language or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getConfirmLanguage()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Language')
            ->find($this->getParam(self::PARAM_LNG_ID));
    }

	/**
	 * Check widget visibility 
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function isVisible()
	{
		return parent::isVisible()
			&& $this->getConfirmLanguage();
	}


}
