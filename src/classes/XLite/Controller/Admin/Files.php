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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

@set_time_limit(0);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Files extends \XLite\Controller\Admin\AAdmin
{
    public $count = 0; //total files count;
    public $action = "default";
    
    // FIXME - check this function 
    function handleRequest()
    {
        require_once LC_ROOT_DIR . 'lib' . LC_DS . 'Archive' . LC_DS . 'Tar.php';

        parent::handleRequest();

        die();
    }
    
    function action_default()
    {
        print "<pre>";
        $this->get_files('.');
        print $this->count;
    }

    function action_tar()
    {
        $tar = new Archive_Tar('STDOUT');
        $this->startDownload('backup.tar');
        $files = array();
        if ($handle = opendir('.')) {
            while (false !== ($file = readdir($handle))) {
                if ($file{0} != "." && $file != "var") {
                    $files[] = $file;
                }
            }
        }
        if (isset(\XLite\Core\Request::getInstance()->mode) && \XLite\Core\Request::getInstance()->mode == "full") {
            // backup database as well
            $this->db->backup('var/backup/sqldump.sql.php', false);
            $files[] = 'var/backup/sqldump.sql.php';
        }
        $tar->create($files);
        $tar->_close();
    }

    function action_tar_skins()
    {
        $tar = new Archive_Tar('STDOUT');
        $this->startDownload('backup.tar');
        $tar->_exceptions = array('.anchors.ini');
        $tar->create('var/html');
        $tar->_close();
    }

    function action_untar_skins()
    {
        $tar = new Archive_Tar('skins.tar');
        if (!$tar->extractModify('var/html','')) {
            die($tar->_error_message);
        }
        // change file permissions
        $files = $tar->listContent();
        foreach ($files as $file) {
            $fn = "var/html/".$file['filename'];
            if (is_dir($fn)) {
                @chmod($fn, get_filesystem_permissions(0777));
            } else {
                @chmod($fn, get_filesystem_permissions(0666));
            }
        }
        print "OK";
        @unlink('skins.tar');
        die();
    }
    
    function get_files($dir) {
        if ($handle = opendir($dir)) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if ($dir == '.') {
                        $full_name = $file;
                    } else {
                        $full_name = $dir . "/" . $file;
                    }
                    if (is_dir($full_name)) {
                        $this->get_files($full_name);
                    } else {
                        $this->count++;
                        echo $full_name . "\r\n";
                    }
                }
            }
            closedir($handle);
        }
    }
}
