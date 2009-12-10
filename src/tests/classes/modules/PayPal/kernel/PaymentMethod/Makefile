#
# Makefile for X-Lite project
#
# $Id: Makefile,v 1.1 2003/06/11 06:52:38 ndv Exp $


PHP_BIN=	/usr/local/bin/php
PHP_ARG=	-Cq
CUR_DIR=	classes/modules/PayPal/kernel/PaymentMethod/
ROOT_DIR=	../../../../../../

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
	cd ${ROOT_DIR}; find ${CUR_DIR} -name "*.php" -exec test -f tests/{}t \; \
	-exec make class-test class={} \;

