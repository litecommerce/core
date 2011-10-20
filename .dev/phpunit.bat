@echo off

REM Running PHPUnit tests on LiteCommerce software
REM
REM Usage examples:
REM
REM Run all tests (both unit and selenium)
REM > phpunit.bat
REM
REM Run all unit tests:
REM > phpunit.bat NOWEB
REM
REM Run all selenium tests:
REM > phpunit.bat ONLYWEB
REM
REM Run deployment test (installs Ecommerce CMS package)
REM > phpunit.bat DEPLOY_DRUPAL
REM
REM Run tests from specific class:
REM > phpunit.bat Model/Session
REM
REM Run specific test from class:
REM > phpunit.bat Model/Session:create
REM
REM When run specific tests please take attention that script will search
REM specified class name in the directories tests/Classes (for unit tests)
REM and tests/Web (for selenium tests)
REM
REM More examples:
REM
REM > phpunit.bat Core/Session
REM > phpunit.bat Module/CDev/Bestsellers/Model/Repo/Product
REM > phpunit.bat Customer/Authentication
REM > phpunit.bat Admin/States
REM


REM Set PHP_HOME variable here if you have not set it yet a an environment
REM variable. This should point to the directory where php.exe file is located
REM For example:
REM set PHP_HOME=c:\wamp\bin\php\php5.3.5\bin\
REM
REM set PHP_HOME=

REM Set VERBOSE_FLAG variable if you want to see more detailed report including
REM skipped and incomplete tests
REM
REM set VERBOSE_FLAG="--verbose" 


if not defined PHP_HOME goto noPhpHome
set PHP_BIN=%PHP_HOME%\php.exe
goto setPhpUnit


:noPhpHome
echo WARNING: You have not set the PHP_HOME environment variable. Trying to use defaults...
set PHP_BIN=php.exe


:setPhpUnit
if not exist %PHP_BIN% goto errorPhp

set CUR_DIR=%cd%
set WORK_DIR=%~dp0

chdir %WORK_DIR%

echo Starting PHPUnit...

%PHP_BIN% phpunit %VERBOSE_FLAG% xliteAllTests tests\AllTests.php LOCAL_TESTS,%*

chdir %CUR_DIR%

echo Tests complete

goto finish


:errorPhp
echo ERROR: Process failed because of %PHP_BIN% not found. Please define PHP_HOME environment variable


:finish

