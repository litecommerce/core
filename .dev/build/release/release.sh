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
  -t   generate builds for testing (with additional data)
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

PHP='/usr/local/bin/php -d date.timezone=Europe/Moscow'
START_TIME=`$PHP -qr 'echo mktime();'`

echo -e "LiteCommerce distributives generator\n"

# Read options
while getopts "b:cd:f:sth" option; do
	case $option in
		b) XLITE_BUILD_NUMBER=$OPTARG ;;
		c) CLEAR_OUTPUT_DIR=1 ;;
		d) PARAM_OUTPUT_DIR=$OPTARG ;;
		f) CONFIG=$OPTARG ;;
		s) SAFE_MODE=1 ;;
		t) TEST_MODE=1 ;;
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

[ ! "x${PARAM_OUTPUT_DIR}" = "x" ] && OUTPUT_DIR=$PARAM_OUTPUT_DIR

[ "x${OUTPUT_DIR}" = "x" ] && OUTPUT_DIR="${BASE_DIR}/output"

OUTPUT_DIR=`realpath ${OUTPUT_DIR}`

if [ -d $OUTPUT_DIR -a ! $CLEAR_OUTPUT_DIR ]; then
	if [ ! $SAFE_MODE ]; then
		echo "Failed: Output directory $OUTPUT_DIR already exists, use -c option to clear this directory";
		exit 2
	fi
fi

[ "x${XLITE_BUILD_NUMBER}" = "x" ] && BUILD_SUFFIX='' || BUILD_SUFFIX="-build${XLITE_BUILD_NUMBER}"

[ "x${DEMO_VERSION}" != "x" ] && BUILD_SUFFIX="${BUILD_SUFFIX}-demo"

[ "x${TEST_MODE}" != "x" ] && BUILD_SUFFIX="${BUILD_SUFFIX}-test"

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
echo "Generating LiteCommerce from SVN repository";
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

	# Do LiteCommerce .dev checkout...

	echo -n "LiteCommerce .dev checkout...";

	if svn export ${XLITE_DEV_SVN} xlite_dev >>LOG_OUT 2>>LOG_ERR; then

		rm -f LOG_ERR LOG_OUT
		echo " [success]"

	else
		SVN_ERROR="LiteCommerce .dev"
		echo " [failed]"
	    echo "Failed: Unable to checkout LiteCommerce .dev directory. Logs are below:"
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

	# Do Drupal .dev checkout...

	echo -n "Drupal .dev checkout..."

	if svn export ${DRUPAL_DEV_SVN} drupal_dev >>LOG_OUT 2>>LOG_ERR; then

		rm -f LOG_ERR LOG_OUT
		echo " [success]"

	else
		SVN_ERROR="Drupal"
		echo " [failed]"
	    echo "Failed: Unable to checkout Drupal .dev directory. Logs are below:"
	    echo "** stderr:"
	    cat LOG_ERR
	    echo "** stdout:"
		cat LOG_OUT
		exit 2
	fi

fi # / if [ ! $SAFE_MODE ]


# Preparing distributives...

