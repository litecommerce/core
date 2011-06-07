#!/bin/sh

#
# GIT: $Id$
#
# Core upgrade generator
#

#############################################################################

#
# Show usage help
#
show_usage ()
{
	cat <<EOT
Usage: $0 [options]
  -v   LiteCommerce core version (ex. 1.0.0)
  -d   output directory (optional, script directory by default)
  -h   this help

Examples:
  $0
  $0 -v 1.0.0
  $0 -d /u/homes/myhome/tmp/outputdir -v 1.0.0
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
	REPLACEMENT="	protected \$phrases = array(\\"

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

get_current_time ()
{
	_current_time=`$PHP -qr 'echo mktime();'`
	eval "$1=$_current_time"
}

#
# Function calculates the time difference between start time and current time
# Parameters:
# $1 - start time
get_elapsed_time()
{
	if [ x$1 = x ]; then
		echo 'Wrong call of get_elapsed_time()'
		exit 2;
	fi

	_php_code='$s=mktime()-'$1'; echo sprintf("%d:%02d:%02d", ($s1=intval($s/3600)), ($s2=intval(($s-$s1*3600)/60)), ($s-$s1*3600-$s2*60));'

	_elapsed_time=`eval $PHP" -qr '"$_php_code"'"`
}

#
# Download an archived code from the GitHub and deploy it to the specified directory
#
# @param $1 The name of temporary directory where to deploy archive
# @param $2 GIT URL
# @param $3 Version
#
prepare_directory()
{
	get_current_time 'GIT_START_TIME'

	_ERR_MSG='';

	if [ "x$1" = "x" -o "x$2" = "x" ]; then
		_ERR_MSG='Error: Wrong parameters passed to prepare_directory()';

	else

		# Download archive
		git clone $2 $1
		cd $1
        git checkout -b master-dev origin/master-dev
		X=`git branch | grep release-$3`
		if [ "$X" != "" ]; then
			git checkout -b release-$3 origin/release-$3
		fi
		cd ..

	fi

	if [ "x${_ERR_MSG}" = "x" ]; then
		echo '[ok]'
	else
		echo ""
		echo $_ERR_MSG
		exit 2;
	fi

	get_elapsed_time $GIT_START_TIME

	echo "(time elapsed: ${_elapsed_time})";
}

#############################################################################

PHP='/usr/local/bin/php -d date.timezone=Europe/Moscow'

get_current_time 'START_TIME';

echo -e "LiteCommerce distributives generator\n"

T=`dirname $0`
BASE_DIR=`realpath $T`
CURRENT_DIR=`realpath ./`

CONFIG="$BASE_DIR/../release/config.sh";
if [ -f $CONFIG ]; then
    . $CONFIG
else
    echo "Failed: Config file not found: ${CONFIG}";
    exit 2
fi

# LiteCommerce repository URL
XLITE_REPO="git://github.com/litecommerce/core.git"

# Read options
while getopts "v:d:h" option; do
	case $option in
		v) VERSION=$OPTARG ;;
		d) PARAM_OUTPUT_DIR=$OPTARG ;;
		h) show_usage $0 ;;
	esac
done

if [ "x${VERSION}" = "x" ]; then
	echo "Failed: LiteCommerce version is not specified";
	exit 2
fi

shift $((OPTIND-1));

[ ! "x${PARAM_OUTPUT_DIR}" = "x" ] && OUTPUT_DIR=$PARAM_OUTPUT_DIR

[ "x${OUTPUT_DIR}" = "x" ] && OUTPUT_DIR="./output"

OUTPUT_DIR=`realpath ${OUTPUT_DIR}`

# Display input parameters
echo "Input data:"
echo "*** Version: ${VERSION}"
echo "*** Output path: ${OUTPUT_DIR}"

# Prepare output directory
if [ -d $OUTPUT_DIR ]; then

	echo "Cleaning the output dir...";

	rm -rf $OUTPUT_DIR;

fi

