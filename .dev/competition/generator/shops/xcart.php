<?php
// vim: set ts=4 sw=4 sts=4 et:
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
 * @package    Comparison
 * @subpackage Generator
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

set_time_limit(86400);
$_SERVER['HTTP_HOST'] = '';
$_SERVER['SERVER_PORT'] = 80;

require_once './top.inc.php';
require_once './init.php';

$config = __g_config();

mt_srand(microtime(true) * 1000000);
$current_area = 'C';
$store_language = 'US';
$current_provider = 'provider';

# Delete old data
__g_echo_title('Delete old data ');

$exist = func_query_column('SHOW TABLES');
$tbls = array(
    'products',
    'categories',
    'categories_lng',
    'categories_subcount',
    'category_memberships',
    'memberships',
    'manufacturers',
    'class_lng',
    'class_options',
    'classes',
    'discounts',
    'download_keys',
    'extra_fields',
    'extra_field_values',
    'feature_classes',
    'feature_classes_lng',
    'feature_options',
    'feature_options_lng',
    'featured_products',
    'giftcerts',
    'images_C',
    'images_T',
    'images_P',
    'images_D',
    'images_F',
    'images_W',
    'images_M',
    'order_details',
    'orders',
    'order_extras',
    'pconf_class_requirements',
    'pconf_class_specifications',
    'pconf_product_types',
    'pconf_products_classes',
    'pconf_slot_markups',
    'pconf_slot_rules',
    'pconf_slots',
    'pconf_specifications',
    'pconf_wizards',
    'pricing',
    'product_bookmarks',
    'product_features',
    'product_foptions',
    'product_links',
    'product_memberships',
    'product_options_ex',
    'product_options_js',
    'product_options_lng',
    'product_reviews',
    'product_taxes',
    'product_votes',
    'products',
    'products_categories',
    'products_lng',
    'returns',
    'shipping_rates',
    'subscriptions',
    'subscription_customers',
    'tax_rate_memberships',
    'tax_rates',
    'taxes',
    'variant_items',
    'variants',
    'wishlist',
    'zone_element',
    'zones',
    'partner_product_commissions',
    'partner_payment',
    'partner_views',
    'partner_plans_commissions',
    'partner_clicks',
    'partner_adv_orders',
);  

foreach ($tbls as $tbl) {
    if (!in_array('xcart_' . $tbl, $exist)) {
        db_query('TRUNCATE ' . $sql_tbl[$tbl]);
        __g_inc();
    }
}

db_query('DELETE FROM ' . $sql_tbl['customers'] . ' WHERE login NOT IN (\'admin\', \'master\', \'provider\')');
    
__g_echo_done();

/**
 * Create user
 */
__g_echo_title('Create Customers ');
db_query('DELETE FROM ' . $sql_tbl['customers'] . ' WHERE usertype = \'C\' AND login LIKE \'customer_%\'');
for ($x = 0; $x < $config['options']['customers']; $x++) {
    __g_xcart_array2insert(
        'customers',
        array(
            'id'          => 1000 + $x,
            'login'       => 'customer_' . $x,
            'usertype'    => 'C',
            'password'    => 'Sedfjggghgpfjfkfrgr',
            'email'       => 'bit-bucket@x-cart.com',
            'status'      => 'Y',
            'last_login'  => time() - 43200,
            'first_login' => time() - 86400,    
            'language'    => 'en', 
            'activity'    => 'Y',
        )
    );
}
__g_echo_done();

/**
 * Create Extra fields
 */
__g_echo_title('Create Extra fields ');
for($x = 0; $x < $config['options']['extra_fields']; $x++) {
    __g_xcart_array2insert(
        'extra_fields',
        array(
            'fieldid'      => 1000 + $x,
            'provider'     => $current_provider,
            'field'        => 'Test EF ' . $x,
            'service_name' => 'TST' . $x,
        )
    );
}
__g_echo_done();

/**
 * Create Memberships
 */
__g_echo_title('Create Memberships ');
for($x = 0; $x < $config['options']['memberships']; $x++) {
    __g_xcart_array2insert(
        'memberships',
        array(
            'membershipid' => 1000 + $x,
            'membership'   => 'Test membership ' . $x,
            'area'         => 'C',
            'active'       => 'Y',
            'orderby'      => $x,
        )
    );
}   
__g_echo_done();

/**
 * Create Manufacturers
 */
