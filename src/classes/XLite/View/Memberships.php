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

namespace XLite\View;

/**
 * Membership modify widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Memberships extends \XLite\View\Dialog
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMemberships()
    {
        $list = \XLite\Core\Database::getRepo('\XLite\Model\Membership')->findAllMemberships();

        // TODO - add linked profiles calculataion

        $language = \XLite::getController()->getCurrentLanguage();

        $result = array();
        foreach ($list as $m) {
            $result[$m->membership_id] = array(
                'name'    => $m->getSoftTranslation($language)->name,
                'orderby' => $m->orderby,
                'active'  => $m->active,
            );
        }

        return $result;
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'memberships';
    }

    /**
     * Get next orderby
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getNextOrderBy()
    {
        $orderby = 0;

        foreach ($this->getMemberships() as $m) {
            $orderby = max($orderby, $m['orderby'] + 1);
        }

        return $orderby;
    }
}
