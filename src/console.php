<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Command line interface
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

if ('cli' != PHP_SAPI) {
    die(1);
}

include_once __DIR__ . '/top.inc.php';

XLite::getInstance()->run(true)->getViewer()->display();
echo PHP_EOL;

exit (defined('CLI_RESULT_CODE') ? CLI_RESULT_CODE : 0);
