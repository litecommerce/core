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

namespace XLite\Controller\Admin;

/**
 * Memberships management page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Memberships extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Check ACL permissions
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage users');
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Membership levels';
    }

    /**
     * Update membership list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
                       ->setParameter('name', $row['membership'])
                       ->setParameter('id', $id)
                       ->setMaxResults(1)
                       ->getSingleResult();

                    if (!is_null($duplicate)) {
                        \XLite\Core\TopMessage::addWarning(
                            '"' . $row['membership'] . '" ' . ' membership name is already in use. '
                            . 'Please specify another name for this membership level'
                        );

                    } else {
                        $m->getTranslation($code)->setName($row['membership']);
                    }

                    $m->setOrderby(intval($row['orderby']));
                    $m->setActive(isset($row['active']) && '1' == $row['active']);

                    \XLite\Core\Database::getEM()->persist($m);

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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDelete()
    {
        $ids = \XLite\Core\Request::getInstance()->deleted_memberships;

        if (is_array($ids) && $ids) {
            list($keys, $data) = \XLite\Core\Database::prepareArray($ids, 'id');
            $list = \XLite\Core\Database::getRepo('\XLite\Model\Membership')->createQueryBuilder()
                ->where('m.membership_id IN (' . implode(', ', $keys) . ')')
                ->setParameters($data)
                ->getResult();

            foreach ($list as $m) {
                \XLite\Core\Database::getEM()->remove($m);
            }
            \XLite\Core\Database::getEM()->flush();

            // TODO - remove membership id from profiles
        }
    }

    /**
     * Add new membership
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionAdd()
    {
        $data = \XLite\Core\Request::getInstance()->new_membership;

        if (!is_array($data)) {
            // TODO - add top message

        } elseif (!isset($data['membership']) || !$data['membership']) {
            // TODO - add top message

        } elseif (\XLite\Core\Database::getRepo('\XLite\Model\Membership')->findOneByName($data['membership'], false)) {
            // TODO - add top message

        } else {
            $code = $this->getCurrentLanguage();
            $membership = new \XLite\Model\Membership();
            $membership->setOrderby(intval($data['orderby']));
            $membership->getTranslation($code)->setName($data['membership']);

            \XLite\Core\Database::getEM()->persist($membership);
            \XLite\Core\Database::getEM()->flush();
        }
    }
}
