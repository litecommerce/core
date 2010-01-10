<?php

class XLite_Module_GiftCertificates_View_CECard extends XLite_View
{
    var $gc = null;

    function getTemplate()
    {
        return "modules/GiftCertificates/ecards/" . $this->get("gc.ecard.template") . ".tpl";
    }

    function getTemplateFile()
    {
        $layout = XLite_Model_Layout::getInstance();
        return "skins/mail/" . $layout->get("locale") . "/" . $this->get("template");
    }

}

