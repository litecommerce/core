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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Core\ImageOperator;

class ImageMagic extends \XLite\Core\ImageOperator\AImageOperator
{
    /**
     * Image file store
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $image;

    /**
     * Check - enabled or not
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setImage(\XLite\Model\Base\Image $image)
    {
        parent::setImage($image);

        $this->image = tempnam(LC_TMP_DIR, 'image');

        file_put_contents($this->image, $image->getBody());
    }

    /**
     * Get image content
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function resize($width, $height)
    {
        $path = \Includes\Utils\FileManager::findExecutable('convert');

        $new = tempnam(LC_TMP_DIR, 'image.new');

        $exec = $path . ' ' . $this->image . ' -adaptive-resize ' . $width . 'x' . $height . ' ' . $new;
        $output = array();
        $return = 0;
        exec($exec, $output, $return);

        if (!$return) {
            $this->image = $new;
        }

        return !$return;
    }
}
