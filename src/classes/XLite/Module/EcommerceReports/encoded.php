<?php
function func_EcommerceReports_getRawProducts(&$dlg)
{
    if (is_null($dlg->rawProducts)) {
        $dlg->rawProducts = array();
        $product_ids = $dlg->getProductIDs();
        $categories = (array)$dlg->get('selected_categories');
        $products_from_categories = array(); // products from selected categories
        if (!empty($categories)) {
            $ids = implode(",", $categories);
            $pc = new \XLite\Model\ProductFromCategory();
            $productTable = $pc->db->getTableByAlias('products');
            $linkTable = $dlg->db->getTableByAlias('product_links');
            $sql = "SELECT links.product_id " .
                "  FROM $productTable, $linkTable links " . 
                " WHERE $productTable.product_id=links.product_id " .
                "       AND links.category_id IN ($ids)";
            foreach ((array)$pc->db->getAll($sql) as $row) {
                $products_from_categories[] = $row['product_id'];
            }
        }
        $products = array_merge($product_ids, $products_from_categories);
        $products = array_unique($products);
        $dlg->rawProducts = $products;
    }
    return $dlg->rawProducts;
}

function func_EcommerceReports_getRawItems($dlg, $unique=true)
{
    if (is_null($dlg->rawItems)) {
        $dlg->rawItems = array();
        $rawProducts = $dlg->get('rawProducts');
        if (!empty($rawProducts)) {
            $ids = implode(",", $rawProducts);
            $fromDate = $dlg->getComplex('period.fromDate');
            $toDate   = $dlg->getComplex('period.toDate');
            $product = new \XLite\Model\Product();
            $ot = $product->db->getTableByAlias('orders');
            $it = $product->db->getTableByAlias('order_items');
            $pt = $product->db->getTableByAlias('profiles');
            if ($dlg->get('split_options')) {
                $options = ", $it.options ";
            }
            $inCountries = $dlg->getInCountries($pt);
            $inStates = $dlg->getInStates($pt);
            $inCities = $dlg->getInCities($pt);
            $inMembership = $dlg->getInMembership($pt);

            $options = '';

            $sql = "SELECT $it.item_id, $it.order_id, $ot.date, ".
                "          $ot.orig_profile_id, $ot.total, ".
                "          $it.product_id, $it.price, $it.amount, ".
                "          $pt.billing_city, $pt.shipping_city, ".
                "          $pt.billing_country, $pt.billing_state, ". 
                "          $pt.shipping_country, $pt.shipping_state, ". 
                "          $pt.membership ".
                           $dlg->getSelect($ot, $it, $pt).
                "          $options ".
                "     FROM $it, $ot, $pt ".
                           $dlg->getFrom($ot, $it, $pt).
                "    WHERE $it.order_id=$ot.order_id ".
                "          AND ($ot.status='C' OR $ot.status='P') ".
                "          AND $ot.date BETWEEN $fromDate AND $toDate ".
                "          AND $it.product_id IN ($ids) " .
                "          AND $pt.profile_id=$ot.profile_id " .
                "          $inCities $inCountries $inStates $inMembership ".
                           $dlg->getWhere($ot, $it, $pt);

                if ($unique) {
                    $sql .= " GROUP BY $it.item_id";
                }

            $dlg->rawItems = (array)$product->db->getAll($sql);
        }
    }
    return $dlg->rawItems;
}
?>
