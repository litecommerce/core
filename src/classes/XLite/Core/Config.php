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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Core;

/**
 * DB-based configuration registry
 *
 */
class Config extends \XLite\Base\Singleton
{
    /**
     * Config (cache)
     *
     * @var \XLite\Core\CommonCell
     */
    protected $config;


    /**
     * Method to access a singleton
     *
     * @return \XLite\Core\CommonCell
     */
    public static function getInstance()
    {
        return parent::getInstance()->readConfig();
    }

    /**
     * Reset state
     *
     * @return void
     */
    public static function updateInstance()
    {
        parent::getInstance()->readConfig(true);
    }


    /**
     * Read config options
     *
     * @param mixed $force ____param_comment____ OPTIONAL
     *
     * @return void
     */
    public function readConfig($force = false)
    {
        if (!isset($this->config) || $force) {
            $this->config = \XLite\Core\Database::getRepo('XLite\Model\Config')->getAllOptions($force);
        }

        return $this->config;
    }

    /**
     * Update and re-read options
     *
     * @return void
     */
    public function update()
    {
        parent::update();

        $this->readConfig(true);
    }
}
