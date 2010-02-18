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
    function init() {

        parent::init();

        $isAdminZone = $this->xlite->is('adminZone');
        $isLoggedIn = $this->auth->is('logged');
        $hasProfileString = strpos($this->href, 'target=profile') !== false;
        $hasDeleteString = strpos($this->href, 'mode=delete') !== false;

        if (!$isAdminZone && $isLoggedIn && $hasProfileString && $hasDeleteString) {
            $profile = $this->auth->get('profile');  
            if ($profile->isAdmin()) {
                $this->set('visible', false);
            }
        }
    }

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

		if ($this->type == 'button') {
			$this->template = 'common/button_adv.tpl';
		}
	}
}

