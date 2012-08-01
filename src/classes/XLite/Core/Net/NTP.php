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
 * @since     1.0.24
 */

namespace XLite\Core\Net;

/**
 * NTP client 
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
class NTP extends \XLite\Base\Singleton
{
    /**
     * Request TTL (seconds) 
     */
    const TTL = 30;

    /**
     * Servers list
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
    protected $servers = array(
        array('host' => 'nist1-ny.ustiming.org', 'port' => 37),
        array('host' => 'nist1-nj.ustiming.orgv', 'port' => 37),
        array('host' => 'nist1-pa.ustiming.org', 'port' => 37),
        array('host' => 'time-a.nist.gov', 'port' => 37),
        array('host' => 'time-b.nist.gov', 'port' => 37),
        array('host' => 'nist1.aol-va.symmetricom.com', 'port' => 37),
        array('host' => 'nist1.columbiacountyga.gov', 'port' => 37),
        array('host' => 'nist1-atl.ustiming.org', 'port' => 37),
        array('host' => 'nist1-chi.ustiming.org', 'port' => 37),
        array('host' => 'nist1.expertsmi.com', 'port' => 37),
        array('host' => 'nist.netservicesgroup.com', 'port' => 37),
        array('host' => 'nisttime.carsoncity.k12.mi.us', 'port' => 37),
        array('host' => 'wwv.nist.gov', 'port' => 37),
        array('host' => 'time-a.timefreq.bldrdoc.gov', 'port' => 37),
        array('host' => 'time-b.timefreq.bldrdoc.gov', 'port' => 37),
        array('host' => 'time-c.timefreq.bldrdoc.gov', 'port' => 37),
        array('host' => 'time.nist.gov', 'port' => 37),
        array('host' => 'utcnist.colorado.edu', 'port' => 37),
        array('host' => 'utcnist2.colorado.edu', 'port' => 37),
        array('host' => 'ntp-nist.ldsbc.edu', 'port' => 37),
        array('host' => 'nist1-lv.ustiming.org', 'port' => 37),
        array('host' => 'time-nw.nist.gov', 'port' => 37),
        array('host' => 'nist1.aol-ca.symmetricom.com', 'port' => 37),
        array('host' => 'nist1.symmetricom.com', 'port' => 37),
        array('host' => 'nist1-sj.ustiming.org', 'port' => 37),
        array('host' => 'nist1-la.ustiming.org', 'port' => 37),
    );

    /**
     * Get time 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getTime()
    {
        $time = null;

        foreach ($this->servers as $server) {

            $data = '';
            $errno = null;
            $errstr = '';

            $fp = @fsockopen($server['host'], $server['port'], $errno, $errstr, static::TTL);
            if ($fp) {
                while (!feof($fp)) {
                    $data .= fgets($fp, 128);
                }
                fclose($fp);

                if ($this->isValidResponse($data)) {
                    $time = $this->processResponse($data);
                    break;
                }
            }
        }

        return $time;
    }

    /**
     * Check - response is valid or not
     * 
     * @param string $data Response
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function isValidResponse($data)
    {
        return 4 === strlen($data);
    }

    /**
     * Process response 
     * 
     * @param string $data Data
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function processResponse($data)
    {
        $time = ord(substr($data, 0, 1)) * 16777216
            + ord(substr($data, 1, 1)) * 65536
            + ord(substr($data, 2, 1)) * 256
            + ord(substr($data, 3, 1));

        // 2840140800 = Thu, 1 Jan 2060 00:00:00 UTC
        // 631152000  = Mon, 1 Jan 1990 00:00:00 UTC

        return ($time - 2840140800) + 631152000;
    }
}

