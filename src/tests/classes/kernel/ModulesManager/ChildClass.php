<?php
/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */
$GLOBALS["xlite_decorated_classes"][strtolower("ChildClass")] = __FILE__;

require_once "BaseClass.php";

/**
* ChildClass description.
*
*/
class ChildClass extends BaseClass
{
    /**
    * Constructor.
    *
    * @param  PARAM LIST
    * @access public
    * @return void
    */
    function ChildClass($param1 = null, $param2 = null)
    {
        $this->_constructor_params = func_get_args();
        $this->BaseClass();
    }

    function constructor($param1 = "asd", $param2 = "asd")
    {
        $this->param1 = $param1;
        $this->param2 = $param2;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
