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

namespace XLite\Core\DataSource;

/**
 * Ecwid data source
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class Ecwid extends ADataSource
{

    /**
     * Get standardized data source information array
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function getInfo()
    {
    }

    /**
     * Checks whether the data source is valid
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function isValid()
    {
        /*
        try {
            // Do some api calls to check state?
        } catch(\Exception $e) {
            return false;
        }
        */

        return true;
    }

    /**
     * Request and return products collection
     * 
     * @return \XLite\Core\DataSource\Ecwid\Products
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function getProductsCollection()
    {
        return new Products($this);
    }

    /**
     * Request and return categories collection
     * 
     * @return \XLite\Core\DataSource\Ecwid\Categories
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function getCategoriesCollection()
    {
        return new Categories($this);
    }

    /**
     * Get Ecwid Store ID
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function getStoreID()
    {
        return 1003; // Testing purposes
    }

    /**
     * Does an Ecwid API call
     * 
     * @param string $apiMethod  API method name to call
     * @param string $params Parameters to pass along OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function doApiCall($apiMethod, $params = array())
    {
        $url = 'http://app.ecwid.com/api/v1/'
            . $this->getStoreID() . '/'
            . $apiMethod
            . ($params ? ('?' . http_build_query($params)) : '');
            

        $bouncer = new \XLite\Core\HTTP\Request($url);

        $bouncer->requestTimeout = 5;
        $response = $bouncer->sendRequest();

        if (200 == $response->code) {
            json_decode($response->body);
        } else {
            throw new \Exception("Call to '$apiMethod' failed with '$response->code' code");
        }
    }
}