if [ -d "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}" -a -d "${OUTPUT_DIR}/${DRUPAL_DIRNAME}" ]; then

	echo "Preparing the distributives...";

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

	# Remove redundant files
	if [ "x${TEST_MODE}" = "x" -a ! "x${XLITE_FILES_TESTMODE}" = "x" ]; then

		for fn in $XLITE_FILES_TESTMODE; do
			rm -rf $fn
		done

	fi

	modules_list_regexp=""
	for j in ${XLITE_MODULES}; do
		modules_list_regexp=$modules_list_regexp"|"$j
	done

	modules_list_regexp=`echo $modules_list_regexp | sed 's/^|//'`

	MODULE_DIRS="
		classes/XLite/Module
		skins/admin/en/modules
		skins/admin/en/images/modules
		skins/default/en/modules
		skins/default/en/images/modules
		skins/drupal/en/modules
		skins/drupal/en/images/modules
		skins/mail/en/modules
		"

	for i in ${MODULE_DIRS}; do

		find -E $i -depth 2 -type d ! -regex ".*/($modules_list_regexp)" -exec echo {} >> ${OUTPUT_DIR}/modules2remove \;

		find $i -depth 1 -type d -empty -exec echo {} >> ${OUTPUT_DIR}/modules2remove \;
	
	done

	for i in `cat ${OUTPUT_DIR}/modules2remove`; do
		rm -rf $i
	done

	if [ "x${DEMO_VERSION}" = "x" -a "x${TEST_MODE}" = "x" ]; then
		find ./images/* -type f -name "demo_store_*" -exec rm -rf {} \;
		for i in $CATEGORY_IMAGES_LIST; do
			rm -f ./public/$i
		done
	fi

	rm -rf quickstart

	mv skins skins_original

	mkdir skins
	cp skins_original/.htaccess skins/.htaccess

#	LOGO_IMAGE=${OUTPUT_DIR}/xlite_dev/build/release/files/images/lc_logo-${XLITE_VERSION}.png

#	if [ -f $LOGO_IMAGE ]; then
#		cp $LOGO_IMAGE ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/skins_original/default/en/images/logo.png
#		cp $LOGO_IMAGE ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/skins_original/drupal/en/images/logo.png

#	else
#		echo "Warning! Logo image file $LOGO_IMAGE not found"
#	fi

	# Modify version of release
	sed -i "" "s/Version, value: xlite_3_0_x/Version, value: '${XLITE_VERSION}'/" sql/xlite_data.yaml
	sed -i "" "s/define('LC_VERSION', '[^']*'/define('LC_VERSION', '${XLITE_VERSION}'/" Includes/install/install_settings.php


	# Save copy of original file PoweredBy.php
	cp ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/classes/XLite/View/PoweredBy.php ${OUTPUT_DIR}/tmp
	cp ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/Includes/install/install_settings.php ${OUTPUT_DIR}/tmp

	# Patch file PoweredBy.php
	insert_seo_phrases "$LC_SEO_PHRASES" "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

	sed -i "" "/'DrupalConnector', \/\/ Allows to use Drupal CMS as a storefront/d" ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/Includes/install/install_settings.php

	$PHP ${BASE_DIR}/../devcode_postprocess.php silentMode=1

	# Prepare permisions
	find . -type d -exec chmod 755 {} \;
	find . -type f -exec chmod 644 {} \;

	cd $OUTPUT_DIR

	# Do not create LC Standalone distributive when generate demo version
	if [ "x${DEMO_VERSION}" = "x" ]; then

		tar -czf litecommerce-${VERSION}.tgz ${LITECOMMERCE_DIRNAME}

		echo -e "\n  + LiteCommerce $VERSION distributive is completed"

	fi

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

	LOGO_IMAGE=${OUTPUT_DIR}/drupal_dev/images/lc_logo-${XLITE_VERSION}.png

	if [ -f $LOGO_IMAGE ]; then
		[ -d ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/profiles/litecommerce ] && cp $LOGO_IMAGE ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/profiles/litecommerce/lc_logo.png
		# Copying logo with version number to the theme is temporary disabled
		# cp $LOGO_IMAGE ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/sites/all/themes/lc3/logo.png
	else
		echo "Warning! Logo image file $LOGO_IMAGE not found"
	fi

	sed -i '' -E 's/lc_dir_default = .*/lc_dir_default = .\/modules\/lc_connector\/litecommerce/' modules/lc_connector/lc_connector.info

	# Restore original file PoweredBy.php from temporary directory
	cp ${OUTPUT_DIR}/tmp/PoweredBy.php ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/classes/XLite/View/
	cp ${OUTPUT_DIR}/tmp/install_settings.php ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/Includes/install/

	# Patch file PoweredBy.php
	insert_seo_phrases "$DRUPAL_SEO_PHRASES" "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

	# Prepare permissions
	find . -type d -exec chmod 755 {} \;
	find . -type f -exec chmod 644 {} \;

	# Do not create some distributives when generate demo version
	if [ "x${DEMO_VERSION}" = "x" ]; then

		# Pack LC Connector module distributive
		cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/modules
		tar -czf ${OUTPUT_DIR}/lc_connector-${VERSION}.tgz lc_connector

		echo "  + LC Connector v.$VERSION module for Drupal is completed"

		# Pack Bettercrumbs module distributive
		#cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/sites/all/modules
		#tar -czf ${OUTPUT_DIR}/bettercrumbs-${VERSION}.tgz bettercrumbs

		#echo "  + Bettercrumbs v.$VERSION module for Drupal is completed"

		# Pack LCCMS theme
		cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/sites/all/themes
		tar -czf ${OUTPUT_DIR}/lc3_clean_theme-${VERSION}.tgz lc3_clean

		echo "  + LC3 v.$VERSION theme for Drupal is completed"

	else

		# Create directory for service files and scripts for demo version
		mkdir -p ${OUTPUT_DIR}/demo_tools/drupal_sql

		# Copy Drupal SQL-files
		cp ${OUTPUT_DIR}/drupal_dev/sql/* ${OUTPUT_DIR}/demo_tools/drupal_sql/

		cp ${OUTPUT_DIR}/xlite_dev/deploy/*.sh ${OUTPUT_DIR}/demo_tools/
		cp ${OUTPUT_DIR}/xlite_dev/deploy/*.php ${OUTPUT_DIR}/demo_tools/

		cd ${OUTPUT_DIR}
		tar -czf demo_tools.tgz demo_tools
		rm -rf demo_tools

		# Patch Drupal code for proxy support (see M:92464 for the details)
		cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}
		RES=`patch < ${OUTPUT_DIR}/xlite_dev/build/release/files/proxy.drupal.6.16.patch 2>&1 | grep -E "(patch: **** malformed patch at line)|(Hunk #([0-9]+) .*(malformed patch)|(with fuzz)|(failed))"`
		[ "x${RES}" != "x" ] && die "[ERROR] Patch applying failed: ${OUTPUT_DIR}/xlite_dev/build/release/files/proxy.drupal.6.16.patch"
		

	fi
	
	# Return to the Drupal root directory
	cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}

	# Move LiteCommerce into LC Connector module directory
	mv ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME} modules/lc_connector/

	cd modules/lc_connector/${LITECOMMERCE_DIRNAME}

	# Replace default skin with drupal skin
	rm -rf skins_original/default
	mv skins_original/drupal skins_original/default

	cd $OUTPUT_DIR

	# Pack Drupal+LC distributive
	tar -czf drupal-lc-${VERSION}.tgz ${DRUPAL_DIRNAME}

	# Remove obsolete directories
	rm -rf ${OUTPUT_DIR}/${DRUPAL_DIRNAME}
	rm -rf ${OUTPUT_DIR}/tmp
	rm -rf ${OUTPUT_DIR}/drupal_dev
	rm -rf ${OUTPUT_DIR}/xlite_dev
	rm -rf ${OUTPUT_DIR}/modules2remove

	echo -e "  + Drupal+LiteCommerce v.$VERSION distributive is completed\n"

	ls -al

else # / if [ -d "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}" -a -d "${OUTPUT_DIR}/${DRUPAL_DIRNAME}" ]

	echo "Failed: LiteCommerce or Drupal repositories have not been checkouted yet"

fi

#
# Calculate and display elapsed time
#
_php_code='$s=mktime()-'$START_TIME'; echo sprintf("%d:%02d:%02d", ($s1=intval($s/3600)), ($s2=intval(($s-$s1*3600)/60)), ($s-$s1*3600-$s2*60));'
_elapsed_time=`eval $PHP" -qr '"$_php_code"'"`

echo -e "\nTime elapsed: ${_elapsed_time}\n"

