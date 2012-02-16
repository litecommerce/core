<?php

class Ec2Client
{

    static function describe($instance_id)
    {
        $result = array('instance_id' => $instance_id, 'ami' => '', 'public_dns' => '', 'private_dns' => '', 'status' => '');
        $output = self::ec2_shell("ec2-describe-instances $instance_id");
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

    static function launch($ami, $options = array())
    {
        $command = "ec2-run-instances -k " . $options['keypair_name'] . " -t " . $options['type'] . " " . $ami;
        $output = self::ec2_shell($command);
        if (preg_match('/INSTANCE\s+(i-\S*)\s+(ami-\S*)/Sm', $output, $matches)) {
            return $matches[1];
        }
        throw new InstanceLaunchError($output);
    }

    static function shutdown($instance_id)
    {
        return self::ec2_shell("ec2-terminate-instances $instance_id");
    }

    static function version()
    {
        return self::ec2_shell("ec2-version");
    }

    static function authorize_port($port)
    {
        print "Opening port $port...";
        self::ec2_shell("ec2-authorize default -p $port");
    }

    static function ec2_shell($command)
    {
        if (self::isTracing())
            print PHP_EOL . "[EC2] '$command'";
        $return = shell_exec('${EC2_HOME}/bin/' . $command);
        if (self::isTracing())
            print PHP_EOL . "[EC2] '$return'";
        return $return;
    }

    static function isTracing()
    {
        return getenv('TRACE_EC2_COMMANDS');
    }
}

class InstanceLaunchError extends Exception
{

}
