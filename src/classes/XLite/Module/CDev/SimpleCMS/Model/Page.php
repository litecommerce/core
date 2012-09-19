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

namespace XLite\Module\CDev\SimpleCMS\Model;

/**
 * Page 
 * 
 * @see   ____class_see____
 * @since 1.0.21
 *
 * @Entity
 * @Table  (name="pages",
 *      indexes={
 *          @Index (name="enabled", columns={"enabled"})
 *      }
 * )
 */
class Page extends \XLite\Model\AEntity
{
    /**
     * Unique ID
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string")
     */
    protected $name;

    /**
     * Is menu enabled or not
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = true;

    /**
     * Clean URL
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255, unique=true, nullable=true)
     */
    protected $cleanURL;

    /**
     * One-to-one relation with page_images table
     *
     * @var   \XLite\Module\CDev\SimpleCMS\Model\Image\Page\Image
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToOne  (targetEntity="XLite\Module\CDev\SimpleCMS\Model\Image\Page\Image", mappedBy="page", cascade={"all"})
     */
    protected $image;

    /**
     * Teaser
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $teaser;

    /**
     * Content 
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $body;

    /**
     * Meta keywords 
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $metaKeywords;

    /**
     * Lifecycle callback
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     *
     * @PrePersist
     * @PreUpdate
     */
    public function prepareBeforeSave()
    {
        if (\XLite\Core\Converter::isEmptyString($this->getCleanURL())) {
            $this->setCleanURL(null);
        }
    }

    /**
     * Get front URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getFrontURL()
    {
        return $this->getId()
            ? \XLite::getInstance()->getShopURL(\XLite\Core\Converter::buildURL('page', '', array('id' => $this->getId()), 'cart.php', true))
            : null;
    }

}
