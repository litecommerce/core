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

namespace XLite\Controller\Admin\Base;

/**
 * Catalog
 *
 */
abstract class Catalog extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Limit of iterations to generate clean URL
     */
    const CLEAN_URL_CHECK_LIMIT = 1000;

    // {{{ Abstract methods

    /**
     * Check if we need to create new product or modify an existsing one
     *
     * NOTE: this function is public since it's neede for widgets
     *
     * @return boolean
     */
    abstract public function isNew();

    /**
     * Return class name for the controller main form
     *
     * @return string
     */
    abstract protected function getFormClass();

    /**
     * Return entity object
     *
     * @return \XLite\Model\AEntity
     */
    abstract protected function getEntity();

    /**
     * Add new entity
     *
     * @return void
     */
    abstract protected function doActionAdd();

    /**
     * Modify existing entity
     *
     * @return void
     */
    abstract protected function doActionUpdate();

    // }}}

    // {{{ Data management

    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    public function checkACL()
    {
        return parent::checkACL() || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage catalog');
    }

    /**
     * Return current (or default) category object
     *
     * @return \XLite\Model\Category
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
     */
    protected function getPostedData($field = null)
    {
        $result = parent::getPostedData($field);

        if (parent::getPostedData('autogenerateCleanURL') && (!isset($field) || 'cleanURL' === $field)) {

            $oldCleanURL = $this->getEntity()->getCleanURL();

            // Autogenerate cleanURL only for the default language
            if (empty($oldCleanURL) || 'en' == $this->getCurrentLanguage() || $this->getCurrentLanguage() == static::getDefaultLanguage()) {
                $value = $this->generateCleanURL(parent::getPostedData('name'));

            } else {
                $value = $oldCleanURL;
            }

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
     */
    public function getCleanURLMaxLength()
    {
        return \XLite\Core\Database::getRepo(get_class($this->getEntity()))->getFieldInfo('cleanURL', 'length');
    }

    /**
     * Generate clean URL
     *
     * @param string $name Product name
     *
     * @return string
     */
    protected function generateCleanURL($name)
    {
        $result = '';

        // Prepare the result and check if it could be generated
        if (!empty($name)) {
            $separator = \XLite\Core\Converter::getCleanURLSeparator();
            $result = strtolower(preg_replace('/\W+/S', $separator, $name));

            if (!preg_replace('/' . preg_quote($separator) . '/', '', $result)) {
                $result = $this->getEntity()->getCleanURL();
                $this->setCleanURLAutoGenerateWarning($name);
                $name = null;
            }
        }

        // Process cleanURL
        if (!empty($name)) {

            $suffix    = '';
            $increment = 1;

            $entity    = $this->getEntity();
            $repo      = \XLite\Core\Database::getRepo(get_class($entity));
    
            while (
                ($tmp = $repo->findOneByCleanURL($result . $suffix))
                && $entity->getUniqueIdentifier() != $tmp->getUniqueIdentifier()
                && $increment < static::CLEAN_URL_CHECK_LIMIT
            ) {
                $suffix = $separator . $increment++;
            }
    
            if (!empty($suffix)) {

                if ($entity->getCleanURL() !== ($result . $suffix)) {
                    $this->setCleanURLWarning($result, $suffix);
                }

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
     */
    protected function setCleanURLWarning($cleanURL, $suffix)
    {
        \XLite\Core\TopMessage::addWarning(
            'Since the "{{clean_url}}" clean URL is already defined, the "{{suffix}}" suffix has been added to it',
            array('clean_url' => $cleanURL, 'suffix' => $suffix)
        );
    }

    /**
     * Set warning
     *
     * @param string $name Product name
     *
     * @return boolean
     */
    protected function setCleanURLAutoGenerateWarning($name)
    {
        \XLite\Core\TopMessage::addWarning(
            'Cannot autogenerate clean URL for the product name "{{name}}". Please specify it manually.',
            array('name' => $name)
        );
    }


    // }}}

    // {{{ Action handlers

    /**
     * doActionModify
     *
     * @return void
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
