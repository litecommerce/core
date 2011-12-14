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
     * Return parameters array for "Import" target
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function getParamsObjectImportExport()
    {
        return array(
            'page'   => 'import',
            'loaded' => 1,
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
                (bool) \XLite\Core\Request::getInstance()->url_copy_to_local
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
                (bool) \XLite\Core\Request::getInstance()->url_copy_to_local
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

    // {{{ Import

    /**
     * Common handler for import
     *
     * @param string $methodToLoad Method to use for getting file
     * @param array  $paramsToLoad Parameters to use in getter method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectImport($methodToLoad, array $paramsToLoad)
    {
        \XLite\Core\Session::getInstance()->importCell = null;
        $methodToLoad .= 'Import';

        $path = call_user_func_array(array($this, $methodToLoad), $paramsToLoad);
        if (is_array($path)) {

            if (!$path[0] && $path[1]) {
                \XLite\Core\TopMessage::addError($path[1]);
            }

            $path = $path[0];
        }

        if ($path) {
            chmod($path, 0644);
            \XLite\Core\Session::getInstance()->importCell = array(
                'path'     => $path,
                'position' => 0,
            );
        }
    }

    /**
     * "Upload" handler for import
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUploadImportExport()
    {
        $this->doActionSelectImport('loadFromRequest', array('uploaded_file'));
    }

    /**
     * "URL" handler for import
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectUrlImportExport()
    {
        $this->doActionSelectImport(
            'loadFromURL',
            array(
                \XLite\Core\Request::getInstance()->url,
            )
        );
    }

    /**
     * "Local file" handler for import
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.7
     */
    protected function doActionSelectLocalImportExport()
    {
        $file = \XLite\View\BrowseServer::getNormalizedPath(\XLite\Core\Request::getInstance()->local_server_file);

        $this->doActionSelectImport(
            'loadFromLocalFile',
            array($file)
        );
    }

    /**
     * Load import file from request
     * 
     * @param string $key Request key
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function loadFromRequestImport($key)
    {
        $result = null;
        $error = null;
        $message = null;

        $cell = isset($_FILES[$key]) ? $_FILES[$key] : null;
        if ($cell) {
            $size = null;
            switch ($cell['error']) {
                case UPLOAD_ERR_OK:
                    $path = \Includes\Utils\FileManager::getUniquePath(LC_DIR_TMP, $cell['name']);
                    if (move_uploaded_file($cell['tmp_name'], $path)) {
                        $result = $path;
                    }
                    break;

                case UPLOAD_ERR_INI_SIZE:
                    $size = ini_get('upload_max_filesize');

                case UPLOAD_ERR_FORM_SIZE:
                    $size = $size ?: \XLite\Core\Request::getInstance()->MAX_FILE_SIZE;
                    $error = 'File size exceeds the maximum size (' . $size . ')';
                    $size = \XLite\Core\Converter::convertShortSizeToHumanReadable($size);
                    $message = \XLite\Core\Translation::lbl('File size exceeds the maximum size', array('size' => $size));
                    break;

                case UPLOAD_ERR_PARTIAL:
                    $error = 'The uploaded file was only partially uploaded';

                case UPLOAD_ERR_NO_FILE:
                    $error = $error ?: 'No file was uploaded';

                case UPLOAD_ERR_NO_TMP_DIR:
                    $error = $error ?: 'Missing a temporary folder';

                case UPLOAD_ERR_CANT_WRITE:
                    $error = $error ?: 'Failed to write file to disk';

                case UPLOAD_ERR_EXTENSION:
                    $message = \XLite\Core\Translation::lbl('The file was not loaded because of a failure on the server.');
                    $error = $error ?: 'File upload stopped by extension';
                    break;
            }
        }

        if ($result && $message) {
            \XLite\Logger::getInstance()->log('Upload file error: ' . $error ?: $message, LOG_ERR);
        }

        return array($result, $message);
    }

    /**
     * Load import file from local file system
     * 
     * @param string $path Local path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function loadFromLocalFileImport($path)
    {
        $result = null;

        $tmpPath = \Includes\Utils\FileManager::getUniquePath(LC_DIR_TMP, basename($path));
        if (copy($path, $tmpPath)) {
            $result = $tmpPath;
        }

        return $result;
    }

    /**
     * Load import file from URL 
     * 
     * @param string $url URL
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function loadFromURLImport($url)
    {
        $result = null;

        $content = \XLite\Core\Operator::getURLContent($url);
        $path = \Includes\Utils\FileManager::getUniquePath(LC_DIR_TMP, basename($path));
        if ($content && file_put_contents($path, $content)) {
            $result = $path;
        }

        return $result;
    } 

    // }}}
}
