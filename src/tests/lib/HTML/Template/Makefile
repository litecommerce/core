#
# Makefile for X-Lite project
#
# $Id: Makefile,v 1.1.1.1 2003/05/23 11:03:34 novik Exp $


PHP_BIN=	/usr/local/bin/php
PHP_ARG=	-Cq
CUR_DIR=	lib/HTML/Template/
ROOT_DIR=	../../../../

#
# Test class. 
# Usage: make class-test class=path/to/class/class.php
# This will search PHPUnit test named tests/path/to/class/class.phpt
# end execute with php-cli
# 
class-test:
	@if [ ! -r "${ROOT_DIR}${CUR_DIR}${class}" ]; then \
		echo "FAILED: class not found: ${class}"; \
		false; \
	fi
	@if [ ! -r "${class}t" ]; then \
		echo "FAILED: class test not found: ${class}t"; \
		false; \
	fi
	@echo ""
	@echo "running unit test ${class}t ..."
	cd ${ROOT_DIR}; ${PHP_BIN} ${PHP_ARG} tests/${CUR_DIR}${class}t

class-test-all:
	@cd ${ROOT_DIR}
	@find classes/${CUR_DIR} -name "*.php" -exec test -f tests/{}t \; \
	-exec make class-test class={} \;

