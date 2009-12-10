<?php


require_once "tests/classes/kernel/Decorator/base.php";

$GLOBALS["xlite_decorated_classes"]["child_one"] = __FILE__;

class child_one extends base_class
{
    var $child_one_name;
    
    function child_one()
    {
        $this->base_class();
        $this->child_one_name = "Child one class";
    }
}

?>
