<?php

print preg_match("/INSTANCE\s+(i-\S*)\s+(ami-\S*)\s+(\S*)\s+(\S*)\s+(\S*)/Sm", 
"RESERVATION    r-3566ad54      387685522472    default
INSTANCE        i-b69e7dd4      ami-adc10ac4    ec2-174-129-157-93.compute-1.amazonaws.com      ip-10-2-17-253.ec2.internal     running lc-test 0               m1.small        2011-12-12T08:35:37+0000        
us-east-1b      aki-407d9529                     monitoring-disabled     174.129.157.93  10.2.17.253                     ebs                                     paravirtual     xen             sg-919880f8     default
BLOCKDEVICE     /dev/sda1       vol-278a1e4a    2011-12-12T08:36:04.000Z");