# Create output directory
[ ! -d $OUTPUT_DIR ] && mkdir -p $OUTPUT_DIR

# Checkout projects
TMP_XLITE_REPO='_tmp_xlite_repo';
[ -d $TMP_XLITE_REPO ] && rm -rf $TMP_XLITE_REPO

echo -n "Getting LiteCommerce core from GitHub...";
prepare_directory $TMP_XLITE_REPO $XLITE_REPO $VERSION

echo -n "   Removing .git* service files/directories..."
for i in `find $TMP_XLITE_REPO -name ".git*"`; do
	rm -rf $i;
done;
echo " [ok]"

if [ -d ${TMP_XLITE_REPO}/src -a -d ${TMP_XLITE_REPO}/.dev ]; then
	[ -d xlite_dev ] && rm -rf xlite_dev
	mv ${TMP_XLITE_REPO}/src ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}
	mv ${TMP_XLITE_REPO}/.dev xlite_dev
	rm -rf ${TMP_XLITE_REPO}
else
	echo "Wrong LiteCommerce repository structure"
	exit 2
fi

# Preparing distributives...
if [ ! -d "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}" ]; then
	echo "Failed: LiteCommerce or Drupal repositories have not been checkouted yet"
	exit 2;
fi

echo "Preparing the distributives...";

cd "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

# Remove redundant files
for fn in $XLITE_FILES_TESTMODE; do
	rm -rf $fn;
done

for fn in `find images/* -type d -depth 0`; do
    rm -rf $fn;
done

rm -rf sql/xlite_demo.yaml

# Remove modules
for fn in `find classes/XLite/Module/* -type d -depth 0`; do
	rm -rf $fn;
done

for fn in `find skins/*/*/modules/* -type d -depth 0`; do
	rm -rf $fn;
done

rm -rf quickstart

cp -r skins skins_original

# Modify version of release
sed -i "" "s/Version, value: xlite_3_0_x/Version, value: '${VERSION}'/" sql/xlite_data.yaml
sed -i "" "s/define('LC_VERSION', '[^']*'/define('LC_VERSION', '${VERSION}'/" Includes/install/install_settings.php
# sed -i "" "s/'1.0'/'1.2'/" classes/XLite.php
# sed -i "" "s/'0'/'3'/" classes/XLite.php

# Patch file PoweredBy.php
insert_seo_phrases "$LC_SEO_PHRASES" "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

sed -i "" "/'DrupalConnector', \/\/ Allows to use Drupal CMS as a storefront/d" ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/Includes/install/install_settings.php

$PHP ${BASE_DIR}/../devcode_postprocess.php silentMode=1

# Add metadata
mkdir -p .phar
$PHP ${BASE_DIR}/metadata.core.php -v ${VERSION} > .phar/.metadata.bin
if [ $? -gt 0 ]; then
	echo 'Metadata is not assembled'
	exit $?
fi
chmod 400 .phar/.metadata.bin

# Create upgrade dir
if [ -d ${CURRENT_DIR}/upgrades/core/$VERSION ]; then
	mkdir -p .core-upgrades/$VERSION
	cp ${CURRENT_DIR}/upgrades/core/$VERSION/* .core-upgrades/$VERSION/
else
	echo "WARNING! Upgrades scrips is not exists!"
fi

# Prepare permisions
find . -type d -exec chmod 755 {} \;
find . -type f -exec chmod 644 {} \;

cd $OUTPUT_DIR/${LITECOMMERCE_DIRNAME}

# Pack upgrade
tar -czf $OUTPUT_DIR/lc-core-${VERSION}.tar.gz * .phar

cd $OUTPUT_DIR
rm -rf $OUTPUT_DIR/${LITECOMMERCE_DIRNAME}

echo -e "\n  + LiteCommerce $VERSION upgrade pack is completed"

#
# Calculate and display elapsed time
#
get_elapsed_time $START_TIME

echo -e "\nTime elapsed: ${_elapsed_time}\n"

