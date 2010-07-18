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
 * @subpackage Include_Utils
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Utils\FileFilter;

/**
 * FilterIterator 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class FilterIterator extends \FilterIterator
{
    /**
     * File extension to use for filtering
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $extension;


    /**
     * Return info about current file
     * 
     * @return RecursiveDirectoryIterator
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFileInfo()
    {
        return $this->getInnerIterator()->current();
    }

    /**
     * Check file extension 
     * 
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkFileExtension()
    {
        return $this->extension === strtolower(pathinfo($this->getFileInfo(), PATHINFO_EXTENSION));
    }


    /**
     * Check if current element of the iterator is acceptable through this filter
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function accept()
    {
        return $this->getFileInfo()->isFile() && $this->checkFileExtension();
    }

    /**
     * Constructor 
     * 
     * @param Iterator $iterator  iterator that is being filtered
     * @param string   $extension file extension to use for filtering
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(\Iterator $iterator, $extension)
    {
        parent::__construct($iterator);

        $this->extension = $extension;
    }
}
