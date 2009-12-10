<?php

require_once "PEAR.php";

class base_class extends PEAR
{
    var $name;
    
    function base_class()
    {
        $this->PEAR();
        $this->name = "Base class";
    }

}

?>
