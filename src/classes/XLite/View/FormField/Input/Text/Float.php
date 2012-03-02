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

namespace XLite\View\FormField\Input\Text;

/**
 * Float
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Float extends \XLite\View\FormField\Input\Text\Base\Numeric
{
    /**
     * Widget param names
     */
    const PARAM_E   = 'e';

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

        $list[] = 'form_field/input/text/float.js';

        return $list;
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_E   => new \XLite\Model\WidgetParam\Int('Number of digits after the decimal separator', 2),
        );
    }

    /**
     * Sanitize value
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function sanitize()
    {
       return round(doubleval(parent::sanitize()), $this->getParam(self::PARAM_E));
    }

    /**
     * Assemble validation rules
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function assembleValidationRules()
    {
        $rules = parent::assembleValidationRules();

        $rules[] = 'custom[number]';

        return $rules;
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

        $classes[] = 'float';

        return $classes;
    }

    /**
     * Get default maximum size
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function getDefaultMaxSize()
    {
        return 15;
    }

    /**
     * getCommonAttributes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCommonAttributes()
    {
        $attributes = parent::getCommonAttributes();

        $e = $this->getE();
        if (isset($e)) {
            $attributes['data-e'] = $e;
        }

        return $attributes;
    }

    /**
     * Get mantis
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getE()
    {
        return null;
    }

}
