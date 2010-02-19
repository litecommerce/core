<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Button
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Button 
 * 
 * @package View
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Button extends XLite_View_Abstract
{    
    /**
     * Template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $template = 'common/button.tpl';

    /**
     * Link
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $href = '#';    

    /**
     * Label 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $label = 'Submit';

    /**
     * Image
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $img = null;

    /**
     * Button type 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $type = 'link';

    /**
     * Initialization 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
        parent::initView();

        switch ($this->type) {
            case 'button':
                $this->template = 'common/button_adv.tpl';
                break;

            case 'button_link':
                $this->template = 'common/button_link.tpl';
                break;
        }

        if (
            !$this->xlite->is('adminZone')
            && $this->auth->is('logged')
            && strpos($this->href, 'target=profile') !== false
            && strpos($this->href, 'mode=delete') !== false
            && $this->auth->get('profile')->isAdmin()
        ) {
            $this->set('visible', false);
        }
    }
}