__g_echo_title('Create Manufacturers ');
for ($x = 0; $x < $config['options']['manufacturers']; $x++) {
    __g_xcart_array2insert(
        'manufacturers',
        array(
            'manufacturerid' => 1000 + $x,
            'manufacturer'   => 'Test manufacturer ' . $x,
            'provider'       => $current_provider,
            'descr'          => 'Test manufacturer ' . $x,
            'avail'          => 'Y',
            'orderby'        => $x,
        )
    );

    if ($config['options']['manufacturer_image']) {
        __g_xcart_add_image('M', 1000 + $x);
    }
}   
__g_echo_done();

# Create Taxes
__g_echo_title('Create Taxes ');
for ($x = 0; $x < $config['options']['taxes'] && 0 < $config['options']['tax_rates']; $x++) {
    // $is_vat = (round(mt_rand(1,2), 0) == 1) ? 'VAT' : 'TAX';
    $is_vat = 'TAX';

    __g_xcart_array2insert(
        'taxes',
        array(
            'taxid'                 => 1000 + $x,
            'tax_name'              => $is_vat . '_' . $x,
            'formula'               => 'DST+SH',
            'address_type'          => 'B',
            'active'                => 'Y',
            'price_includes_tax'    => 'N',
            'display_including_tax' => 'N',
            'display_info'          => 'A',
            'regnumber'             => 'REG' . $is_vat . '_' . $x,
            'priority'              => $x,
        )
    );

    $taxid = 1000 + $x;
    for ($y = 0; $y < $config['options']['tax_rates']; $y++) {
        $is_percent = 1 == round(mt_rand(1, 2), 0) ? '%' : '$';
        __g_xcart_array2insert(
            'tax_rates',
            array(
                'taxid'      => $taxid,
                'zoneid'     => 0,
                'formula'    => '',
                'rate_value' => round(mt_rand(1, 20), 2),
                'rate_type'  => $is_percent,
                'provider'   => $current_provider,
            )
        );
    }
}
__g_echo_done();

/**
 * Create Shipping rates
 */
__g_echo_title('Create Shipping rates ');
$ids = func_query_column('SELECT shippingid FROM ' . $sql_tbl['shipping'] . '');

for ($x = 0; $x < $config['options']['shippings'] && $ids; $x++) {

    $id = round(rand(0, count($ids)), 0);
    __g_xcart_array2insert(
        'shipping_rates',
        array(
            'rateid'      => 100000 + $x,
            'shippingid'  => $id,
            'zoneid'      => 0,
            'rate'        => round(mt_rand(1, 10), 2),
            'item_rate'   => round(mt_rand(1, 10), 2),
            'weight_rate' => round(mt_rand(1, 10), 2),
            'rate_p'      => round(mt_rand(1, 10), 2),
            'provider'    => $current_provider,
        )
    );
}
__g_echo_done();

/**
 * Create Product features
 */
__g_echo_title('Create Product features ');
for ($x = 0; $x < $config['options']['feature_classes'] && 0 < $config['options']['feature_options_per_class']; $x++) {

    __g_xcart_array2insert(
        'feature_classes',
        array(
            'fclassid' => 1000 + $x,
            'class'    => 'Feature class ' . $x,
            'orderby'  => $x,
            'provider' => $current_provider,
        )
    );
    $fclassid = 1000 + $x;

    __g_xcart_array2insert(
        'feature_classes_lng',
        array(
            'fclassid' => $fclassid,
            'code'     => 'en',
            'class'    => 'Feature class ' . $x . ' (en)',
        )
    );

    # Feature options
    for ($y = 0; $y < $config['options']['feature_options_per_class']; $y++) {
        __g_xcart_array2insert(
            'feature_options',
            array(
                'foptionid'   => $x * 1000 + $y,
                'fclassid'    => $fclassid,
                'option_name' => 'Feature option ' . $x . ' / ' . $y,
                'option_type' => 'T',
                'orderby'     => $y,
            )
        );

        __g_xcart_array2insert(
            'feature_options_lng',
            array(
                'foptionid'   => $x * 1000 + $y,
                'code'        => 'en',
                'option_name' => 'Feature option ' . $x . ' / ' . $y . ' (en)',
            )
        );

    }

    # Feature image
    if ($config['options']['feature_class_image']) {
        __g_xcart_add_image('F', $fclassid);
    }
}
__g_echo_done();

# Create Categories
$c_count = 0;
__g_echo_title('Create Categories ');
x_load('category');
db_query('START TRANSACTION');
__g_xcart_add_category();
db_query('COMMIT');
__g_echo_done();

# Create Products
__g_echo_title('Create Products ');
$res = db_query('SELECT categoryid FROM ' . $sql_tbl['categories'] . '');
if ($res) {
    while ($cid = db_fetch_array($res)) {
        db_query('START TRANSACTION');
        __g_xcart_add_products($cid['categoryid']);
        db_query('COMMIT');
    }
    db_free_result($res);
}
__g_echo_done();

