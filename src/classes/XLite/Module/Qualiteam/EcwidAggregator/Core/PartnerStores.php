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
 * @since     1.0.18
 */

namespace XLite\Module\Qualiteam\EcwidAggregator\Core;

/**
 * PartnerStores collection represents information about all stores
 * registered for a specific API key
 * 
 * @see   ____class_see____
 * @since 1.0.18
 */
class PartnerStores implements \Countable, \SeekableIterator
{
    /**
     * Test Partner API endpoint
     */
    const TEST_API_ENDPOINT = 'https://mydev.ecwid.com/resellerapi/v1/stores';

    /**
     * Live Partner API endpoint
     */
    const LIVE_API_ENDPOINT = 'https://my.ecwid.com/resellerapi/v1/stores';

    /**
     * List of store attributes to retrieve via API
     * An optional type casting function can be specified
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.18
     */
    protected $storeFields = array(
        'channelId' => '',
        'email'     => '',
        'id'        => 'intval',
        'name'      => '',
        'nick'      => '',
        'registered'=> 'strtotime',
        'storage'   => 'intval',
        'suspended' => '',
        'traffic'   => 'intval',
        'url'       => '',
    );

    /**
     * Current selected API endpoint
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.18
     */
    protected $apiEndpoint;

    /**
     * Cached stores information (retrieved via API)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.18
     */
    protected $stores = array();

    /**
     * Number of stores registered
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.18
     */
    protected $storesCount = null;

    /**
     * Current iterator position
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.18
     */
    protected $position = 0;

    /**
     * Constructs current iterator object using specified API mode
     * 
     * @param boolean $testMode Specifies if a test mode should be enabled OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function __construct($testMode = false)
    {
        $this->apiEndpoint = $testMode ? self::TEST_API_ENDPOINT : self::LIVE_API_ENDPOINT;
    }

    /**
     * Countable::count 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function count()
    {
        if (null === $this->storesCount) {
            $this->getStores();
        }

        return $this->storesCount;
    }

    /**
     * SeekableIterator::key 
     * Returns current store index
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * SeekableIterator::rewind
     * Sets position to the start
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * SeekableIterator::next
     * Advances position one step forward
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * SeekableIterator::valid
     * Checks if current position is valid
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function valid()
    {
        return 0 <= $this->key() && $this->key() < $this->count();
    }

    /**
     * SeekableIterator::seek
     * Seeks to the specified position
     * 
     * @param integer $position Position to go to
     *  
     * @return void
     * @throws OutOfBoundException
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function seek($position)
    {
        $this->position = $position;

        if (!$this->valid()) {
            throw new \OutOfBoundsException("Ecwid PartnerStores: invalid seek position ($position)");
        }
    }

    /**
     * SeekableIterator::current
     * Returns current store information
     * 
     * @return void
     * @throws OutOfBoundException
     * @see    ____func_see____
     * @since  1.0.18
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new \OutOfBoundsException("Ecwid PartnerStores: invalid position ($position)");
        }

        if (!isset($this->stores[$this->position])) {
            $this->getStores($this->position);
        }

        return $this->stores[$this->position];
    }

    /**
     * Get current partner API key
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function getApiKey()
    {
        return '12345';
    }

    /**
     * Retrieves information about stores and prepares it for iterator access
     * 
     * @param string $offset First store offset
     * @param string $limit  Number of stores to limit request to
     *  
     * @return string
     * @throws Exception
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function fetchStoresData($offset, $limit)
    {
        $url = $this->apiEndpoint
            . '?key=' . $this->getApiKey()
            . '&offset=' . $offset
            . '&limit=' . $limit;

        $bouncer = new \XLite\Core\HTTP\Request($url);

        $bouncer->requestTimeout = 5;
        $response = $bouncer->sendRequest();

        if (200 == $response->code) {
            return $response->body;
        } else {
            throw new \Exception("Call to '$url' failed with '$response->code' code");
        }
    }

    /**
     * getStores 
     * 
     * @param integer $offset First store offset OPTIONAL
     * @param integer $limit  Number of stores to limit request to OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function getStores($offset = 0, $limit = 100)
    {
        try {
            $stringData = $this->fetchStoresData($offset, $limit);
        } catch (\Exception $e) {
            $this->storesCount = 0;

            return;
        }

        $xml = \XLite\Core\XML::getInstance();

        $xmlParsed = $xml->parse($stringData, $err);

        $this->storesCount = intval($xml->getArrayByPath($xmlParsed, 'storeList/#/total/0/#'));

        if (0 < $this->storesCount) {

            $storesData = $xml->getArrayByPath($xmlParsed, 'storeList/#/stores');

            $storeFields = $this->storeFields;
            $stores = array_map(function ($storeData) use ($storeFields, $xml) {

                $store = array(); 
                foreach ($storeFields as $field => $typeCast) {
                    $store[$field] = $xml->getArrayByPath($storeData, '#/' . $field . '/0/#');

                    if ($typeCast) {
                        // Apply type casting function
                        $store[$field] = $typeCast($store[$field]);
                    }
                }

                return $store;

            }, $storesData);

            foreach ($stores as $index => $store) {
                $this->stores[$offset + $index] = $store;
            }

        }
    }
}
