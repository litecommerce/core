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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Wysiwyg_MediatorHtmlStorage extends XLite_Base
{
    function save($file, $content)
    {
        $file = HTML_BUILDER_PATH . $file;
        mkdirRecursive(dirname($file));
        $fd = fopen($file, "wb");
        fwrite($fd, $content);
        fclose($fd);
    }
    function read($file)
    {
        return file_get_contents(HTML_BUILDER_PATH . $file);
    }
    function getFileList()
    {
        $list = array();
        if ($dh = @opendir(HTML_BUILDER_PATH)) {
            while (($file = @readdir($dh)) !== false) {
                if ($file{0} != '.' && @is_file(HTML_BUILDER_PATH . $file) && substr($file, -5)==".html") {
                    $list[] = $file;
                }
            }
            @closedir($dh);
        }
        return $list;
    }
}
