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

    /**
     * @var RemoteControl
     */
    public $micro_farm;

    function start($app){
        print PHP_EOL . "Started new Hub at " . $this->public_dns . PHP_EOL;
        $this->run("nohup rake hub:start BACKGROUND=true", array('pwd' => $app['selenium_grid_path'], 'keypair' => $app['keypair']));

        print PHP_EOL . "Starting a new EC2 Instance...";
        try {
            $farm = Server::boot_and_acquire_dns($this->ami, array('keypair_name' => $app['keypair_name'], 'type' => 't1.micro'));
            $this->micro_farm = $farm;
            print PHP_EOL . "Started new Remote Control farm at " . $farm->public_dns . PHP_EOL;
            $farm->run("nohup rake rc:start_all SELENIUM_ARGS=\"-firefoxProfileTemplate " . $app['firefox_profile'] . "\"  HUB_URL=" . $this->private_url . " HOST=" . $farm->private_dns . " PORTS=" . $app['remote_control_port_range'] . " BACKGROUND=true",
                array('display' => ":0", 'path' => "/usr/lib/firefox-8.0", 'pwd' => $app["selenium_grid_path"], 'keypair' => $app['keypair']));
            $farm->run("nohup vncserver :0", array('keypair' => $app['keypair']));
            $app['cloud']->save();
        }
        catch (Exception $e) {
            print PHP_EOL . "Failed to boot new Remote Control farm.";
        }

    }

    function shutdown(){
        print PHP_EOL . "Shutting down EC2 Instance " . $this->micro_farm->public_dns . "...";
        $this->micro_farm->shutdown();
        parent::shutdown();
    }
    function console(){
        exec("firefox " . $this->console_url);
    }

}