###############################################################################
#
# Service functions
#

#
# Add category (recursive)
#
function __g_xcart_add_category($level = 1, $parentid = 0) {
    global $config, $sql_tbl;

    for($c = 0; $c < $config['options']['categories']; $c++) {

        $cid = func_insert_category($parentid);

        func_array2update(
            'categories',
            array(
                'category'    => 'Category ' . $c . ' / ' . $parentid,
                'description' => 'Category description ' . $c . ' / ' . $parentid,
            ),
            'categoryid = ' . $cid
        );

        # Multilanguage names
        __g_xcart_array2insert(
            'categories_lng',
            array(
                'categoryid' => $cid,
                'code' => 'en',
                'category'    => 'Category ' . $c . ' / ' . $parentid . ' (en)',
                'description' => 'Category description ' . $c . ' / ' . $parentid . ' (en)',
            )
        );
        
        # Category icon
        if ($config['options']['category_image']) {
            __g_xcart_add_image('C', $cid);
        }

        # Create child categories
        if ($level > $config['options']['categories_deep_limit']) {
            __g_xcart_add_category($level + 1, $cid);
        }

        if (
            round(mt_rand(0, 1), 1) < $config['options']['category_featured_owner_ratio']
            && 0 < $config['options']['featured_products']
        ) {

            $res = db_query('SELECT productid FROM ' . $sql_tbl['products'] . ' WHERE ROUND(POW(RAND(NOW()+0),2), 1) > 0.5 LIMIT '.intval($config['options']['featured_products']));
            if ($res) {
                while ($pid = db_fetch_array($res)) {
                    __g_xcart_array2insert(
                        'featured_products',
                        array(
                            'categoryid' => $cid,
                            'productid'  => $pid['productid'],
                        )
                    );
                }
                db_free_result($res);
            }

        }

    }
}

