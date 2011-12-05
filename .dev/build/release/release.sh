#!/bin/sh

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
  -l   generate distributives from local repository instead of remote
  -s   safe mode (output directory is not removed and checkout is skipped)
  -t   generate builds for testing (with additional data)
  -u   generate only LiteCommerce core package for upgrade (requires CORE_VERSION)
  -h   this help

Examples:
  $0
  $0 -b 1234
  $0 -f myconfig.sh -b 1234
  $0 -d /u/homes/myhome/tmp/outputdir -c
  $0 -u
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

	sed_cmd="$SED_EXT '/$search_for/ c\\
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
# @param $2 URL of an archive to download (remote repo mode) or directory path (local repo mode)
# @param $3 Flag: passed for Drupal deploying only
#
prepare_directory()
{
	get_current_time 'GIT_START_TIME'

	_ERR_MSG='';

	if [ "x$1" = "x" -o "x$2" = "x" ]; then
		_ERR_MSG='Error: Wrong parameters passed to prepare_directory()';

	else

		if [ "x${LOCAL_REPO}" = "x" ]; then
			# Download archive
			curl -L $2 -o $1.tgz >>LOG 2>>LOG
			#cp ${BASE_DIR}/$1.tgz .

			if [ "$3" ]; then
				# If it is a Drupal then need to download also archives for lc_connector and lc3_clean
				curl -L $LC_CONNECTOR_REPO -o $1_lc_connector.tgz >>LOG 2>>LOG
				curl -L $LC3_CLEAN_REPO -o $1_lc3_clean.tgz >>LOG 2>>LOG
			fi

		else

			if [ -d $2 ]; then
				_curdir=`pwd`
				cd $2
				git archive --format=tar --prefix=git-prj/ HEAD | gzip > ${OUTPUT_DIR}/$1.tgz
				
				if [ "$3" ]; then
					# If it is a Drupal then need to get also archives for lc_connector and lc3_clean
					cd $2/modules/lc_connector
					git archive --format=tar --prefix=git-prj/ HEAD | gzip > ${OUTPUT_DIR}/$1_lc_connector.tgz

					cd $2/sites/all/themes/lc3_clean
					git archive --format=tar --prefix=git-prj/ HEAD | gzip > ${OUTPUT_DIR}/$1_lc3_clean.tgz
				fi

				cd $_curdir
			else
				die "Local repo directory not found ($2)"
			fi

		fi

		# Check the downloaded archive
		tar -tzf $1.tgz >>TAR_LOG 2>>TAR_LOG
		_ERR=`grep "^tar: Error " TAR_LOG`

	    if [ "x${_ERR}" = "x" -a "$3" ]; then
			# Check the downloaded archive
			tar -tzf $1_lc_connector.tgz >>TAR_LOG 2>>TAR_LOG
			_ERR=`grep "^tar: Error " TAR_LOG`

			if [ "x${_ERR}" = "x" ]; then
				tar -tzf $1_lc3_clean.tgz >>TAR_LOG 2>>TAR_LOG
				_ERR=`grep "^tar: Error " TAR_LOG`
			fi
		fi

		if [ "x${_ERR}" = "x" ]; then

			rm -f TAR_LOG LOG

			if [ "$3" ]; then
				# Upack Drupal components and move it to the specified temporary directory ($1)
				mkdir _xxx
				cd _xxx
				tar -xzf ../$1.tgz
				_tmp_dir=`ls`
				mv $_tmp_dir ../$1
				tar -xzf ../$1_lc_connector.tgz
				_tmp_dir=`ls`
				rm -rf ../$1/modules/lc_connector
				mv $_tmp_dir ../$1/modules/lc_connector
				tar -xzf ../$1_lc3_clean.tgz
				_tmp_dir=`ls`
				rm -rf ../$1/sites/all/themes/lc3_clean
				mv $_tmp_dir ../$1/sites/all/themes/lc3_clean
				cd ..
				rm -rf _xxx
				rm -f $1.tgz $1_lc_connector.tgz $1_lc3_clean.tgz

			else
				# Unpack archive and move it to the specified temporary directory ($1)
				mkdir _xxx
				cd _xxx
				tar -xzf ../$1.tgz
				_tmp_dir=`ls`
				mv $_tmp_dir ../$1
				cd ..
				rm -rf _xxx
				rm -f $1.tgz
			fi

		else
			_ERR_MSG='Error: Archive is corrupted';
		fi
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

PHP='/usr/bin/env php -d date.timezone=Europe/Moscow'

get_current_time 'START_TIME';

echo -e "LiteCommerce distributives generator\n"

# Read options
while getopts "b:cd:f:lstuh" option; do
	case $option in
		b) XLITE_BUILD_NUMBER=$OPTARG ;;
		c) CLEAR_OUTPUT_DIR=1 ;;
		d) PARAM_OUTPUT_DIR=$OPTARG ;;
		f) CONFIG=$OPTARG ;;
		l) LOCAL_REPO=1;;
		s) SAFE_MODE=1 ;;
		t) TEST_MODE=1 ;;
		u) GENERATE_CORE=1 ;;
		h) show_usage $0 ;;
	esac
