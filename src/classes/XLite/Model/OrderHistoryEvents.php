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

namespace XLite\Model;

/**
 * Order history events
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Table (name="order_history_events")
 */
class OrderHistoryEvents extends \XLite\Model\AEntity
{
    /**
     * Order history event unique id
     *
     * @var   mixed
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $event_id;

    /**
     * Code of event
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $code;

    /**
     * Human-readable description of event
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=1024, nullable=true)
     */
    protected $description;

    /**
     * Data for human-readable description
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $data;

    /**
     * Details of event
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $details;

    /**
     * Event creation timestamp
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $date;

    /**
     * Relation to a order entity
     *
     * @var   \XLite\Model\Order
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Order", inversedBy="events", fetch="LAZY")
     * @JoinColumn (name="order_id", referencedColumnName="order_id")
     */
    protected $order;

    /**
     * Author profile of the event
     *
     * @var   \XLite\Model\Profile
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne   (targetEntity="XLite\Model\Profile", inversedBy="event", cascade={"merge", "detach"})
     * @JoinColumn (name="author_id", referencedColumnName="profile_id")
     */
    protected $author;

    
    /**
     * Data getter
     *
     * @return array
     * @see   ____var_see____
     * @since 1.0.0
     */
    public function getData()
    {
        return unserialize($this->data);
    }

    /**
     * Data setter
     *
     * @param array $data Data to store
     *
     * @return void
     * @see   ____var_see____
     * @since 1.0.0
     */
    public function setData(array $data)
    {
        $this->data = serialize($data);
    }

    /**
     * Description getter
     *
     * @return string
     * @see   ____var_see____
     * @since 1.0.0
     */
    public function getDescription()
    {
        return static::t($this->description, $this->getData());
    }
}
