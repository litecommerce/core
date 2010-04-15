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
class XLite_Module_InventoryTracking_Controller_Admin_UpdateInventory extends XLite_Controller_Admin_UpdateInventory implements XLite_Base_IDecorator
{
	protected $inventory = null;

    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages["amount"] = "Update amount";
        $this->pageTemplates["amount"] = "modules/InventoryTracking/update_amount.tpl";
    }

    function handleRequestAmount()
    {
        $this->inventory = XLite_Module_InventoryTracking_Model_Inventory::getInstance();
    }

    function export_amount()
    {
        global $DATA_DELIMITERS;

        // save export layout
        $this->action_layout("amount_layout");
        $this->startDownload("product_amount.csv");
        $this->inventory->export($this->amount_layout, $DATA_DELIMITERS[$this->delimiter]);
        exit();
    }

    function import_amount()
    {
        $this->startDump();
        $options = array(
            "file" => $this->getUploadedFile(),
            "layout" => $this->amount_layout,
            "delimiter" => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
			"return_error" => true,
            );
        $this->inventory->import($options);    
		$this->importError = $this->inventory->importError;

		$text = "Import process failed.";
		if (!$this->importError) $text = "Product amount imported successfully.";
		$text = $this->importError.'<br>'.$text.' <a href="admin.php?target=update_inventory&page=amount"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
		exit();
    }
}
