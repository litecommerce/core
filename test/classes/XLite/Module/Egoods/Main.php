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
    public static function getType()
    {
        return self::MODULE_GENERAL;
    }

    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getVersion()
    {
        return '2.9.RC4';
    }

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    public static function getDescription()
    {
        return 'Support for downloadable product sales (e-books, audio and video files, software and PIN codes)';
    }	

	public $showSettingsForm = true;	
	public $minVer = "2.0";
	
    function init()
    {
        parent::init();

		$this->xlite->set("EgoodsEnabled",true);
    }
}

