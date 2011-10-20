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

namespace XLite\Core;

/**
 * DB-based temporary variables
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class TmpVars extends \XLite\Base\Singleton
{
    /**
     * Getter
     *
     * @param string $name Name
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __get($name)
    {
        return ($var = $this->getVar($name)) ? unserialize($var->getValue()) : null;
    }

    /**
     * Setter
     *
     * @param string $name  Name
     * @param mixed  $value Value
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __set($name, $value)
    {
        $var = $this->getVar($name);

        if (isset($value)) {
            $data = array('value' => serialize($value));

            if (!isset($var)) {
                $var = $this->getRepo()->insert($data + array('name' => $name));

            } else {
                $this->getRepo()->update($var, $data);
            }

        } elseif ($var) {
            $this->getRepo()->delete($var);
        }
    }

    /**
     * Check if value is set
     * 
     * @param string $name Variable name to check
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __isset($name)
    {
        return !is_null($this->getVar($name));
    }

    /**
     * Search var in DB table
     *
     * @param string $name Var name
     *
     * @return \XLite\Model\TmpVar
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getVar($name)
    {
        return $this->getRepo()->findOneBy(array('name' => $name));
    }

    /**
     * Return the Doctrine repository
     *
     * @return \XLite\Model\Repo\TmpVar
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRepo()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\TmpVar');
    }
}
