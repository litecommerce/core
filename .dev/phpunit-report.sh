#!/bin/sh

# Running PHPUnit tests on LiteCommerce software with code coverage reporting 
#
# Please take your attention that code coverage report builds only when unit
# tests running but not selenium tests
#
# Usage examples:
#
# Run all tests (both unit and selenium)
# > phpunit-report.bat
#
# Run all unit tests:
# > phpunit-report.bat NOWEB
#
# Run tests from specific class:
# > phpunit-report.bat Model/Session
#
# Run specific test from class:
# > phpunit-report.bat Model/Session:create
#
# When run specific tests please take attention that script will search
# specified class name in the directories tests/Classes (for unit tests)
#
# More examples:
#
# > phpunit-report.bat Core/Session
# > phpunit-report.bat Module/CDev/Bestsellers/Model/Repo/Product
#

cd $(dirname $0)
COVERAGE_DIR=`realpath ../coverage`

./phpunit --coverage-html $COVERAGE_DIR xliteAllTests tests/AllTests.php LOCAL_TESTS,$1

