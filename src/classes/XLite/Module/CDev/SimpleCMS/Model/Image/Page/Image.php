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

namespace XLite\Module\CDev\SimpleCMS\Model\Image\Page;

/**
 * Page image
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Entity (repositoryClass="\XLite\Module\CDev\SimpleCMS\Model\Repo\Image\Page\Image")
 * @Table  (name="page_images")
 */
class Image extends \XLite\Model\Base\Image
{
    /**
     * Relation to a page entity
     *
     * @var   \XLite\Module\CDev\SimpleCMS\Model\Page
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToOne   (targetEntity="XLite\Module\CDev\SimpleCMS\Model\Page", inversedBy="image")
     * @JoinColumn (name="page_id", referencedColumnName="id")
     */
    protected $page;

}
