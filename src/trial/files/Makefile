#
# Makefile for LiteCommerce project
#
# $Id: Makefile,v 1.7 2008/11/21 09:15:38 sheriff Exp $
#
# VARIABLES {{{
#PHP_BIN     = /usr/local/bin/php
PHP_BIN     = /usr/local/php-4311/bin/php
PHP_ARG     = -f
FILES       =
LOG         = /tmp/x-lite.test-log.$(USER)
DISTDIR     = ../dist
QUICKSTART	= quickstart
SOURCES     = COPYRIGHT CHANGELOG VERSION LICENSE.fake .htaccess \
              admin.php cart.php https_check.php install.php index.php loader.php \
              shop_closed.html welcome.html cart.html \
              includes bin classes compat etc lib lib5 sql schemas var images files catalog skins
ENC_SOURCES	= 
IONCUBE_DIR	= tools/ioncube
LOADER		= ${IONCUBE_DIR}/loader.php
IONCUBE		= /usr/local/ioncube/ioncube_encoder --without-loader-check --optimize max --erase-target
LC_TRIAL_SH = ./lc_trial.sh
SKINS       = skins              
INSTALLER_DIR   = tools/installer
INSTALLER_ARCH	= litecommerce.tar.gz
INSTALLER_EXE   = litecommerce.exe
BUILDER			= tools/installer/build.php
TEMPLATE_DIR = var/run/compile_dir
DEMODIR = demo
DEMOUSER = bit-bucket@x-cart.com
DEMO_MODULES_REMOVE = asp
DEMOTAR = xlite-demo.tar.gz
ASPDIR = asp
PUBLIC_KEY = 46f4855da3070db86634e2b04f0ecd194409578cf15c80ffcec0f6b5552f7669 550038e1e0f4b960040f519b286cdbf2e5ce51d77f15080c1d6b208a5a46dff7
OS_NAMES_LIST = win bsd dar fre lin net ope sun
# }}}

#.include <sql/Makefile>

release: # {{{
# }}}

clean: # {{{
	@echo "Cleaning CVS reject files.."
	-find . -type f -name "\.#*" -exec rm -f {} \;
	@echo "Checking for vim SWP files.."
	-find . -type f -name "\.*.swp" -exec echo Found SWP file {}, please check! \;
	@echo "Cleanup dist/demo/asp.."
	-rm -rf dist demo asp
# }}}

trial-license: # {{{
	@php -f create_license.php
# }}}

x-lite: pre-dist # {{{
	@#echo "Copying ionCube PHP loader.."
	@#cp ${LOADER} ${DISTDIR}
	@echo "Copying ioncube loaders.."
	@mkdir ${DISTDIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DISTDIR}/ioncube
	@echo "Copying classes cache.."
	@cp -R var ${DISTDIR}/var
	@cp classes/classes.lst ${DISTDIR}/classes/classes.lst
	@echo "Setting world accessible permission on distributive files.."
	@find ${DISTDIR} -type d -exec chmod 755 {} \;
	@find ${DISTDIR} -type f -exec chmod 644 {} \;
# }}}	