done

shift $((OPTIND-1));


T=`dirname $0`
BASE_DIR=`realpath $T`


if [ "`uname`" = "Linux" ]; then
	SED_EXT="sed -i";
	SED_REGEX_LINUX='-regextype posix-extended';
else
	SED_EXT="sed -i ''";
	SED_REGEX_FBSD='-E';
fi


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

# Include local config file
if [ -f ${BASE_DIR}/config.local.sh ]; then
	. ${BASE_DIR}/config.local.sh
fi

# Check parameters
if [ "x${XLITE_VERSION}" = "x" ]; then
	echo "Failed: LiteCommerce version is not specified";
	exit 2
fi

# Check parameters
if [ "${GENERATE_CORE}" -a "x${CORE_VERSION}" = "x" ]; then
	echo "Failed: LiteCommerce core version is not specified";
	exit 2
fi


if [ "x${LOCAL_REPO}" = "x" ]; then

	if [ "x${XLITE_REPO}" = "x" ]; then
		echo "Failed: LiteCommerce repository is not specified";
		exit 2
	fi

	if [ ! "${GENERATE_CORE}" -a "x${DRUPAL_REPO}" = "x" ]; then
		echo "Failed: Drupal repository is not specified";
		exit 2
	fi

	if [ "x${LC_CONNECTOR_REPO}" = "x" ]; then
		echo "Failed: LC Connector repository is not specified";
		exit 2
	fi

	if [ "x${LC3_CLEAN_REPO}" = "x" ]; then
		echo "Failed: LC3 Clean repository is not specified";
		exit 2
	fi

else

	if [ ! "${GENERATE_CORE}" -a "x${DRUPAL_LOCAL_REPO}" = "x" ]; then
		echo "Failed: Drupal local repository is not specified";
		exit 2
	fi

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

[ "x${XLITE_BUILD_NUMBER}" = "x" ] && BUILD_SUFFIX='' || BUILD_SUFFIX="-${XLITE_BUILD_NUMBER}"

if [ "x${DEMO_VERSION}" != "x" ]; then
	
	if [ "x${DEMO_FILES}" = "x" ]; then
		echo "Failed: Directory with additional files for demo version is not specified (DEMO_FILES)";
		exit 2
	fi

	BUILD_SUFFIX="${BUILD_SUFFIX}-demo"
fi

[ "x${TEST_MODE}" != "x" ] && BUILD_SUFFIX="${BUILD_SUFFIX}-test"

VERSION=${XLITE_VERSION}${BUILD_SUFFIX}

[ ! "$LC_CONNECTOR_VERSION" ] && LC_CONNECTOR_VERSION=$VERSION
[ ! "$LC3_CLEAN_VERSION" ] && LC3_CLEAN_VERSION=$VERSION

# Display input parameters
echo "Input data:"
echo "*** CONFIG: ${CONFIG}"
echo "*** VERSION: ${VERSION}"

if [ "${GENERATE_CORE}" ]; then
	echo "*** CORE VERSION: ${CORE_VERSION}"
fi

if [ "x${LOCAL_REPO}" = "x" ]; then
	echo "*** MODE: REMOTE REPO"
	echo "*** LC REPOSITORY: $XLITE_REPO"

	if [ ! "${GENERATE_CORE}" ]; then
		echo "*** DRUPAL REPOSITORY: $DRUPAL_REPO"
		echo "*** LC_CONNECTOR REPOSITORY: $LC_CONNECTOR_REPO"
		echo "*** LC3_CLEAN REPOSITORY: $LC3_CLEAN_REPO"
	fi

else
	echo "*** MODE: LOCAL REPO"
	[ ! "${GENERATE_CORE}" ] && echo "*** DRUPAL LOCAL REPOSITORY: $DRUPAL_LOCAL_REPO"
fi

echo "*** OUTPUT_DIR: $OUTPUT_DIR"

