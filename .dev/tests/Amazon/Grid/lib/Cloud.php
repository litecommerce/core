<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 09.12.11
 * Time: 10:00
 * To change this template use File | Settings | File Templates.
 */

require_once 'Hub.php';
require_once 'LcServer.php';

class Cloud
{
    /**
     * @var Hub
     */
    public $hub;
    /**
     * @var Server[]
     */
    public $farms = array();
    /**
     * @var LcServer
     */
    public $server;

    /**
     * @var Cloud
     */
    public static $instance;

    /**
     * @var Server
     */
    public $micro_farm;

    public static function getInstance(){
        if (self::$instance)
            return self::$instance;
        return self::update();
    }

    static function update(){
        if (file_exists('data.txt'))
            self::$instance = unserialize(file_get_contents("data.txt"));
        else
            self::$instance = new Cloud();
        //print_r(self::$instance);
        return self::$instance;
    }
    function save(){
        //$data = array('farms' => $this->farms, 'hub' => $this->hub, 'server' => $this->server);
        file_put_contents('data.txt',serialize(self::$instance));
    }
}
