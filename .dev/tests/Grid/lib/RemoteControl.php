<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 4/5/12
 * Time: 2:04 PM
 * To change this template use File | Settings | File Templates.
 */
require_once 'Server.php';
class RemoteControl extends Server
{
    static function start_farms($app){

        foreach ($app['cloud']->farms as $farm) {
            $farm->start($app);
        }

    }

    function start($app){
        $hub = $app['cloud']->hub;
        print PHP_EOL . "Starting Remote Control at " . $this->public_dns . "..." .PHP_EOL;
        $this->run("nohup rake rc:start_all SELENIUM_ARGS=\"-firefoxProfileTemplate " . $app['firefox_profile'] . "\" HUB_URL=" . $hub->private_url . " HOST=" . $this->private_dns . " PORTS=" . $app['remote_control_port_range'] . " BACKGROUND=true",
            array('display' => ":0", 'path' => "/usr/lib/firefox-8.0", 'pwd' => $app["selenium_grid_path"], 'keypair' => $app['keypair']));
        $this->run("nohup vncserver :0", array('keypair' => $app['keypair']));
    }

    static function boot_farms($app){
        $cloud = $app['cloud'];
        for ($i = 0; $i < $app['farms_count']; $i++) {
            print PHP_EOL . "Starting a new EC2 Instance...";
            try {
                $farm = RemoteControl::boot_and_acquire_dns($app['hub_ami'], array('keypair_name' => $app['keypair_name'], 'type' => $app['grid_instance_type']));
                $cloud->farms[] = $farm;
                $cloud->save();
                print PHP_EOL . "Started new Remote Control farm at " . $farm->public_dns . PHP_EOL;
            }
            catch (Exception $e) {
                print PHP_EOL . "Failed to boot new Remote Control farm.";
            }
        }
        Ec2Client::authorize_port(5900);
        Ec2Client::authorize_port(6000);
    }
    static function get_screenshots($app)
    {
        if (!file_exists($app['log_dir'] . '/screenshots'))
            mkdir($app['log_dir'] . '/screenshots');
        $options = array('keypair' => $app['keypair'], 'user' => 'ubuntu');
        foreach ($app['cloud']->farms as $farm) {
            $farm->download('/var/www/selenium-screenshots/*', $app['log_dir'] . '/screenshots', $options);
            //exec('scp -i ' . $app['keypair'] . ' ubuntu@' . $farm->public_dns . ':/var/www/selenium-screenshots/* ' . $app['log_dir'] . '/screenshots');
        }
        if ($app['cloud']->hub->micro_farm) {
            $app['cloud']->hub->micro_farm->download('/var/www/selenium-screenshots/*', $app['log_dir'] . '/screenshots', $options);
        }
    }
}
