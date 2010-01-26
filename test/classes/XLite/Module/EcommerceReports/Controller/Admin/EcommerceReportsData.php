<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* @package Module_EcommerceReports
* @access public
* @version $Id$
*/
class XLite_Module_EcommerceReports_Controller_Admin_EcommerceReportsData extends XLite_Module_EcommerceReports_Controller_Admin_SalesDynamics
{	
    public $type = "bars"; // type := bars | lines	
    public $limit = 50;    // limit for type "bars"	
    public $signature = "Sales dynamics";

    function init() 
    {
        parent::init();
        // get request data from session
        $salesDynamics = $this->getComplex('session.salesDynamics');
        if (is_array($salesDynamics) && !empty($salesDynamics)) {
            $this->mapRequest($salesDynamics);
        }
    }
    
    function handleRequest()
    {
        // $this->genOrders();

        set_time_limit(180);
        $data = $this->get("sales");
        $data["signature"] = $this->signature;
        $data["type"]      = count($data["x"]) > $this->limit? "lines" : $this->type;
        $result = array();
        $result[] = (object)$data;
        print "&falshData=" . urlencode(serialize($result)) . "&";
        exit();
    }

    function genOrders() // {{{
    {
        set_time_limit(0);
        ini_set("memory_limit", "64M");

        func_refresh_start();

        echo "Generating test orders...<br>";

        // $date = mktime(11, 20, 0, 9, 2, 2002);
        $date = mktime(11, 20, 0, 1, 5, 2004);
        $end = mktime(16, 15, 0, 10, 6, 2004);
        $maxOrders = 10;
        $maxItems = 3;
        $maxAmount = 3;
        $totalProducts = 99;

        // init randomizer
        // seed with microseconds
        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));

        // build cloud of product IDs
        $p = new XLite_Model_Product();
        // get min price and total products cost
		$table = $this->db->getTableByAlias("products");
        $minPrice = ceil($p->db->getOne("SELECT MIN(price) FROM $table"));
        $maxPrice = ceil($p->db->getOne("SELECT MAX(price) FROM $table"));
        $sumPrice = ceil($p->db->getOne("SELECT SUM(price) FROM $table"));
        // fetch all products
        $products = $p->db->getAll("SELECT product_id, price FROM $table ORDER BY price");
        // calculate weighted index for each product
        $sumIndex = 0;
        $maxIndex = 0;
        $minIndex = 99999999999;
        foreach ($products as $id => $info) {
            $products[$id]["index"] = $maxIndex + ceil($maxPrice / $products[$id]["price"]);
            $maxIndex = max($maxIndex, $products[$id]["index"]);
            $minIndex = min($minIndex, $products[$id]["index"]);
        }
        do {
            // seed with microseconds
            list($usec, $sec) = explode(' ', microtime());
            srand((float) $sec + ((float) $usec * 100000));
            $numOrders = mt_rand(0, $maxOrders);

            echo "Building $numOrders orders for date <b>". date("r", $date) ."</b><br>";
            for ($i = 0; $i < $numOrders; $i++) {
            
                echo "Order #$i ";

                // create order
                $order = new XLite_Model_Order();
                $order->set("date", $date);
                $order->set("status", "P");

                $numItems = mt_rand(1, $maxItems);
                echo " ($numItems items in order) .. ";

                for ($k = 0; $k < $numItems; $k++) {
                    // select random product
                    // seed with microseconds
                    list($usec, $sec) = explode(' ', microtime());
                    srand((float) $sec + ((float) $usec * 100000));
                    $index = mt_rand($minIndex, $maxIndex);
                    foreach ($products as $info) {
                        if ($index > $info["index"]) {
                            continue;
                        } else {
                            $product_id = $info["product_id"];
                            break;
                        }
                    }
                    // create product
                    $product = new XLite_Model_Product($product_id);
                    // product QTY
                    // seed with microseconds
                    list($usec, $sec) = explode(' ', microtime());
                    srand((float) $sec + ((float) $usec * 100000));
                    $amount = mt_rand(1, $maxAmount);
                    if ($product->get("price") > 100) {
                        $amount = 1; // no more that 1 expensive product
                    }
                    // create order item
                    $item = new XLite_Model_OrderItem();
                    $item->set("product", $product);
                    $item->set("amount", $amount);

                    // add item to order
                    $order->addItem($item);
                }

                // finally, update order
                $order->calcTotals();
                $order->update();

                // create order's profile
                // default profile
                $profile = new XLite_Model_Profile(1);
                $op = new XLite_Model_Profile();
                $properties = $profile->get("properties");
                if (isset($properties["profile_id"])) {
                	unset($properties["profile_id"]);
                }
                $op->set("properties", $properties);
                $op->set("order_id", $order->get("order_id"));
                $op->create();

                // link order to profile
                $order->set("profile_id", $op->get("profile_id"));
                $order->set("orig_profile_id", $profile->get("profile_id"));
                $order->update();

                echo " finished<br>";
            }

            $date += 86400;
        } while ($date <= $end);

        echo "DONE<BR>";

        func_refresh_end();

        exit();
    } // }}}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
