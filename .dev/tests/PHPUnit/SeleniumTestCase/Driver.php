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

require_once 'PHPUnit/Extensions/SeleniumTestCase/Driver.php';

class XLite_Extensions_SeleniumTestCase_Driver extends PHPUnit_Extensions_SeleniumTestCase_Driver
{
    /**
     * Get current sessionId 
     * 
     * @return string
     * @access public
     */
	public function getSessionId()
	{
		return $this->sessionId;
    }

    /**
     * Get current value of 'sleep' property 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSleep()
    {
        return $this->sleep;
    }


    /**
     * Send a command to the Selenium RC server (via curl).
     *
     * @param  string $command
     * @param  array  $arguments
     * @return string
     * @author Seth Casana <totallymeat@gmail.org>
     */
    protected function doCommand($command, array $arguments = array())
    {
        $url = sprintf(
          'http://%s:%s/selenium-server/driver/?cmd=%s',
          $this->host,
          $this->port,
          urlencode($command)
        );

        $numArguments = count($arguments);

        for ($i = 0; $i < $numArguments; $i++) {
            $argNum = strval($i + 1);
            $url   .= sprintf(
                        '&%s=%s',
                        $argNum,
                        urlencode(trim($arguments[$i]))
                      );
        }

        if (isset($this->sessionId)) {
            $url .= sprintf('&%s=%s', 'sessionId', $this->sessionId);
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 60);

        $response = curl_exec($curl);
        $info     = curl_getinfo($curl);

        if (!$response) {
            throw new RuntimeException(curl_error($curl));
        }

        curl_close($curl);

        if ($info['http_code'] != 200) {
            $this->stop();

            throw new RuntimeException(
              'The response from the Selenium RC server is invalid: ' .
              $response
            );
		}

        if (!preg_match('/^OK/', $response)) {

            throw new PHPUnit_Framework_ExpectationFailedException(
                'Non-Ok response from Selenium RC server was received',
                PHPUnit_Framework_ComparisonFailure::diffEqual('OK', $response),
                sprintf(
                    "Response from Selenium RC server for %s(%s).\n%s.\n",
                    $command,
                    implode(', ', $arguments),
                    $response
                )
            );
        }

        return $response;
    }
}