pre-dist: clean dist-clean build # {{{
	@echo "Removing empty folders ..."
	@echo "Preparing LiteCommerce package..."
	@if [ ! -d ${DISTDIR} ]; then \
        echo "Create dir ${DISTDIR}..."; \
        mkdir ${DISTDIR}; \
    fi
	@echo "Copying distributive source files..."
	@cp -R ${SOURCES} ${DISTDIR}
	@mv ${DISTDIR}/LICENSE.fake ${DISTDIR}/LICENSE
	@echo "Remove additional config file..."
	@if [ -f ${DISTDIR}/etc/config.local.php ]; then \
        rm -f ${DISTDIR}/etc/config.local.php; \
    fi    
	@echo "Removing Makefile(s)...";
	@-rm -f ${DISTDIR}/sql/Makefile
	@echo "Copying skin set..."
	@cp -RP ${SKINS} ${DISTDIR}/${SKINS}_original
	@-rm -f ${DISTDIR}/${SKINS}_original/.htaccess
	@echo "Copying Quick Start Wizard..."
	@cp -RP ${QUICKSTART} ${DISTDIR}/${QUICKSTART}
	@echo "Removing unnecessary files..."
	-rm -rf ${DISTDIR}/catalog/*
	-rm -rf ${DISTDIR}/files/*
	-rm -rf ${DISTDIR}/images/*
	-rm -rf ${DISTDIR}/skins/*
	@echo "Removing old library package(s)..."
	-rm -rf ${DISTDIR}/lib/HTML
	-rm -rf ${DISTDIR}/lib5/HTML
	@echo "Cleaning up CVS tags..."
	-find ${DISTDIR} -type d -name CVS -execdir rm -rf {} \;
# }}}

asp: clean build # {{{
	@echo "Preparing LiteCommerce ASP Edition package..."
	@echo "Removing empty folders ..."
	@cvs up -P    
	-rm -rf ${ASPDIR}
	@mkdir ${ASPDIR}
	@echo "Copying distributive source files.."
	@cp -R cpanel.php quickstart ${SOURCES} ${ASPDIR}
	@echo "Remove customer's frontend from ASP install dir..."
	@rm -f ${ASPDIR}/cart.php
	@echo "Remove additional config file.."
	@if [ -f ${ASPDIR}/etc/config.local.php ]; then \
        rm -f ${ASPDIR}/etc/config.local.php; \
    fi    
	@echo "Remove Makefile(s)..";
	-rm -f ${ASPDIR}/sql/Makefile
	@echo "Copying skin set.."
	@cp -RP ${SKINS} ${ASPDIR}/${SKINS}_original
	@echo "Remove modules from distribution.."
	-rm -rf ${ASPDIR}/classes/modules/*
	-rm -f ${ASPDIR}/sql/xlite_modules.sql
	-rm -f ${ASPDIR}/sql/xlite_demo_store.sql
	-find ${ASPDIR}/skins_original -type d -name modules -execdir rm -rf {} \;
	@echo "Copying ASP module..."
	@cp -Rv classes/modules/asp ${ASPDIR}/classes/modules
	@echo "Copying ASP module templates..."
	@mkdir ${ASPDIR}/skins_original/admin/en/modules
	@cp -Rv skins/admin/en/modules/asp ${ASPDIR}/skins_original/admin/en/modules
	@cp -Rv skins/mail/en/modules/asp ${ASPDIR}/skins_original/mail/en/modules
	@echo "Copying ioncube loaders.."
	@mkdir ${ASPDIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${ASPDIR}/ioncube
	@echo "Inserting license public key.."
	@sed "s/decKeyFromString(\"\([^\"]*\)\"/decKeyFromString(\"${PUBLIC_KEY}\"/" license.php > license.php.official
#
# Encoding + license
#
# classes/modules/asp/decoration.php -> encode + license
#
# classes/modules/asp/cpprepend.php -> encode -> includes/prepend.php
#
# classes/modules/asp/crypt.php -> encode
# classes/modules/asp/cart.php -> encode
# classes/modules/asp/admin.php -> encode
#
# classes/modules/asp/cpinstall.php -> install.php
# classes/modules/asp/cpindex.php -> index.php
#
	@echo "Encoding ASP source files.."
#	
# sources to encode + ASP classes/modules/asp/decoration.php
#
.for source in ${ENC_SOURCES} classes/modules/asp/decoration.php
	@cat ${source} | grep -v '^@include "license.php"' | sed -e 's/^class Object/class object__/g' > ${source}.php
	@echo "Adding license code.."
	@cat license.php.official >> ${source}.php
	@echo "Encoding file ${source} to ${ASPDIR}/${source}";
	@${IONCUBE} ${source}.php -o ${ASPDIR}/${source}
	@rm -f ${source}.php
.endfor
	@rm license.php.official
	@echo "Encoding ASP prepend.."
	@${IONCUBE} ${ASPDIR}/classes/modules/asp/cpprepend.php -o ${ASPDIR}/includes/prepend.php
	@rm -f ${ASPDIR}/classes/modules/asp/cpprepend.php
	@echo "Encoding ASP customer's interfaces.."
.for int in crypt.php cart.php admin.php
	@${IONCUBE} classes/modules/asp/${int} -o ${ASPDIR}/classes/modules/asp/${int}
.endfor
	@echo "Moving install and index.."
	@mv -f ${ASPDIR}/classes/modules/asp/cpindex.php ${ASPDIR}/index.php
	@mv -f ${ASPDIR}/classes/modules/asp/cpinstall.php ${ASPDIR}/install.php
	@echo "Cleanup CVS tags.."
	-find ${ASPDIR} -type d -name CVS -execdir rm -rf {} \;
	@echo "Setting world accessible permission on distributive files.."
	@find ${ASPDIR} -type d -exec chmod 755 {} \;
	@find ${ASPDIR} -type f -exec chmod 644 {} \;
# }}}

demo: clean # {{{
	@echo "Preparing LiteCommerce demo package..."
	-rm -rf ${DEMODIR}
	@mkdir ${DEMODIR}
	@echo "Copying distributive source files.."
	@cp -R ${SOURCES} ${DEMODIR}
	@cp restoredb ${DEMODIR}
	@echo "Copying demo store images.."
	@cp images.tgz ${DEMODIR}
	@echo "Remove additional config file.."
	@if [ -f ${DEMODIR}/etc/config.local.php ]; then \
        rm -f ${DEMODIR}/etc/config.local.php; \
    fi    
	@rm -f ${DEMODIR}/install.php
	@echo "Remove Makefile(s)..";
	-rm -f ${DEMODIR}/sql/Makefile
	@echo "Copying skin set.."
	@cp -RP ${SKINS} ${DEMODIR}/${SKINS}
	@echo "Copying Quick Start Wizard..."
	@cp -RP ${QUICKSTART} ${DEMODIR}/${QUICKSTART}
	@echo "Cleanup CVS tags.."
	-find ${DEMODIR} -type d -name CVS -execdir rm -rf {} \;
.for MODULE in ${DEMO_MODULES_REMOVE}
	@echo "Remove module ${MODULE}.."
	-rm -rf ${DEMODIR}/classes/modules/${MODULE}/*
	-rm -rf ${DEMODIR}/${SKINS}/admin/en/modules/${MODULE} ${DEMODIR}/${SKINS}/default/en/modules/${MODULE}
.endfor
	@echo "Create Makefile..."
	-cp Makefile-demo ${DEMODIR}/Makefile
	@echo "Copying ionclube PHP loader.."
	@cp ${LOADER} ${DEMODIR}
	@echo "Copying ioncube loaders.."
	mkdir ${DEMODIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DEMODIR}/ioncube
	@echo "Encoding source files.."
.for source in ${ENC_SOURCES}
	@cat ${source} | grep -v '^@include "license.php"' | sed -e 's/^class Object/class object__/g' > ${source}.php
	@echo "Adding license code.."
	@cat license.php >> ${source}.php
	@echo "Encoding file ${source} to ${DEMODIR}/${source}";
	@${IONCUBE} ${source}.php -o ${DEMODIR}/${source}
	@rm -f ${source}.php
.endfor
	make demo-modules
	@echo "Copying demo LICENSe.."
	@cp LICENSE.DEMO ${DEMODIR}/LICENSE
	@echo "Create ${DEMOTAR}.."
	-tar -czvf ${DEMOTAR} demo
# }}}

demo-modules: # {{{
	@echo "Encoding modules.."
	@for module in `ls classes/modules`; do \
	    if [ $${module} != "CVS" -a $${module} != "non-encoded" -a $${module} != "asp" ]; then \
	        if [ ! -f "classes/modules/non-encoded/$${module}" ]; then \
	            echo Encoding module $${module}; \
	            ${IONCUBE} classes/modules/$${module}/encoded.php -o ${DEMODIR}/classes/modules/$${module}/encoded.php; \
	        fi; \
	    fi; \
	done
# }}}

dist-clean: # {{{
	@echo "Cleaning up dist directory.."
	@if [ -d ${DISTDIR} ]; then \
		rm -rf ${DISTDIR}; \
	fi
# }}}

upgrade_patch: # {{{
	@echo ${ver} | sed s/-.*// > dist/UPGRADEVER
	@echo ${ver} | sed s/^.*-// >> dist/UPGRADEVER
	-mkdir dist/upgrade
	@cp upgrade/upgrade${ver}.php dist/upgrade
	@cp -R upgrade/upgrade${ver} dist/upgrade
	@cp upgrade/UPGRADE.readme dist
	@cp upgrade/MANIFEST${ver} dist/MANIFEST
	-find dist/upgrade -type d -name CVS -execdir rm -rf {} \;
	@tar -C dist -I upgrade/file-list${ver} -cz MANIFEST UPGRADE.readme upgrade/upgrade${ver}.php upgrade/upgrade${ver} UPGRADEVER -fupgrade${ver}.tgz
	-rm dist/UPGRADE.readme
	-rm dist/UPGRADEVER
	-rm -Rf dist/upgrade
