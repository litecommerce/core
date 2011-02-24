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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Model\Repo\Base;

/**
 * Common translation repository
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Translation extends \XLite\Model\Repo\ARepo
{
    /**
     * Find one by record 
     * 
     * @param array                $data   Record
     * @param \XLite\Model\AEntity $parent Parent model
     *  
     * @return \XLite\Model\AEntity|void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findOneByRecord(array $data, \XLite\Model\AEntity $parent = null)
    {
        $data['code'] = (isset($data['code']) && $data['code'])
            ? $data['code']
            : \XLite\Model\Base\Translation::DEFAULT_LANGUAGE;

        return $parent
            ? $parent->getTranslation($data['code'])
            : parent::findOneByRecord($data, $parent);
    }

    /**
     * Get repository type 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRepoType()
    {
        return isset($this->_class->associationMappings['owner'])
            ? \XLite\Core\Database::getRepo($this->_class->associationMappings['owner']['targetEntity'])->getRepoType()
            : parent::getRepoType();
    }
}
