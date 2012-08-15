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

namespace XLite\Model;

/**
 * Order history events
 *
 *
 * @Entity
 * @Table (name="order_history_events")
 * @HasLifecycleCallbacks
 */
class OrderHistoryEvents extends \XLite\Model\AEntity
{
    /**
     * Order history event unique id
     *
     * @var mixed
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $event_id;

    /**
     * Event creation timestamp
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $date;

    /**
     * Code of event
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $code;

    /**
     * Human-readable description of event
     *
     * @var string
     *
     * @Column (type="string", length=1024, nullable=true)
     */
    protected $description;

    /**
     * Data for human-readable description
     *
     * @var string
     *
     * @Column (type="array")
     */
    protected $data;

    /**
     * Event comment
     *
     * @var string
     *
     * @Column (type="text")
     */
    protected $comment = '';

    /**
     * Event details
     *
     * @var \XLite\Model\OrderHistoryEventsData
     *
     * @OneToMany (targetEntity="XLite\Model\OrderHistoryEventsData", mappedBy="event", cascade={"all"})
     */
    protected $details;

    /**
     * Relation to a order entity
     *
     * @var \XLite\Model\Order
     *
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="events", fetch="LAZY")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Author profile of the event
     *
     * @var \XLite\Model\Profile
     *
     * @ManyToOne   (targetEntity="XLite\Model\Profile", inversedBy="event", cascade={"merge", "detach"})
     * @JoinColumn (name="author_id", referencedColumnName="profile_id")
     */
    protected $author;

    
    /**
     * Prepare order event before save data operation
     *
     * @return void
     *
     * @PrePersist
     * @PreUpdate
     */
    public function prepareBeforeSave()
    {
        if (!is_numeric($this->date)) {
            $this->setDate(time());
        }
    }

    /**
     * Description getter
     *
     * @return string
     */
    public function getDescription()
    {
        return static::t($this->description, $this->getData());
    }

    /**
     * Details setter
     * 
     * @param array $details Array of event details array($name => $value)
     *  
     * @return void
     */
    public function setDetails(array $details)
    {
        if (!empty($details)) {

            foreach ($details as $detail) {

                $data = new \XLite\Model\OrderHistoryEventsData();
                $data->setName($detail['name']);
                $data->setValue($detail['value']);
                $this->addDetails($data);
                $data->setEvent($this);
            }
        }
    }
}
