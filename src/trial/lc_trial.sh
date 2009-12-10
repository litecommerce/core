DISTR_LIST="bsd_litecommerce.tar.gz:litecommerce-2.2-trial-bsd.tgz dar_litecommerce.tar.gz:litecommerce-2.2-trial-macos.tgz fre_litecommerce.tar.gz:litecommerce-2.2-trial-freebsd.tgz win_litecommerce.tar.gz:litecommerce-2.2-trial-windows.zip lin_litecommerce.tar.gz:litecommerce-2.2-trial-linux.tgz net_litecommerce.tar.gz:litecommerce-2.2-trial-netbsd.tgz ope_litecommerce.tar.gz:litecommerce-2.2-trial-openbsd.tgz sun_litecommerce.tar.gz:litecommerce-2.2-trial-solaris.tgz"

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
				zip -9 -r -q ../${distr_dst} *
			cd ..
			rm ${distr_src}
			rm -rf $zipdir
		else
			mv ${distr_src} ${distr_dst}
		fi
	fi
done

