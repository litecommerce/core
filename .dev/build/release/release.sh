#!/bin/sh

#
# SVN: $Id$
#
# Release generator script for LiteCommerce
#

#############################################################################

#
# Show usage help
#
show_usage ()
{
	cat <<EOT
Usage: $0 [options]
  -b   LiteCommerce build number (optional, empty by default)
  -c   clear output directory if exists
  -d   output directory (optional, script directory by default)
  -f   config file (<script_dir>/config.sh by default)
  -s   safe mode (output directory is not removed and checkout is skipped)
  -h   this help

Examples:
  $0
  $0 -b 1234
  $0 -f myconfig.sh -b 1234
  $0 -d /u/homes/myhome/tmp/outputdir -c
  $0 -h
EOT
	exit 2
}

#
# Insert SEO phrases to classes/XLite/View/PoweredBy.php 
#
insert_seo_phrases ()
{

	# Check if file for patching exists
	[ ! -f "$2/classes/XLite/View/PoweredBy.php" ] && die "Failed: output file not found: $2/classes/XLite/View/PoweredBy.php"

	# Prepare replacement text
	REPLACEMENT="    protected \$phrases = array(\\"

	index=1
	while true; do
		str=`echo "$1" | sed '/^$/d' | sed -n "${index}p"`
		[ "x$str" = "x" ] && break
		REPLACEMENT="$REPLACEMENT
	    \"$str\",\\"
		index=`expr $index + 1`
	done

	REPLACEMENT=$REPLACEMENT"
    );
	"

	# Prepare sed command
	search_for="protected \$phrases = array();"

	sed_cmd="sed -i '' '/$search_for/ c\\
    $REPLACEMENT
' $2/classes/XLite/View/PoweredBy.php"

	eval "$sed_cmd"
}

#
# Display error message and exit
#
die ()
{
	[ "x$1" != "x" ] && echo $1
	exit 2
}

#############################################################################

PHP='/usr/local/bin/php'
START_TIME=`$PHP -qr 'echo mktime();'`

echo -e "LiteCommerce distributives generator\n"

# Directory names within distribution packages
LITECOMMERCE_DIRNAME="litecommerce"
DRUPAL_DIRNAME="drupal"

# Read options
while getopts "b:cd:f:sh" option; do
	case $option in
		b) XLITE_BUILD_NUMBER=$OPTARG ;;
		c) CLEAR_OUTPUT_DIR=1 ;;
		d) OUTPUT_DIR=$OPTARG ;;
		f) CONFIG=$OPTARG ;;
		s) SAFE_MODE=1 ;;
		h) show_usage $0 ;;
	esac
done

shift $((OPTIND-1));


T=`dirname $0`
BASE_DIR=`realpath $T`

# Check and include the config file
if [ "x${CONFIG}" = "x" ]; then
	CONFIG="${BASE_DIR}/config.sh"
fi

if [ -f $CONFIG ]; then
	. $CONFIG
else
	echo "Failed: Config file not found: ${CONFIG}";
	exit 2
fi

# Check parameters
if [ "x${XLITE_VERSION}" = "x" ]; then
	echo "Failed: LiteCommerce version is not specified";
	exit 2
fi

if [ "x${XLITE_SVN}" = "x" ]; then
	echo "Failed: LiteCommerce SVN repository is not specified";
	exit 2
fi

if [ "x${DRUPAL_SVN}" = "x" ]; then
	echo "Failed: Drupal SVN repository is not specified";
	exit 2
fi

if [ "x${XLITE_MODULES}" = "x" ]; then
	echo "Failed: LiteCommerce modules is not specified";
	exit 2
fi

[ "x${OUTPUT_DIR}" = "x" ] && OUTPUT_DIR="${BASE_DIR}/output"

OUTPUT_DIR=`realpath ${OUTPUT_DIR}`

if [ -d $OUTPUT_DIR -a ! $CLEAR_OUTPUT_DIR ]; then
	if [ ! $SAFE_MODE ]; then
		echo "Failed: Output directory $OUTPUT_DIR already exists, use -c option to clear this directory";
		exit 2
	fi
