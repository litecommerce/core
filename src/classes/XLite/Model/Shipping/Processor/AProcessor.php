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

namespace XLite\Model\Shipping\Processor;

/**
 * Shipping processor model
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AProcessor extends \XLite\Base\SuperClass
{
    /**
     * Unique processor Id
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $processorId = null;

    /**
     * Url of shipping server for rates calculation
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $apiURL = null;

    /**
     * Log of request/response pairs during communitation with a shipping server
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $apiCommunicationLog = null;

    /**
     * Error message
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $errorMsg = null;


    /**
     * Returns processor name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getProcessorName();

    /**
     * Returns processor's shipping methods rates
     *
     * @param array|\XLite\Logic\Order\Modifier\Shipping $inputData   Shipping order modifier or array of data
     * @param boolean                                    $ignoreCache Flag: if true then do not get rates from cache OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getRates($inputData, $ignoreCache = false);

    /**
     * Define public constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct()
    {
    }

    /**
     * Returns processor's shipping methods
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProcessorId()
    {
        return $this->processorId;
    }

    /**
     * Returns true if shipping methods names may be modified by admin
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isMethodNamesAdjustable()
    {
        return true;
    }

    /**
     * Returns true if shipping methods can be removed by admin
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isMethodDeleteEnabled()
    {
        return false;
    }

    /**
     * Returns an API URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getApiURL()
    {
        return $this->apiURL;
    }

    /**
     * Returns an API communication log
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getApiCommunicationLog()
    {
        return $this->apiCommunicationLog;
    }

    /**
     * Returns $errorMsg 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.7
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * Write transaction log
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.8
     */
    public function logTransaction()
    {
        \XLite\Logger::getInstance()->log($this->getLogMessage());
    }

    /**
     * getDataFromCache
     *
     * @param string $key Key of a cache cell
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function saveDataInCache($key, $data)
    {
        \XLite\Core\Database::getCacheDriver()->save(md5($key), $data);
    }

    /**
     * getLogMessage
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function getLogMessage()
    {
        return sprintf('[%s] Error: %s', $this->getProcessorName(), $this->getErrorMsg());
    }
}
