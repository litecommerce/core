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

namespace XLite\Controller\Admin;

/**
 * Memberships management page controller
 *
 */
class Memberships extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage users');
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     */
    public function getTitle()
    {
        return 'Membership levels';
    }

    /**
     * Update membership list
     *
     * @return void
     */
    protected function doActionUpdate()
    {
        $data = \XLite\Core\Request::getInstance()->update_memberships;

        if (!is_array($data)) {
            // TODO - add top message

        } else {
            $code = $this->getCurrentLanguage();
            $flag = false;

            foreach ($data as $id => $row) {
                $m = \XLite\Core\Database::getRepo('\XLite\Model\Membership')->find($id);

                if ($m) {
                    $duplicate = \XLite\Core\Database::getRepo('\XLite\Model\Membership')->createQueryBuilder()
                       ->andWhere('translations.name = :name', 'm.membership_id != :id')
                       ->setParameter('name', $row['name'])
                       ->setParameter('id', $id)
                       ->setMaxResults(1)
                       ->getSingleResult();

                    if (!is_null($duplicate)) {
                        \XLite\Core\TopMessage::addWarning(
                            '"' . $row['name'] . '" ' . ' membership name is already in use. '
                            . 'Please specify another name for this membership level'
                        );

                    } else {

                        \XLite\Core\Database::getRepo('\XLite\Model\Membership')->update($m, $row);
                    }

                    $flag = true;

                } else {
                    \XLite\Core\TopMessage::addError(
                        'Could not find membership ID#' . $id . ' record in the database'
                    );
                }
            }

            if ($flag) {
                \XLite\Core\Database::getEM()->flush();
            }
        }
    }

    /**
     * Delete some membership(s)
     *
     * @return void
     */
    protected function doActionDelete()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Membership')->deleteInBatchById($this->getMembershipIdsToDelete());
    }

    /**
     * Define memberships identificators array to remove
     *
     * @return array
     */
    protected function getMembershipIdsToDelete()
    {
        $ids = \XLite\Core\Request::getInstance()->deleted_memberships;

        return (is_array($ids) && $ids) ? array_flip(\Includes\Utils\ArrayManager::filter($ids, function ($value) {return $value !== '';})) : array();
    }

    /**
     * Add new membership
     *
     * @return void
     */
    protected function doActionAdd()
    {
        $data = \XLite\Core\Request::getInstance()->new_membership;

        if (!is_array($data)) {
            // TODO - add top message

        } elseif (!isset($data['name']) || !$data['name']) {
            // TODO - add top message

        } elseif (\XLite\Core\Database::getRepo('\XLite\Model\Membership')->findOneByName($data['name'], false)) {
            // TODO - add top message

        } else {

            \XLite\Core\Database::getRepo('\XLite\Model\Membership')->insert($data);
        }
    }
}
