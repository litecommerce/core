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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_Egoods_Controller_Admin_ExportCatalog extends XLite_Controller_Admin_ExportCatalog implements XLite_Base_IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["export_pin_codes"] = "Export PIN codes";
        $this->pageTemplates["export_pin_codes"] = "modules/Egoods/export_pin_codes.tpl";
    }

    function action_export_pin_codes()
    {
        global $DATA_DELIMITERS;

        // save layout & export
        $dlg = new XLite_Controller_Admin_ImportCatalog();
        $dlg->action_layout("pin_codes_layout");
        $this->startDownload("pin_codes.csv");
        $wp = new XLite_Module_Egoods_Model_PinCode();
        $wp->export($this->pin_codes_layout, $DATA_DELIMITERS[$this->delimiter], $where = null, $orderby = "product_id,pin_id");
        exit();
    }
}
