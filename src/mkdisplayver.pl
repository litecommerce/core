#!/usr/bin/perl
$_ = shift @ARGV;
($v1, $v2, $v3) = split(/\./);
if ($v3 eq "0") {
    print "$v1.$v2";
} else {
    print "$v1.$v2-sp$v3";
}
