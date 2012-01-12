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
//attr_accessor :options
//
    function __construct($command, $options = array()){
        $this->command = $this->full_command($command, $options);
    }
//      def initialize(command, options={})
//        @command, @options = command, options
//      end
//
    function execute(){
        print PHP_EOL.$this->command;
        $exitcode = 0;
        passthru($this->command, $exitcode);
        return $exitcode;
    }
//      def execute
//        puts full_command
//        system full_command
//        raise "Error with #{full_command}" if 0 != $?
//      end
//
    function full_command($command, $options){
        $cmd = $this->ssh_command($options);
        if ( array_key_exists('su', $options))
            $cmd .= " \"su -l ".$options['su']." -c";
        $cmd .= $this->remote_command($command, $options);
        if (array_key_exists('su', $options))
            $cmd .= '"';
        return $cmd;
    }
//      def full_command
//        cmd = "#{ssh_command} "
//        cmd << "\"su -l #{options[:su]} -c " if options[:su]
//        cmd << "'#{remote_command}'"
//        cmd << '"' if options[:su]
//        cmd
//      end
//
    function ssh_command($options){
        $command = "ssh -o StrictHostKeyChecking=no ";
        $user = (array_key_exists('user', $options) ? $options['user'] : "ubuntu");
        if ($options['keypair'])
            $command .= " -i '".$options['keypair']."' ";
        $command .= $user."@".$options['host'];
        return $command;
    }
//      def ssh_command
//        shell_command = [ "ssh" ]
//        user = options[:user] || "ubuntu"
//        shell_command << "-i '#{options[:keypair]}'" if options[:keypair]
//        shell_command << "#{user}@#{options[:host]}"
//
//        shell_command.join " "
//      end
//
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
//      def remote_command
//        shell_command = []
//        shell_command << "PATH=#{options[:path]}:${PATH}; export PATH;" if options[:path]
//        shell_command << "DISPLAY=#{options[:display]}; export DISPLAY;" if options[:display]
//        shell_command << "cd '#{options[:pwd]}';" if options[:pwd]
//        shell_command << @command
//
//        shell_command.join " "
//      end
}