fi

[ "x${XLITE_BUILD_NUMBER}" = "x" ] && BUILD_SUFFIX='' || BUILD_SUFFIX="-build${XLITE_BUILD_NUMBER}"

VERSION=${XLITE_VERSION}${BUILD_SUFFIX}

# Display input parameters
echo "Input data:"
echo "*** CONFIG: ${CONFIG}"
echo "*** VERSION: ${VERSION}"
echo "*** LC REPOSITORY: $XLITE_SVN"
echo "*** DRUPAL REPOSITORY: $DRUPAL_SVN"
echo "*** OUTPUT_DIR: $OUTPUT_DIR"

[ $SAFE_MODE ] && echo "*** SAFE_MODE enabled"

echo "";
echo "Generating LiteCommerce from SVN reporitory";
echo "";

# Prepare output directory
if [ -d $OUTPUT_DIR -a ! $SAFE_MODE ]; then

	echo "Cleaning the output dir...";

	[ $CLEAR_OUTPUT_DIR ] && rm -rf $OUTPUT_DIR

fi

# Create output directory
[ ! -d $OUTPUT_DIR ] && mkdir -p $OUTPUT_DIR

# Create directory for temporary files
[ ! -d $OUTPUT_DIR/tmp ] && mkdir -p $OUTPUT_DIR/tmp

cd $OUTPUT_DIR

# Checkout projects
if [ ! $SAFE_MODE ]; then

	# Do LiteCommerce checkout...

	echo -n "LiteCommerce checkout...";

	if svn export ${XLITE_SVN} ${LITECOMMERCE_DIRNAME} >>LOG_OUT 2>>LOG_ERR; then

		rm -f LOG_ERR LOG_OUT
		echo " [success]"

	else
		SVN_ERROR="LiteCommerce"
		echo " [failed]"
	    echo "Failed: Unable to checkout LiteCommerce. Logs are below:"
	    echo "** stderr:"
	    cat LOG_ERR
	    echo "** stdout:"
		cat LOG_OUT
		exit 2
	fi

	# Do Drupal checkout...

	echo -n "Drupal checkout..."

	if svn export ${DRUPAL_SVN} ${DRUPAL_DIRNAME} >>LOG_OUT 2>>LOG_ERR; then

		rm -f LOG_ERR LOG_OUT
		echo " [success]"

	else
		SVN_ERROR="Drupal"
		echo " [failed]"
	    echo "Failed: Unable to checkout Drupal. Logs are below:"
	    echo "** stderr:"
	    cat LOG_ERR
	    echo "** stdout:"
		cat LOG_OUT
		exit 2
	fi

fi # / if [ ! $SAFE_MODE ]


# Preparing distributives...

