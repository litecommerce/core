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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model\Shipping\Processor;

/**
 * Shipping processor model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class AProcessor extends \XLite\Base\SuperClass
{
    /**
     * Unique processor Id 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $processorId = null;

    /**
     * Url of shipping server for rates calculation
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $apiUrl = null;

    /**
     * Log of request/response pairs during communitation with a shipping server 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $apiCommunicationLog = null;

    /**
     * Define public constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
    }

    /**
     * Returns processor name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function getProcessorName();

    /**
     * Returns processor's shipping methods rates
     * 
     * @param mixed $order       Order object or an array with input data
     * @param bool  $ignoreCache Flag: if true then do not get rates from cache
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract public function getRates($order, $ignoreCache= false);

    /**
     * Returns processor's shipping methods 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingMethods()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Shipping\Method')
            ->findMethodsByProcessor($this->getProcessorId());
    }

    /**
     * Returns processor Id 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProcessorId()
    {
        return $this->processorId;
    }

    /**
     * Returns true if shipping methods named may be modified by admin
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isMethodNamesAdjustable()
    {
        return true;
    }

    /**
     * Returns an API URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Returns an API communication log 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getApiCommunicationLog()
    {
        return $this->apiCommunicationLog;
    }

    /**
     * getDataFromCache 
     * 
     * @param string $key Key of a cache cell
     *  
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDataFromCache($key)
    {
        $data = null;
        
        $cacheDriver = \XLite\Core\Database::getCacheDriver();

        $key = md5($key);

        if ($cacheDriver->contains($key)) {
            $data = $cacheDriver->fetch($key);
        }

        return $data;
    }

    /**
     * saveDataInCache 
     * 
     * @param string $key  Key of a cache cell
     * @param mixed  $data Data object for saving in the cache
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveDataInCache($key, $data)
    {
        $cacheDriver = \XLite\Core\Database::getCacheDriver();

        $cacheDriver->save(md5($key), $data);
    }

}
