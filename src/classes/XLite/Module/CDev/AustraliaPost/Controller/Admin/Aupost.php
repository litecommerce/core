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

namespace XLite\Module\CDev\AustraliaPost\Controller\Admin;

/**
 * ____description____
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Aupost extends \XLite\Controller\Admin\ShippingSettings
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
        return 'AustraliaPost settings';
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
        return 'AustraliaPost settings';
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
        return 'CDev\AustraliaPost';
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

        if (isset($postedData['weight']) && 0 < doubleval($postedData['weight'])) {
            $data['weight'] = doubleval($postedData['weight']);

        } else {
            $data['weight'] = 1;
        }

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

        echo ('<h2>Input data</h2>');

        ob_start();
        print_r($data);
        $dataStr = '<pre>' . ob_get_contents() . '</pre>';
        ob_clean();

        echo ($dataStr);

        if (empty($errorFields)) {

            // Get rates

            $aupost = new \XLite\Module\CDev\AustraliaPost\Model\Shipping\Processor\AustraliaPost();

            $startTime = microtime(true);

            $rates = $aupost->getRates($data, true);

            $proceedTime = microtime(true) - $startTime;

            $errorMsg = $aupost->getErrorMsg();

            if (!isset($errorMsg)) {
                
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
            }

        } else {
            $errorMsg = static::t(
                'The following expected input data have wrong format or empty: ' . implode(', ', $errorFields)
            );
        }

        if (!empty($errorMsg)) {
            echo ('<h3>' . $errorMsg . '</h3>');
        }

        if (isset($aupost)) {
            $cmLog = $aupost->getApiCommunicationLog();
        }

        if (isset($cmLog)) {

            echo ('<h2>Communication log</h2>');

            ob_start();

            echo ('API URL: ' . $aupost->getApiURL() . PHP_EOL . PHP_EOL);

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
