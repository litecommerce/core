<?php

class XLite_Module_Protx_Main extends XLite_Module_Abstract
{
    /**
     * Module version
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $version = '2.1.RC1';

    /**
     * Module description
     *
     * @var    string
     * @access protected
     * @since  3.0
     */
    protected $description = 'Protx VSP Direct and Protx VSP Form credit card payment processor';

    /**
     * Determines if module is switched on/off
     *
     * @var    bool
     * @access protected
     * @since  3.0
     */
    protected $enabled = true;	

	public $minVer = "2.0";	
	public $showSettingsForm = true;

	function getSettingsForm()
	{
		return "admin.php?target=payment_method&payment_method=protxdirectCc";
	}

    function init()
    {
        parent::init();

        $pm = new XLite_Model_PaymentMethod();
		$pm->find('payment_method = \'protxdirect_cc\'');

		switch($pm->get("params.solution")) {

			case "form":
				$this->registerPaymentMethod('protxform_cc');
				break;

			case "direct":
			default:
				$this->registerPaymentMethod('protxdirect_cc');
				break;
		}

		if ($this->xlite->mm->get("activeModules.ProtxDirect")) {
			$modules = $this->xlite->mm->get("modules");
			$ids = array();
			foreach ($modules as $module) {
				if ($module->get("name") != "ProtxDirect" && $module->get("enabled") ) {
					$ids[] = $module->get("module_id");
				}
			}

			$this->xlite->mm->updateModules($ids);
			$this->session->set("ProtxDirectOff", true);
		}

		$this->xlite->set("ProtxEnabled", true);
    }
}

