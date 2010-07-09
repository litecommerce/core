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

/**
 * Access selector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WholesaleTrading_View_AccessSelect extends XLite_View_AView
{
    public $field;
    public $formName;
    protected $selectedGroups = null;
    public $allOption = false;
    public $noneOption = false;

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/WholesaleTrading/product_access/access_select.tpl';
    }

    public function getSelectedGroups()
    {
        if (is_null($this->selectedGroups)) {
            $this->selectedGroups = $this->get("component." . $this->field);
        }

        return $this->selectedGroups;
    }
    
    public function setFieldName($name)
    {
        $this->formField = $name;
        $pos = strpos($name, '[');
        $this->field = false === $pos ? $name : substr($name, $pos + 1, -1);
    }

}
