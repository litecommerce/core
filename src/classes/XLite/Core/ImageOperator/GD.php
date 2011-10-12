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
 * @since     1.0.0
 */

namespace XLite\Core\ImageOperator;

/**
 * GD
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class GD extends \XLite\Core\ImageOperator\AImageOperator
{
    /**
     * MIME types
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected static $types = array(
        'image/jpeg' => 'jpeg',
        'image/jpg'  => 'jpeg',
        'image/gif'  => 'gif',
        'image/xpm'  => 'xpm',
        'image/gd'   => 'gd',
        'image/gd2'  => 'gd2',
        'image/wbmp' => 'wbmp',
        'image/bmp'  => 'wbmp',
        'image/png'  => 'png',
    );

    /**
     * Image resource
     *
     * @var   resource
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $image;

    /**
     * Check - enabled engine or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isEnabled()
    {
        return parent::isEnabled()
            && \XLite\Core\Converter::isGDEnabled();
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
        $this->image = null;

        $result = parent::setImage($image);

        if ($result && $this->getImageType() && $image->getBody()) {
            $func = 'imagecreatefrom' . $this->getImageType();

            if (function_exists($func)) {
                $data = $image->getBody();

                $fn = tempnam(LC_DIR_TMP, 'image');

                file_put_contents($fn, $data);
                unset($data);

                // $func is assembled from 'imagecreatefrom' + image type
                $this->image = $func($fn);
                unlink($fn);

                $result = (bool)$this->image;
            }
        }

        return $result;
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
        $image = null;

        $func = 'image' . $this->getImageType();

        if (function_exists($func)) {

            ob_start();
           // $func is assembled from 'image' + image type
            $result = $func($this->image);
            $image = ob_get_contents();
            ob_end_clean();
        }

        return $image;
    }

    /**
     * Resize
     *
     * @param integer $width  Width
     * @param integer $height Height
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function resize($width, $height)
    {
        $newImage = imagecreatetruecolor($width, $height);
        imagealphablending($newImage, false);
        imagesavealpha($newImage, true);

        $result = imagecopyresampled(
            $newImage,
            $this->image,
            0,
            0,
            0,
            0,
            $width,
            $height,
            $this->width,
            $this->height
        );
        if ($result) {

            imagedestroy($this->image);
            $this->image = $newImage;
            $this->width = $width;
            $this->height = $height;

            if (\XLite::getInstance()->getOptions(array('images', 'unsharp_mask_filter_on_resize'))) {

                include_once LC_DIR_LIB . 'phpunsharpmask.php';

                $unsharpImage = UnsharpMask($this->image);
                if ($unsharpImage) {
                    $this->image = $unsharpImage;
                }
            }

        } else {
            $this->image = $old;
        }

        return $result;
    }

    /**
     * Get image GD-based type
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getImageType()
    {
        return isset(static::$types[$this->getMimeType()]) ? static::$types[$this->getMimeType()] : null;
    }
}
