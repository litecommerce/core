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
     * Image Magick installation path
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $imageMagick = '';

    /**
     * Return Image Magick executable
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    public static function getImageMagickExecutable()
    {
        $imageMagickPath = \Includes\Utils\ConfigParser::getOptions(array('images', 'image_magick_path'));

        return !empty($imageMagickPath)
            ? \Includes\Utils\FileManager::findExecutable($imageMagickPath . 'convert')
            : '';
    }

    /**
     * Check - enabled or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function isEnabled()
    {
        return parent::isEnabled()
            && (bool) self::getImageMagickExecutable();
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
     * Resize procedure
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
        $new = tempnam(LC_DIR_TMP, 'image.new');

        $result = $this->execFilmStripLook($new);

        if (0 === $result) {

            $result = $this->execResize($new, $width, $height);

            if (0 === $result) {
                $this->image = $new;
            }
        }

        return 0 === $result;
    }

    /**
     * Execution of preparing film strip look
     *
     * @param string $newImage File path to new image
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function execFilmStripLook($newImage)
    {
        exec(
            '"' . self::getImageMagickExecutable()
                . '" ' . $this->image . ' -coalesce '
                . $newImage,
            $output,
            $result
        );

        return $result;
    }

    /**
     * Execution of resizing
     *
     * @param string  $newImage File path to new image
     * @param integer $width    Width
     * @param integer $height   Height
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function execResize($newImage, $width, $height)
    {
        exec(
            '"' . self::getImageMagickExecutable() . '" '
                . $newImage
                . ' -adaptive-resize '
                . $width . 'x' . $height . ' '
                . $newImage,
            $output,
            $result
        );

        return $result;
    }
}
