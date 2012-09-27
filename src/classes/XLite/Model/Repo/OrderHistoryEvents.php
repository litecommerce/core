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

namespace XLite\Model\Repo;

/**
 * Order history events repository
 *
 */
class OrderHistoryEvents extends \XLite\Model\Repo\ARepo
{
    /**
     * Search for events of the given order
     *
     * @param integer $orderId Order identificator
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     */
    public function search($orderId)
    {
        $queryBuilder = $this->createQueryBuilder('ohe')
            ->andWhere('ohe.order = :order_id')
            ->setParameter('order_id', $orderId)
            ->addOrderBy('ohe.date', 'DESC');

        return $queryBuilder->getResult();
    }

    /**
     * Register event to the order
     *
     * @param integer $orderId     Order identificator
     * @param string  $code        Event code
     * @param string  $description Event description
     * @param array   $data        Data for event description OPTIONAL
     * @param string  $comment     Event comment OPTIONAL
     * @param array   $details     Event details OPTIONAL
     *
     * @return void
     */
    public function registerEvent($orderId, $code, $description, array $data = array(), $comment = '', $details = array())
    {
        $event = new \XLite\Model\OrderHistoryEvents(
            array(
                'date'         => time(),
                'code'         => $code,
                'description'  => $description,
                'data'         => $data,
                'comment'      => $comment,
            )
        );

        if (!empty($details)) {
            $event->setDetails($details);
        }

        $order = \XLite\Core\Database::getRepo('XLite\Model\Order')->find($orderId);

        if (\XLite\Core\Auth::getInstance()->getProfile()) {
            $event->setAuthor(\XLite\Core\Auth::getInstance()->getProfile());
        }

        $event->setOrder($order);

        $order->addEvents($event);

        $this->insert($event);
    }
}
