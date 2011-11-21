@echo off

REM Local PHP Code Shiffer
REM
REM Usage example:
REM
REM phpcs-report.bat
REM phpcs-report.bat file1
REM phpcs-report.bat file1 file2
REM


REM Set PHP_HOME variable here if you have not set it yet a an environment
REM variable. This should point to the directory where php.exe file is located
REM For example:
REM set PHP_HOME=c:\wamp\bin\php\php5.3.5\bin\
REM
REM set PHP_HOME=


if not defined PHP_HOME goto noPhpHome
set PHP_BIN=%PHP_HOME%\php.exe
goto setPhpCS


:noPhpHome
echo WARNING: You have not set the PHP_HOME environment variable. Trying to use defaults...
set PHP_BIN=php.exe


:setPhpCS

set FILES_LIST=

if "" == "%*" goto noArguments
set FILES_LIST=%*

goto doSniff

:noArguments
set FILES_LIST=%cd%

:doSniff

set CUR_DIR=%cd%
set WORK_DIR=%~dp0

set STANDARD=%WORK_DIR%code-sniffs\XLite

echo Standard: %STANDARD%

%PHP_BIN% %WORK_DIR%phpcs -s --report=full --standard=%STANDARD% %FILES_LIST%