[ $SAFE_MODE ] && echo "*** SAFE_MODE enabled"

echo "";


# Prepare output directory
if [ -d "$OUTPUT_DIR" -a ! "$SAFE_MODE" ]; then

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

	TMP_XLITE_REPO='_tmp_xlite_repo';

	if [ "x${LOCAL_REPO}" = "x" ]; then
		echo -n "Getting LiteCommerce core from GitHub...";
		prepare_directory $TMP_XLITE_REPO $XLITE_REPO
	else
		echo -n "Getting LiteCommerce core from local git repository...";
		prepare_directory $TMP_XLITE_REPO `realpath ${BASE_DIR}/../../../`
	fi


	echo -n "   Removing .git* service files/directories..."

	cd $TMP_XLITE_REPO
	find . -name ".git*" -exec rm -rf {} \;
	cd ..
	echo " [ok]"

	if [ -d ${TMP_XLITE_REPO}/src -a -d ${TMP_XLITE_REPO}/.dev ]; then
		mv ${TMP_XLITE_REPO}/src ${LITECOMMERCE_DIRNAME}
		mv ${TMP_XLITE_REPO}/.dev xlite_dev
		rm -rf ${TMP_XLITE_REPO}
	else
		echo "Wrong LiteCommerce repository structure"
		exit 2
	fi


	if [ ! "${GENERATE_CORE}" ]; then

		# Do Drupal checkout...

		TMP_DRUPAL_REPO='_tmp_drupal_repo';

		if [ "x${LOCAL_REPO}" = "x" ]; then
			echo -n "Getting Drupal from GitHub..."
			prepare_directory $TMP_DRUPAL_REPO $DRUPAL_REPO 1
		else
			echo -n "Getting Drupal from local git repository..."
			prepare_directory $TMP_DRUPAL_REPO $DRUPAL_LOCAL_REPO 1
		fi


		echo -n "   Removing .git* service files/directories..."

		cd $TMP_DRUPAL_REPO
		find . -name ".git*" -exec rm -rf {} \;
		cd ..
		echo " [ok]"

		if [ -d ${TMP_DRUPAL_REPO}/.dev ]; then
			mv ${TMP_DRUPAL_REPO}/.dev drupal_dev
			mv ${TMP_DRUPAL_REPO} ${DRUPAL_DIRNAME}
		else
			echo "Wrong Drupal repository structure"
			exit 2
		fi

	fi # / if [ "${GENERATE_CORE}" ]

fi # / if [ ! $SAFE_MODE ]


# Preparing distributives...

[ "${GENERATE_CORE}" -o -d "${OUTPUT_DIR}/${DRUPAL_DIRNAME}" ] && _is_drupal_dir_exists=1

if [ -d "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}" -a "${_is_drupal_dir_exists}" ]; then

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


	rm -rf quickstart

#	mv skins skins_original

#	mkdir skins
#	cp skins_original/.htaccess skins/.htaccess

#	LOGO_IMAGE=${OUTPUT_DIR}/xlite_dev/build/release/files/images/lc_logo-${XLITE_VERSION}.png

#	if [ -f $LOGO_IMAGE ]; then
#		cp $LOGO_IMAGE ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/skins_original/default/en/images/logo.png
#		cp $LOGO_IMAGE ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/skins_original/drupal/en/images/logo.png

