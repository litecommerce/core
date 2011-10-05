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
 * @since     1.0.7
 */

namespace XLite\Controller\Admin;

/**
 * Select File controller
 *
 * @see   ____class_see____
 * @since 1.0.7
 */
class SelectFile extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Upload file';
    }

    /**
     * getModelFormClass
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModelFormClass()
    {
        return '\XLite\View\Model\FileDialog\Select';
    }

    /**
     * Return target for redirect URL. Object string should contain the target name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function getObjectTarget()
    {
        return $this->getModelForm()->getObject();
    }

    /**
     * Return array with parameters for redirect URL. Inner method.
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function getParamsObject()
    {
        $methodParamsObject = 'getParamsObject' . \XLite\Core\Converter::convertToCamelCase($this->getObjectTarget());

        return method_exists($this, $methodParamsObject)
            ? call_user_func(array($this, $methodParamsObject))
            : array();
    }

    /**
     * Return parameters array for "Product" target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function getParamsObjectProduct()
    {
        return array(
            'page'       => \XLite\Core\Request::getInstance()->fileObject,
            'product_id' => \XLite\Core\Request::getInstance()->objectId,
        );
    }

    /**
     * Return parameters array for "Category" target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function getParamsObjectCategory()
    {
        return array(
            'mode'        => 'modify',
            'category_id' => \XLite\Core\Request::getInstance()->objectId,
        );
    }

    /**
     * Main 'Select' action handler.
     *  - calls "doActionSelect<file_select_action><object><file_object>()" handler
     *  - set return URL which is set by "getParamsObject<object>()" getter
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelect()
    {
        $actionName = 'doActionSelect'
            . \XLite\Core\Converter::convertToCamelCase(\XLite\Core\Request::getInstance()->file_select)
            . \XLite\Core\Converter::convertToCamelCase($this->getObjectTarget())
            . \XLite\Core\Converter::convertToCamelCase(\XLite\Core\Request::getInstance()->fileObject);

        if (method_exists($this, $actionName)) {
            call_user_func(array($this, $actionName));
        }

        $this->setReturnURL(
            $this->buildURL(
                $this->getRedirectTarget(),
                '',
                $this->getParamsObject()
            )
        );
    }

    /**
     * Get redirect target 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getRedirectTarget()
    {
        return $this->getObjectTarget();
    }

    // {{{ Category image

    /**
     * Common handler for category images.
     *
     * @param string $methodToLoad Method to use for getting images
     * @param array  $paramsToLoad Parameters to use in image getter method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectCategoryImage($methodToLoad, array $paramsToLoad)
    {
        $categoryId = intval(\XLite\Core\Request::getInstance()->objectId);

        $category = \XLite\Core\Database::getRepo('XLite\Model\Category')->find($categoryId);

        $image = $category->getImage();

        if (!$image) {
            $image = new \XLite\Model\Image\Category\Image();
        }

        if (call_user_func_array(array($image, $methodToLoad), $paramsToLoad)) {

            $image->setCategory($category);

            $category->setImage($image);

            \XLite\Core\Database::getEM()->persist($image);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The image has been updated'
            );

        } else {
            \XLite\Core\TopMessage::addError(
                'Failed to update category image'
            );
        }
    }

    /**
     * "Upload" handler for category images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUploadCategoryImage()
    {
        $this->doActionSelectCategoryImage('loadFromRequest', array('uploaded_file'));
    }

    /**
     * "URL" handler for category images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUrlCategoryImage()
    {
        $this->doActionSelectCategoryImage(
            'loadFromURL',
            array(
                \XLite\Core\Request::getInstance()->url,
                \XLite\Core\Request::getInstance()->url_copy_to_local
            )
        );
    }

    /**
     * "Local file" handler for category images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectLocalCategoryImage()
    {
        $file = \XLite\View\BrowseServer::getNormalizedPath(\XLite\Core\Request::getInstance()->local_server_file);

        $this->doActionSelectCategoryImage(
            'loadFromLocalFile',
            array($file)
        );
    }

    // }}}

    // {{{ Product images

    /**
     * Common handler for product images.
     *
     * @param string $methodToLoad Method to use for getting images
     * @param array  $paramsToLoad Parameters to use in image getter method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectProductImages($methodToLoad, array $paramsToLoad)
    {
        $productId = intval(\XLite\Core\Request::getInstance()->objectId);

        $product = \XLite\Core\Database::getRepo('\XLite\Model\Product')->find($productId);

        if (!isset($product)) {
            $product = new \XLite\Model\Product();
        }

        $image = new \XLite\Model\Image\Product\Image();

        // TODO: methodToLoad - move to this controller from Image model
        if (call_user_func_array(array($image, $methodToLoad), $paramsToLoad)) {

            $image->setProduct($product);

            $product->getImages()->add($image);

            \XLite\Core\Database::getEM()->persist($image);
            \XLite\Core\Database::getEM()->flush();

            \XLite\Core\TopMessage::addInfo(
                'The detailed image has been successfully added'
            );

        } else {
            \XLite\Core\TopMessage::addError(
                'Failed to add detailed image'
            );
        }
    }

    /**
     * "Upload" handler for product images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUploadProductImages()
    {
        $this->doActionSelectProductImages('loadFromRequest', array('uploaded_file'));
    }

    /**
     * "URL" handler for product images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUrlProductImages()
    {
        $this->doActionSelectProductImages(
            'loadFromURL',
            array(
                \XLite\Core\Request::getInstance()->url,
                \XLite\Core\Request::getInstance()->url_copy_to_local
            )
        );
    }

    /**
     * "Local file" handler for product images.
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectLocalProductImages()
    {
        $file = \XLite\View\BrowseServer::getNormalizedPath(\XLite\Core\Request::getInstance()->local_server_file);

        $this->doActionSelectProductImages(
            'loadFromLocalFile',
            array($file)
        );
    }

    // }}} 
}
