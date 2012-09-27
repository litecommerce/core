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

namespace XLite\Module\CDev\FileAttachments\Controller\Admin;

/**
 * Product controller
 * 
 */
class Product extends \XLite\Controller\Admin\Product implements \XLite\Base\IDecorator
{
    // {{{ Pages

    /**
     * Get pages sections
     *
     * @return array
     */
    public function getPages()
    {
        $list = parent::getPages();

        if (!$this->isNew()) {
            $list['attachments'] = 'Attachments';
        }

        return $list;
    }

    /**
     * Get pages templates
     *
     * @return array
     */
    protected function getPageTemplates()
    {
        $list = parent::getPageTemplates();

        if (!$this->isNew()) {
            $list['attachments'] = 'modules/CDev/FileAttachments/product_tab.tpl';
        }

        return $list;
    }

    // }}}

    /**
     * Remove file
     *
     * @return void
     */
    protected function doActionRemoveAttachment()
    {
        $attachment = \XLite\Core\Database::getRepo('XLite\Module\CDev\FileAttachments\Model\Product\Attachment')
            ->find(\XLite\Core\Request::getInstance()->id);

        if ($attachment) {
            $attachment->getProduct()->getAttachments()->removeElement($attachment);
            \XLite\Core\Database::getEM()->remove($attachment);
            \XLite\Core\TopMessage::addInfo('Attachment has been deleted successfully');
            $this->setPureAction(true);

        } else {
            $this->valid = false;
            \XLite\Core\TopMessage::addError('Attachment is not deleted');
        }

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Update files
     *
     * @return void
     */
    protected function doActionUpdateAttachments()
    {
        $changed = false;

        $data = \XLite\Core\Request::getInstance()->data;
        if ($data && is_array($data)) {
            $repository = \XLite\Core\Database::getRepo('XLite\Module\CDev\FileAttachments\Model\Product\Attachment');
            foreach ($data as $id => $row) {
                $attachment = $repository->find($id);

                if ($attachment) {
                    $attachment->map($row);
                    $changed = true;
                }
            }
        }

        if ($changed) {
            \XLite\Core\TopMessage::addInfo('Attachments have been updated successfully');
        }

        \XLite\Core\Database::getEM()->flush();
    }
}
