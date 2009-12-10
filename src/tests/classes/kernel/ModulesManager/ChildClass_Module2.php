<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
$GLOBALS["xlite_decorated_classes"][strtolower("ChildClass_Module2")] = __FILE__;

require_once "ChildClass.php";

/**
* ChildClass_Module2 description.
*
*/
class ChildClass_Module2 extends ChildClass
{
    /**
    * Constructor.
    *
    * @param  PARAM LIST
    * @access public
    * @return void
    */
    function ChildClass_Module2()
    {
        $this->ChildClass();
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
