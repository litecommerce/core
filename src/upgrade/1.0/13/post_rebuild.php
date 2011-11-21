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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.11
 */

return function()
{
    // Loading data to the database from yaml file
    $yamlFile = __DIR__ . LC_DS . 'post_rebuild.yaml';

    if (\Includes\Utils\FileManager::isFileReadable($yamlFile)) {
        \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yamlFile);
    }

    // Apply config changes
    $repo = \XLite\Core\Database::getRepo('XLite\Model\Config');

    $option = $repo->findOneBy(array('name' => 'add_on_mode_page'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\DeskOperationMode');
    }

    $option = $repo->findOneBy(array('name' => 'date_format'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\DateFormat');
    }

    $option = $repo->findOneBy(array('name' => 'decimal_delim'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\DecimalPart');
    }

    $option = $repo->findOneBy(array('name' => 'smtp_security'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\SMTPSecurity');
    }

    $option = $repo->findOneBy(array('name' => 'subcategories_look'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\SubcategoriesLook');
    }

    $option = $repo->findOneBy(array('name' => 'thousand_delim'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\ThousandDelimiter');
    }

    $option = $repo->findOneBy(array('name' => 'time_format'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\TimeFormat');
    }

    $option = $repo->findOneBy(array('name' => 'time_zone'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\TimeZone');
    }

    $option = $repo->findOneBy(array('name' => 'weight_unit'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\WeightUnit');
    }

    $option = $repo->findOneBy(array('name' => 'featured_products_look'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Select\\SubcategoriesLook');
    }

    $option = $repo->findOneBy(array('name' => 'ga_tracking_type'));
    if ($option) {
        $option->setType('XLite\\Module\\CDev\\GoogleAnalytics\\View\\FormField\\Select\\TrackingType');
    }

    foreach (array('welcome_changefreq', 'category_changefreq', 'product_changefreq') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\Module\\CDev\\XMLSitemap\\View\\FormField\\Select\\ChangeFrequency');
        }
    }

    $option = $repo->findOneBy(array('name' => 'bestsellers_menu'));
    if ($option) {
        $option->setType('XLite\\Module\\CDev\\Bestsellers\\View\\FormField\\Select\\Menu');
    }

    foreach (array('company_fax', 'company_phone') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\View\\FormField\\Input\\Text\\Phone');
        }
    }

    foreach (array('company_website', 'smtp_server_url', 'logo_url', 'drupal_root_url') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\View\\FormField\\Input\\Text\\URL');
        }
    }

    foreach (array('orders_department', 'site_administrator', 'support_department', 'users_department') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\View\\FormField\\Input\\Text\\Email');
        }
    }

    foreach (array('default_purchase_limit', 'login_lifetime', 'orders_per_page', 'order_starting_number', 'products_per_page', 'products_per_page_admin', 'recent_orders', 'users_per_page', 'number_of_bestsellers') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\View\\FormField\\Input\\Text\\Integer');
            $option->setWidgeParameters(array('min' => 1));
        }
    }

    foreach (array('maximal_order_amount') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\View\\FormField\\Input\\Text\\Float');
            $option->setWidgeParameters(array('min' => 1));
        }
    }

    foreach (array('minimal_order_amount') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\View\\FormField\\Input\\Text\\Float');
            $option->setWidgeParameters(array('min' => 0));
        }
    }

    $option = $repo->findOneBy(array('name' => 'smtp_server_port'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Input\\Text\\Integer');
        $option->setWidgeParameters(array('min' => 0, 'max' => 65535));
    }

    $option = $repo->findOneBy(array('name' => 'start_year'));
    if ($option) {
        $option->setType('XLite\\View\\FormField\\Input\\Text\\PastYear');
    }

    foreach (array('welcome_priority', 'category_priority', 'product_priority') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            $option->setType('XLite\\View\\FormField\\Input\\Text\\Float');
            $option->setWidgeParameters(array('min' => 0, 'max' => 1, 'e' => 1));
        }
    }

    foreach (array('clear_cc_info', 'memberships', 'membershipsCollection', 'params', 'partner_product_banner', 'partner_profile', 'defaultSources', 'product_layout', 'user_layout', 'enable_credit_card_validation', 'enable_extra_fields_inherit', 'enable_categories_extra_fields') as $name) {
        $option = $repo->findOneBy(array('name' => $name));
        if ($option) {
            \XLite\Core\Database::getEM()->remove($option);
        }
    }

    \XLite\Core\Database::getEM()->flush();
    \XLite\Core\Database::getCacheDriver()->clear();

};
