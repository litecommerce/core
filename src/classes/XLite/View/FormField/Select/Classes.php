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
 * Category selector
 *
 */
class Classes extends \XLite\View\FormField\Select\Multiple
{

    /**
     * getCSSFiles
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/select_classes.css';

        return $list;
    }

    /**
     * getJSFiles
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/select_classes.js';

        return $list;
    }

    /**
     * Return field template
     *
     * @return string
     */
    protected function getFieldTemplate()
    {
        return 'select_classes.tpl';
    }

    /**
     * Return class list
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        $list = array();
        foreach (\XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->search() as $class) {
            $list[$class->getId()] = $class->getName();
        }

        return $list;
    }

    /**
     * Return String representation of selected product classes
     *
     * @return string
     */
    protected function getSelectedClassesList()
    {
        $classNames = array();

        foreach ($this->getValue()->toArray() as $class) {
            $classNames[] = $class->getName();
        }

        return implode(', ', $classNames);
    }
}
