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
 * @since     1.0.17
 */

return function()
{
    // Update language labels

    $labels = array(
        'insert' => array(
            'Select a country or a state from a list, specify the zone' => 'Select a country or a state from a list, specify the zone where the country or state should be listed and click on the "Apply" button. To select more than one country/state, hold down the CTRL key while making a selection. A zone can contain either countries or states. You cannot include both states and countries into the same zone.',
        ),
        'update' => array(
            'Import / Export' => 'Import/Export',
/* ----> */ 'After you enabled this tax it will be included in product prices' => array('After you enable this tax it will be included in product prices', array('After you enabled this tax it will be included in product prices. This means that it will not be shown as separate surcharge during checkout.' => 'After you enable this tax it will be included in product prices. This means that it will not be shown as separate surcharge during checkout.')),
            'All memberships' => 'All membership levels',
            'Amount: high to low' => 'Quantity: high to low',
            'Amount: low to high' => 'Quantity: low to high',
            'Any membership' => 'Any membership level',
            'Attachment has not been deleted successfully' => 'Attachment is not deleted',
            'Attachments has been updated successfully' => 'Attachments have been updated successfully',
/* ----> */ 'Congratulations! Your order has been successfully placed' => array('Congratulations! Your order has been placed successfully', array('Congratulations! Your order has been successfully placed.<br />Thank you for using our store. An order notification has been sent to your e-mail address.<br />Your order will be processed according to the delivery details.' => 'Congratulations! Your order has been  placed successfully.<br />Thank you for using our store. An order notification has been sent to your e-mail address.<br />Your order will be processed according to the delivery details.')),
            'Descriptions for various rate types are provided below:' => 'Descriptions of various rate types are provided below',
/* ----> */ 'During the import was recorded X errors. You can get them by downloading the log imports.' => array('During the import was recorded X errors. You can get them by downloading the log files.', array('During the import was recorded {{count}} errors. You can get them by <a href="{{url}}">downloading</a> the log imports.' => 'During the import was recorded {{count}} errors. You can get them by <a href="{{url}}">downloading</a> the log files.')),
            'How to backup your store database' => 'How to back up your store database',
            'If the product is assigned to multiple classes only the first tax rate with highest priority will be applied on it.' => 'If the product is assigned to multiple classes, only the first tax rate with the highest priority will be applied to it.',
            'If you have a license key for commercial module you may enter them here to register a purchase of the appropriate module.' => 'If you have a license key for a commercial module, you can enter it here to register the purchase of the appropriate module.',
/* ----> */ 'If you don\'t have moneybookers account yet, please sign up for the free moneybookers account at http://www.moneybookers.com' => array('If you don\'t have a moneybookers account yet, please sign up for a free moneybookers account at: http://www.moneybookers.com', array('If you don\'t have moneybookers account yet, please sign up for the free moneybookers account at: <a href=\"http://www.moneybookers.com/partners/?p=LiteCommerce\">http://www.moneybookers.com</a>' => 'If you don\'t have a moneybookers account yet, please sign up for a free moneybookers account at: <a href=\"http://www.moneybookers.com/partners/?p=LiteCommerce\">http://www.moneybookers.com</a>')),
            'if you store product images in the database, they will be included in the SQL dump file. If product images are located on the file system, they are not included. To backup such images you need to download them directly from the server.' => 'If you store product images in the database, they are included in the SQL dump file. If the product images are located on the file system, they are not included in the SQL dump file. To back such images up you need to download them directly from the server.',
/* ----> */ 'incorrect owner for X directory' => array('Incorrect owner of X directory', array('incorrect owner for {{X}} directory' => 'Incorrect owner of {{X}} directory')),
/* ----> */ 'incorrect owner for X file' => array('Incorrect owner of X file', array('incorrect owner for {{X}} file' => 'Incorrect owner of {{X}} file')),
/* ----> */ 'Logoff' => array('Log off', array(null => 'Log off')),
            'New access key will be also sent to the Site administrator email address' => 'New access key will also be sent to the Site administrator\'s email address',
            'Low limit amount' => 'Low limit quantity',
            'Mark, what search engines you want to inform about the structure of your site using the site map' => 'Mark, the search engines you want to inform of the structure of your site using the site map',
            'New product has been successfully added' => 'New product has been added successfully',
            'Not a numeric' => 'Not numeric',
            'Not tax rate defined' => 'No tax rate defined',
            'Options has been successfully changed' => 'Options have been successfully changed',
            'Perform backup of your store database' => 'Make back-up of your store database',
            'Product added to the bag' => 'Product added to bag',
            'Product has been added to the cart' => 'Product has been added to cart',
            'Product info has been successfully updated' => 'Product info has been updated successfully',
            'Restore procedure is irreversible and erases all data tables from your store database. It is highly recommended that you backup your present database data before restoring one of the previous states from a backup.' => 'The restoration procedure is irreversible and erases all data tables from your store database. It is highly recommended that you back your present database data up before restoring one of the previous states from a back-up.',
/* ----> */ 'Select the membership level and the area which product prices, including VAT, are defined for by the shop administrator' => array('Select the membership level and area. for which product prices, including VAT, are defined by the shop administrator', array('Select the membership level and the area which product prices, including VAT, are defined for by the shop administrator. The included VAT will be subtracted and then recalculated for customers from other locations or having a different membership level.<br /><br />If your prices are defined excluding VAT, select the membership level and the area with a 0% VAT rate defined below (or with no applicable rate).' => 'Select the membership level and area, for which product prices, including VAT, are defined by the shop administrator. The included VAT will be subtracted and then recalculated for customers from other locations or having a different membership level.<br /><br />If your prices are defined excluding VAT, select the membership level and the area with a 0% VAT rate defined below (or with no applicable rate).')),
            'Setup your online catalog structure' => 'Online catalog structure set-up',
            'Shipping address is not complete defined yet' => 'Shipping address is not completly defined yet',
            'Shipping markup is successfully created' => 'Shipping markup has been created successfully',
            'Shipping rates are comprised of several components (rate types) and are calculated according to the following generic patterns:' => 'Shipping rates are comprised of several components (rate types) and calculated according to the following generic patterns',
            'Tax rates has been updated successfully' => 'Tax rates have been updated successfully',
            'The attachment has been successfully added' => 'The attachment has been added successfully',
            'The detailed image has been successfully added' => 'The detailed image has been added successfully',
            'The detailed images have been successfully updated' => 'The detailed images have been updated successfully',
            'The exceptions have been successfully updated' => 'The exceptions have been updated successfully',
            'The following dependent add-ons will be automatically disabled:' => 'The following dependent add-ons will be disabled  automatically',
            'The product option group has been successfully added' => 'The product option group has been added successfully',
            'The product option group has been successfully updated' => 'The product option group has been updated successfully',
            'The product option groups have been successfully updated' => 'The product option groups have been updated successfully',
/* ----> */ 'The text label has not been added, because its translation for the default application language has not been specified' => array('The text label has not been added, because its translation to the default application language has not been specified', array(null => null)),
/* ----> */ 'The text label has not been added, because its translation for the default interface language has not been specified' => array('The text label has not been added, because its translation to the default interface language has not been specified', array(null => null)),
/* ----> */ 'The text label has not been modified, because its translation for the default application language has not been specified' => array('The text label has not been modified, because its translation to the default application language has not been specified', array(null => null)),
/* ----> */ 'The text label has not been modified, because its translation for the default interface language has not been specified' => array('The text label has not been modified, because its translation to the default interface language has not been specified', array(null => null)),
/* ----> */ 'The value of X field must be greater less Y' => array('The value of the X field must be less than Y', array('The value of {{name}} field must be less than {{max}}' => 'The value of the {{name}} field must be less than {{max}}')),
/* ----> */ 'The value of X field must be greater than Y' => array('The value of the X field must be greater than Y', array('The value of {{name}} field must be greater than {{min}}' => 'The value of the {{name}} field must be greater than {{min}}')),
/* ----> */ 'The value of X has an incorrect format' => array('The value of the X field has an incorrect format', array('The value of {{name}} has an incorrect format' => 'The value of the {{name}} field has an incorrect format')),
/* ----> */ 'The value of X should not be longer than Y' => array('The value of the X field should not be longer than Y', array('The value of {{name}} should not be longer than {{max}}' => 'The value of the {{name}} field should not be longer than {{max}}')),
            'There are no user with specified email address' => 'There is no user with specified email address',
            'This taxis calculated based on customer\'s billing address.' => 'This tax is calculated based on customer\'s billing address',
/* ----> */ 'To get the format of the import data, you can export your products to a file.' => array('To get the format of the import data you can export your products to a file', array('To get the format of the import data, you can <a href="{{url}}">export your products</a> to a file and them review the format of that file.' => 'To get the format of the import data you can <a href="{{url}}">export your products</a> to a file and then review the format of that file.')),
/* ----> */ 'To restore the images which are stored in the file system, you have to copy them from the archive to LiteCommerce catalog' => array('To restore the images stored in the file system you have to copy them from the archive to the LiteCommerce catalog', array('To restore the images which are stored in the file system, you have to copy them from the archive to LiteCommerce catalog, taking into consideration the catalog structure.' => 'To restore the images stored in the file system you have to copy them from the archive to the LiteCommerce catalog, taking into consideration the catalog structure.')),
/* ----> */ 'Unable to install module X because some modules which it depends on, have not been installed or activated yet' => array('Unable to install module X because some modules, which it depends on, have not been installed or activated yet', array('Unable to install module &quot;{{X}}&quot; because some modules which it depends on, have not been installed or activated yet.' => 'Unable to install module &quot;{{X}}&quot; because some modules, which it depends on, have not been installed or activated yet.')),
            'Use this section to backup the database of your online store. Please note that database backup procedure can take up to several minutes.' => 'Use this section to back the database of your online store up. Please note that the database backup procedure can take up to several minutes.',
            'Use this section to define rules for calculating shipping rates' => 'Use this section to define shipping rates calculation rules',
            'Use this section to restore the database of your online store. Please note that database restore procedure can take up to several minutes.' => 'Use this section to restore the database of your online store. Please note that the database restoration procedure can take up to several minutes',
            'When the exporting is complete, you will be prompted to download the product data file' => 'When the export is completed, you will be prompted to download the product data file',
/* ----> */ 'You are going to delete X language' => array('You are going to delete the X language', array('You are going to delete {{language}} language. This operation is irreversable. Are you sure you want to continue?' => 'You are going to delete the {{language}} language. This operation is irreversible. Are you sure you want to continue?')),
            'You can choose to download your database data (SQL dump) directly to your local computer by clicking on the \'Download SQL file\' button, or save database data to a file on the web server (\'var/backup/sqldump.sql.php\') by clicking on the \'Create SQL file\' button.' => 'You can choose if to download your database data (SQL dump) directly to your local computer by clicking on the "Download SQL file" button or to save database data to a file on the web server ("var/backup/sqldump.sql.php") by clicking on the "Create SQL file" button',
/* ----> */ 'Your profile has been modified. You can check your account after you logging in to the site' => array('Your profile has been modified. You can check your account after you log in to the site', array('Your profile has been modified. You can check your account after you logging in to the site at {{url}}' => 'Your profile has been modified. You can check your account after you log in to the site at {{url}}')),
            'Module Marketplace' => 'Modules Marketplace',
            'Can\'t connect to the Module Marketplace server' => 'Can\'t connect to the Modules Marketplace server',
            'You need Phar extension for PHP on your server to download modules from Module Marketplace' => 'You need Phar extension for PHP on your server to download modules from Modules Marketplace',
            'In order to recover your password, please type in your valid e-mail address you used as a login' => 'To recover your password, please type in the valid e-mail address you use as a login',
            'Search for pattern' => 'Pattern search',
            'State added successfully' => 'The state has been added successfully',
            'State(s) deleted successfully' => 'States have been deleted successfully',
            'State(s) updated successfully' => 'States have been updated successfully',
            'Backup database' => 'Back up database',
            'Search orders' => 'Search for orders',
            'Search products' => 'Search for products',
            'Search users' => 'Search for users',
            'User profile deleted' => 'User profile is deleted',
            'Use navigation bar above this dialog to navigate through the catalog categories.' => 'Use the navigation bar above this dialog to navigate through the catalog categories',
            'This component is automatically calculated by shipping add-on modules and cannot be edited.' => 'This component is automatically calculated by shipping add-on module and cannot be edited',
            'Other state' => 'Another state',
            'Updates available' => 'Updates are available',
            'If you have a plugin in a .tar format, you may install it by uploading it here.' => 'If you have a plugin in the .tar format, you can install it by uploading it here',
            'There are updates for installed modules and/or LC core' => 'Updates for the LC core and/or installed modules are available',
            'unable to add module entry to the install list: "{{name}}"' => 'unable to add module entry to the installation list: "{{name}}"',
            'unable to add module entry to the install list: "{{path}}"' => 'unable to add module entry to the installation list: "{{path}}"',
/* ----> */ 'Alternatively, upload file sqldump.sql.php to the var/backup/ sub-directory click on the "Restore from server" button' => array(null, array('Alternatively, upload the file named sqldump.sql.php to the var/backup/ sub-directory of your LiteCommerce installation on the web server and click on the "Restore from server" button. After the restore you might want to delete the file from the server by clicking on the "Delete SQL file" button above.' => 'Alternatively, upload the file named sqldump.sql.php to the var/backup/ sub-directory of your LiteCommerce installation on the web server and click on the "Restore from server" button. After the restoration you might want to delete the file from the server by clicking on the "Delete SQL file" button above.')),
/* ----> */ 'Please specify text labels for each language' => array(null, array('Please specify text labels for each language. If you do not specify a value for some language, the {{language}} label wiil be used.' => 'Please specify text labels for each language. If you do not specify a value for some language, the {{language}} label will be used.')),
/* ----> */ 'Some products could have been imported incorrectly' => array(null, array('Some products could have been imported incorrectly. Please check your catalog. Find the ID of the products that could have been imported incorrectly in the import log file available at the above link.' => 'Some products could have been imported incorrectly. Please check your catalog. Find the ID\'s of such products in the import log file available at the above link.')),
/* ----> */ 'The email with your account information was mailed to' => array(null, array('The email with your account information was mailed to {{email}}. We encourage you to authenticate again to verify the received data.' => 'The email with your account information was mailed to {{email}}. We encourage you to authenticate yourself again to verify the received data.')),
        ),
/* ----> */ 'This user name is used for an existing account. Enter another user name or sign in' => array(null, array('This user names is used for an existing account. Enter another user name or <a href="{{URL}}" class="log-in">sign in</a>' => 'This user name is used for an existing account. Enter another user name or <a href="{{URL}}" class="log-in">sign in</a>')),
/* ----> */ 'To fix this problem, do the following: 3 points' => array(null, array('To fix this problem, do the following: <ul><li>make sure that your hosting service provider has HTTPS protocol enabled;</li><li>verify your HTTPS settings ("https_host" parameter in the "etc/config.php" file must be valid);</li><li>reload this page.</li></ul>' => 'To fix this problem take the following steps: <ul><li>make sure that the HTTPS protocol is enabled by your hosting service provider;</li><li>verify your HTTPS settings ("https_host" parameter in the "etc/config.php" file must be valid);</li><li>reload this page.</li></ul>')),
        'delete' => array(
            'Successfully imported X new products and upgraded Y old products' => 'Successfully imported {{new}} new products and upgraded {{old}} old products',
            'Successfully imported X new products' => 'Successfully imported {{new}} new products',
            'Successfully upgraded Y old products' => 'Successfully upgraded {{old}} old products',
            'The module has been partially uninstalled' => null,
            'This product is out of stock or it has been disabled for sale' => '(!) This product is out of stock or it has been disabled for sale.',
            'Shipping method has been added' => null,
            'Shipping methods have been updated' => null,
            'Failed to add attachment' => 'Failed to add the attachment',
            'Failed to add attachment. File is forbidden to download' => 'Failed to add the attachment. The file download is forbidden',
            'These modules may be incompatible with the upcoming upgrade. It can not be guaranteed whether the store will operate correctly if the modules will remain enabled. So, please decide what to do with the modules before proceeding to the next step.' => 'These modules may be incompatible with the upcoming upgrade. No guarantee that the store will operate correctly if the modules remain enabled. Please consider disabling the modules before proceeding to the next step',
            'Please, note that some of these modules are definitely incompatible with the upcoming upgrade and will be disabled in order to prevent system crash.' => 'Please note that some of these modules are definitely incompatible with the upcoming upgrade and will be disabled in order to prevent the system crash',
        ),
    );

    $objects = array();

    foreach ($labels as $method => $tmp) {
        $objects[$method] = array();

        foreach ($tmp as $oldKey => $data) {
            $object = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->findByName($oldKey);

            if (isset($object)) {
                if (empty($data)) {
                    $data = $oldKey;
                }

                switch ($method) {
                    case 'update':
                        if (is_array($data)) {
                            list($newKey, list($oldTranslation, $newTranslation)) = $data;

                            if (isset($newKey)) {
                                $object->setName($newKey);
                            }

                            if (is_null($object->getLabel())) {
                                $objects['delete'] = $object;
                                unset($object);

                            } elseif ($object->getLabel() === $oldTranslation) {
                                if (isset($newTranslation)) {
                                    $object->setLabel($newTranslation);

                                } else {
                                    $objects['delete'] = $object;
                                    unset($object);
                                }
                            }

                        } else {
                            $object->setName($data);
                        }

                        break;

                    case 'delete':
                        if (!is_null($object->getLabel()) && $object->getLabel() !== $data) {
                            unset($object);
                        }

                        break;

                    default:
                        // ...
                }

            } elseif ('insert' === $method) {
                $object = new \XLite\Model\LanguageLabel();
                $object->setName($oldKey);
                $object->setLabel($data);
            }

            if (isset($object)) {
                $objects[$method][] = $object;
            }
        }
    }

    foreach ($objects as $method => $labels) {
        \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->{$method . 'InBatch'}($labels);
    }


    // Update config options

    $config = array(
        'insert' => array(
        ),
        'update' => array(
            'General' => array(
                'shop_closed' => array('Check this to temporary close the shop', 'Check this to close the shop temporarily'),
                'login_lifetime' => array('Days to store last login data', 'Number of days to store the last login data'),
                'recent_orders' => array('Amount of orders in the recent orders list', 'The number of orders in the recent order list'),
            ),
            'Company' => array(
                'location_custom_state' => array('Other state (specify)', 'Another state (specify)'),
            ),
            'Shipping' => array(
                'anonymous_custom_state' => array('Other state (specify)', 'Another state (specify)'),
            ),
        ),
        'delete' => array(
            'General' => array(
                'add_on_mode',
                'add_on_mode_page',
                'direct_product_url',
            ),
        ),
    );

    $objects = array();

    foreach ($config as $method => $tmp) {
        foreach ($tmp as $category => $data) {
            foreach ($data as $name => $value) {
                $object = \XLite\Core\Database::getRepo('\XLite\Model\Config')->findBy(array('name' => $name, 'category' => $category));

                if (isset($object)) {
                    switch ($method) {
                        case 'update':
                            list($oldLabel, $newLabel) = $value;

                            if ($object->getOptionName() === $oldLabel) {
                                $object->setOptionName($newValue);
                            }

                            break;
    
                        case 'delete':
                            // ...
                            break;

                        default:
                            // ...
                    }

                } elseif ('insert' === $method) {
                    $object = new \XLite\Model\Config();
                    $object->setCategory($category);
                    $object->setName($name);

                    // Add other actions (if needed)
                    // list($value, $label, $orderby) = $value;
                }

                if (isset($object)) {
                    $objects[$method] = $object;
                }
            }
        }
    }

    foreach ($objects as $method => $config) {
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->{$method . 'InBatch'}($config);
    }

};
