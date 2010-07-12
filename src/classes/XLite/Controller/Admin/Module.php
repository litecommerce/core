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

/**
 * Module settings
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Module extends AAdmin
{
    /**
     * params 
     * FIXME - must be protected (at first)
     * FIXME - to remove; see Core/Handler.php (lowest priority task)
     * 
     * @var    array
     * @access public
     * @since  3.0.0
     */
    public $params = array('target', 'page');
    
    /**
     * Return current module options
     * 
     * @return array 
     * @access protected
     * @since  3.0.0
     */
    public function getOptions()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Config')->getByCategory($this->page, true, true);
    }

    /**
     * Update module settings 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        foreach ($this->getOptions() as $option) {

            $name  = $option->name;
            $value = \XLite\Core\Request::getInstance()->$name;

            switch ($option->type) {

                case 'checkbox':
                    $value = isset($value) ? 'Y' : 'N';
                    break;

                case 'serialized':
                    $value = serialize($value);
                    break;

                default:
                    $value = trim($value);
            }

            \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
                array(
                    'category' => $this->page,
                    'name'     => $name,
                    'value'    => $value,
                    'type'     => $type
                )
            );
        }
    }
}
