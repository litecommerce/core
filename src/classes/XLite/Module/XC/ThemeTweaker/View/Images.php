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

namespace XLite\Module\XC\ThemeTweaker\View;

/**
 * Images widget
 *
 */
class Images extends \XLite\View\AView
{
    protected $images;

    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        return array_merge(parent::getAllowedTargets(), array('custom_css_images'));
    }

   /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'items_list/model/table/style.css';
        $list[] = 'items_list/model/style.css';
        $list[] = 'modules/XC/ThemeTweaker/images/style.css';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'modules/XC/ThemeTweaker/images/body.tpl';
    }


    /**
     * Get iterator for template files
     *
     * @return \Includes\Utils\FileFilter
     */
    protected function getImagesIterator()
    {
        return new \Includes\Utils\FileFilter(
            $this->getImagesDir()
        );
    }

    /**
     * Get images 
     *
     * @return array
     */
    protected function getImages()
    {
        if (!isset($this->images)) {
            $this->images = array();
            try {
                foreach ($this->getImagesIterator()->getIterator() as $file) {
                    if ($file->isFile()) {
                        $this->images[] = \Includes\Utils\FileManager::getRelativePath($file->getPathname(), $this->getImagesDir());
                    }
                }
            } catch (\Exception $e) {
            }
        }

        return $this->images;
    }

    /**
     * Get image dir
     *
     * @param string $image Image
     *
     * @return string
     */
    protected function getImageUrl($image)
    {
        return \XLite\Core\Layout::getInstance()->getResourceWebPath(
            'theme/images/' . $image,
            \XLite\Core\Layout::WEB_PATH_OUTPUT_URL,
            'custom'
        );
    }

    /**
     * Get images dir
     *
     * @return string
     */
    protected function getImagesDir()
    {
        return \XLite\Module\XC\ThemeTweaker\Main::getThemeDir() . 'images' . LC_DS;
    }
}
