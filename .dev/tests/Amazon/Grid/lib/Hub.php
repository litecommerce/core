<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 08.12.11
 * Time: 16:43
 * To change this template use File | Settings | File Templates.
 */
require_once 'Server.php';
class Hub extends Server
{
    function __get($name){
        switch($name){
            case 'url':
            case 'public_url':
                return "http://" . $this->public_dns . ":4444";
            case 'private_url':
                return "http://" . $this->private_dns . ":4444";
            case 'console_url':
                return "http://" . $this->public_dns . ":4444/console";
        }
        return $this->$$name;
    }
}
