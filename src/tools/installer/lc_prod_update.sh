DISTR_LIST="litecommerce-2.2-build35-bsd litecommerce-2.2-build35-macos litecommerce-2.2-build35-freebsd litecommerce-2.2-build35-windows litecommerce-2.2-build35-linux litecommerce-2.2-build35-netbsd litecommerce-2.2-build35-openbsd litecommerce-2.2-build35-solaris"
UPDATE_FILE=update.zip
UPDATE_MD5=make_installmd5_2.php

if [ -f ./${UPDATE_FILE} ]; then
    for distr in ${DISTR_LIST}; do
		if [ -d ${distr} ]; then
			rm -rf ${distr}
		fi
    done
    for distr in ${DISTR_LIST}; do
    	DISTR_FOUND=
		if [ "x$distr" = "xlitecommerce-2.2-build35-windows" ]; then
    		if [ -f ${distr}.zip ]; then
    			DISTR_FOUND=1
			fi
		else
    		if [ -f ${distr}.tgz ]; then
    			DISTR_FOUND=1
			fi
		fi
		if [ "x$DISTR_FOUND" = "x1" ]; then
    		echo "Updating $distr ..."
    		mkdir $distr
    		cd $distr
			if [ "x$distr" = "xlitecommerce-2.2-build35-windows" ]; then
				unzip -q ../${distr}.zip
    		else
				gnu_tar -zxf ../${distr}.tgz
    		fi
    		unzip -o -q ../${UPDATE_FILE}
			find ./ -type d -exec chmod 0755 '{}' ';'
    		find ./ -type f -exec chmod 0644 '{}' ';'
    		cp ../${UPDATE_MD5} ./
    		chmod 0700 ./${UPDATE_MD5}
    		./${UPDATE_MD5}
    		rm ./${UPDATE_MD5}
			if [ "x$distr" = "xlitecommerce-2.2-build35-windows" ]; then
				zip -9 -r -q ../${distr}.zip .htaccess *
    		else
    			gnu_tar -zcvf ../${distr}.tgz .htaccess * > /dev/null
    		fi
    		cd ..
			rm -rf ${distr}
    	fi
    done
else
	echo "Update file cannot be found for processing!"
fi

