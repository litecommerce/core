#!/bin/sh
#
# $Id$
#
# Local PHP Code Shiffer
#
# Usage example:
#
# ./phpcs-report.sh
# ./phpcs-report.sh file1
# ./phpcs-report.sh file1 file2
#

files_list=""

for f in $@; do
    [ -r "$f" ] && files_list=$files_list" "$(realpath $f);
done

cd $(realpath $(dirname $0))

if [ x"${files_list}" = x ]; then
	files_list=$(realpath ../);
fi

path=$(realpath ./);

standard=$path/code-sniffs/XLite

echo Standard: $standard;

$path/phpcs -s --report=full --standard=$standard --ignore=.dev,src/skins/admin,src/skins/default,src/etc,src/var $files_list
