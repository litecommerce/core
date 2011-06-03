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
 * Abstract image operator enagine
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AImageOperator extends \XLite\Base\Singleton
{
    /**
     * MIME type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $mimeType;

    /**
     * Width
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $width;

    /**
     * Height
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $height;


    /**
     * Get image content
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract public function getImage();

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
    abstract public function resize($width, $height);


    /**
     * Check - enabled engine or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isEnabled()
    {
        return true;
    }

    /**
     * Get cropped dimensions
     *
     * @param integer $w    Original width
     * @param integer $h    Original height
     * @param integer $maxw Maximum width
     * @param integer $maxh Maximum height
     *
     * @return array (new width & height)
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getCroppedDimensions($w, $h, $maxw, $maxh)
    {
        $maxw = max(0, intval($maxw));
        $maxh = max(0, intval($maxh));

        $properties = array(
            'width'  => 0 < $w ? $w : $maxw,
            'height' => 0 < $h ? $h : $maxh,
        );

        if (0 < $w && 0 < $h && (0 < $maxw || 0 < $maxh)) {

            if (0 < $maxw && 0 < $maxh) {
                $kw = $w > $maxw ? $maxw / $w : 1;
                $kh = $h > $maxh ? $maxh / $h : 1;
                $k = $kw < $kh ? $kw : $kh;

            } elseif (0 < $maxw) {
                $k = $w > $maxw ? $maxw / $w : 1;

            } elseif (0 < $maxh) {
                $k = $h > $maxh ? $maxh / $h : 1;

            }

            $properties['width'] = max(1, round($k * $w, 0));
            $properties['height'] = max(1, round($k * $h, 0));
        }

        if (0 == $properties['width']) {
            $properties['width'] = null;
        }

        if (0 == $properties['height']) {
            $properties['height'] = null;
        }

        return array($properties['width'], $properties['height']);
    }


    /**
     * Set image
     *
     * @param \XLite\Model\Base\Image $image Image
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setImage(\XLite\Model\Base\Image $image)
    {
        $this->mimeType = $image->getMime();
        $this->width = $image->getWidth();
        $this->height = $image->getHeight();

        return true;
    }

    /**
     * Get width
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Get height
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Get MIME type
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Resize down  by limits
     *
     * @param integer $width  Width top limit OPTIONAL
     * @param integer $height Height top limt OPTIONAL
     *
     * @return array New width, new height and operation result
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function resizeDown($width = null, $height = null)
    {
        list($newWidth, $newHeight) = self::getCroppedDimensions(
            $this->width,
            $this->height,
            $width,
            $height
        );

        return ($newWidth != $this->width || $newHeight != $this->height)
            ? array($newWidth, $newHeight, $this->resize($newWidth, $newHeight))
            : array($newWidth, $newHeight, false);
    }
}
