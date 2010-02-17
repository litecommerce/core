<?php

class XLite_Module_GiftCertificates_View_CEcard extends XLite_View_Abstract
{	
    public $gc = null;

    function getTemplate()
    {
        return "modules/GiftCertificates/ecards/" . $this->getComplex('gc.ecard.template') . ".tpl";
    }

    function getTemplateFile()
    {
        $layout = XLite_Model_Layout::getInstance();
        return "skins/mail/" . $layout->get("locale") . "/" . $this->get("template");
    }

}

