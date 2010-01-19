<?php

class XLite_Module_Protx_Main extends XLite_Module_Abstract
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
        return self::MODULE_PAYMENT;
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
        return '2.1.RC1';
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
        return 'Protx VSP Direct and Protx VSP Form credit card payment processor';
    }	

    /**
     * Determines if we need to show settings form link
     *
     * @return bool
     * @access public
     * @since  3.0
     */
    public static function showSettingsForm()
    {
        return true;
    }

    /**
     * Return link to settings form
     *
     * @return string
     * @access public
     * @since  3.0
     */
    public static function getSettingsForm()
	{
		return "admin.php?target=payment_method&payment_method=protxdirectCc";
	}

    /**
     * Perform some actions at startup
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function init()
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

