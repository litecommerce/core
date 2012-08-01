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
 * DB-based temporary variables
 *
 */
class TmpVars extends \XLite\Base\Singleton
{
    /**
     * Getter
     *
     * @param string $name Name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->getRepo()->getVar($name);
    }

    /**
     * Setter
     *
     * @param string $name  Name
     * @param mixed  $value Value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $this->getRepo()->setVar($name, $value);
    }

    /**
     * Check if value is set
     * 
     * @param string $name Variable name to check
     *  
     * @return boolean
     */
    public function __isset($name)
    {
        return !is_null($this->__get($name));
    }

    /**
     * Unset value
     *
     * @param string $name Variable name to check
     *
     * @return void
     */
    public function __unset($name)
    {
        $this->__set($name, null);
    }

    /**
     * Return the Doctrine repository
     *
     * @return \XLite\Model\Repo\TmpVar
     */
    protected function getRepo()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\TmpVar');
    }
}
