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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_View_Model 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
abstract class XLite_View_Model_Abstract extends XLite_View_Dialog
{
    /**
     * Passed model object 
     * 
     * @var    XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected $modelObject = null;

    /**
     * Unique name of current web form
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $formName = null;

    /**
     * List of form fields 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $formFields = null;


    /**
     * getDefaultModelObjectClass 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultModelObjectClass();

    /**
     * getDefaultModelObjectKeys 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getDefaultModelObjectKeys();

    /**
     * Return name of web form widget class
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    abstract protected function getFormClass();


    /**
     * getDefaultModelObjectSignature 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObjectSignature()
    {
        return __METHOD__ . $this->getDefaultModelObjectClass();
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultModelObject()
    {
        return XLite_Model_CachingFactory::getObject(
            $this->getDefaultModelObjectSignature(),
            $this->getDefaultModelObjectClass(),
            $this->getDefaultModelObjectKeys()
        );
    }

    /**
     * Return model object to use
     * 
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return isset($this->modelObject) ? $this->modelObject : $this->getDefaultModelObject();
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'model';
    }

    /**
     * Return form templates directory name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormDir()
    {
        return 'form';
    }

    /**
     * Define form field classes and values 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function defineFormFields()
    {
        $this->formFields = array();
    }

    /**
     * Generate unique name for the current form widget
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function generateFormName()
    {
        return uniqid();
    }

    /**
     * Return unique name for the current form widget (this name is used by Flexy compiler)
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormName()
    {
        if (!isset($this->formName)) {
            $this->formName = $this->generateFormName();
        }

        return $this->formName;
    }

    /**
     * Ret urn list of web form widget params
     *
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getFormParams()
    {
        return array();
    }


    /**
     * Define and set handler attributes; initialize handler
     * 
     * @param array                $params      handler params
     * @param XLite_Model_Abstract $modelObject if passed, this object will be used instead of the default one
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function __construct(array $params = array(), XLite_Model_Abstract $modelObject = null)
    {
        parent::__construct($params);

        $this->modelObject = $modelObject;
    }

    /**
     * Return list of form fields
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getFormFields()
    {
        if (!isset($this->formFields)) {
            $this->defineFormFields();
        }

        return $this->formFields;
    }
}

