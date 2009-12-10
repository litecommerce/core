#!/usr/bin/perl
$_ = shift @ARGV;
($fromVer, $toVer) = split(/-/);
print "$fromVer\n$toVer\n";

