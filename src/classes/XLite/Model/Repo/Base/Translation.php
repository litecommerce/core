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

namespace XLite\Model\Repo\Base;

/**
 * Common translation repository
 *
 */
class Translation extends \XLite\Model\Repo\ARepo
{
    /**
     * Find one by record
     *
     * @param array                $data   Record
     * @param \XLite\Model\AEntity $parent Parent model OPTIONAL
     *
     * @return \XLite\Model\AEntity|void
     */
    public function findOneByRecord(array $data, \XLite\Model\AEntity $parent = null)
    {
        if (empty($data['code'])) {
            $data['code'] = \XLite\Model\Base\Translation::DEFAULT_LANGUAGE;
        }

        return isset($parent) ? $parent->getTranslation($data['code']) : parent::findOneByRecord($data, $parent);
    }

    /**
     * Get repository type
     *
     * @return string
     */
    public function getRepoType()
    {
        return isset($this->_class->associationMappings['owner'])
            ? \XLite\Core\Database::getRepo($this->_class->associationMappings['owner']['targetEntity'])->getRepoType()
            : parent::getRepoType();
    }
}
