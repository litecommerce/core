<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 09.12.11
 * Time: 10:00
 * To change this template use File | Settings | File Templates.
 */

require_once 'Hub.php';
require_once 'RemoteControl.php';
require_once 'LcServer.php';

class Cloud
{
    /**
     * @var Hub
     */
    public $hub;
    /**
     * @var RemoteControl[]
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
     * @var Jmeter
     */
    public  $jmeter;

    /**
     * @var Server
     */
    public $micro_farm;

    public function start_hub($app){
        print PHP_EOL . "Starting a new EC2 Instance...";
        $this->hub = Hub::boot_and_acquire_dns($app['hub_ami'], array('keypair_name' => $app['keypair_name'], 'type' => 't1.micro' /*$app['grid_instance_type']*/));
        $this->hub->start($app);
    }
    public function shutdown_hub(){
        if ($this->hub){
            $this->hub->shutdown();
            $this->hub = null;
            $this->save();
        }
    }

    public function start_rc($app){
        RemoteControl::boot_farms($app);
        RemoteControl::start_farms($app);
    }

    public function shutdown_rc(){
        $farms = $this->farms;
        foreach ($farms as $key => $farm) {
            print PHP_EOL . "Shutting down EC2 Instance " . $farm->public_dns . "...";
            $farm->shutdown();
            unset($this->farms[$key]);
        }
        $this->save();
    }


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
        return self::$instance;
    }
    function save(){
        file_put_contents('data.txt',serialize(self::$instance));
    }
}
