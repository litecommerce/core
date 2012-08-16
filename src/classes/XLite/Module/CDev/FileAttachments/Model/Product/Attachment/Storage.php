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

namespace XLite\Module\CDev\FileAttachments\Model\Product\Attachment;

/**
 * Product attchament's storage 
 * 
 *
 * @Entity
 * @Table  (name="product_attachment_storages")
 */
class Storage extends \XLite\Model\Base\Storage
{
    // {{{ Associations

    /**
     * Relation to a attachment
     *
     * @var \XLite\Module\CDev\FileAttachments\Model\Product\Attachment
     *
     * @OneToOne  (targetEntity="XLite\Module\CDev\FileAttachments\Model\Product\Attachment", inversedBy="storage")
     * @JoinColumn (name="attachment_id", referencedColumnName="id")
     */
    protected $attachment;

    // }}}

    // {{{ Service operations

    /**
     * Get valid file system storage root
     *
     * @return string
     */
    protected function getValidFileSystemRoot()
    {
        $path = parent::getValidFileSystemRoot();

        if (!file_exists($path . LC_DS . '.htaccess')) {
            file_put_contents(
                $path . LC_DS . '.htaccess',
                'Options -Indexes' . PHP_EOL
                . 'Allow from all' . PHP_EOL
            );
        }

        return $path;
    }

    /**
     * Assemble path for save into DB
     *
     * @param string $path Path
     *
     * @return string
     */
    protected function assembleSavePath($path)
    {
        return $this->getAttachment()->getProduct()->getProductId() . LC_DS . parent::assembleSavePath($path);
    }

    /**
     * Get valid file system storage root
     *
     * @return string
     */
    protected function getStoreFileSystemRoot()
    {
        $path = parent::getStoreFileSystemRoot() . $this->getAttachment()->getProduct()->getProductId() . LC_DS;
        \Includes\Utils\FileManager::mkdirRecursive($path);

        return $path;
    }

    // }}}
}

