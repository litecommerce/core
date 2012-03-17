<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 08.12.11
 * Time: 16:44
 * To change this template use File | Settings | File Templates.
 */
require_once 'Server.php';
class LcServer extends Server
{
    public function __get($name){
            switch($name){
                case 'url':
                case 'public_url':
                    return "http://" . $this->public_dns;
                case 'private_url':
                    return "http://" . $this->private_dns;
                case 'admin_url':
                    return "http://" . $this->public_dns . "/xlite/src/admin.php";
                case 'cms_url':
                    return "http://" . $this->public_dns . "/xlite_cms";
            }
            return 'blah';
        }
}
