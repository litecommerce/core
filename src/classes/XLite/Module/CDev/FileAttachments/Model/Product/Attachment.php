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
 * @since     1.0.10
 */

namespace XLite\Module\CDev\FileAttachments\Model\Product;

/**
 * Product attchament 
 * 
 * @see   ____class_see____
 * @since 1.0.10
 *
 * @Entity
 * @Table  (name="product_attachments",
 *      indexes={
 *          @Index (name="o", columns={"orderby"})
 *      }
 * )
 */
class Attachment extends \XLite\Model\Base\I18n
{
    // {{{ Collumns

    /**
     * Unique id
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.10
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Sort position
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.10
     *
     * @Column (type="integer")
     */
    protected $orderby = 0;

    // }}}

    // {{{ Associations

    /**
     * Relation to a product entity
     *
     * @var   \XLite\Model\Product
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @ManyToOne  (targetEntity="XLite\Model\Product", inversedBy="attachments")
     * @JoinColumn (name="product_id", referencedColumnName="product_id")
     */
    protected $product;

    /**
     * Relation to a product entity
     *
     * @var   \XLite\Module\CDev\FileAttachments\Model\Product\Attachment\Storage
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToOne  (targetEntity="XLite\Module\CDev\FileAttachments\Model\Product\Attachment\Storage", mappedBy="attachment", cascade={"all"}, fetch="EAGER")
     */
    protected $storage;

    // }}}

    // {{{ Getters / setters

    /**
     * Get storage 
     * 
     * @return \XLite\Module\CDev\FileAttachments\Model\Product\Attachment\Storage
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function getStorage()
    {
        if (!$this->storage) {
            $this->setStorage(new \XLite\Module\CDev\FileAttachments\Model\Product\Attachment\Storage);
            $this->storage->setAttachment($this);
        }

        return $this->storage;
    }

    /**
     * Get public title 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function getPublicTitle()
    {
        return $this->getTitle() ?: $this->getStorage()->getFileName();
    }

    // }}}
}

