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
class XLite_Module_ProductOptions_Controller_Admin_ImportCatalog extends XLite_Controller_Admin_ImportCatalog implements XLite_Base_IDecorator
{
    public function __construct(array $params = array())
    {
        parent::__construct($params);
        $this->pages["import_product_options"] = "Import product options";
        $this->pageTemplates["import_product_options"] = "modules/ProductOptions/import_product_options.tpl";
    }

    function action_import_product_options()
    {
        $this->startDump();
        $options = array(
            "file"              => $this->getUploadedFile(),
            "layout"            => $this->product_options_layout,
            "delimiter"         => $this->delimiter,
            "text_qualifier"    => $this->text_qualifier,
			"return_error"		=> true,
            );
        $p = new XLite_Module_ProductOptions_Model_ProductOption();
        $p->import($options);
		$this->importError = $p->importError;

		$text = "Import process failed.";
		if (!$this->importError) $text = "Product options imported successfully.";
		$text = $this->importError.' <SCRIPT language="javascript"> loaded = true; </SCRIPT> <br>'.$text.' <a href="admin.php?target=import_catalog&page=import_product_options"><u>Click here to return to admin interface</u></a><br><br>';

		echo $text;
		func_refresh_end();
		exit();
    }
}
