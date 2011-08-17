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
 * @since     1.0.0
 */

namespace XLite\Module\CDev\USPS\Controller\Admin;

/**
 * USPS module settings page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Usps extends \XLite\Controller\Admin\ShippingSettings
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'U.S.P.S. settings';
    }

    /**
     * Returns options for PackageSize selector 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPackageSizeOptions()
    {
        return array(
            'REGULAR'  => 'Regular (package dimensions are 12" or less)',
            'LARGE'    => 'Large (any package dimension is larger than 12")',
        );
    }

    /**
     * Returns options for MailType selector 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMailTypeOptions()
    {
        return array(
            'Package'                  => 'Package',
            'Postcards or aerogrammes' => 'Postcards or aerogrammes',
            'Envelope'                 => 'Envelope',
            'LargeEnvelope'            => 'Large envelope',
            'FlatRate'                 => 'Flat rate',
        );
    }

    /**
     * Returns options for Container selector (domestic API)
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getContainerOptions()
    {
        return array(
            'VARIABLE'       => 'Variable',
            'FLAT RATE ENVELOPE' => 'Flat rate envelope',
            'PADDED FLAT RATE ENVELOPE' => 'Padded flat rate envelope',
            'LEGAL FLAT RATE ENVELOPE' => 'Legal flat rate envelope',
            'SM FLAT RATE ENVELOPE' => 'SM flat rate envelope',
            'WINDOW FLAT RATE ENVELOPE' => 'Window flat rate envelope',
            'GIFT CARD FLAT RATE ENVELOPE' => 'Gift card flat rate envelope',
            'FLAT RATE BOX' => ' Flat rate box',
            'SM FLAT RATE BOX' => 'SM flat rate box',
            'MD FLAT RATE BOX' => 'MD flat rate box',
            'LG FLAT RATE BOX' => 'LG flat rate box',
            'REGIONALRATEBOXA' => 'Regional rate boxA',
            'REGIONALRATEBOXB' => 'Regional rate boxB',
            'RECTANGULAR'    => 'Rectangular',
            'NONRECTANGULAR' => 'Non-rectangular',
        );
    }

    /**
     * Returns options for Container selector (international API) 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getContainerIntlOptions()
    {
        return array(
            'RECTANGULAR'    => 'Rectangular',
            'NONRECTANGULAR' => 'Non-rectangular',
        );
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'U.S.P.S. settings';
    }

    /**
     * getOptionsCategory
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getOptionsCategory()
    {
        return 'CDev\USPS';
    }

    /**
     * doActionTest
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionTest()
    {
        $postedData = \XLite\Core\Request::getInstance()->getData();

        // Generate input data array for rates calculator

        $data = array();
        $errorFields = array();

        if (isset($postedData['sourceZipcode']) && !empty($postedData['sourceZipcode'])) {
            $data['srcAddress']['zipcode'] = $postedData['sourceZipcode'];

        } else {
            $data['srcAddress']['zipcode'] = \XLite\Core\Config::getInstance()->Company->location_zipcode;
        }

        if (isset($postedData['destinationZipcode']) && !empty($postedData['destinationZipcode'])) {
            $data['dstAddress']['zipcode'] = $postedData['destinationZipcode'];

        } else {
            $errorFields[] = 'destinationZipcode';
        }

        if (isset($postedData['destinationCountry']) && !empty($postedData['destinationCountry'])) {
            $data['dstAddress']['country'] = $postedData['destinationCountry'];

        } else {
            $errorFields[] = 'destinationCountry';
        }

        $package = array();

        if (isset($postedData['weight']) && 0 < doubleval($postedData['weight'])) {
            $package['weight'] = doubleval($postedData['weight']);

        } else {
            $package['weight'] = 1;
        }

        if (isset($postedData['subtotal']) && 0 < doubleval($postedData['subtotal'])) {
            $package['subtotal'] = doubleval($postedData['subtotal']);

        } else {
            $package['subtotal'] = 1;
        }

        $data['packages'] = array($package);

        echo ('<h2>Input data</h2>');

        ob_start();
        print_r($data);
        $dataStr = '<pre>' . ob_get_contents() . '</pre>';
        ob_clean();

        echo ($dataStr);

        if (empty($errorFields)) {

            // Get rates

            $usps = new \XLite\Module\CDev\USPS\Model\Shipping\Processor\USPS();

            $startTime = microtime(true);

            $rates = $usps->getRatesByArray($data, true);

            $proceedTime = microtime(true) - $startTime;

            if (!empty($rates)) {

                // Rates have been successfully calculated, display them
                echo ('<h2>Rates:</h2>');

                foreach ($rates as $rate) {
                    echo (sprintf('%s (%0.2f)<br>', $rate->getMethodName(), $rate->getBaseRate()));
                }

                echo (sprintf('<br /><i>Time elapsed: %0.3f seconds</i>', $proceedTime));

            } else {
                $errorMsg = static::t(
                    'There are no rates available for specified source/destination and/or package measurements/weight.'
                );
            }

        } else {
            $errorMsg = static::t(
                'The following expected input data have wrong format or empty: ' . implode(', ', $errorFields)
            );
        }

        if (!empty($errorMsg)) {
            echo ('<h3>$errorMsg</h3>');
        }

        if (isset($usps)) {
            $cmLog = $usps->getApiCommunicationLog();
        }

        if (isset($cmLog)) {

            echo ('<h2>Communication log</h2>');

            ob_start();

            echo ('API URL: ' . $usps->getApiURL() . PHP_EOL . PHP_EOL);

            foreach ($cmLog as $log) {
                print_r($log);
                echo (PHP_EOL . '<hr />' . PHP_EOL);
            }

            $msg = '<pre>' . ob_get_contents() . '</pre>';
            ob_clean();

            echo ($msg);
        }

        die ();
    }
}
