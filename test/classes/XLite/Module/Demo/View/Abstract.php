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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Abstract widget
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Module_Demo_View_Abstract extends XLite_View_Abstract
implements XLite_Base_IDecorator
{

    /**
     * Compile and display a template
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function includeCompiledFile()
    {
        if (preg_match('/admin\/en\/main\.tpl$/Ss', $this->getTemplateFile())) {
            echo ('<div class="demo-header">This LiteCommerce Admin zone demo has been created for illustrative purposes only. No changes made in the demo will be reflected in the Customer zone.</div>');
        }

        parent::includeCompiledFile();
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        if (preg_match('/admin\/en\/main\.tpl$/Ss', $this->getTemplateFile())) {
            $list[] = 'modules/Demo/style.css';
        }

        return $list;
    }

}
