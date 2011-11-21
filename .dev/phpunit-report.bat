@echo off

REM Running PHPUnit tests on LiteCommerce software with code coverage reporting 
REM
REM Please take your attention that code coverage report builds only when unit
REM tests running but not selenium tests
REM
REM Usage examples:
REM
REM Run all tests (both unit and selenium)
REM > phpunit-report.bat
REM
REM Run all unit tests:
REM > phpunit-report.bat NOWEB
REM
REM Run tests from specific class:
REM > phpunit-report.bat Model/Session
REM
REM Run specific test from class:
REM > phpunit-report.bat Model/Session:create
REM
REM When run specific tests please take attention that script will search
REM specified class name in the directories tests/Classes (for unit tests)
REM
REM More examples:
REM
REM > phpunit-report.bat Core/Session
REM > phpunit-report.bat Module/CDev/Bestsellers/Model/Repo/Product
REM


REM Set PHP_HOME variable here if you have not set it yet a an environment
REM variable. This should point to the directory where php.exe file is located
REM For example:
REM set PHP_HOME=c:\wamp\bin\php\php5.3.5\bin\
REM
REM set PHP_HOME=


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

set COVERAGE_DIR=..\coverage

%PHP_BIN% phpunit --coverage-html %COVERAGE_DIR% xliteAllTests tests\AllTests.php LOCAL_TESTS,%*

chdir %CUR_DIR%

echo Tests complete

goto finish


:errorPhp
echo ERROR: Process failed because of %PHP_BIN% not found. Please define PHP_HOME environment variable


:finish