# }}}

installer: # {{{
	@chmod 0700 tools/installer/build.php
	@chmod 0700 ../lc_trial.sh
	@echo "Creating installation package.."
	@if [ -d ${DISTDIR} ]; then \
		echo "Packing distribution files to ${INSTALLER_ARCH}..."; \
		find ${INSTALLER_DIR}/ -type f -name "*\.tar\.gz" -exec rm -rf {} \; ; \
		cd ${DISTDIR}; \
		gnu_tar -zcvf ${INSTALLER_ARCH} *; \
		mv ${INSTALLER_ARCH} ../; \
		find ioncube/ -type f -name "*\.so" -exec rm -rf {} \; ; \
		find ioncube/ -type f -name "*\.dll" -exec rm -rf {} \; ; \
		cd ../predistr; \
		for os_name in ${OS_NAMES_LIST}; do \
			echo "Packing distribution files to $${os_name}_${INSTALLER_ARCH}..."; \
			cp ../../${IONCUBE_DIR}/ioncube_loader/*_$${os_name}_* ${DISTDIR}/ioncube; \
			cd ${DISTDIR}; \
			find ioncube/ -type f -exec chmod 644 {} \; ; \
			gnu_tar -zcvf $${os_name}_${INSTALLER_ARCH} *; \
			mv $${os_name}_${INSTALLER_ARCH} ../; \
			find ioncube/ -type f -name "*\.so" -exec rm -rf {} \; ; \
			find ioncube/ -type f -name "*\.dll" -exec rm -rf {} \; ; \
			cd ../predistr; \
		done; \
		cd ${DISTDIR}; \
		find ioncube/ -type f -name "*\.so" -exec rm -rf {} \; ; \
		find ioncube/ -type f -name "*\.dll" -exec rm -rf {} \; ; \
		gnu_tar -zcvf exe_${INSTALLER_ARCH} *; \
		mv exe_${INSTALLER_ARCH} ../; \
		cd ..; \
    	${LC_TRIAL_SH}; \
	fi	
	@#${BUILDER} -s ${INSTALLER_DIR}/bin/install.exe -a ${INSTALLER_DIR}/${INSTALLER_ARCH} -o ${INSTALLER_DIR}/${INSTALLER_EXE}
# }}}		

post-clean: # {{{
	@#mv ${INSTALLER_DIR}/${INSTALLER_ARCH} ../${INSTALLER_ARCH}
	@#mv ${INSTALLER_DIR}/${INSTALLER_EXE} ../${INSTALLER_EXE}
	@#echo "Download installer executable from: ${INSTALLER_EXE}"
# }}}

install: # {{{
	@echo "NOT IMPLEMENTED YET"
	@false
# }}}

permissions: # {{{
	-find . -type d -exec chmod 751 {} \;
	-find . -type f -exec chmod 640 {} \;
	-chmod 755 tools/installer/build.php
	-chmod 660 cart.html
	-chmod -fR 770 classes/modules
	-chmod -fR 770 skins
	-chmod -fR 777 var
	-chmod +x bin/*
	-chmod +x .libs/*
	-chmod +x bin/.libs/*
# }}}

build: # {{{
	#./build_classes.php
# }}}

#
# Test cart application
#
# in order to test concrete target(s) use
#   make app-test target=target1,target2,target3
#
app-test: # {{{
	@${PHP_BIN} ${PHP_ARG} tests/application/application.php ${target}
# }}}

#
# Test class. 
# Usage: make class-test class=path/to/class/class.php
# This will search PHPUnit test named tests/path/to/class/class.phpt
# end execute with php-cli
# 
class-test: # {{{
	@if [ ! -r "${class}" ]; then \
		echo "FAILED: class not found: ${class}"; \
		false; \
	fi
	@if [ ! -r "tests/${class}t" ]; then \
		echo "FAILED: class test not found: tests/${class}t"; \
		false; \
	fi
	@echo ""
	@echo "Running unit test tests/${class}t ..."
	@${PHP_BIN} ${PHP_ARG} tests/${class}t
# }}}

module-test: # {{{
	@echo "Test module ${module}..."
	@${CMD} -e "update xlite_modules set enabled=1 where '${module}' like concat('%/',name)" ${MYSQL_BASE}
	@find ${module} -name "*.php" -exec test -f tests/{}t \; -exec make class-test class={} \; 2>>${LOG}
	@${CMD} -e "update xlite_modules set enabled=0 where '${module}' like concat('%/',name)" ${MYSQL_BASE}
# }}}

class-test-all: # {{{
	@echo -n > ${LOG}
	@find classes -name "*.php" -not -path "classes/modules*" -exec test -f tests/{}t \; \
	-exec make class-test class={} \; 2>>${LOG}
	@find classes/modules -maxdepth 1 -mindepth 1 -exec make module-test module={} \;
	./restoredb
	@if [ -s ${LOG} ]; then \
	    echo ""; \
	    echo ""; \
		echo "One or more test(s) failed, log follows:"; \
        echo "============================================="; \
	    echo ""; \
		cat ${LOG}; \
	    false; \
	fi
	@echo ""
	@echo ""
	@echo "   OOO     OK   OK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "OK     OK  OKOK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "   OOO     OK   OK"; 
	@echo ""
	@echo ""
	-rm -f ${LOG}
	-rm -Rf var/run/compile_dir/*
# }}}

modules-enc: # {{{
	@echo Create encoded modules..
	@make modules-dist arg=-enc
# }}}

modules-dist: # {{{
	@echo Create modules..
	@for module in `ls classes/modules`; do \
		if [ $${module} != "CVS" -a $${module} != "non-encoded" ]; then \
		    if [ -f "classes/modules/non-encoded/$${module}" ]; then \
                echo Do not encode module $${module}; \
    			make module module=$${module}; \
            else \
    			make module${arg} module=$${module}; \
            fi; \
		fi; \
	done
# }}}

# create a list of module files {{{
.if defined(module) && !empty(module)

.if exists( classes/modules/${module} )
FILES+= classes/modules/${module}
.endif
.if exists( skins/default/en/modules/${module} )
FILES+= skins/default/en/modules/${module}
.endif
.if exists( skins/default/en/images/modules/${module} )
FILES+= skins/default/en/images/modules/${module}
.endif
.if exists( skins/admin/en/images/modules/${module} )
FILES+= skins/admin/en/images/modules/${module}
.endif
.if exists( skins/admin/en/modules/${module} )
FILES+= skins/admin/en/modules/${module}
.endif
.if exists( skins/mail/en/modules/${module} )
FILES+= skins/mail/en/modules/${module}
.endif
# }}}

.if !empty(FILES)

module-enc: # {{{
.if exists( classes/modules/${module}/${module}.php )
.if exists( classes/modules/${module}/encoded.php )
	@echo Create encrypted module ${module}..
	@echo Backup main module class..
	@cp classes/modules/${module}/encoded.php classes/modules/
	@echo Encode with ioncube
	@${IONCUBE} classes/modules/encoded.php -o classes/modules/${module}/encoded.php
	@echo Create module archive..
	@make module module=${module}
	@echo Restore original main module class
	@mv classes/modules/encoded.php classes/modules/${module}/
.else
	@echo Create non-encrypted module ${module}..
	@make module module=${module}
.endif    
	@echo ""
	@echo ""
.else
	@echo "*** Main module class classes/${module}/${module}.php not found"
.endif
# }}}

# Creates module package by module name
.if exists( classes/modules/${module}/MANIFEST )
VERSION=`grep ^version classes/modules/${module}/MANIFEST | sed -e 's/.*=//g'`
.else
VERSION=1.0
.endif
module: # {{{
.if exists( classes/modules/${module}/Makefile )
.include "classes/modules/${module}/Makefile"
.else
	@echo Creating archive for module ${module}, version: ${VERSION} ...
	@tar -cf ${module}_add-on-${VERSION}.tar ${FILES} --exclude=CVS
.endif
# }}}

# Creates module package with CVS data
module-cvs: # {{{
	@tar -cf ${module}_add-on-${VERSION}.tar ${FILES}
# }}}
.endif
.endif
