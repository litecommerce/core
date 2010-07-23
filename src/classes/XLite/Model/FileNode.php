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

namespace XLite\Model;

define('SHOW_FULL_PATH', 1);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class FileNode extends \XLite\Base
{
    public function __construct($path = null, $comment = null, $options = 0)
    {
        $this->path = $path;
        $this->comment = $comment;
        $this->options = $options;
    }

    function getID()
    {
        return $this->path;
    }

    function getComment()
    {
        $this->checkAccess($this->path);

        if ($this->comment) {
            if ($this->comment == "EMPTY") {
                return "";
            } else {
                return $this->comment;
            }
        } else {
            $cnt = is_file($this->path) ? file_get_contents($this->path) : "";
            if (preg_match('/\{\*\*(.+) \* @\w+/USs', $cnt, $match)) {
                $this->comment = $match[1];
                $this->comment = str_replace(
                    array("\n", ' * ', '  '),
                    array(' ', '', ' '),
                    $this->comment
                );
                $this->comment = trim($this->comment);

                return $this->comment;
            }
            $this->comment = "EMPTY";
            return "";
        }
    }

    function getName()
    {
        if (isset($this->name)) {
            return $this->name;
        }
        if ($this->options & SHOW_FULL_PATH) {
            return $this->path;
        } else {
            $pos = strrpos($this->path, '/');
            if ($pos) {
                return substr($this->path, $pos+1);
            }
            return $this->path;
        }
    }

    function getNode()
    {
        return ($this->path == "cart.html" || $this->path == "shop_closed.html") ? "" : dirname($this->path);
    }

    function getContent()
    {
        $this->checkAccess($this->path);
        return @file_get_contents($this->path);
    }

    function isLeaf()
    {
        return is_file($this->path);
    }
    
    function create()
    {
        $this->content = "";
        $this->write();
    }

    function remove()
    {
        $this->checkAccess($this->path);
        if ($this->isLeaf()) {
            @unlink($this->path);
        } else {
            \Includes\Utils\FileManager::unlinkRecursive($this->path);
        }
    }

    function createDir()
    {
        $this->checkAccess($this->path);
        umask(0000);
        @mkdir($this->path, get_filesystem_permissions(0755));
    }

    function copy()
    {
        $this->checkAccess($this->path);
        $this->checkAccess($this->newPath);
        copyRecursive($this->path, $this->newPath);
    }

    function rename()
    {
        $this->checkAccess($this->path);
        $this->checkAccess($this->newPath);
        rename($this->path, $this->newPath);
        is_dir($this->newPath) ? @chmod($this->newPath,get_filesystem_permissions(0755)) : @chmod($this->newPath, get_filesystem_permissions(0666));
    }

    function update()
    {
        $this->write();
    }

    function write()
    {
        if (is_null($this->path)) return;
        $this->checkAccess($this->path);
        $this->writePermitted = false;
        $fd = @fopen($this->path, "wb");
        if ($fd) {
            fwrite($fd, str_replace("\r", '', $this->content));
            if (!empty($this->content)) {
                fwrite($fd, "\n");
            }
            fclose($fd);
            @chmod($this->path, get_filesystem_permissions(0666));
        } else {
        	$this->writePermitted = true;
        }
    }

    function checkAccess($file)
    {
        if (empty($file)) return;
        // add-on mode 
        if ($file == "cart.html" || $file == "shop_closed.html" || $file == "skins" || $file == "skins_original") return true;
        // check permission to access the file
        $i = 0;
        foreach (explode('/', $file) as $element) {
            if ($element == '..') $i--;
            if ($element != 'skins' && $element != 'skins_original' && $element != 'schemas' && $element != 'tests' && $i == 0) {
                $this->accessDenied();
            }
            if ($element != '.' && $element != '..') $i++;
        }
        if ($i <= 2) {
            $this->accessDenied();
        }
    }

    function accessDenied()
    {
        die("Access error! You have no permission to access file $this->path");
    }

    function isExists()
    {
        return file_exists($this->path);
    }
}
