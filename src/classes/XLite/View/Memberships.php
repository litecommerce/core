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

namespace XLite\View;

/**
 * Membership modify widget
 *
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Memberships extends \XLite\View\Dialog
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'memberships';

        return $result;
    }

    /**
     * Get memberships
     *
     * @return array
     */
    public function getMemberships()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Membership')->findAllMemberships();
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'memberships';
    }

    /**
     * Get next orderby
     *
     * @return integer
     */
    protected function getNextOrderBy()
    {
        $orderby = 0;

        foreach ($this->getMemberships() as $m) {

            $orderby = max($orderby, $m->getOrderby()) + 1;
        }

        return $orderby;
    }
}
