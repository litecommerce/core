<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 08.12.11
 * Time: 16:43
 * To change this template use File | Settings | File Templates.
 */

require_once 'Ec2Client.php';
require_once 'RemoteCommand.php';

class Server extends Ec2Client
{
    public $instance_id;
    public $public_dns;
    public $private_dns;

    function __construct($instance_id){
        $this->instance_id = $instance_id;
    }

    static function boot($ami, $options = array()){
        $class = get_called_class();
        return new $class(self::launch($ami, $options));
    }

/**
 * @static
 * @param $ami
 * @param array $options
 * @return Server
 */
    static function boot_and_acquire_dns($ami, $options = array()){
        $server = self::boot($ami,$options);
        $server->wait_for_dns();
        return $server;
    }

    function wait_for_dns(){
        print PHP_EOL."Fetching DNS Info...";
        while(!$this->is_dns_allocated()){
            sleep(2);
            print '.';
            $this->refresh_status();
        }
    }

    function is_dns_allocated(){
        return !empty($this->public_dns) && !empty($this->private_dns);
    }

    function refresh_status(){
        $info = self::describe($this->instance_id);
        $this->public_dns = $info['public_dns'];
        $this->private_dns = $info['private_dns'];
    }

    function shutdown(){
        return self::ec2_shell("ec2-terminate-instances $this->instance_id");
    }

    function run($command, $options = array()){
        $options['host'] = $this->public_dns;

        $this->wait_for_ssh($options);

        $command = new RemoteCommand($command, $options);
        return $command->execute();
    }

    private function wait_for_ssh($options){
        $ls = new RemoteCommand('ls > /dev/null', $options);
        $code = $ls->execute(true);
        while ($code != 0){
            sleep(2);
            $code = $ls->execute(true);
        }
    }

    function download($filename, $toDir, $options = array()){
        if (!isset($options['keypair'])){
            throw new Exception('Keypair must be set');
        }
        if (!isset($options['user'])){
            $options['user'] = 'ubuntu';
        }
        if (!file_exists($toDir) && is_dir($toDir)){
            mkdir($toDir);
        }
        exec('scp -o StrictHostKeyChecking=no -r -i ' . $options['keypair'] . ' ' .$options['user']. '@' . $this->public_dns . ':'.$filename.' ' . $toDir);
    }

    function upload($filename, $toDir, $options = array()){
        if (!isset($options['keypair'])){
            throw new Exception('Keypair must be set');
        }
        if (!isset($options['user'])){
            $options['user'] = 'ubuntu';
        }
        exec('scp -o StrictHostKeyChecking=no -r -i ' . $options['keypair'] . ' ' . $filename . ' ' .$options['user']. '@' . $this->public_dns . ':'.$toDir);
    }

}
