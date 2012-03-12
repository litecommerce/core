<?php
/**
 * Created by JetBrains PhpStorm.
 * User: humanoid
 * Date: 09.12.11
 * Time: 9:15
 * To change this template use File | Settings | File Templates.
 */
class RemoteCommand
{
    private $command;

    function __construct($command, $options = array()){
        $this->command = $this->full_command($command, $options);
    }

    function execute($silent = false){
        if (!$silent){
            print PHP_EOL.$this->command;
        }
        $exitcode = 0;
        passthru($this->command, $exitcode);
        return $exitcode;
    }

    function full_command($command, $options){
        $cmd = $this->ssh_command($options);
        if ( array_key_exists('su', $options))
            $cmd .= " \"su -l ".$options['su']." -c";
        $cmd .= $this->remote_command($command, $options);
        if (array_key_exists('su', $options))
            $cmd .= '"';
        return $cmd;
    }

    function ssh_command($options){
        $command = "ssh -o StrictHostKeyChecking=no ";
        $user = (array_key_exists('user', $options) ? $options['user'] : "ubuntu");
        if ($options['keypair'])
            $command .= " -i '".$options['keypair']."' ";
        $command .= $user."@".$options['host'];
        return $command;
    }

    function remote_command($command, $options){
        $remote_command = " '";
        if (array_key_exists('path', $options))
            $remote_command .= " PATH=".$options['path'].':${PATH}; export PATH; ';
        if (array_key_exists('display', $options))
            $remote_command .= ' DISPLAY='.$options['display'].'; export DISPLAY; ';
        if (array_key_exists('pwd', $options))
            $remote_command .= " cd ".$options['pwd']."; ";
        return $remote_command . $command . "' ";

    }
}
