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

namespace XLite\View\FormField\Select;

/**
 * \XLite\View\FormField\Select\State
 *
 */
class State extends \XLite\View\FormField\Select\Regular
{
    /**
     * Widget parameters name
     */
    const PARAM_HAS_SELECT_ONE = 'hasSelectOne';

    /**
     * "Select one"
     *
     * @return boolean
     */
    public function hasSelectOne()
    {
        return (bool)$this->getParam(static::PARAM_HAS_SELECT_ONE);
    }

    /**
     * Assemble classes
     *
     * @param array $classes Classes
     *
     * @return array
     */
    protected function assembleClasses(array $classes)
    {
        $classes = parent::assembleClasses($classes);

        if (!$this->hasSelectOne()) {

            $classes[] = 'no-select-one';
        }

        return $classes;
    }

    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return 'select_state.tpl';
    }

    /**
     * getDefaultOptions
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\State')->findAllStates();
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_HAS_SELECT_ONE  => new \XLite\Model\WidgetParam\Bool('Has "Select one"', 1),
        );
    }
}
