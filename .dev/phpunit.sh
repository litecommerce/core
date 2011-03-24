#!/usr/local/bin/zsh
#
# GIT: $Id$
#
# Local PHP Unit
#
# Usage example:
#
# ./phpunit.sh
#

cd $(dirname $0)
cd ..
.dev/phpunit xliteAllTests .dev/tests/AllTests.php LOCAL_TESTS,$1
