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
/u/xcart/bin/phpunit-report --coverage-html coverage xpaymentsAllTests .dev/tests/AllTests.php $1

P=`realpath ./ | replace '/u/'$USER'/public_html' ''`
echo 'Open coverage report http://xcart2.crtdev.local/~'$USER$P'/coverage link';
