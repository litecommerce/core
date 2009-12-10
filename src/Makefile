#
# Makefile for LiteCommerce project
#
# $Id: Makefile,v 1.183 2009/10/19 11:25:39 fundaev Exp $
#
# VARIABLES {{{
PHP_BIN     = /usr/local/bin/php
#PHP_BIN     = /usr/local/php-4311/bin/php
PHP_ARG     = -f
FILES       =
LOG         = /tmp/x-lite.test-log.$(USER)
DISTDIR     = dist
DISTDIRHE	= dist_he
HE_SHOP_PATCH = lc_hsp/shop_patch
HE_SHOP_PATCH_FILES = classes etc includes license.php skins_original
HE_SHOP_FILES = .htaccess CHANGELOG COPYRIGHT VERSION admin.php bin cart.html cart.php classes cleanup.php compat etc https_check.php includes index.php lib lib5 loader.php quickstart schemas shop_closed.html skins_original sql welcome.html 
HE_SHOPION_FILES = ioncube
HE_SHOP_MODULES_FILES = classes/modules skins_original/admin/en/modules skins_original/admin/en/images/modules skins_original/default/en/modules skins_original/default/en/images/modules
HE_SERVER_PATCH = lc_hsp/ls
HE_SERVER_SOURCES = cleanup.php loader.php includes bin compat lib lib5
HE_SERVER_PATCH_FILES = .htaccess CHANGELOG COPYRIGHT VERSION classes cpanel.php index.php etc license.php post.php skins sql includes/prepend.php includes/prepend2.php etc/config.php http_check.php crypt.php
HE_SERVER_FILES = .htaccess CHANGELOG COPYRIGHT VERSION cpanel.php bin index.php classes cleanup.php compat etc post.php includes loader.php skins sql
HE_SERVERCHK_FILES = http_check.php crypt.php lib lib5
HE_SERVERION_FILES = ioncube
QUICKSTART	= quickstart
SOURCES     = COPYRIGHT CHANGELOG VERSION LICENSE.fake .htaccess \
              admin.php cart.php cleanup.php https_check.php install.php index.php loader.php \
              shop_closed.html welcome.html cart.html \
              includes bin classes compat etc lib lib5 sql schemas var images files catalog skins
ENC_SOURCES	= includes/decoration.php compat/gmp.php classes/base/Object.php classes/base/LObject.php
MODULES_LIST = 2CheckoutCom AccountingPackage AdvancedSecurity AuthorizeNet AutoUpdateCatalog BankOfAmerica Bestsellers CyberSource DetailedImages Echo FeaturedProducts GiftCertificates GreetVisitor HSBC HTMLCatalog Intershipper InventoryTracking LinkPoint MultiCategories NetRegistry Ogone PayPal PaySystems PlugnPay ProductOptions Promotion ProtxDirect ShowcaseOrganizer TrustCommerce UPS USPS WellsFargo WorldPay XCartImport ePDQ eSelect eWAYxml
MODULES_NOT_IN_HE = AntiFraud BankOfAmerica DefaultLogins DemoMode LiveUpdate UPS XCartImport asp non-encoded VeriSign VerisignLink
IONCUBE_DIR	= tools/ioncube
LOADER		= ${IONCUBE_DIR}/loader.php
#IONCUBE		= /usr/local/ioncube/ioncube_encoder --without-loader-check --allow-untrusted-extensions --optimize max --erase-target --binary
IONCUBE     = ./ion.sh
LC_PRODUCTION_SH = ./lc_production.sh
SKINS       = skins              
INSTALLER_DIR   = tools/installer
INSTALLER_ARCH	= litecommerce.tar.gz
INSTALLER_EXE   = litecommerce.exe
DISPLAY_VER     = `./mkdisplayver.pl ${ver}`
INSTALL_ARCH    = neo:/bigstuff/litecommerce/releases/litecommerce-${DISPLAY_VER}.tgz
INSTALL_EXE     = neo:/bigstuff/litecommerce/releases/litecommerce-${DISPLAY_VER}.exe
INSTALL_ASP     = neo:/bigstuff/litecommerce/asp/litecommerce-ASP-${DISPLAY_VER}.tgz
BUILDER			= tools/installer/build.php
TEMPLATE_DIR = var/run/compile_dir
DEMODIR = demo
DEMOUSER = demo@litecommerce.com
DEMO_MODULES_REMOVE = asp non-encoded
DEMOTAR = xlite-demo.tar.gz
ASPDIR = asp
PUBLIC_KEY = 46f4855da3070db86634e2b04f0ecd194409578cf15c80ffcec0f6b5552f7669 550038e1e0f4b960040f519b286cdbf2e5ce51d77f15080c1d6b208a5a46dff7
TMPFILE = 1080309773
OS_NAMES_LIST = win bsd dar fre lin net ope sun
# }}}

.include <sql/Makefile>

executable:
	@chmod 0700 ion.sh ion_caller.php build_classes.php restoredb mktrial.php tools/installer/build.php

clean-trash: # {{{
	@echo "Cleaning CVS reject files..."
	@-find . -type f -name "\.#*" -exec rm -rf {} \;
	@if [ ! -d ${DISTDIR} ]; then \
        echo "Create dir ${DISTDIR}.."; \
        mkdir ${DISTDIR}; \
    fi
	@echo "Checking for vim SWP files..."
	@if [ -f ${DISTDIR}/.swp.lock ]; then \
        rm -f ${DISTDIR}/.swp.lock; \
    fi    
	@-find . -type f -name "\.*.swp" -exec echo "Found SWP file {}, please check!" > ${DISTDIR}/.swp.lock \;
	@if [ -s ${DISTDIR}/.swp.lock ]; then \
		echo " "; \
		cat ${DISTDIR}/.swp.lock; \
        rm -f ${DISTDIR}/.swp.lock; \
        false; \
    fi    
	@if [ -f ${DISTDIR}/.swp.lock ]; then \
        rm -f ${DISTDIR}/.swp.lock; \
    fi    
# }}}

clean-dirs: # {{{
	@echo "Cleaning up working directories..."
	@if [ -d ${DISTDIR} ]; then \
		rm -rf ${DISTDIR}; \
	fi
	@if [ -d ${DISTDIRHE} ]; then \
		rm -rf ${DISTDIRHE}; \
	fi
	@if [ -d demo ]; then \
		rm -rf demo; \
	fi
	@if [ -d asp ]; then \
		rm -rf asp; \
	fi
# }}}

clean: cleandb clean-trash clean-dirs # {{{
	@echo ""
# }}}

trial-license: # {{{
	@php -f create_license.php
# }}}

x-lite: pre-dist # {{{
	@echo "Copying ionCube PHP loader..."
	@cp ${LOADER} ${DISTDIR}
	@mkdir ${DISTDIR}/ioncube
	@echo "Inserting public key..."
	@sed "s/decKeyFromString(\"\([^\"]*\)\"/decKeyFromString(\"${PUBLIC_KEY}\"/" license.php > license.php.official
	@echo "Inserting software links..."
	@cp classes/base/Object.php classes/base/Object.orig.php
	@chmod 0700 software_links.php
	@./software_links.php -b LC -p "\"ecommerce software\", \"e-commerce software\"" -d classes/base/Object.php
	@echo "Copying ioncube loaders..."
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DISTDIR}/ioncube
	@echo "Encoding source files..."
