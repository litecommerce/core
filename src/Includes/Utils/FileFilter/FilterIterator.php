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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace Includes\Utils\FileFilter;

/**
 * FilterIterator
 *
 * @package XLite
 */
class FilterIterator extends \FilterIterator
{
    /**
     * Pattern to filter paths
     *
     * @var string
     */
    protected $pattern;

    /**
     * List of filtering callbacks
     *
     * @var array
     */
    protected $callbacks = array();


    /**
     * Constructor
     *
     * @param \Iterator $iterator iterator to use
     * @param string    $pattern  pattern to filter paths
     *
     * @return void
     */
    public function __construct(\Iterator $iterator, $pattern = null)
    {
        parent::__construct($iterator);

        $this->pattern = $pattern;
    }

    /**
     * Add callback to filter files
     *
     * @param array $callback Callback to register
     *
     * @return void
     */
    public function registerCallback(array $callback)
    {
        if (!is_callable($callback)) {
            \Includes\ErrorHandler::fireError('Filtering callback is not valid');
        }

        $this->callbacks[] = $callback;
    }

    /**
     * Check if current element of the iterator is acceptable through this filter
     *
     * @return bool
     */
    public function accept()
    {
        if (!($result = !isset($this->pattern))) {
            $result = preg_match($this->pattern, $this->getPathname());
        }

        if (!empty($this->callbacks)) {

            while ($result && (list(, $callback) = each($this->callbacks))) {
                $result = call_user_func_array($callback, array($this));
            }

            reset($this->callbacks);
        }

        return $result;
    }
}