#
# Add product
#
function __g_xcart_add_products($cid) {
    global $config, $sql_tbl, $current_provider;

    for ($x = 0; $x < $config['options']['products']; $x++) {
        $price = round(mt_rand(1, 200), 2);
        $id = 1000 * $cid + $x;
        __g_xcart_array2insert(
            'products',
            array(
                'productid'   => $id,
                'productcode' => 'SKU ' . $id,
                'product'     => 'Product ' . $cid . ' - ' . $id,
                'provider'    => $current_provider,
                'weight'      => round(mt_rand(1, 10), 0),
                'list_price'  => $price + round(mt_rand(1,10), 2),
                'descr'       => 'Product description ' . $cid . ' - ' . $id,
                'fulldescr'   => 'Product full description ' . $cid . ' - ' . $id,
                'add_date'    => time(),
                'return_time' => 7,
                'avail'       => 'Y',
                
            )
        );

        __g_xcart_array2insert(
            'pricing',
            array(
                'productid' => $id,
                'price'     => $price,
                'quantity'  => 1,
            )
        );

        __g_xcart_array2insert(
            'products_categories',
            array(
                'productid'  => $id,
                'categoryid' => $cid,
                'main'       => 'Y',
                'orderby'    => 0,
            )
        );

        __g_xcart_array2insert(
            'products_lng',
            array(
                'productid' => $id,
                'code'      => 'en',
                'product'   => 'Product ' . $cid . ' - ' . $id . ' (en)',
                'descr'     => 'Product description ' . $cid . ' - ' . $id . ' (en)',
                'fulldescr' => 'Product full description ' . $cid . ' - ' . $id . ' (en)',
            )
        );

        # Wholesale prices
        for ($y = 0; $y < $config['options']['wholesale_prices']; $y++) {
            __g_xcart_array2insert(
                'pricing',
                array(
                    'productid' => $id,
                    'price'     => round(rand(1, $price * 0.75), 2),
                    'quantity'  => ($y + 1) * 10,
                )
            );
        }

        # Reviews
        for ($y = 0; $y < $config['options']['reviews']; $y++) {
            __g_xcart_array2insert(
                'product_reviews',
                array(
                    'productid' => $id,
                    'remote_ip' => '127.0.0.1',
                    'email'     => 'bit-bucket@x-cart.com',
                    'message'   => 'Review ' . $y . ' (for product #' . $id . ')',
                )
            );
        }

        # Votes
        for ($y = 0; $y < $config['options']['votes']; $y++) {
            __g_xcart_array2insert(
                'product_votes',
                array(
                    'productid'  => $id,
                    'remote_ip'  => '127.0.0.1',
                    'vote_value' => round(mt_rand(1, 5), 0),
                ) 
            );
        }

        # Extra fields
        if ($config['options']['extra_fields'] > 0) {
            $ef = db_query('SELECT fieldid FROM ' . $sql_tbl['extra_fields'] . '');
            if ($ef) {
                while ($fid = db_fetch_array($ef)) {
                    __g_xcart_array2insert(
                        'extra_field_values',
                        array(
                            'productid' => $id,
                            'fieldid'   => $fid['fieldid'],
                            'value'     => 'EF ' . $fid['fieldid'] . ' value',
                        ) 
                    );
                }
                db_free_result($ef);
            }
        }

        # Thumbnail
        if ($config['options']['product_thumbnail']) {
            __g_xcart_add_image('T', $id);
        }

        # Product image
        if ($config['options']['product_image']) {
            __g_xcart_add_image('P', $id);
        }

        # Product feature values
        if (round(mt_rand(0, 1), 1) < $config['options']['product_feature_owner_ratio']) {
            $res = db_query('SELECT fclassid, foptionid FROM ' . $sql_tbl['feature_options'] . '');
            if ($res) {
                $flag = false;
                while ($fid = db_fetch_array($res)) {
                    if (!$flag) {
                        __g_xcart_array2insert(
                            'product_features',
                            array(
                                'productid' => $id,
                                'fclassid'  => $fid['fclassid'],
                            )
                        );
                        $flag = true;
                    }
                    __g_xcart_array2insert(
                        'product_foptions',
                        array(
                            'productid' => $id,
                            'foptionid' => $fid['foptionid'],
                            'value'     => 'Test value',
                        )
                    );
                }
                db_free_result($res);
            }
        }

        # Add Product options
        if (
            round(mt_rand(0, 1), 1) < $config['options']['product_options_owner_ratio']
            && 0 < $config['options']['option_groups']
            && 0 < $config['options']['options']
        ) {
            for ($y = 0; $y < $config['options']['option_groups']; $y++) {

                $classid = 1000 * $id + $y;
                __g_xcart_array2insert(
                    'classes',
                    array(
                        'classid'   => $classid,
                        'productid' => $id,
                        'class'     => 'POption_' . $y,
                        'classtext' => 'Product option #' . $y,
                        'orderby' => $y,
                    )
                );

                # Add option values
                for ($z = 0; $z < $config['options']['options']; $z++) {
                    $optionid = 1000 * $classid + $z;
                    __g_xcart_array2insert(
                        'class_options',
                        array(
                            'optionid'    => $optionid,
                            'classid'     => $classid,
                            'option_name' => 'Option ' . $y . ' / ' . $z,
                            'orderby'     => $z,
                            'price_modifier' => round(mt_rand(0, $price / 4), 2),
                        )
                    );

                    __g_xcart_array2insert(
                        'product_options_lng',
                        array(
                            'optionid'    => $optionid,
                            'code'        => 'en',
                            'option_name' => 'Option ' . $y . ' / ' . $z . ' (en)',
                        )
                    );
                }

                # Add multilingual option names
                __g_xcart_array2insert(
                    'class_lng',
                    array(
                        'classid'   => $classid,
                        'code'      => 'en',
                        'class'     => 'POption_' . $y . ' (en)',
                        'classtext' => 'Product option #' . $y . ' (en)',
                    )
                );
            }
        }

        // Apply taxes
        $taxes = func_query_column('SELECT taxid FROM ' . $sql_tbl['taxes'] . '');
        foreach ($taxes as $tid) {
           __g_xcart_array2insert(
                'product_taxes',
                array(
                    'productid' => $id,
                    'taxid'     => $tid,
                )
            );
        }
    }
}

function __g_xcart_array2insert($tbl, array $data)
{
    func_array2insert($tbl, $data);
    __g_inc();
}

#
# Add image
#
function __g_xcart_add_image($type, $id) {
    global $xcart_dir;

    $path = $xcart_dir . '/skin/common_files/images/logo.gif';
    $dpath = $xcart_dir . '/images/' . $type . '/' . $id . '.gif';

    $data = array(
        'id'         => $id,
        'image_path' => $id . '.gif',
        'filename'   => $id . '.gif',
        'date'       => time(),
        'image_size' => filesize($path),
        'image_x'    => 284,
        'image_y'    => 60,
        'image_type' => 'image/gif',
    );

    if (file_exists($dpath)) {
        unlink($dpath);
    }

    copy($path, $dpath);

    return __g_xcart_array2insert(
        'images_' . $type,
        $data
    );
}

function _get_row(&$res, $query) {
    $id = db_fetch_array($res);
    if (!$id) {
        db_free_result($res);
        $res = db_query($query);
        $id = db_fetch_array($res);
    }   
    return $id;
}
