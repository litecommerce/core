<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

/**
 * XLite_View_Body 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_View_Body extends XLite_View_Abstract
{
    /**
     * Chunk size
     */
    const BUFFER_SIZE = 8192;


    /**
     * echoChunk
     *
     * @param mixed $chunk ____param_comment____
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected static function echoChunk($chunk)
    {
        echo $chunk;
    }

    /**
     * display
     *
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function display()
    {
        array_map(array('self', 'echoChunk'), str_split(XLite_View_Controller::$bodyContent, self::BUFFER_SIZE));
    }
}

