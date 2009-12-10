BUILD=`cut -d . -f 3,3 ../../VERSION`
DISTR_LIST="bsd_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-bsd.tgz dar_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-macos.tgz fre_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-freebsd.tgz win_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-windows.zip lin_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-linux.tgz net_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-netbsd.tgz ope_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-openbsd.tgz sun_litecommerce.tar.gz:litecommerce-2.2-build$BUILD-solaris.tgz"

for distr in ${DISTR_LIST}; do
	distr_src=`echo "$distr" | cut -d: -f1`
	distr_dst=`echo "$distr" | cut -d: -f2`
	if [ -f ${distr_src} ]; then
		echo "$distr_src ==> $distr_dst"
		if [ "x$distr_src" = "xwin_litecommerce.tar.gz" ]; then
			zipdir="win_litecommerce"
			if [ -d $zipdir ]; then
				rm -rf $zipdir
			fi
			mkdir $zipdir
			find $zipdir -type d -exec chmod 0755 '{}' ';'
			find $zipdir -type f -exec chmod 0644 '{}' ';'
			cd $zipdir
				gnu_tar -zxf ../${distr_src}
				zip -9 -r -q ../${distr_dst} .htaccess *
			cd ..
			rm ${distr_src}
			rm -rf $zipdir
		else
			mv ${distr_src} ${distr_dst}
		fi
	fi
done