if [ -d "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}" -a -d "${OUTPUT_DIR}/${DRUPAL_DIRNAME}" ]; then

	echo "Preparing the LiteCommerce standalone distributive...";

	#
	# LiteCommerce standalone distributive generating...
	#

	cd "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

	# Remove redundant files
	if [ ! "x${XLITE_FILES_TODELETE}" = "x" ]; then

		for fn in $XLITE_FILES_TODELETE; do
			rm -rf $fn
		done

	fi

	# Generate the list of modules that must be removed
	MODULES_TODELETE=""
	LIST_FOR_SEARCH=`ls classes/XLite/Module | grep -v '\.php'`" "`ls skins/admin/en/modules`" "`ls skins/default/en/modules`" "`ls skins/drupal/en/modules`" "`ls skins/mail/en/modules`
	for i in ${LIST_FOR_SEARCH}; do
		found=0
		for j in ${XLITE_MODULES}; do
			if [ $i = $j -a ! $i = '.' -a ! $i = '..' ]; then
				found=1
				break
			fi
		done
		if [ $found = 0 ]; then
			MODULES_TODELETE=$MODULES_TODELETE" "$i
		fi
	done

	# Remove the redundant modules
	for dn in $MODULES_TODELETE; do
		rm -rf classes/XLite/Module/${dn}
		rm -rf skins/admin/en/modules/${dn}
		rm -rf skins/default/en/modules/${dn}
		rm -rf skins/drupal/en/modules/${dn}
		rm -rf skins/mail/en/modules/${dn}
	done

	rm -f images/*

	mv skins skins_original

	mkdir skins
	cp skins_original/.htaccess skins/.htaccess

	# Modify version of release
	sed -i "" "s/'version','','[^']*'/'version','','${XLITE_VERSION}'/" sql/xlite_data.sql
	sed -i "" "s/define('LC_VERSION', '[^']*'/define('LC_VERSION', '${XLITE_VERSION}'/" includes/install/install_settings.php


	# Save copy of original file PoweredBy.php
	cp ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/classes/XLite/View/PoweredBy.php ${OUTPUT_DIR}/tmp
	cp ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/includes/install/install_settings.php ${OUTPUT_DIR}/tmp

	# Patch file PoweredBy.php
	insert_seo_phrases "$LC_SEO_PHRASES" "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

	sed -i "" "/'DrupalConnector', \/\/ Allows to use Drupal CMS as a storefront/d" ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/includes/install/install_settings.php

	# Prepare permisions
	find . -type d -exec chmod 755 {} \;
	find . -type f -exec chmod 644 {} \;

	cd $OUTPUT_DIR

	tar -czf litecommerce-${VERSION}.tgz ${LITECOMMERCE_DIRNAME}

	echo -e "\n  + LiteCommerce $VERSION distributive is completed"

	#
	# LiteCommerce+Drupal distributive generating...
	#

	cd "${OUTPUT_DIR}/${DRUPAL_DIRNAME}"

	# Remove redundant files
	if [ ! "x${DRUPAL_FILES_TODELETE}" = "x" ]; then

		for fn in $DRUPAL_FILES_TODELETE; do
			rm -rf $fn
		done

	fi

	sed -i '' -E 's/lc_path = .*/lc_path = .\/litecommerce/' modules/lc_connector/lc_connector.info

	# Restore orininal file PoweredBy.php from temporary directory
	cp ${OUTPUT_DIR}/tmp/PoweredBy.php ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/classes/XLite/View/
	cp ${OUTPUT_DIR}/tmp/install_settings.php ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/includes/install/

	# Patch file PoweredBy.php
	insert_seo_phrases "$DRUPAL_SEO_PHRASES" "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

	# Prepare permissions
	find . -type d -exec chmod 755 {} \;
	find . -type f -exec chmod 644 {} \;

	tar -czf ${OUTPUT_DIR}/lc_connector-${VERSION}.tgz modules/lc_connector

	echo "  + LC Connector v.$VERSION module for Drupal is completed"

	tar -czf ${OUTPUT_DIR}/lccms_theme-${VERSION}.tgz themes/lccms

	echo "  + LCCMS v.$VERSION theme for Drupal is completed"

	mv ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME} modules/lc_connector/

	cd modules/lc_connector/${LITECOMMERCE_DIRNAME}

	rm -rf skins_original/default
	mv skins_original/drupal skins_original/default

	cd $OUTPUT_DIR

	tar -czf drupal-lc-${VERSION}.tgz ${DRUPAL_DIRNAME}

	# Remove obsolete directories
	rm -rf ${OUTPUT_DIR}/${DRUPAL_DIRNAME}
	rm -rf ${OUTPUT_DIR}/tmp

	echo "  + Drupal+LiteCommerce v.$VERSION distributive is completed"


else # / if [ -d "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}" -a -d "${OUTPUT_DIR}/${DRUPAL_DIRNAME}" ]

	echo "Failed: LiteCommerce or Drupal repositories have not been checkouted yet"

fi

#
# Calculate and display elapsed time
#
_php_code='$s=mktime()-'$START_TIME'; echo sprintf("%d:%02d:%02d", ($s1=intval($s/3600)), ($s2=intval(($s-$s1*3600)/60)), ($s-$s1*3600-$s2*60));'
_elapsed_time=`eval $PHP" -qr '"$_php_code"'"`

echo -e "\nTime elapsed: ${_elapsed_time}"

ls -al

