<?php

class XLite_Module_Egoods_Main extends XLite_Module_Abstract
{
    /**
     * Module type
     *
     * @var    int
     * @access protected
     * @since  3.0
     */
    protected $type = self::MODULE_GENERAL;

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $version = '2.9.RC4';

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $description = 'This module introduces support for downloadable product sales (e-books, audio and video files, software and PIN codes)';

    /**
     * Determines if module is switched on/off
     *
     * @var    bool
     * @access protected
     * @since  3.0
     */
    protected $enabled = true;	

	public $showSettingsForm = true;	
	public $minVer = "2.0";
	
    function init()
    {
        parent::init();

		$this->xlite->set("EgoodsEnabled",true);
    }
}

