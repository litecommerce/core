#!/bin/sh

#IONCUBE="/usr/local/ioncube/ioncube_encoder --without-loader-check --allow-untrusted-extensions --optimize max --erase-target --binary"

#IONCUBE="/u/ioncube/ioncube --without-loader-check --allow-untrusted-extensions --optimize max --erase-target --binary"

#IONCUBE="/u/ioncube/ioncube-6.0 --without-loader-check --allow-untrusted-extensions --optimize max --erase-target --binary"

IONCUBE=./ion_caller.php
IONCUBE_OPTS="--without-loader-check_--optimize_max_--erase-target"

#IONCUBE="/u/ioncube/ioncube-6.5.9 --without-loader-check --allow-untrusted-extensions --optimize max --erase-target --binary"

if [ "$1" = "--ascii" ] 
then
    BINARY=
    ASCII=_--ascii
    FILE=$2
    OUTFILE=$3
else 
    BINARY=_--binary
    ASCII=
    FILE=$1
    OUTFILE=$2
fi

#REMOTEDIR=encode
REMOTEDIR=/u/sheriff/encode
HOSTSPEC=neo
REMOTE=$HOSTSPEC:$REMOTEDIR

#scp $FILE $REMOTE/temp-in.php
#ssh $HOSTSPEC "cd $REMOTEDIR; $IONCUBE $ASCII temp-in.php -o temp-out.php"
#scp $REMOTE/temp-out.php $OUTFILE

#$IONCUBE $ASCII $FILE -o $OUTFILE
#cp $FILE $OUTFILE
$IONCUBE -o $IONCUBE_OPTS$BINARY$ASCII -s $FILE -d $OUTFILE 