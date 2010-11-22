#!/usr/local/bin/zsh
#
# $Id$
#
# Local PHP Unit
#
# Usage example:
#
# ./phpunit.sh
#

cd $(dirname $0)
cd ..
phpunit xliteAllTests .dev/tests/AllTests.php LOCAL_TESTS,$1
