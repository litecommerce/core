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
     * Return entity class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract protected function getEntityClass();

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
     * Check ACL permissions
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage catalog');
    }

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

        if (parent::getPostedData('autogenerateCleanURL') && (!isset($field) || 'cleanURL' === $field)) {
            $value = $this->generateCleanURL(parent::getPostedData('name'));

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
     * Return maximum length of the "cleanURL" model field.
     * Function is public since it's used in templates
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function getCleanURLMaxLength()
    {
        return \XLite\Core\Database::getRepo($this->getEntityClass())->getFieldInfo('cleanURL', 'length');
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

            $suffix    = '';
            $increment = 1;
            
            while (\XLite\Core\Database::getRepo($this->getEntityClass())->findOneByCleanURL($result . $suffix)) {
                $suffix = $separator . $increment++;
            }   
            
            if (!empty($suffix)) {
                // DO NOT change call order
                $this->setCleanURLWarning($result, $suffix);
                $result .= $suffix;
            }
        }

        return $result;
    }

    /**
     * Set warning
     *
     * @param string $cleanURL Clean URL
     * @param string $suffix   Suffix
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setCleanURLWarning($cleanURL, $suffix)
    {
        \XLite\Core\TopMessage::addWarning(
            'Since the "{{clean_url}}" clean URL is already defined, the "{{suffix}}" suffix has been added to it',
            array('clean_url' => $cleanURL, 'suffix' => $suffix)
        );
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
        $form = \Includes\Pattern\Factory::create($this->getFormClass());
        $data = $form->getRequestData();
        $util = '\Includes\Utils\ArrayManager';
        $pref = $this->getPrefixPostedData();

        \XLite\Core\Request::getInstance()->mapRequest(
            array(
                $pref => array(
                    'cleanURL' => $util::getIndex($util::getIndex($data, $pref), 'cleanURL'),
                )
            )
        );

        if ($form->getValidationMessage()) {
            \XLite\Core\TopMessage::addError($form->getValidationMessage());
            
        } elseif ($this->isNew()) {
            $this->doActionAdd(); 
            
        } else {
            $this->doActionUpdate();
        }
    }

    // }}}
}
