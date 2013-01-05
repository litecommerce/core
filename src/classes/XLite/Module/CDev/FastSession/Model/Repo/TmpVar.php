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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\FastSession\Model\Repo;

/**
 * Temporary variables repository
 */
abstract class TmpVar extends \XLite\Model\Repo\TmpVar implements \XLite\Base\IDecorator
{
    const STORAGE_PREFIX = 'tmpvar';

    /**
     * Set variable
     *
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     *
     * @return void
     */
    public function setVar($name, $value)
    {
        $this->getStorage()->$name = $value;
    }

    /**
     * Get variable
     *
     * @param string $name Variable name
     *
     * @return mixed
     */
    public function getVar($name)
    {
        return $this->getStorage()->$name;
    }

    /**
     * Get storage
     *
     * @return \XLite\Module\CDev\FastSession\Core\Storage
     */
    protected function getStorage()
    {
        if (!isset($this->storage)) {
            $this->storage = new \XLite\Module\CDev\FastSession\Core\Storage(static::STORAGE_PREFIX, 0);
        }

        return $this->storage;
    }
    
}
