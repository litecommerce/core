#!/bin/sh

PHP_BIN="/usr/local/php-530/bin/php";

log=`find . -type f -name '*.php' -exec $PHP_BIN -l '{}' ';' | grep -v 'No syntax '`;

if [ x"${log}" != x ]; then
	echo -e "<h4>The following PHP errors/warnings discovered:</h4>\n\n<ul>";
	echo $(echo $log | sed -E "s/^(.{1})/<li> \1/g" | sed "s/\n/<br \/>/g");
	echo -e "</ul>";
	exit 1; # Exit with error
else
	echo "No syntax errors detected in PHP scripts";
fi

