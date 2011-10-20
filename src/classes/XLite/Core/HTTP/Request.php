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

namespace XLite\Core\HTTP;

/**
 * Request
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Request extends \PEAR2\HTTP\Request
{
    /**
     * Sets up the adapter
     *
     * @param string                      $url      URL for this request OPTIONAL
     * @param \PEAR2\HTTP\Request\Adapter $instance The adapter to use OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($url = null, $instance = null)
    {
        try {
            parent::__construct($url, $instance);

        } catch (\Exception $exception) {
            $this->logBouncerError($exception);
        }
    }

    /**
     * Asks for a response class from the adapter
     *
     * @return \PEAR2\HTTP\Request\Response
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function sendRequest()
    {
        try {
            $result = parent::sendRequest();

        } catch (\Exception $exception) {
            $result = null;
            $this->logBouncerError($exception);
        }

        return $result;
    }

    /**
     * Sends a request storing the output to a file
     *
     * @param string $file File to store to
     *
     * @return \PEAR2\HTTP\Request\Response
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function requestToFile($file)
    {
        try {
            $result = parent::sendRequest();

        } catch (\Exception $exception) {
            $result = null;
            $this->logBouncerError($exception);
        }

        return $result;
    }

    /**
     * Logging
     *
     * @param \Exception $exception Thrown exception
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function logBouncerError(\Exception $exception)
    {
        \XLite\Logger::getInstance()->log($exception->getMessage(), $this->getLogLevel());
    }

    /**
     * Return type of log messages
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLogLevel()
    {
        return PEAR_LOG_WARNING;
    }
}
