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
     * Pattern to filter paths
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pattern;


    /**
     * Constructor 
     * 
     * @param \Iterator $iterator iterator to use
     * @param string    $pattern  pattern to filter paths
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(\Iterator $iterator, $pattern = null)
    {
        parent::__construct($iterator);

        $this->pattern = $pattern;
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
        // It's the hack (to increase Decorator perfomance) for developers
        if (LC_DEVELOPER_MODE && (false !== strpos($this->getPathname(), '.svn'))) {
            return false;
        }

        return !isset($this->pattern) ?: preg_match($this->pattern, $this->getPathname());
    }
}
