<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 08.12.11
 * Time: 16:48
 * To change this template use File | Settings | File Templates.
 */
class Ec2Client
{

    static function describe($instance_id)
    {
        $result = array('instance_id' => $instance_id, 'ami' => '', 'public_dns' => '', 'private_dns' => '', 'status' => '');
        $output = self::ec2_shell("ec2-describe-instances $instance_id");
        //print $output;
        if (preg_match('/INSTANCE\s+(i-\S*)\s+(ami-\S*)\s+(\S*)\s+(\S*)\s+(\S*)/Sm', $output, $matches)) {

            $result['instance_id'] = $matches[1];
            $result['ami'] = $matches[2];
            if (preg_match("/running$/S", $matches[0])) {
                $result['public_dns'] = $matches[3];
                $result['private_dns'] = $matches[4];
                $result['status'] = $matches[5];
            }
            else {
                $result['status'] = $matches[3];
            }
        }
        return $result;
    }

//def describe(instance_id)
//        output = ec2_shell "ec2-describe-instances #{instance_id}"
//        output =~ /INSTANCE\s+(i-.*)$/
//        fields = $1.split(/\s+/)
//        if output =~ /running/
//          {:instance_id => fields[0],
//           :ami => fields[1],
//           :public_dns => fields[2],
//           :private_dns => fields[3],
//           :status => fields[4] }
//        else
//          {:instance_id => fields[0],
//           :ami => fields[1],
//           :status => fields[2] }
//        end
//      end
//
    static function launch($ami, $options = array())
    {
        $command = "ec2-run-instances -k " . $options['keypair_name'] . " -t " . $options['type'] . " " . $ami;
        //print PHP_EOL.$command;
        $output = self::ec2_shell($command);
        //print PHP_EOL.$output;
        if (preg_match('/INSTANCE\s+(i-\S*)\s+(ami-\S*)/Sm', $output, $matches)) {
            return $matches[1];
        }
        throw new InstanceLaunchError($output);
    }

//      def launch(ami, options ={})
//        output = ec2_shell "ec2-run-instances -k #{options[:keypair]} -t #{options[:type]} #{ami}"
//        output =~ /INSTANCE\s+(i-\S+)\s+ami-/
//        if $1 != nil
//          $1
//        else
//          raise InstanceLaunchError, output
//        end
//      end
//
//    static function shutdown($instance_id)
//    {
//        return self::ec2_shell("ec2-terminate-instances $instance_id");
//    }

//      def shutdown(instance_id)
//        ec2_shell "ec2-terminate-instances #{instance_id}"
//      end
//
    static function version()
    {
        return self::ec2_shell("ec2-version");
    }

//      def version
//        ec2_shell "ec2-version"
//      end
//
    static function authorize_port($port)
    {
        print "Opening port $port...";
        self::ec2_shell("ec2-authorize default -p $port");
    }

//      def authorize_port(port)
//        puts "Opening port #{port}..."
//        ec2_shell "ec2-authorize default -p #{port}"
//      end
//

    static function ec2_shell($command)
    {
        if (self::isTracing())
            print PHP_EOL . "[EC2] '$command'";
        $return = shell_exec('${EC2_HOME}/bin/' . $command);
        if (self::isTracing())
            print PHP_EOL . "[EC2] '$return'";
        return $return;
    }

//      def ec2_shell(command)
//        puts "[EC2] '#{command}'" if tracing?
//        output = `${EC2_HOME}/bin/#{command}`
//        puts "[EC2] #{output}" if tracing?
//        output
//      end
//
    static function isTracing()
    {
        return getenv('TRACE_EC2_COMMANDS');
    }
//      def tracing?
//        ENV['TRACE_EC2_COMMANDS']
//      end

}

class InstanceLaunchError extends Exception
{

}
