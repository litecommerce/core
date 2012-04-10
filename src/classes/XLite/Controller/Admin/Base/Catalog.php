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

namespace XLite\Controller\Admin\Base;

/**
 * Catalog
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Catalog extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Use this char as separator, if the default one is not set in the config
     */
    const CLEAN_URL_DEFAULT_SEPARATOR = '-';

    // {{{ Abstract methods

    /**
     * Check if we need to create new product or modify an existsing one
     *
     * NOTE: this function is public since it's neede for widgets
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.21
     */
    abstract public function isNew();

    /**
     * Return class name for the controller main form
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.21
     */
    abstract protected function getFormClass();

    /**
     * Check if specified clean URL is unique or not
     *
     * @param string $cleanURL Clean URL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.21
     */
    abstract protected function checkCleanURL($cleanURL);

    /**
     * Add new entity
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.21
     */
    abstract protected function doActionAdd();

    /**
     * Modify existing entity
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.21
     */
    abstract protected function doActionUpdate();

    // }}}

    // {{{ Data management

    /**
     * Return current (or default) category object
     *
     * @return \XLite\Model\Category
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategory()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory($this->getCategoryId());
    }

    /**
     * Get posted data
     *
     * @param string $field Name of the field to retrieve OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPostedData($field = null)
    {
        $result = parent::getPostedData($field);

        if (!isset($field) || 'cleanURL' === $field) {
            $value = $this->generateCleanURL(
                parent::getPostedData(parent::getPostedData('autogenerateCleanURL') ? 'name' : 'cleanURL')
            );

            if (isset($field)) {
                $result = $value;
                
            } else {
                $result['cleanURL'] = $value;
            }
        }

        return $result;
    }

    // }}}

    // {{{ Clean URL routines

    /**
     * For validation in forms
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.21
     */
    public function getCleanURLPattern()
    {
        return '/[\w' . static::CLEAN_URL_DEFAULT_SEPARATOR . ']+/S';
    }

    /**
     * Set error
     *
     * @param string $cleanURL Clean URL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setCleanURLError($cleanURL)
    {
        \XLite\Core\TopMessage::addError(
            'The "{{clean_url}}" clean URL is already defined',
            array('clean_url' => $cleanURL)
        );
    }

    /**
     * Generate clean URL
     *
     * @param string $name Product name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function generateCleanURL($name)
    {
        $result = '';

        if (isset($name)) {
            $separator = \Includes\Utils\ConfigParser::getOptions(array('clean_urls', 'default_separator'));
            $result   .= strtolower(preg_replace('/\W+/S', $separator ?: static::CLEAN_URL_DEFAULT_SEPARATOR, $name));
        }

        return $result;
    }

    // }}}

    // {{{ Action handlers

    /**
     * doActionModify
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function doActionModify()
    {
        if ($this->checkCleanURL($this->getPostedData('cleanURL'))) {
            $form = \Includes\Pattern\Factory::create($this->getFormClass());
            $form->getRequestData();
        
            if ($form->getValidationMessage()) {
                \XLite\Core\TopMessage::addError($form->getValidationMessage());
            
            } elseif ($this->isNew()) {
                $this->doActionAdd(); 
            
            } else {
                $this->doActionUpdate();
            }
        }
    }

    // }}}
}