.for source in ${ENC_SOURCES}
	@cat ${source} | grep -v '^@include "license.php"' | sed -e 's/^class Object/class object__/g' | sed -e 's/^class LObject extends Object/class lobject__ extends object__/g' > ${source}.php
	@echo "Adding license code..."
	@cat license.php.official >> ${source}.php
	@echo "Encoding file ${source} to ${DISTDIR}/${source}";
	@chmod 0640 ${source}.php
	@${IONCUBE} ${source}.php ${DISTDIR}/${source}
	@rm -f ${source}.php
.endfor
	@rm license.php.official
	@cp classes/base/Object.orig.php classes/base/Object.php
	@rm -f classes/base/Object.orig.php
	@echo "Adding MD5 signature of essential files into installer script..."
	@chmod 0700 ${INSTALLER_DIR}/make_installmd5.php
	@${PHP_BIN} ${PHP_ARG} ${INSTALLER_DIR}/make_installmd5.php
	@echo "Setting world accessible permission on distributive files..."
	@find ${DISTDIR} -type d -exec chmod 755 {} \;
	@find ${DISTDIR} -type f -exec chmod 644 {} \;
	@chmod 0666 ${DISTDIR}/cart.html
# }}}	

pre-dist: clean dist-clean build # {{{
	@echo "Removing empty folders ..."
	@cvs up -P 2>&-
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
	-rm -rf ${DISTDIR}/var/*
	@echo "Removing modules from distribution..."
	-rm -rf ${DISTDIR}/classes/modules/*
	-rm -f ${DISTDIR}/sql/xlite_modules.sql
	-rm -f ${DISTDIR}/sql/xlite_demo_store.sql
	-find ${DISTDIR}/skins_original -type d -name modules -execdir rm -rf {} \;
	@echo "Removing old library package(s)..."
	-rm -rf ${DISTDIR}/lib/HTML
	-rm -rf ${DISTDIR}/lib5/HTML
	@echo "Cleaning up CVS tags..."
	-find ${DISTDIR} -type d -name CVS -execdir rm -rf {} \;
# }}}

asp: executable clean build # {{{
	@echo "Preparing LiteCommerce ASP Edition package..."
	@echo "Removing empty folders ..."
	@cvs up -P 2>&-
	-rm -rf ${ASPDIR}
	@mkdir ${ASPDIR}
	@echo "Copying distributive source files..."
	@cp -R cpanel.php batch.php quickstart images ${SOURCES} ${ASPDIR}
	@echo "Remove default front/back-ends from ASP install dir..."
	@rm -f ${ASPDIR}/cart.php
	@rm -f ${ASPDIR}/admin.php
	@echo "Remove additional config file..."
	@if [ -f ${ASPDIR}/etc/config.local.php ]; then \
        rm -f ${ASPDIR}/etc/config.local.php; \
    fi    
	@echo "Remove Makefile(s)...";
	@-rm -f ${ASPDIR}/sql/Makefile
	@echo "Copying skin set..."
	@cp -RP ${SKINS} ${ASPDIR}/${SKINS}_original
	@echo "Remove modules from distribution..."
	-rm -rf ${ASPDIR}/classes/modules/*
	-rm -f ${ASPDIR}/sql/xlite_modules.sql
	-rm -f ${ASPDIR}/sql/xlite_demo_store.sql
	-find ${ASPDIR}/skins_original -type d -name modules -execdir rm -rf {} \;
	@echo "Copying ASP module..."
	@cp -Rv classes/modules/asp ${ASPDIR}/classes/modules
	@echo "Copying ASP module templates... "
	@mkdir ${ASPDIR}/skins_original/admin/en/modules
	@mkdir -p ${ASPDIR}/skins_original/admin/en/images/modules/asp
	@mkdir ${ASPDIR}/skins_original/mail/en/modules
	@cp -Rv skins/admin/en/modules/asp ${ASPDIR}/skins_original/admin/en/modules
	@cp -Rv skins/admin/en/images/modules/asp ${ASPDIR}/skins_original/admin/en/images/modules
	@cp -Rv skins/mail/en/modules/asp ${ASPDIR}/skins_original/mail/en/modules
	@echo "Copying ioncube loaders..."
	@mkdir ${ASPDIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${ASPDIR}/ioncube
	@echo "Inserting license public key..."
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
# classes/modules/asp/dscart.php -> encode
# classes/modules/asp/dsadmin.php -> encode
#
# classes/modules/asp/cpinstall.php -> install.php
# classes/modules/asp/cpindex.php -> index.php
#
	@echo "Encoding ASP source files..."
#	
# sources to encode + ASP classes/modules/asp/decoration.php
#
.for source in ${ENC_SOURCES} classes/modules/asp/decoration.php
	@cat ${source} | grep -v '^@include "license.php"' | sed -e 's/^class Object/class object__/g' | sed -e 's/^class LObject extends Object/class lobject__ extends object__/g' | sed -e 's/lc.gif/lc_asp.gif/g' > ${source}.php
	@echo "Adding license code..."
	@cat license.php.official >> ${source}.php
	@echo "Encoding file ${source} to ${ASPDIR}/${source}";
	@chmod 0640 ${source}.php
	@${IONCUBE} --ascii ${source}.php ${ASPDIR}/${source}
	@rm -f ${source}.php
.endfor
	@rm license.php.official
	@echo "Encoding ASP prepend..."
	@chmod 0640 classes/modules/asp/cpprepend.php
	@${IONCUBE} --ascii classes/modules/asp/cpprepend.php ${ASPDIR}/includes/prepend.php
	@rm -f ${ASPDIR}/classes/modules/asp/cpprepend.php
	@echo "Encoding ASP customer's interfaces..."
.for int in crypt.php cart.php admin.php
	@chmod 0640 classes/modules/asp/${int}
	@${IONCUBE} --ascii classes/modules/asp/${int} ${ASPDIR}/classes/modules/asp/${int}
.endfor
	@chmod 0640 classes/modules/asp/dscart.php
	@${IONCUBE} --ascii classes/modules/asp/dscart.php ${ASPDIR}/cart.php
	@rm ${ASPDIR}/classes/modules/asp/dscart.php
	@chmod 0640 classes/modules/asp/dsadmin.php
	@${IONCUBE} --ascii classes/modules/asp/dsadmin.php ${ASPDIR}/admin.php
	@rm ${ASPDIR}/classes/modules/asp/dsadmin.php
	@chmod 0640 classes/modules/asp/http_check.php
	@${IONCUBE} --ascii classes/modules/asp/http_check.php ${ASPDIR}/http_check.php
	@rm ${ASPDIR}/classes/modules/asp/http_check.php
	@echo "Moving install and index..."
	@mv -f ${ASPDIR}/classes/modules/asp/cpindex.php ${ASPDIR}/index.php
	@mv -f ${ASPDIR}/classes/modules/asp/cpinstall.php ${ASPDIR}/install.php
	@echo "Rewriting callback images..."
.for f in ${ASPDIR}/install.php ${ASPDIR}/skins_original/admin/en/menu.tpl
	@echo ${f}
	@cat ${f} | sed -e 's/lc.gif/lc_asp.gif/g' | sed -e 's/logo_lite.gif/logo_lite_asp.gif/g' > /tmp/${TMPFILE}.tmp 
	@cat /tmp/${TMPFILE}.tmp > ${f}
.endfor
	@mv ${ASPDIR}/LICENSE.fake ${ASPDIR}/LICENSE
	@echo "Cleanup CVS tags..."
	-find ${ASPDIR} -type d -name CVS -execdir rm -rf {} \;
	@echo "Adding MD5 signature of essential files into installer script..."
	@chmod 0700 ${INSTALLER_DIR}/make_installmd5_asp.php
	@${PHP_BIN} ${PHP_ARG} ${INSTALLER_DIR}/make_installmd5_asp.php
	@echo "Setting world accessible permission on distributive files..."
	@find ${ASPDIR} -type d -exec chmod 755 {} \;
	@find ${ASPDIR} -type f -exec chmod 644 {} \;
	@echo "Removing unnecessary files..."
	@rm -rf ${ASPDIR}/catalog/*
	@rm -rf ${ASPDIR}/files/*
	@rm -rf ${ASPDIR}/images/*
	@rm -rf ${ASPDIR}/skins/*
	@rm -rf ${ASPDIR}/var/*
	@-rm -f ${ASPDIR}/${SKINS}_original/.htaccess
	@rm /tmp/${TMPFILE}.tmp
# }}}

demo: clean # {{{
	@echo "Preparing LiteCommerce demo package..."
	-rm -rf ${DEMODIR}
	@mkdir ${DEMODIR}
	@echo "Copying distributive source files..."
	@cp -R ${SOURCES} ${DEMODIR}
	@cp restoredb ${DEMODIR}
	@echo "Copying demo store images..."
	@cp images.tgz ${DEMODIR}
	@echo "Remove additional config file..."
	@if [ -f ${DEMODIR}/etc/config.local.php ]; then \
        rm -f ${DEMODIR}/etc/config.local.php; \
    fi    
	@rm -f ${DEMODIR}/install.php
	@echo "Remove Makefile(s)...";
	@-rm -f ${DEMODIR}/sql/Makefile
	@echo "Copying skin set..."
	@cp -RP ${SKINS} ${DEMODIR}/${SKINS}
	@echo "Copying Quick Start Wizard..."
	@cp -RP ${QUICKSTART} ${DEMODIR}/${QUICKSTART}
	@echo "Cleanup CVS tags..."
	-find ${DEMODIR} -type d -name CVS -execdir rm -rf {} \;
.for MODULE in ${DEMO_MODULES_REMOVE}
	@echo "Remove module ${MODULE}..."
	-rm -rf ${DEMODIR}/classes/modules/${MODULE}/*
	-rm -rf ${DEMODIR}/${SKINS}/admin/en/modules/${MODULE} ${DEMODIR}/${SKINS}/default/en/modules/${MODULE}
.endfor
	@echo "Create Makefile..."
	-cp Makefile-demo ${DEMODIR}/Makefile
	@echo "Copying ionclube PHP loader..."
	@cp ${LOADER} ${DEMODIR}
	@echo "Copying ioncube loaders..."
	@mkdir ${DEMODIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DEMODIR}/ioncube
	@echo "Encoding source files..."
.for source in ${ENC_SOURCES}
	@cat ${source} | grep -v '^@include "license.php"' | sed -e 's/^class Object/class object__/g' | sed -e 's/^class LObject extends Object/class lobject__ extends object__/g' > ${source}.php
	@echo "Adding license code..."
	@cat license.php >> ${source}.php
	@echo "Encoding file ${source} to ${DEMODIR}/${source}";
	@chmod 0640 ${source}.php
	${IONCUBE} ${source}.php ${DEMODIR}/${source}
	@rm -f ${source}.php
.endfor
	make demo-modules
	@echo "Copying demo LICENSE..."
	@cp LICENSE.DEMO ${DEMODIR}/LICENSE
	@echo "Create ${DEMOTAR}..."
	-gnu_tar -czvf ${DEMOTAR} demo
# }}}

demo-modules: # {{{
	@echo "Encoding modules..."
	@for module in ${MODULES_LIST}; do \
	    if [ $${module} != "CVS" -a $${module} != "non-encoded" -a $${module} != "asp" ]; then \
	        if [ ! -f "classes/modules/non-encoded/$${module}" ]; then \
	            echo Encoding module $${module}; \
	            ${IONCUBE} classes/modules/$${module}/encoded.php ${DEMODIR}/classes/modules/$${module}/encoded.php; \
	        fi; \
	    fi; \
	done
# }}}

dist-clean: # {{{
	@echo "Cleaning up dist directory..."
	@if [ -d ${DISTDIR} ]; then \
		rm -rf ${DISTDIR}; \
	fi
# }}}

prepare_upgrade: #  accepts two parameters: from= and to= that are version numbers {{{
	@echo "Preparing upgrade package..."
	@./prepare_upgrade.pl ${ver} ${SOURCES} skins
# }}}

upgrade_patch: # {{{
	@chmod 0700 ./upgradever.pl
	./upgradever.pl ${ver} > dist/UPGRADEVER
	-mkdir dist/upgrade
	@cp upgrade/upgrade${ver}.php dist/upgrade
	@cp upgrade/upgrade${ver}.sql dist/upgrade
	@cp -R upgrade/upgrade${ver} dist/upgrade
	@cp upgrade/UPGRADE.readme dist
	@cp upgrade/MANIFEST${ver} dist/MANIFEST
	-find dist/upgrade -type d -name CVS -execdir rm -rf {} \;
	@echo "Setting world accessible permission on upgrade files..."
	@find dist/upgrade -type d -exec chmod 755 {} \;
	@find dist/upgrade -type f -exec chmod 644 {} \;
	@gnu_tar -C dist -I upgrade/file-list${ver} -cz MANIFEST UPGRADE.readme upgrade/upgrade${ver}.php upgrade/upgrade${ver}.sql upgrade/upgrade${ver} UPGRADEVER -fupgrade${ver}.tgz
	-rm dist/UPGRADE.readme
	-rm dist/UPGRADEVER
	-rm -Rf dist/upgrade
# }}}

installer: x-lite # {{{
	@echo "Creating installation package..."
	@if [ -d ${DISTDIR} ]; then \
		echo "Packing distribution files to ${INSTALLER_ARCH}..."; \
		cd ${DISTDIR}; \
		gnu_tar -zcvf ${INSTALLER_ARCH} .htaccess *; \
		mv ${INSTALLER_ARCH} ../${INSTALLER_DIR}; \
		find ioncube/ -type f -name "*\.so" -exec rm -rf {} \; ; \
		find ioncube/ -type f -name "*\.dll" -exec rm -rf {} \; ; \
		cd ..; \
		for os_name in ${OS_NAMES_LIST}; do \
			echo "Packing distribution files to $${os_name}_${INSTALLER_ARCH}..."; \
			cp ${IONCUBE_DIR}/ioncube_loader/*_$${os_name}_* ${DISTDIR}/ioncube; \
			cd ${DISTDIR}; \
			find ioncube/ -type f -exec chmod 644 {} \; ; \
			gnu_tar -zcvf $${os_name}_${INSTALLER_ARCH} .htaccess *; \
			mv $${os_name}_${INSTALLER_ARCH} ../${INSTALLER_DIR}; \
			find ioncube/ -type f -name "*\.so" -exec rm -rf {} \; ; \
			find ioncube/ -type f -name "*\.dll" -exec rm -rf {} \; ; \
			cd ..; \
		done; \
	fi	
	@${BUILDER} -s ${INSTALLER_DIR}/bin/install.exe -a ${INSTALLER_DIR}/${INSTALLER_ARCH} -o ${INSTALLER_DIR}/${INSTALLER_EXE}
	@echo "Download installer executable from: ${INSTALLER_EXE}"
	@if [ -f ${INSTALLER_DIR}/${INSTALLER_ARCH} ]; then \
		cd  ${INSTALLER_DIR}; \
		chmod 0700 ${LC_PRODUCTION_SH}; \
		${LC_PRODUCTION_SH}; \
	fi
# }}}		

install: asp installer # {{{
#	@scp ${INSTALLER_DIR}/${INSTALLER_ARCH} ${INSTALL_ARCH}
#	@scp ${INSTALLER_DIR}/${INSTALLER_EXE} ${INSTALL_EXE}
#	-gnu_tar -czvf ${ASPDIR}.tgz ${ASPDIR}
#	@scp ${ASPDIR}.tgz ${INSTALL_ASP}
# }}}

permissions: # {{{
	-find . -type f -exec chmod 0644 {} \;
	-find . -type d -exec chmod 0755 {} \;
	-chmod 0600 Makefile
	-chmod 0755 tools/installer/build.php
	-chmod 0644 cart.html
	-chmod -fR 0775 classes/modules
	-chmod -fR 0775 skins
	-chmod -fR 0777 var
	-chmod 0777 .
	-chmod +x bin/*
	-chmod 0700 build_classes.php
	-chmod 0700 ion.sh
	-chmod 0700 ion_caller.php
	-chmod 0700 restoredb
	-chmod +x .libs/*
	-chmod +x bin/.libs/*
# }}}

build: executable # {{{
	@./build_classes.php
	@echo "Checking PHP syntax ..."
	@-find classes/ -type f -name "*.php" -exec ${PHP_BIN} -l {} \; | grep "Parse error" > classes/.phpchk
	@if [ -s classes/.phpchk ]; then \
		cat classes/.phpchk; \
        rm -f classes/.phpchk; \
        false; \
    else \
        rm -f classes/.phpchk; \
    fi    
	@-find includes/ -type f -name "*.php" -exec ${PHP_BIN} -l {} \; | grep "Parse error" > includes/.phpchk
	@if [ -s includes/.phpchk ]; then \
		cat includes/.phpchk; \
        rm -f includes/.phpchk; \
        false; \
    else \
        rm -f includes/.phpchk; \
    fi    
	@-rm -rf var/run/classes
# }}}

lc-trial: executable build # {{{
	@if [ ! -r "etc/config.local.php" ]; then \
		echo "Local config file nod found. could not continue"; \
		false; \
	fi
	@./restoredb
	@if [ -r "LICENSE" ]; then \
		cat LICENSE > trial/files/LICENSE.OLD; \
	fi
	@cat trial/files/LICENSE > LICENSE
	@make permissions
	@${PHP_BIN} ${PHP_ARG} mktrial.php
	@if [ -r "trial/files/LICENSE.OLD" ]; then \
		cat trial/files/LICENSE.OLD > LICENSE; \
	fi
	@(cd trial/predistr; make x-lite)
	@(cd trial/predistr; make installer)
	@(cd trial/predistr; make post-clean)
#	@rm -rf trial/predistr
# }}}

lc-he-shop: lc-he-shop-pre-dist # {{{
	@echo ""
	@echo "Creating distribution package..."
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-modules.tgz ${HE_SHOP_MODULES_FILES}
	@mv ${DISTDIR}/../lc-he-modules.tgz ${INSTALLER_DIR}
	@cd ${DISTDIR}; \
	rm -rf ${HE_SHOP_MODULES_FILES}
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-shop.tgz ${HE_SHOP_FILES}
	@mv ${DISTDIR}/../lc-he-shop.tgz ${INSTALLER_DIR}
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-ioncube.tgz ${HE_SHOPION_FILES}
	@mv ${DISTDIR}/../lc-he-ioncube.tgz ${INSTALLER_DIR}
	@echo ""
	@echo ""
	@echo "   OOO     OK   OK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "OK     OK  OKOK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "   OOO     OK   OK"; 
	@echo ""
	@echo ""
	@make clean-dirs
# }}}	

lc-he-shop-pre-dist: clean build # {{{
	@echo "Updating LC project structure..."
	@cvs up -P 2>&- 1>&-
	@echo "Preparing LiteCommerce package..."
	@if [ ! -d ${DISTDIR} ]; then \
        echo "Creating dir ${DISTDIR}..."; \
        mkdir ${DISTDIR}; \
    fi
	@echo "Copying distributive source files..."
	@cp -R ${SOURCES} ${DISTDIR}
	@echo "Removing additional config file..."
	@if [ -f ${DISTDIR}/etc/config.local.php ]; then \
        rm -f ${DISTDIR}/etc/config.local.php; \
    fi    
	@echo "Removing Makefile(s)...";
	@-rm -f ${DISTDIR}/sql/Makefile
	@echo "Copying skin sets..."
	@cp -RP ${SKINS} ${DISTDIR}/${SKINS}_original
	@echo "Copying Quick Start Wizard..."
	@cp -RP ${QUICKSTART} ${DISTDIR}/${QUICKSTART}
	@echo "Removing old library package(s)..."
	@-rm -f ${DISTDIR}/sql/xlite_demo_store.sql
	@-rm -rf ${DISTDIR}/lib/HTML
	@-rm -rf ${DISTDIR}/lib5/HTML
	@echo "Copying ionCube PHP loader..."
	@cp ${LOADER} ${DISTDIR}
	@echo "Copying ioncube loaders..."
	@-mkdir ${DISTDIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DISTDIR}/ioncube
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIR} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Creating dir ${DISTDIRHE}.."
	@-mkdir ${DISTDIRHE}
	@echo "Updating LC HE project structure..."
	@cd ${DISTDIRHE}; \
	cvs checkout lc_hsp 2>&- 1>&-
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIRHE}/${HE_SHOP_PATCH} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Removing unnecessary modules from distribution..."
	@for module in ${MODULES_NOT_IN_HE}; do \
		if [ -d ${DISTDIR}/classes/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/classes/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/admin/en/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/admin/en/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/admin/en/modules/images/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/admin/en/modules/images/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/default/en/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/default/en/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/default/en/modules/images/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/default/en/modules/images/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/mail/en/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/mail/en/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/mail/en/modules/images/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/mail/en/modules/images/$${module}; \
		fi; \
	done
	@echo "Copying LC HE project's files..."
	@for file in ${HE_SHOP_PATCH_FILES}; do \
		cp -R ${DISTDIRHE}/${HE_SHOP_PATCH}/$${file} ${DISTDIR}; \
	done
	@sed "s/new RSA/new rsa__/" ${DISTDIR}/license.php > ${DISTDIR}/license.php.rpk
	@echo "Inserting public key..."
	@cat ${DISTDIR}/license.php | sed -e "s/Root_Pub_Key = \"\([^\"]*\)\"/Root_Pub_Key = \"${PUBLIC_KEY}\"/" | sed -e "s/new RSA/new rsa__/" > ${DISTDIR}/license.php.rpk
#	@sed "s/Root_Pub_Key = \"\([^\"]*\)\"/Root_Pub_Key = \"${PUBLIC_KEY}\"/" ${DISTDIR}/license.php > ${DISTDIR}/license.php.rpk
	@echo "Adding license code..."
.for source in ${ENC_SOURCES}
	@cat ${DISTDIR}/${source} | grep -v '^@include "license.php"' > ${DISTDIR}/${source}.php
	@cat ${DISTDIR}/license.php.rpk >> ${DISTDIR}/${source}.php
	@cp ${DISTDIR}/${source}.php ${DISTDIR}/${source}
	@rm -f ${DISTDIR}/${source}.php
.endfor
	@rm -f ${DISTDIR}/license.php
	@rm -f ${DISTDIR}/license.php.rpk
	@echo "Preparing encode decoration chains..."
	@${INSTALLER_DIR}/enc_decorator.php -d ${DISTDIR}
	@cp build_classes.php ${DISTDIR}/build_classes.php
	@find ${DISTDIR} -type f -name "build_classes.php" -execdir ./{} \;
	@rm ${DISTDIR}/build_classes.php
	@echo "Preparing objects' code to encoding..."
	@mv ${DISTDIR}/classes/modules ${DISTDIR}/
	@-find ${DISTDIR}/classes -type f -name "*.php" -exec ${INSTALLER_DIR}/decoration.php -s {} -o {}.php \;
#	@-find ${DISTDIR}/classes -type f -name "*.php" -exec cp {} {}.php \;
	@-find ${DISTDIR}/classes -type f -name "enc_*.php" -and ! -name "enc_*.php.php" | sed -e "s/enc_//" | xargs -I % cp % %.deco
	@-find ${DISTDIR}/classes -type f -name "enc_*.php" -and ! -name "enc_*.php.php" | sed -e "s/enc_//" | xargs -I % rm -f %
	@cp ${DISTDIR}/classes/XLite.php.php ${DISTDIR}/classes/XLite.php
	@sed "s/Logger::singleton/logger__::singleton/" ${DISTDIR}/classes/XLite.php > ${DISTDIR}/classes/XLite.php.php
#	@cp ${DISTDIR}/classes/XLite.php ${DISTDIR}/classes/XLite.php.php
	@cp ${DISTDIR}/classes/kernel/Logger.php.php ${DISTDIR}/classes/kernel/Logger.php
	@sed "s/new Logger/new logger__/" ${DISTDIR}/classes/kernel/Logger.php > ${DISTDIR}/classes/kernel/Logger.php.php
#	@cp ${DISTDIR}/classes/kernel/Logger.php ${DISTDIR}/classes/kernel/Logger.php.php
	@cp ${DISTDIR}/includes/decoration.php ${DISTDIR}/includes/decoration.php.php
	@cp ${DISTDIR}/compat/gmp.php ${DISTDIR}/compat/gmp.php.php
	@echo "Encoding objects' code..."
	@-find ${DISTDIR}/classes -type f -name "*.php" -and ! -name "*.php.php" -exec ${IONCUBE} {}.php {} \;
	@-find ${DISTDIR}/classes -type f -name "*.php.php" -exec rm -f {} \;
	@-find ${DISTDIR}/classes -type f -name "enc_*.php" | sed -e "s/enc_//" | xargs -I % cp %.deco %
	@-find ${DISTDIR}/classes -type f -name "*.php.deco" -delete
	@chmod 0640 ${DISTDIR}/includes/decoration.php.php
	@${IONCUBE} ${DISTDIR}/includes/decoration.php.php ${DISTDIR}/includes/decoration.php
	@rm -f ${DISTDIR}/includes/decoration.php.php
	@chmod 0640 ${DISTDIR}/compat/gmp.php.php
	@${IONCUBE} ${DISTDIR}/compat/gmp.php.php ${DISTDIR}/compat/gmp.php
	@rm -f ${DISTDIR}/compat/gmp.php.php
	@mv ${DISTDIR}/modules ${DISTDIR}/classes
	@echo "Preparing modules' code to encoding..."
	@-find ${DISTDIR}/classes/modules -type f -name "encoded.php" -exec cp {} {}.php \;
	@echo "Encoding modules' code..."
	@-find ${DISTDIR}/classes/modules -type f -name "encoded.php" -and ! -name "encoded.php.php" -exec ${IONCUBE} {}.php {} \;
	@-find ${DISTDIR}/classes/modules -type f -name "*.php.php" -exec rm -f {} \;
# }}}

lc-he-server: lc-he-server-pre-dist # {{{
	@echo ""
	@echo "Creating distribution package..."
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-server.tgz ${HE_SERVER_FILES}
	@mv ${DISTDIR}/../lc-he-server.tgz ${INSTALLER_DIR}
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-server-chk.tgz ${HE_SERVERCHK_FILES}
	@mv ${DISTDIR}/../lc-he-server-chk.tgz ${INSTALLER_DIR}
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-ioncube.tgz ${HE_SERVERION_FILES}
	@mv ${DISTDIR}/../lc-he-ioncube.tgz ${INSTALLER_DIR}
	@echo ""
	@echo ""
	@echo "   OOO     OK   OK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "OK     OK  OKOK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "   OOO     OK   OK"; 
	@echo ""
	@echo ""
	@make clean-dirs
# }}}	

lc-he-server-pre-dist: clean build # {{{
	@echo "Updating LC project structure..."
	@cvs up -P 2>&- 1>&-
	@echo "Preparing LiteCommerce package..."
	@if [ ! -d ${DISTDIR} ]; then \
        echo "Creating dir ${DISTDIR}..."; \
        mkdir ${DISTDIR}; \
    fi
	@echo "Copying distributive source files..."
	@cp -R ${HE_SERVER_SOURCES} ${DISTDIR}
	@echo "Removing additional config file..."
	@if [ -f ${DISTDIR}/etc/config.local.php ]; then \
        rm -f ${DISTDIR}/etc/config.local.php; \
    fi    
	@echo "Removing Makefile(s)...";
	@-rm -f ${DISTDIR}/sql/Makefile
	@echo "Removing old library package(s)..."
	@-rm -f ${DISTDIR}/sql/xlite_demo_store.sql
	@-rm -rf ${DISTDIR}/lib/HTML
	@-rm -rf ${DISTDIR}/lib5/HTML
	@echo "Copying ionCube PHP loader..."
	@cp ${LOADER} ${DISTDIR}
	@echo "Copying ioncube loaders..."
	@mkdir ${DISTDIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DISTDIR}/ioncube
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIR} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Creating dir ${DISTDIRHE}.."
	@mkdir ${DISTDIRHE}
	@echo "Updating LC HE project structure..."
	@cd ${DISTDIRHE}; \
	cvs checkout lc_hsp 2>&- 1>&-
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIRHE}/${HE_SERVER_PATCH} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Copying LC HE project's files..."
	@for file in ${HE_SERVER_PATCH_FILES}; do \
		echo ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ${DISTDIR}/$${file}; \
		if [ -d ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ]; then \
			cp -R ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ${DISTDIR}; \
		else \
			cp ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ${DISTDIR}/$${file}; \
		fi; \
	done
	@echo "Inserting public key..."
	@cat ${DISTDIR}/license.php | sed -e "s/XB_Pub_Key = \"\([^\"]*\)\"/XB_Pub_Key = \"${PUBLIC_KEY}\"/" | sed -e "s/new RSA/new rsa__/" > ${DISTDIR}/license.php.rpk
#	@sed "s/XB_Pub_Key = \"\([^\"]*\)\"/XB_Pub_Key = \"${PUBLIC_KEY}\"/" ${DISTDIR}/license.php > ${DISTDIR}/license.php.rpk
#	@cp ${DISTDIR}/license.php ${DISTDIR}/license.php.rpk
	@echo "Adding license code..."
.for source in ${ENC_SOURCES}
	@cat ${DISTDIR}/${source} | grep -v '^@include "license.php"' > ${DISTDIR}/${source}.php
	@cat ${DISTDIR}/license.php.rpk >> ${DISTDIR}/${source}.php
	@cp ${DISTDIR}/${source}.php ${DISTDIR}/${source}
	@rm -f ${DISTDIR}/${source}.php
.endfor
	@rm -f ${DISTDIR}/license.php
	@rm -f ${DISTDIR}/license.php.rpk
	@cp ${DISTDIR}/includes/prepend2.php ${DISTDIR}/includes/prepend2n.php
	@chmod 0640 ${DISTDIR}/includes/prepend2n.php
	@${IONCUBE} ${DISTDIR}/includes/prepend2n.php ${DISTDIR}/includes/prepend2.php
	@rm ${DISTDIR}/includes/prepend2n.php
	@cp ${DISTDIR}/crypt.php ${DISTDIR}/cryptn.php
	@chmod 0640 ${DISTDIR}/cryptn.php
	@${IONCUBE} ${DISTDIR}/cryptn.php ${DISTDIR}/crypt.php
	@rm ${DISTDIR}/cryptn.php
	@echo "Preparing objects' code to encoding..."
	@cat ${DISTDIR}/classes/modules/lshsp/dialog/gate.php | sed -e "s/class_exists(\"rsa\")/class_exists(\"rsa__\")/" | sed -e "s/new RSA/new rsa__/" > ${DISTDIR}/classes/modules/lshsp/dialog/gate.php.rpk
	@mv ${DISTDIR}/classes/modules/lshsp/dialog/gate.php.rpk ${DISTDIR}/classes/modules/lshsp/dialog/gate.php
	@-find ${DISTDIR}/classes -type f -name "*.php" -exec ${INSTALLER_DIR}/decoration.php -s {} -o {}.php \;
#	@-find ${DISTDIR}/classes -type f -name "*.php" -exec cp {} {}.php \;
	@cp ${DISTDIR}/classes/XLite.php.php ${DISTDIR}/classes/XLite.php
	@sed "s/Logger::singleton/logger__::singleton/" ${DISTDIR}/classes/XLite.php > ${DISTDIR}/classes/XLite.php.php
#	@cp ${DISTDIR}/classes/XLite.php ${DISTDIR}/classes/XLite.php.php
	@cp ${DISTDIR}/classes/kernel/Logger.php.php ${DISTDIR}/classes/kernel/Logger.php
	@sed "s/new Logger/new logger__/" ${DISTDIR}/classes/kernel/Logger.php > ${DISTDIR}/classes/kernel/Logger.php.php
#	@cp ${DISTDIR}/classes/kernel/Logger.php ${DISTDIR}/classes/kernel/Logger.php.php
	@cp ${DISTDIR}/includes/decoration.php ${DISTDIR}/includes/decoration.php.php
	@cp ${DISTDIR}/compat/gmp.php ${DISTDIR}/compat/gmp.php.php
	@echo "Encoding objects' code..."
	@-find ${DISTDIR}/classes -type f -name "*.php" -and ! -name "*.php.php" -exec ${IONCUBE} {}.php {} \;
	@-find ${DISTDIR}/classes -type f -name "*.php.php" -exec rm -f {} \;
	@chmod 0640 ${DISTDIR}/includes/decoration.php.php
	@${IONCUBE} ${DISTDIR}/includes/decoration.php.php ${DISTDIR}/includes/decoration.php
	@rm -f ${DISTDIR}/includes/decoration.php.php
	@chmod 0640 ${DISTDIR}/compat/gmp.php.php
	@${IONCUBE} ${DISTDIR}/compat/gmp.php.php ${DISTDIR}/compat/gmp.php
	@rm -f ${DISTDIR}/compat/gmp.php.php
# }}}

lc-he-trialshop: lc-he-trialshop-pre-dist # {{{
	@echo ""
	@echo "Creating distribution package..."
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-shop-trial.tgz ${HE_SHOP_FILES}
	@mv ${DISTDIR}/../lc-he-shop-trial.tgz ${INSTALLER_DIR}
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-ioncube-trial.tgz ${HE_SHOPION_FILES}
	@mv ${DISTDIR}/../lc-he-ioncube-trial.tgz ${INSTALLER_DIR}
	@echo ""
	@echo ""
	@echo "   OOO     OK   OK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "OK     OK  OKOK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "   OOO     OK   OK"; 
	@echo ""
	@echo ""
	@make clean-dirs
# }}}	

lc-he-trialshop-pre-dist: clean build # {{{
	@echo "Updating LC project structure..."
	@cvs up -P 2>&- 1>&-
	@echo "Preparing LiteCommerce package..."
	@if [ ! -d ${DISTDIR} ]; then \
        echo "Creating dir ${DISTDIR}..."; \
        mkdir ${DISTDIR}; \
    fi
	@echo "Copying distributive source files..."
	@cp -R ${SOURCES} ${DISTDIR}
	@echo "Removing additional config file..."
	@if [ -f ${DISTDIR}/etc/config.local.php ]; then \
        rm -f ${DISTDIR}/etc/config.local.php; \
    fi    
	@echo "Removing Makefile(s)...";
	@-rm -f ${DISTDIR}/sql/Makefile
	@echo "Copying skin sets..."
	@cp -RP ${SKINS} ${DISTDIR}/${SKINS}_original
	@echo "Copying Quick Start Wizard..."
	@cp -RP ${QUICKSTART} ${DISTDIR}/${QUICKSTART}
	@echo "Removing old library package(s)..."
	@-rm -f ${DISTDIR}/sql/xlite_demo_store.sql
	@-rm -rf ${DISTDIR}/lib/HTML
	@-rm -rf ${DISTDIR}/lib5/HTML
	@echo "Copying ionCube PHP loader..."
	@cp ${LOADER} ${DISTDIR}
	@echo "Copying ioncube loaders..."
	@-mkdir ${DISTDIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DISTDIR}/ioncube
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIR} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Creating dir ${DISTDIRHE}.."
	@-mkdir ${DISTDIRHE}
	@echo "Updating LC HE project structure..."
	@cd ${DISTDIRHE}; \
	cvs checkout lc_hsp 2>&- 1>&-
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIRHE}/${HE_SHOP_PATCH} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Removing unnecessary modules from distribution..."
	@for module in ${MODULES_NOT_IN_HE}; do \
		if [ -d ${DISTDIR}/classes/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/classes/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/admin/en/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/admin/en/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/admin/en/modules/images/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/admin/en/modules/images/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/default/en/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/default/en/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/default/en/modules/images/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/default/en/modules/images/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/mail/en/modules/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/mail/en/modules/$${module}; \
		fi; \
		if [ -d ${DISTDIR}/skins/mail/en/modules/images/$${module} ]; then \
			rm -rf ${DISTDIR}/skins/mail/en/modules/images/$${module}; \
		fi; \
	done
	@echo "Copying LC HE project's files..."
	@for file in ${HE_SHOP_PATCH_FILES}; do \
		cp -R ${DISTDIRHE}/${HE_SHOP_PATCH}/$${file} ${DISTDIR}; \
	done
	@sed "s/new RSA/new rsa__/" ${DISTDIR}/license.php > ${DISTDIR}/license.php.rpk
#	@cp ${DISTDIR}/license.php ${DISTDIR}/license.php.rpk
	@echo "Adding license code..."
.for source in ${ENC_SOURCES}
	@cat ${DISTDIR}/${source} | grep -v '^@include "license.php"' > ${DISTDIR}/${source}.php
	@cat ${DISTDIR}/license.php.rpk >> ${DISTDIR}/${source}.php
	@cp ${DISTDIR}/${source}.php ${DISTDIR}/${source}
	@rm -f ${DISTDIR}/${source}.php
.endfor
	@rm -f ${DISTDIR}/license.php
	@rm -f ${DISTDIR}/license.php.rpk
	@echo "Preparing encode decoration chains..."
	@${INSTALLER_DIR}/enc_decorator.php -d ${DISTDIR}
	@cp build_classes.php ${DISTDIR}/build_classes.php
	@find ${DISTDIR} -type f -name "build_classes.php" -execdir ./{} \;
	@rm ${DISTDIR}/build_classes.php
	@echo "Preparing objects' code to encoding..."
	@mv ${DISTDIR}/classes/modules ${DISTDIR}/
	@-find ${DISTDIR}/classes -type f -name "*.php" -exec ${INSTALLER_DIR}/decoration.php -s {} -o {}.php \;
#	@-find ${DISTDIR}/classes -type f -name "*.php" -exec cp {} {}.php \;
	@-find ${DISTDIR}/classes -type f -name "enc_*.php" -and ! -name "enc_*.php.php" | sed -e "s/enc_//" | xargs -I % cp % %.deco
	@-find ${DISTDIR}/classes -type f -name "enc_*.php" -and ! -name "enc_*.php.php" | sed -e "s/enc_//" | xargs -I % rm -f %
	@cp ${DISTDIR}/classes/XLite.php.php ${DISTDIR}/classes/XLite.php
	@sed "s/Logger::singleton/logger__::singleton/" ${DISTDIR}/classes/XLite.php > ${DISTDIR}/classes/XLite.php.php
	@cp ${DISTDIR}/classes/kernel/Logger.php.php ${DISTDIR}/classes/kernel/Logger.php
	@sed "s/new Logger/new logger__/" ${DISTDIR}/classes/kernel/Logger.php > ${DISTDIR}/classes/kernel/Logger.php.php
	@cp ${DISTDIR}/includes/decoration.php ${DISTDIR}/includes/decoration.php.php
	@echo "Encoding objects' code..."
	@-find ${DISTDIR}/classes -type f -name "*.php" -and ! -name "*.php.php" -exec ${IONCUBE} {}.php {} \;
	@-find ${DISTDIR}/classes -type f -name "*.php.php" -exec rm -f {} \;
	@-find ${DISTDIR}/classes -type f -name "enc_*.php" | sed -e "s/enc_//" | xargs -I % cp %.deco %
	@-find ${DISTDIR}/classes -type f -name "*.php.deco" -delete
	@chmod 0640 ${DISTDIR}/includes/decoration.php.php
	@${IONCUBE} ${DISTDIR}/includes/decoration.php.php ${DISTDIR}/includes/decoration.php
	@rm -f ${DISTDIR}/includes/decoration.php.php
	@mv ${DISTDIR}/modules ${DISTDIR}/classes
	@echo "Preparing modules' code to encoding..."
	@-find ${DISTDIR}/classes/modules -type f -name "encoded.php" -exec cp {} {}.php \;
	@echo "Encoding modules' code..."
	@-find ${DISTDIR}/classes/modules -type f -name "encoded.php" -and ! -name "encoded.php.php" -exec ${IONCUBE} {}.php {} \;
	@-find ${DISTDIR}/classes/modules -type f -name "*.php.php" -exec rm -f {} \;
# }}}

lc-he-trialserver: lc-he-trialserver-pre-dist # {{{
	@echo ""
	@echo "Creating distribution package..."
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-server-trial.tgz ${HE_SERVER_FILES}
	@mv ${DISTDIR}/../lc-he-server-trial.tgz ${INSTALLER_DIR}
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-server-chk-trial.tgz ${HE_SERVERCHK_FILES}
	@mv ${DISTDIR}/../lc-he-server-chk-trial.tgz ${INSTALLER_DIR}
	@cd ${DISTDIR}; \
	gnu_tar -zcf ../lc-he-ioncube-trial.tgz ${HE_SERVERION_FILES}
	@mv ${DISTDIR}/../lc-he-ioncube-trial.tgz ${INSTALLER_DIR}
	@echo ""
	@echo ""
	@echo "   OOO     OK   OK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "OK     OK  OKOK"; 
	@echo " OK   OK   OK  OK"; 
	@echo "   OOO     OK   OK"; 
	@echo ""
	@echo ""
	@make clean-dirs
# }}}	

lc-he-trialserver-pre-dist: clean build # {{{
	@echo "Updating LC project structure..."
	@cvs up -P 2>&- 1>&-
	@echo "Preparing LiteCommerce package..."
	@if [ ! -d ${DISTDIR} ]; then \
        echo "Creating dir ${DISTDIR}..."; \
        mkdir ${DISTDIR}; \
    fi
	@echo "Copying distributive source files..."
	@cp -R ${HE_SERVER_SOURCES} ${DISTDIR}
	@echo "Removing additional config file..."
	@if [ -f ${DISTDIR}/etc/config.local.php ]; then \
        rm -f ${DISTDIR}/etc/config.local.php; \
    fi    
	@echo "Removing Makefile(s)...";
	@-rm -f ${DISTDIR}/sql/Makefile
	@echo "Removing old library package(s)..."
	@-rm -f ${DISTDIR}/sql/xlite_demo_store.sql
	@-rm -rf ${DISTDIR}/lib/HTML
	@-rm -rf ${DISTDIR}/lib5/HTML
	@echo "Copying ionCube PHP loader..."
	@cp ${LOADER} ${DISTDIR}
	@echo "Copying ioncube loaders..."
	@mkdir ${DISTDIR}/ioncube
	@cp ${IONCUBE_DIR}/ioncube_loader/[LRi]* ${DISTDIR}/ioncube
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIR} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Creating dir ${DISTDIRHE}.."
	@mkdir ${DISTDIRHE}
	@echo "Updating LC HE project structure..."
	@cd ${DISTDIRHE}; \
	cvs checkout lc_hsp 2>&- 1>&-
	@echo "Cleaning up CVS tags..."
	@-find ${DISTDIRHE}/${HE_SERVER_PATCH} -type d -name CVS -execdir rm -rf {} \; 2>&-
	@echo "Copying LC HE project's files..."
	@for file in ${HE_SERVER_PATCH_FILES}; do \
		echo ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ${DISTDIR}/$${file}; \
		if [ -d ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ]; then \
			cp -R ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ${DISTDIR}; \
		else \
			cp ${DISTDIRHE}/${HE_SERVER_PATCH}/$${file} ${DISTDIR}/$${file}; \
		fi; \
	done
	@${INSTALLER_DIR}/he_trial.php -s ${DISTDIR}/license.php -o ${DISTDIR}/license.php
	@sed "s/new RSA/new rsa__/" ${DISTDIR}/license.php > ${DISTDIR}/license.php.rpk
#	@cp ${DISTDIR}/license.php ${DISTDIR}/license.php.rpk
	@echo "Adding license code..."
.for source in ${ENC_SOURCES}
	@cat ${DISTDIR}/${source} | grep -v '^@include "license.php"' > ${DISTDIR}/${source}.php
	@cat ${DISTDIR}/license.php.rpk >> ${DISTDIR}/${source}.php
	@cp ${DISTDIR}/${source}.php ${DISTDIR}/${source}
	@rm -f ${DISTDIR}/${source}.php
.endfor
	@rm -f ${DISTDIR}/license.php
	@rm -f ${DISTDIR}/license.php.rpk
	@cp ${DISTDIR}/includes/prepend2.php ${DISTDIR}/includes/prepend2n.php
	@chmod 0640 ${DISTDIR}/includes/prepend2n.php
	@${IONCUBE} ${DISTDIR}/includes/prepend2n.php ${DISTDIR}/includes/prepend2.php
	@rm ${DISTDIR}/includes/prepend2n.php
	@cp ${DISTDIR}/crypt.php ${DISTDIR}/cryptn.php
	@chmod 0640 ${DISTDIR}/cryptn.php
	@${IONCUBE} ${DISTDIR}/cryptn.php ${DISTDIR}/crypt.php
	@rm ${DISTDIR}/cryptn.php
	@echo "Preparing objects' code to encoding..."
	@-find ${DISTDIR}/classes -type f -name "*.php" -exec ${INSTALLER_DIR}/decoration.php -s {} -o {}.php \;
	@cp ${DISTDIR}/classes/XLite.php.php ${DISTDIR}/classes/XLite.php
	@sed "s/Logger::singleton/logger__::singleton/" ${DISTDIR}/classes/XLite.php > ${DISTDIR}/classes/XLite.php.php
	@cp ${DISTDIR}/classes/kernel/Logger.php.php ${DISTDIR}/classes/kernel/Logger.php
	@sed "s/new Logger/new logger__/" ${DISTDIR}/classes/kernel/Logger.php > ${DISTDIR}/classes/kernel/Logger.php.php
	@cp ${DISTDIR}/includes/decoration.php ${DISTDIR}/includes/decoration.php.php
	@echo "Encoding objects' code..."
	@-find ${DISTDIR}/classes -type f -name "*.php" -and ! -name "*.php.php" -exec ${IONCUBE} {}.php {} \;
	@-find ${DISTDIR}/classes -type f -name "*.php.php" -exec rm -f {} \;
	@chmod 0640 ${DISTDIR}/includes/decoration.php.php
	@${IONCUBE} ${DISTDIR}/includes/decoration.php.php ${DISTDIR}/includes/decoration.php
	@rm -f ${DISTDIR}/includes/decoration.php.php
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
	@echo Create encoded modules...
	@make modules-dist arg=-enc
# }}}

modules-asp: # {{{
	@echo Creating encoded modules for LiteCommerce ASP Edition...
	@make modules-dist arg=-enc asp=-ASP
# }}}

modules-dist: # {{{
	@echo Create modules ...
	@for module in ${MODULES_LIST}; do \
		if [ $${module} != "CVS" -a $${module} != "non-encoded" ]; then \
		    if [ -f "classes/modules/non-encoded/$${module}" ]; then \
                echo Do not encode module $${module}; \
    			make module module=$${module}; \
            elif [ -n "${asp}" ]; then \
    			make module${arg} module=$${module} asp=-ASP; \
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
.if exists( skins/mail/en/modules/images/${module} )
FILES+= skins/mail/en/modules/images/${module}
.endif
# }}}

.if !empty(FILES)

module-enc: executable clean-trash # {{{
	@echo "Checking PHP syntax ..."
	@-find classes/modules/${module}/ -type f -name "*.php" -exec ${PHP_BIN} -l {} \; | grep "Parse error" > classes/modules/${module}/.phpchk
	@if [ -s classes/modules/${module}/.phpchk ]; then \
		cat classes/modules/${module}/.phpchk; \
        rm -f classes/modules/${module}/.phpchk; \
        false; \
    else \
        rm -f classes/modules/${module}/.phpchk; \
    fi    
	@for module_path in ${FILES}; do \
		find ./$${module_path} -type d -exec chmod 0755 '{}' ';'; find ./$${module_path} -type f -exec chmod 0644 '{}' ';'; \
	done
.if exists( classes/modules/${module}/${module}.php )
.if exists( classes/modules/${module}/encoded.php )
	@echo Create encrypted module ${module}...
	@echo Rebuild classes list for module ${module}...
	@./build_classes.php ${module}
	@echo Backup ${module} encoded.php ...
	@cp classes/modules/${module}/encoded.php classes/modules/
	@if [ -n "${asp}" ]; then \
        echo "Replace license check call in encoded code..."; \
        cat classes/modules/encoded.php | sed -e 's/check_module_license/check_module_license_asp/g' > classes/modules/encoded.asp.php; \
		chmod 0644 classes/modules/encoded.asp.php; \
        ${IONCUBE} classes/modules/encoded.asp.php classes/modules/${module}/encoded.php; \
        rm -f classes/modules/encoded.asp.php; \
    else \
		chmod 0644 classes/modules/encoded.php; \
        ${IONCUBE} classes/modules/encoded.php classes/modules/${module}/encoded.php; \
    fi
	@echo Create module archive...
	@if [ -n "${asp}" ]; then \
        make module module=${module} asp=-ASP; \
    else \
        make module module=${module}; \
    fi
	@echo Restore  ${module} encoded.php ...
	@mv classes/modules/encoded.php classes/modules/${module}/
.else
	@echo Create non-encrypted module ${module}...
	@echo Rebuild classes list for module ${module}...
	@./build_classes.php ${module}
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
	@for module_path in ${FILES}; do \
		find ./$${module_path} -type d -exec chmod 0755 '{}' ';'; find ./$${module_path} -type f -exec chmod 0644 '{}' ';'; \
	done
	@gnu_tar -cf ${module}_add-on-${VERSION}${asp}.tar ${FILES} --exclude=CVS
	@for module_path in ${FILES}; do \
		find ./$${module_path} -type d -exec chmod 0710 '{}' ';'; find ./$${module_path} -type f -exec chmod 0640 '{}' ';'; find ./$${module_path} -type f -name "*.php" -exec chmod 0600 '{}' ';';\
	done
.endif
# }}}

# Creates module package with CVS data
module-cvs: # {{{
	@tar -cf ${module}_add-on-${VERSION}.tar ${FILES}
# }}}
.endif
.endif
