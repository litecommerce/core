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
//attr_accessor :instance_id, :public_dns, :private_dns
//
    function __construct($instance_id){
        $this->instance_id = $instance_id;
    }
//     def initialize(instance_id)
//       self.instance_id = instance_id
//     end
//
    static function boot($ami, $options = array()){
        $class = get_called_class();
        return new $class(self::launch($ami, $options));
    }
//     def self.boot(ami, options = {})
//       new launch(ami, options)
//     end
//
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
//     def self.boot_and_acquire_dns(ami, options)
//       server = boot(ami, options)
//       server.wait_for_dns
//     end
//
    function wait_for_dns(){
        print PHP_EOL."Fetching DNS Info...";
        while(!$this->is_dns_allocated()){
            sleep(2);
            print '.';
            $this->refresh_status();
        }
    }
//     def wait_for_dns
//       puts "Fetching DNS Info..."
//       until dns_allocated?
//         sleep 2
//         putc "."
//         refresh_status
//       end
//       puts
//       self
//     end
//
    function is_dns_allocated(){
        return !empty($this->public_dns) && !empty($this->private_dns);
    }
//     def dns_allocated?
//       public_dns != nil && public_dns != "" &&
//       private_dns != nil && private_dns != ""
//     end
//
    function refresh_status(){
        $info = self::describe($this->instance_id);
       // print PHP_EOL.$info;
        $this->public_dns = $info['public_dns'];
        $this->private_dns = $info['private_dns'];
    }
//     def refresh_status
//       info = self.class.describe instance_id
//       @public_dns = info[:public_dns]
//       @private_dns = info[:private_dns]
//     end
//
    function shutdown(){
        return self::ec2_shell("ec2-terminate-instances $this->instance_id");
    }
//     def shutdown
//       self.class.shutdown instance_id
//     end
//
    function run($command, $options = array()){
        $options['host'] = $this->public_dns;
        $command = new RemoteCommand($command, $options);
        return $command->execute();
    }
//     def run(command, options)
//       command = RemoteCommand.new command, options.merge(:host => public_dns)
//       command.execute
//     end
//   end
//

}
