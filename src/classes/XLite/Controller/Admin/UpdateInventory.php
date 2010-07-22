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

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class UpdateInventory extends AAdmin
{
    public $params = array('target', 'page');
    public $pages = array('pricing' => 'Update pricing');
    public $pageTemplates = array('pricing' => 'product/update_inventory.tpl');
    public $page = "pricing";

    protected $inventory = null;

    function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            @set_time_limit(0);
        }
        $handler = "handleRequest$this->page";
        method_exists($this, $handler) or die("undefined handler $handler");
        $this->$handler();

        if ($this->action == "import" && !$this->checkUploadedFile()) {
        	$this->set('valid', false);
        	$this->set('invalid_file', true);
        }

        parent::handleRequest();
    }

    function handleRequestPricing()
    {
        if (!isset($this->inventory)) {
            $this->inventory = new \XLite\Model\ProductInventory();
        }
    }

    function action_export()
    {
        $method = "export_$this->what";
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    function action_layout($layout)
    {
        // save/update layout
        $dlg = new \XLite\Controller\Admin\ImportCatalog();
        $dlg->action_layout($layout);
    }

    function export_pricing()
    {
        global $DATA_DELIMITERS;
        
        // save layout
        $this->action_layout('inventory_layout');
        // export
        $this->startDownload('product_pricing.csv');
        $this->inventory->export($this->inventory_layout, $DATA_DELIMITERS[$this->delimiter]);
        exit();
    }

    function action_import()
    {
        $method = "import_$this->what";
        if (method_exists($this, $method)) {
            $this->$method();
        }
    }

    function import_pricing()
    {
        $this->startDump();
        $options['file'] =  $this->getUploadedFile();
        $options['delimiter'] = $this->delimiter;
        $options['text_qualifier'] = $this->text_qualifier;
        $options['layout'] = $this->inventory_layout;
        $options['return_error'] = true;
        $this->inventory->import($options);
        $this->importError = $this->inventory->importError;

        $text = "Import process failed.";
        if (!$this->importError) $text = "Product pricing imported successfully.";
        $text = $this->importError.'<br>'.$text.' <a href="admin.php?target=update_inventory"><u>Click here to return to admin interface</u></a><br><br>';

        echo $text;
        func_refresh_end();
        exit();
    }
}
