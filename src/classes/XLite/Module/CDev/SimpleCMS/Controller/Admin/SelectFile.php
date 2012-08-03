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
 * @page  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.7
 */

namespace XLite\Module\CDev\SimpleCMS\Controller\Admin;

/**
 * Select File controller
 *
 * @see   ____class_see____
 * @since 1.0.7
 */
class SelectFile extends \XLite\Controller\Admin\SelectFile implements \XLite\Base\IDecorator
{
    /**
     * Return parameters array for "Page" target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function getParamsObjectPage()
    {
        return array(
            'id'   => \XLite\Core\Request::getInstance()->objectId,
        );
    }

    // {{{ Page image

    /**
     * Common handler for page images.
     *
     * @param string $methodToLoad Method to use for getting images
     * @param array  $paramsToLoad Parameters to use in image getter method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectPageImage($methodToLoad, array $paramsToLoad)
    {
        $pageId = intval(\XLite\Core\Request::getInstance()->objectId);

        $page = \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleCMS\Model\Page')->find($pageId);

        $image = $page->getImage();

        if (!$image) {
            $image = new \XLite\Module\CDev\SimpleCMS\Model\Image\Page\Image();
        }

        if (call_user_func_array(array($image, $methodToLoad), $paramsToLoad)) {

            $image->setPage($page);

            $page->setImage($image);

            \XLite\Core\Database::getEM()->persist($image);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The image has been updated'
            );

        } else {
            \XLite\Core\TopMessage::addError(
                'Failed to update page image'
            );
        }
    }

    /**
     * "Upload" handler for page images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUploadPageImage()
    {
        $this->doActionSelectPageImage('loadFromRequest', array('uploaded_file'));
    }

    /**
     * "URL" handler for page images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUrlPageImage()
    {
        $this->doActionSelectPageImage(
            'loadFromURL',
            array(
                \XLite\Core\Request::getInstance()->url,
                (bool) \XLite\Core\Request::getInstance()->url_copy_to_local
            )
        );
    }

    /**
     * "Local file" handler for page images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectLocalPageImage()
    {
        $file = \XLite\View\BrowseServer::getNormalizedPath(\XLite\Core\Request::getInstance()->local_server_file);

        $this->doActionSelectPageImage(
            'loadFromLocalFile',
            array($file)
        );
    }

    // }}}

}
