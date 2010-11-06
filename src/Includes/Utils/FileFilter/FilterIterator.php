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
     * List of filter functions
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $filterCallbacks = array();


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
     * Get file extension 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFileExtension()
    {
        return strtolower(pathinfo($this->getFileInfo()->getBasename(), PATHINFO_EXTENSION));
    }

    /**
     * Execute passed filter function
     * 
     * @param array $data callback info
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function executeFilterCallback(array $data)
    {
        return (bool) call_user_func_array(
            $data[0],
            array_merge(empty($data[1]) ? array() : $data[1], array($this->getFileInfo()))
        );
    }


    /**
     * The filter callback
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function filterByTypeDir()
    {
        return $this->getFileInfo()->isDir();
    }

    /**
     * The filter callback
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function filterByTypeFile()
    {
        return $this->getFileInfo()->isFile();
    }

    /**
     * The filter callback
     * 
     * @param string $extension extension to compare with
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function filterByExtension($extension)
    {
        return $this->filterByTypeFile() && ($this->getFileExtension() === strtolower($extension));
    }

    /**
     * The filter callback
     *
     * @param string $pattern pattern to use
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function filterByPattern($pattern)
    {
        return preg_match($pattern, $this->getPathname());
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
        $result = true;

        foreach ($this->filterCallbacks as $data) {
            if (!($result = $this->executeFilterCallback($data))) break;
        }

        return $result;
    }

    /**
     * Add function to filter 
     * 
     * @param mixed $callback callback function
     * @param array $params   call params
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addFilter($callback, array $params = array())
    {
        $this->filterCallbacks[] = array(
            is_array($callback) ? $callback : array($this, 'filterBy' . ucfirst($callback)),
            $params,
        );
    }
}
