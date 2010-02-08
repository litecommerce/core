<?php

/* $Id$ */

/**
 * Main module class 
 * 
 * @package    Lite Commerce
 * @subpackage Module DrupalConnector
 * @since      3.0
 */
class XLite_Module_DrupalConnector_Main extends XLite_Module_Abstract
{
    /**
     * Module type
     *
     * @var    int
     * @access protected
     * @since  3.0
     */
    public static function getType()
    {
        return self::MODULE_CONNECTOR;
    }

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getVersion()
    {
        return '1.0';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getDescription()
    {
        return 'Integration with the Drupal CMS';
    }	
}

