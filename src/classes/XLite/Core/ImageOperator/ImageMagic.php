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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Core\ImageOperator;

/**
 * ImageMagic 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class ImageMagic extends \XLite\Core\ImageOperator\AImageOperator
{
    /**
     * Image file store
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $image;

    /**
     * Check - enabled or not
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isEnabled()
    {
        return parent::isEnabled()
            && (bool)\Includes\Utils\FileManager::findExecutable('convert');
    }

    /**
     * Set image
     *
     * @param \XLite\Model\Base\Image $image Image
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setImage(\XLite\Model\Base\Image $image)
    {
        parent::setImage($image);

        $this->image = tempnam(LC_DIR_TMP, 'image');

        file_put_contents($this->image, $image->getBody());
    }

    /**
     * Get image content
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getImage()
    {
        return file_get_contents($this->image);
    }

    /**
     * Resize
     *
     * @param integer $width  Width
     * @param integer $height Height
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function resize($width, $height)
    {
        $path = \Includes\Utils\FileManager::findExecutable('convert');

        $new = tempnam(LC_DIR_TMP, 'image.new');

        $output = array();
        $return = 0;
        exec($path . ' ' . $this->image . ' -coalesce ' . $new, $output, $return);

        if (!$return) {
            exec($path . ' ' . $new . ' -adaptive-resize ' . $width . 'x' . $height . ' ' . $new, $output, $return);

            if (!$return) {
                $this->image = $new;
            }
        }

        return !$return;
    }
}
