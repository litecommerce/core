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
 * @since     1.0.24
 */

namespace XLite\Model\Base;

/**
 * Abstract catalog model
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @MappedSuperclass
 * @HasLifecycleCallbacks
 */
abstract class Catalog extends \XLite\Model\Base\I18n
{
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
}