#	else
#		echo "Warning! Logo image file $LOGO_IMAGE not found"
#	fi

	# Modify version of release
	sed_cmd="$SED_EXT \"s/Version, value: xlite_3_0_x/Version, value: '${XLITE_VERSION}'/\" sql/xlite_data.yaml"
	eval "$sed_cmd"
	sed_cmd="$SED_EXT \"s/define('LC_VERSION', '[^']*'/define('LC_VERSION', '${XLITE_VERSION}'/\" Includes/install/install_settings.php"
	eval "$sed_cmd"


	# Save copy of original file PoweredBy.php
	#cp ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/classes/XLite/View/PoweredBy.php ${OUTPUT_DIR}/tmp
	#cp ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/Includes/install/install_settings.php ${OUTPUT_DIR}/tmp

	# Patch file PoweredBy.php
	#insert_seo_phrases "$LC_SEO_PHRASES" "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

	#sed_cmd="$SED_EXT \"/'DrupalConnector', \/\/ Allows to use Drupal CMS as a storefront/d\" ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/Includes/install/install_settings.php"
	#eval "$sed_cmd"


	$PHP ${BASE_DIR}/../devcode_postprocess.php silentMode=1

	# Prepare permisions
	find . -type d -exec chmod 755 {} \;
	find . -type f -exec chmod 644 {} \;


	if [ ! "$TEST_MODE" = "" ]; then
		XLITE_MODULES="
		${XLITE_MODULES}
		${XLITE_SEPARATE_MODULES}
		"
		XLITE_SEPARATE_MODULES=""
	fi

	# Directories where can be located module files
	MODULE_DIRS="
		classes/XLite/Module
		skins/admin/en/modules
		skins/default/en/modules
		skins/mail/en/modules
		skins/common/en/modules
	"

	if [ ! "${GENERATE_CORE}" -a ! "${XLITE_SEPARATE_MODULES}" = "" ]; then

		if [ -f "classes/XLite.php" ]; then
			default_module_major_version=`cat classes/XLite.php| grep -A 2 "function getMajorVersion()" | grep -o -E "[0-9]+\.[0-9]+"`
		else
			die "classes/XLite.php file not found"
		fi

		# Pack separate modules distributives
		for j in ${XLITE_SEPARATE_MODULES}; do

			module_files_list=""
			for k in ${MODULE_DIRS}; do
				[ -d $k/$j ] && module_files_list=$module_files_list" "$k/$j
			done

			if [ "$module_files_list" ]; then

				module_file_name=`echo "$j" | sed 's!/!-!'`

				module_main_file="classes/XLite/Module/${j}/Main.php"

				if [ -f $module_main_file ]; then
					module_major_version=`cat $module_main_file | grep -A 2 "function getMajorVersion()" | grep -o -E "'.+'" | sed "s!'!!g"`

					if [ "$module_major_version" = "" ]; then
						module_major_version=$default_module_major_version
					fi

					module_minor_version=`cat $module_main_file | grep -A 2 "function getMinorVersion()" | grep -o -E "'.+'" | sed "s!'!!g"`

					module_version=`echo "${module_major_version}.${module_minor_version}" | sed "s!\.!_!g"`

					module_actual_name=`echo "$j" | sed 's!/!\\\\!g'`
					module_author=`cat $module_main_file | grep -A 2 "function getAuthorName()" | grep -o -E "'.+'" | sed "s!'!!g"`
					module_name=`cat $module_main_file | grep -A 2 "function getModuleName()" | grep -o -E "'.+'" | sed "s!'!!g"`
					module_icon=`cat $module_main_file | grep -A 2 "function getIconURL()" | grep -o -E "'.+'" | sed "s!'!!g"`
					module_descr=`cat $module_main_file | grep -A 2 "function getDescription()" | grep -o -E "'.+'" | sed "s!'!!g"`

				else
					die "File classes/XLite/Module/${j} not found!"
				fi

				# Generate module meta data
				mkdir -p .phar

				_php_code="echo serialize(array('RevisionDate'=>time(),'ActualName'=>'${module_actual_name}','VersionMajor'=>'${module_major_version}','VersionMinor'=>'${module_minor_version}','Name'=>'${module_name}','Author'=>'${module_author}','IconLink'=>'${module_icon}','Description'=>'${module_descr}','Dependencies'=>array()));"

				$PHP -qr "$_php_code" > .phar/.metadata.bin

				module_hash_data='array('
				for h in $module_files_list; do
					for hd in `find $h -type f`; do
						module_hash_data=$module_hash_data"'${hd}'=>'`md5 -q ${hd}`',"
					done
				done
				module_hash_data=$module_hash_data")"

				_php_code="echo json_encode(${module_hash_data});"

				$PHP -qr "$_php_code" > .hash

				# Backup DrupalConnector module to insert it later into Ecommerce CMS package
				if [ "$j" = "CDev/DrupalConnector" ]; then
					tar -cf ${OUTPUT_DIR}/_drupal-connector-tmp.tar $module_files_list
				fi

				tar -cf ${OUTPUT_DIR}/${module_file_name}-v${module_version}.tar .phar $module_files_list .hash
				rm -rf .phar
				rm .hash

				echo "  + ${module_name} module package is complete: ${module_file_name}-v${module_version}.tar"

			else
				echo "  - ${j} module not found"
			fi

		done

	fi

	# Delete modules from LiteCommerce distributive

	modules_list_regexp=""
	for j in ${XLITE_MODULES}; do
		modules_list_regexp=$modules_list_regexp"|"$j
	done

	modules_list_regexp=`echo $modules_list_regexp | sed 's/^|//'`

	for i in ${MODULE_DIRS}; do

		if [ -d $i ]; then

			if [ ! "${GENERATE_CORE}" ]; then
				# Find modules by pattern
				find $SED_REGEX_FBSD $i $SED_REGEX_LINUX -mindepth 2 -maxdepth 2 -type d ! -regex ".*/($modules_list_regexp)" -exec echo {} >> ${OUTPUT_DIR}/modules2remove \;

			else
				# Find all module dirs
				find $i -mindepth 2 -maxdepth 2 -type d -exec echo {} >> ${OUTPUT_DIR}/modules2remove \;
			fi

			# Find all empty module authors dirs
			find $i -maxdepth 2 -type d -empty -exec echo {} >> ${OUTPUT_DIR}/modules2remove \;

		fi
	
	done

	for i in `cat ${OUTPUT_DIR}/modules2remove`; do
		rm -rf $i
	done

	if [ "x${DEMO_VERSION}" = "x" -a "x${TEST_MODE}" = "x" ]; then

		if [ ! "${GENERATE_CORE}" ]; then

			find ./images/* -type f -name "demo_store_*" -exec rm -rf {} \;
			for i in $CATEGORY_IMAGES_LIST; do
				rm -f ./public/$i
			done
		
		else
			rm -rf images
			find ./public/ -mindepth 1 -maxdepth 1 -type f -not -name "error*" -delete

			# Remove install.php file as it may be renamed after installation
			rm -f install.php
		fi
	fi
	
	if [ "${GENERATE_CORE}" ]; then

		# Add metadata
		mkdir -p .phar
		$PHP ${BASE_DIR}/metadata.core.php -v ${CORE_VERSION} > .phar/.metadata.bin

		if [ $? -gt 0 ]; then
			echo 'Metadata is not assembled'
			exit $?
		fi

		chmod 400 .phar/.metadata.bin

		# Create upgrade dir
		if [ -d ${BASE_DIR}/../upgrades/core/$VERSION ]; then

			mkdir -p .core-upgrades/$VERSION

			cp ${CURRENT_DIR}/upgrades/core/$VERSION/* .core-upgrades/$VERSION/

		else

			echo "WARNING! Upgrades scripts not found!"

		fi

	fi

	cd $OUTPUT_DIR

	if [ ! "${GENERATE_CORE}" ]; then

		# Do not create LC Standalone distributive when generate demo version
		if [ "x${DEMO_VERSION}" = "x" ]; then

			tar -czf litecommerce3-${VERSION}.tgz ${LITECOMMERCE_DIRNAME}
			zip -rq litecommerce3-${VERSION}.zip ${LITECOMMERCE_DIRNAME}

			echo -e "\n  + LiteCommerce $VERSION distributive is completed"

		fi

	else

		# Generate core package
		cd ${LITECOMMERCE_DIRNAME}
		tar -czf ${OUTPUT_DIR}/lc-core-${CORE_VERSION}.tar.gz * .phar
		cd $OUTPUT_DIR

		rm -rf ${LITECOMMERCE_DIRNAME}

		echo -e "\n  + LiteCommerce $CORE_VERSION upgrade pack is completed\n"
	fi

	#
	# LiteCommerce+Drupal distributive generating...
	#

	if [ ! "${GENERATE_CORE}" ]; then

		cd "${OUTPUT_DIR}/${DRUPAL_DIRNAME}"

		# Remove redundant files
		if [ ! "x${DRUPAL_FILES_TODELETE}" = "x" ]; then

			for fn in $DRUPAL_FILES_TODELETE; do
				rm -rf $fn
			done

		fi
	
		# Remove redundant files
		if [ "x${TEST_MODE}" = "x" -a ! "x${DRUPAL_FILES_TESTMODE}" = "x" ]; then

			for fn in $DRUPAL_FILES_TESTMODE; do
				rm -rf $fn
			done

		fi

		LOGO_IMAGE=${OUTPUT_DIR}/drupal_dev/images/lc_logo-${XLITE_VERSION}.png

		if [ -f $LOGO_IMAGE ]; then
			[ -d ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/profiles/litecommerce ] && cp $LOGO_IMAGE ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/profiles/litecommerce/lc_logo.png
			# Copying logo with version number to the theme is temporary disabled
			# cp $LOGO_IMAGE ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/sites/all/themes/lc3/logo.png
		fi

		sed_cmd="$SED_EXT 's/lc_dir_default = .*/lc_dir_default = .\/modules\/lc_connector\/litecommerce/' modules/lc_connector/lc_connector.info"
		eval "$sed_cmd"

		if [ "${TEST_MODE}" ]; then
			mv ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/profiles/litecommerce/litecommerce.profile ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/profiles/litecommerce/litecommerce.profile.php
		fi

		# Restore original file PoweredBy.php from temporary directory
		#cp ${OUTPUT_DIR}/tmp/PoweredBy.php ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/classes/XLite/View/
		#cp ${OUTPUT_DIR}/tmp/install_settings.php ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}/Includes/install/

		# Patch file PoweredBy.php
		#insert_seo_phrases "$DRUPAL_SEO_PHRASES" "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}"

		# Prepare permissions
		find . -type d -exec chmod 755 {} \;
		find . -type f -exec chmod 644 {} \;

		# Do not create some distributives when generate demo version
		if [ "x${DEMO_VERSION}" = "x" ]; then

			# Pack LC Connector module distributive
			cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/modules
			tar -czf ${OUTPUT_DIR}/lc_connector-${LC_CONNECTOR_VERSION}.tgz lc_connector
			zip -rq ${OUTPUT_DIR}/lc_connector-${LC_CONNECTOR_VERSION}.zip lc_connector


			echo "  + LC Connector v.$VERSION module for Drupal is completed"

			# Pack Bettercrumbs module distributive
			#cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/sites/all/modules
			#tar -czf ${OUTPUT_DIR}/bettercrumbs-${VERSION}.tgz bettercrumbs

			#echo "  + Bettercrumbs v.$VERSION module for Drupal is completed"

			# Pack LCCMS theme
			cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}/sites/all/themes
			tar -czf ${OUTPUT_DIR}/lc3_clean_theme-${LC3_CLEAN_VERSION}.tgz lc3_clean
			zip -rq ${OUTPUT_DIR}/lc3_clean_theme-${LC3_CLEAN_VERSION}.zip lc3_clean


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

			cp -R $DEMO_FILES/* ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}

			# Patch Drupal code for proxy support (see M:92464 for the details)
			#cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}
			#RES=`patch < ${OUTPUT_DIR}/xlite_dev/build/release/files/proxy.drupal.6.16.patch 2>&1 | grep -E "(patch: **** malformed patch at line)|(Hunk #([0-9]+) .*(malformed patch)|(with fuzz)|(failed))"`
			#[ "x${RES}" != "x" ] && die "[ERROR] Patch applying failed: ${OUTPUT_DIR}/xlite_dev/build/release/files/proxy.drupal.6.16.patch"
		

		fi
	
		# Return to the Drupal root directory
		cd ${OUTPUT_DIR}/${DRUPAL_DIRNAME}

		# Move LiteCommerce into LC Connector module directory
		mv ${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME} modules/lc_connector/

		cd modules/lc_connector/${LITECOMMERCE_DIRNAME}

		if [ "${TEST_MODE}" = "" -a "${DEMO_VERSION}" = "" ]; then
			# Add DrupalConnector module
			tar -xf ${OUTPUT_DIR}/_drupal-connector-tmp.tar
			rm ${OUTPUT_DIR}/_drupal-connector-tmp.tar
		fi

		cd $OUTPUT_DIR

		# Pack Drupal+LC distributive
		tar -czf drupal-lc3-${VERSION}.tgz ${DRUPAL_DIRNAME}
		[ "${DEMO_VERSION}" = "" ] && zip -rq drupal-lc3-${VERSION}.zip ${DRUPAL_DIRNAME}


		echo -e "  + Drupal+LiteCommerce v.$VERSION distributive is completed\n"

	fi # / if [ ! "${GENERATE_CORE}" ]

	# Remove obsolete directories
	rm -rf ${OUTPUT_DIR}/${DRUPAL_DIRNAME}
	rm -rf ${OUTPUT_DIR}/tmp
	rm -rf ${OUTPUT_DIR}/drupal_dev
	rm -rf ${OUTPUT_DIR}/xlite_dev
	rm -rf ${OUTPUT_DIR}/modules2remove

	echo "Output directory contains (${OUTPUT_DIR}):"
	ls -al

else # / if [ -d "${OUTPUT_DIR}/${LITECOMMERCE_DIRNAME}" -a -d "${OUTPUT_DIR}/${DRUPAL_DIRNAME}" ]

	echo "Failed: LiteCommerce or Drupal repositories have not been checkouted yet"

fi

#
# Calculate and display elapsed time
#
get_elapsed_time $START_TIME

echo -e "\nTime elapsed: ${_elapsed_time}\n"

