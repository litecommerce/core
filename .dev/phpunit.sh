#!/bin/sh
#
# Running PHPUnit tests on LiteCommerce software
#
# Usage examples:
#
# Run all tests (both unit and selenium)
# > phpunit.bat
#
# Run all unit tests:
# > phpunit.bat NOWEB
#
# Run all selenium tests:
# > phpunit.bat ONLYWEB
#
# Run deployment test (installs Ecommerce CMS package)
# > phpunit.bat DEPLOY_DRUPAL
#
# Run tests from specific class:
# > phpunit.bat Model/Session
#
# Run specific test from class:
# > phpunit.bat Model/Session:create
#
# When run specific tests please take attention that script will search
# specified class name in the directories tests/Classes (for unit tests)
# and tests/Web (for selenium tests)
#
# More examples:
#
# > phpunit.bat Core/Session
# > phpunit.bat Module/CDev/Bestsellers/Model/Repo/Product
# > phpunit.bat Customer/Authentication
# > phpunit.bat Admin/States
#

# Set VERBOSE_FLAG variable if you want to see more detailed report including
# skipped and incomplete tests
#
# VERBOSE_FLAG="--verbose"

cd $(dirname $0)
./phpunit xliteAllTests tests/AllTests.php LOCAL_TESTS,$1

