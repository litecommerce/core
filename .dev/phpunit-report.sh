#!/usr/local/bin/zsh
#
# $Id$
#
# Local PHP Unit + HTML coverage report in 'coverage' directory
#
# Usage example:
#
# ./phpunit-report.sh
#

cd $(dirname $0)
cd ..
RP=`realpath .dev/tests/AllTests.php`
CP=`realpath ./coverage`
phpunit --coverage-html $CP xliteAllTests .dev/tests/AllTests.php LOCAL_TESTS,$1
#phpunit xliteAllTests .dev/tests/AllTests.php $1

P=`realpath ./ | replace '/u/'$USER'/public_html' ''`
echo 'Open coverage report http://xcart2.crtdev.local/~'$USER$P'/coverage link';
