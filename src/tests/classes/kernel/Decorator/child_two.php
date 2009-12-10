<?php

require_once "tests/classes/kernel/Decorator/base.php";

$GLOBALS["xlite_decorated_classes"]["child_two"] = __FILE__;

class child_two extends base_class
{
    var $child_two_name;
    
    function child_two()
    {
        $this->base_class();
        $this->child_two_name = "Child two class";
    }
}

?>
