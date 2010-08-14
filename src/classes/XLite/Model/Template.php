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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Template extends \XLite\Model\FileNode
{
    public $file; // file name	
    public $comment; // template comment (null if not available)	
    public $content; // template file content (null if not read)

    public function __construct($path = null, $comment = null)
    {
        parent::__construct($path);
        if (isset($path)) {
            $this->setPath($path);
        }
        $this->comment = $comment;
    }

    function setPath($path)
    {
        $this->path = $path;
        $this->file = \XLite\Model\Layout::getInstance()->getPathCustomer() . $path;
    }

    function setContent($content)
    {
        $this->content = $content;
    }

    function getContent()
    {
        if (!isset($this->content)) {
            $this->_read();
        }
        return $this->content;
    }

    /**
    * Use getContent instead
    */
    function _read()
    {
        $this->content = file_get_contents($this->file);
    }

    function write()
    {
        $fd = fopen($this->file, "wb");
        fwrite($fd, str_replace("\r", '', $this->content));
        fwrite($fd, "\n");
        fclose($fd);
        umask(0000);
        @chmod($this->file, get_filesystem_permissions(0777));
    }

    function save()
    {
        copyFile($this->file, $this->file . ".bak");
        $this->write();
    }
}
