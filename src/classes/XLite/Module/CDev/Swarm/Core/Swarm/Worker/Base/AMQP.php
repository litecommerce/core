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
 * @since     1.0.19
 */

namespace XLite\Module\CDev\Swarm\Core\Swarm\Worker\Base;

/**
 * Abstract AMQP worker
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AMQP extends \Swarm\Worker\AMQP
{
    /**
     * Get pid's files directory 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    public static function getPidsDirectory()
    {
        return sys_get_temp_dir();
    }

    /**
     * Constructor
     *
     * @param \Swarm\Manager $manager Manager
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(\Swarm\Manager $manager)
    {
        parent::__construct($manager);

        file_put_contents(
            static::getPidsDirectory() . LC_DS . 'xlite.worker.' . posix_getpid() . '.pid',
            posix_getpid()
        );
    }

    /**
     * Get AMQP server settings
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAMQPServerSettings()
    {
        return array();
    }

    /**
     * Initialize connection
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function initializeConnection()
    {
        $driver = new \XLite\Core\EventDriver\AMQP;

        $this->connection = null;
        $this->channel = $driver->getChannel();
    }

}

