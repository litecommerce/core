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
 * @since     1.0.24
 */

namespace XLite\View\FormField\Input\Text\Base;

/**
 * Autocomplete 
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
abstract class Autocomplete extends \XLite\View\FormField\Input\Text
{
    /**
     * Get dictionary name
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract protected function getDictionary();

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'form_field/input/text/autocomplete.js';

        return $list;
    }

    /**
     * Assemble classes
     *
     * @param array $classes Classes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function assembleClasses(array $classes)
    {
        $classes = parent::assembleClasses($classes);

        $classes[] = 'auto-complete';

        return $classes;
    }

    /**
     * setCommonAttributes
     *
     * @param array $attrs Field attributes to prepare
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setCommonAttributes(array $attrs)
    {
        $attrs = parent::setCommonAttributes($attrs);

        $attrs['data-source-url'] = $this->getURL();

        return $attrs;
    }

    /**
     * Get URL 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getURL()
    {
        return \XLite\Core\Converter::buildURL(
            'autocomplete',
            '',
            array('dictionary' => $this->getDictionary(), 'term' => '$term$')
        );
    }
}

