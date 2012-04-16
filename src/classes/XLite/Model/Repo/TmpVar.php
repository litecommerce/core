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

namespace XLite\Model\Repo;

/**
 * Temporary variables repository
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class TmpVar extends \XLite\Model\Repo\ARepo
{
    /**
     * Set variable 
     * 
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function setVar($name, $value)
    {
        $entity = $this->findOneBy(array('name' => $name));

        if (!$entity) {
            $entity = new \XLite\Model\TmpVar;
            $entity->setName($name);
            \XLite\Core\Database::getEM()->persist($entity);
        }

        $entity->setValue($value);

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Get variable 
     * 
     * @param string $name Variable name
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function getVar($name)
    {
        $entity = $this->findOneBy(array('name' => $name));

        return $entity ? $entity->getValue() : null;
    }
}

