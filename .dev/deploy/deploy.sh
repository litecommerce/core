#!/bin/sh

#
# SVN: $Id$
#
# Script for deployment of LiteCommerce CMS site and demo version
#

#############################################################################

#
# Show usage help
#
show_usage ()
{
	cat <<EOT
Usage: $0 [options]
  -f   config file (<script_dir>/config.sh by default)
  -h   this help

Examples:
  $0
  $0 -f myconfig.sh
  $0 -h
EOT
	exit 2
}


#
# Display error message and exit
#
die ()
{
	[ "x${1}" != "x" ] && echo -e $1
	exit 2
}

#############################################################################

echo -e "LiteCommerce CMS deployment...\n"


# Read options
while getopts "f:h" option; do
	case $option in
		f) CONFIG=$OPTARG ;;
		h) show_usage $0 ;;
	esac
done

shift $((OPTIND-1));


# Calculate script directory
T=`dirname $0`
BASE_DIR=`realpath $T`


# Check and include the config file
if [ "x${CONFIG}" = "x" ]; then
	CONFIG="${BASE_DIR}/config.sh"
fi

if [ -f $CONFIG ]; then
	. $CONFIG
else
	die "Failed: Config file not found: ${CONFIG}";
fi


START_TIME=`$PHP -qr '@date_default_timezone_set(@date_default_timezone_get()); echo mktime();'`


[ "x${DEPLOYMENT_DIR}" = "x" ] && die "Deployment directory is not specified"

if [ ! -d $DEPLOYMENT_DIR ]; then
	die "Failed: Directory $DEPLOYMENT_DIR not found";
fi

DEPLOYMENT_DIR=`realpath ${DEPLOYMENT_DIR}`

#
# Generate mysql command line to access site database
#
if [ "x$SITE_DBSOCK" = "x" ]; then
	DBSOCK=""
else
	DBSOCK="-S $SITE_DBSOCK"
fi

if [ "x$SITE_DBPORT" = "x" -o "x$DBSOCK" != "x" ]; then
	DBPORT=""
	DR_DBPORT=""
else
	DBPORT="-S $SITE_DBPORT"
	DR_DBPORT=":$SITE_DBPORT"
fi

MYSQL_SITE_CMD="$MYSQL -h $SITE_DBHOST $DBSOCK $DBPORT -u $SITE_DBUSER --password=$SITE_DBPASS $SITE_DBNAME"

#
# Generate database URL and prefix for replacing in the drupal settings.php
#
DBURL="mysql://${SITE_DBUSER}:${SITE_DBPASS}@${SITE_DBHOST}${DR_DBPORT}/${SITE_DBNAME}"
DBPREFIX="drupal_"

#
# Generate mysql command line to access LiteCommerce admin database
#
if [ "x$LC_DBSOCK" = "x" ]; then
	DBSOCK=""
else
	DBSOCK="-S $LC_DBSOCK"
fi

if [ "x$LC_DBPORT" = "x" -o "x$DBSOCK" != "x" ]; then
	DBPORT=""
else
	DBPORT="-S $LC_DBPORT"
fi

MYSQL_LC_CMD="$MYSQL -h $LC_DBHOST $DBSOCK $DBPORT -u $LC_DBUSER --password=$LC_DBPASS $LC_DBNAME"


# Display input parameters
echo "Input data:"
echo "*** CONFIG: ${CONFIG}"
echo "*** DEPLOYMENT_DIR: $DEPLOYMENT_DIR"
echo "";


cd ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/

echo -n "Copying LiteCommerce files..."

cp -r skins_original/* skins/

echo -e "ok\n"

echo -e "Site database installing...\n"

# Generate modules list automatically from all existing modules
MODULES_DIR=${DEPLOYMENT_DIR}"/modules/lc_connector/litecommerce/classes/XLite/Module"
for i in `ls ${MODULES_DIR}`; do
	[ -f ${MODULES_DIR}/$i/install.sql ] && SITE_MODULES_SQL_FILES=$SITE_MODULES_SQL_FILES" "${MODULES_DIR}/$i/install.sql
done

# List of SQL files for site
SITE_SQL_FILES=${SITE_LC_SQL_BASE_FILES}" "${SITE_MODULES_SQL_FILES}" "${SITE_LC_SQL_DEMO_DATA_FILES}

# Add Drupal SQL files
for i in $SITE_DRUPAL_SQL_FILES; do
	SITE_SQL_FILES=${SITE_SQL_FILES}" "${BASE_DIR}"/drupal_sql/"$i
done

# List of SQL files for LC admin demo
LC_SQL_FILES=${LC_SQL_BASE_FILES}" "${SITE_MODULES_SQL_FILES}" "${LC_SQL_DEMO_DATA_FILES}

# List of SQL files with absolute paths
SQL_FILES=""
SQL_FILES_LC=""

#
# Checks if all SQL files exist
#
for i in $SITE_SQL_FILES; do
	[ -f $i ] || die "File $i not found"
	SQL_FILES=$SQL_FILES" "`realpath $i`
done

for i in $LC_SQL_FILES; do
	[ -f $i ] || die "File $i not found"
	SQL_FILES_LC=$SQL_FILES_LC" "`realpath $i`
done

#
# Installs SQL files to the site database
#
for i in $SQL_FILES; do
	echo -n "   $i ..."
	RESULT=`$MYSQL_SITE_CMD < $i 2>&1`
	[ "x${RESULT}" != "x" ] && die "\nMySQL error: $RESULT"
	echo -e "ok"
done

echo -e "\nLC admin interface database installing...\n"

#
# Install SQL files to the LC admin demo database
#
for i in $SQL_FILES_LC; do
	echo -n "   $i ..."
	RESULT=`$MYSQL_LC_CMD < $i 2>&1`
	[ "x${RESULT}" != "x" ] && die "\nMySQL error: $RESULT"
	echo -e "ok"
done


#
# Update drupal/sites/default/settings.php file
#
echo ""
echo -n "Drupal and LiteCommerce configs updating..."

cd ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc
cp config.php config.demo.php

cd ${DEPLOYMENT_DIR}/sites/default
cp default.settings.php settings.php
mkdir -p files


FIND_1="^\\\$db_url =.*;$"
REPLACE_1="\\\$db_url = \"${DBURL}\";"
FILE_1="${DEPLOYMENT_DIR}/sites/default/settings.php"

FIND_2="^\$db_prefix =.*;$"
REPLACE_2="\$db_prefix = \"${DBPREFIX}\";"
FILE_2="${DEPLOYMENT_DIR}/sites/default/settings.php"

FIND_3="^hostspec = \".*\"$"
REPLACE_3="hostspec = \"${SITE_DBHOST}\""
FILE_3="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_4="^socket = \".*\"$"
REPLACE_4="socket = \"${SITE_DBSOCK}\""
FILE_4="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_5="^port = \".*\"$"
REPLACE_5="port = \"${SITE_DBPORT}\""
FILE_5="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_6="^database = \".*\"$"
REPLACE_6="database = \"${SITE_DBNAME}\""
FILE_6="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_7="^username = \".*\"$"
REPLACE_7="username = \"${SITE_DBUSER}\""
FILE_7="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_8="^password = \".*\"$"
REPLACE_8="password = \"${SITE_DBPASS}\""
FILE_8="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_9="^http_host = \".*\"$"
REPLACE_9="http_host = \"${LC_HTTP_HOST}\""
FILE_9="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_10="^https_host = \".*\"$"
REPLACE_10="https_host = \"${LC_HTTPS_HOST}\""
FILE_10="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_11="^web_dir = \".*\"$"
REPLACE_11="web_dir = \"${LC_WEB_DIR}\""
FILE_11="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

CURRENT_DATE=`date`
LC_AUTH_CODE=`$MD5 -q -s "$CURRENT_DATE"`

FIND_12="^auth_code = \".*\"$"
REPLACE_12="auth_code = \"${LC_AUTH_CODE}\""
FILE_12="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.php"

FIND_13="^hostspec = \".*\"$"
REPLACE_13="hostspec = \"${LC_DBHOST}\""
FILE_13="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_14="^socket = \".*\"$"
REPLACE_14="socket = \"${LC_DBSOCK}\""
FILE_14="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_15="^port = \".*\"$"
REPLACE_15="port = \"${LC_DBPORT}\""
FILE_15="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_16="^database = \".*\"$"
REPLACE_16="database = \"${LC_DBNAME}\""
FILE_16="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_17="^username = \".*\"$"
REPLACE_17="username = \"${LC_DBUSER}\""
FILE_17="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_18="^password = \".*\"$"
REPLACE_18="password = \"${LC_DBPASS}\""
FILE_18="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_19="^http_host = \".*\"$"
REPLACE_19="http_host = \"${LC_HTTP_HOST}\""
FILE_19="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_20="^https_host = \".*\"$"
REPLACE_20="https_host = \"${LC_HTTPS_HOST}\""
FILE_20="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_21="^web_dir = \".*\"$"
REPLACE_21="web_dir = \"${LC_WEB_DIR}\""
FILE_21="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_22="^auth_code = \".*\"$"
REPLACE_22="auth_code = \"${LC_AUTH_CODE}\""
FILE_22="${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/etc/config.demo.php"

FIND_23="^ *# RewriteBase /drupal$"
REPLACE_23="RewriteBase ${SITE_WEB_DIR}"
FILE_23="${DEPLOYMENT_DIR}/.htaccess"


index=1
max=23
while true; do

	[ $index -gt $max ] && break

	eval "FIND=\"\$FIND_$index\""
	eval "REPLACE=\"\$REPLACE_$index\""
	eval "FILE=\"\$FILE_$index\""

	SED_CMD="sed -i '' 's|$FIND|$REPLACE|' $FILE 2>&1"

	RESULT=`eval "$SED_CMD"`
	[ "x${RESULT}" != "x" ] && die "\nSED error: $RESULT"

	index=`expr $index + 1`

done

echo -e "ok\n"

#
# Enable all existing modules in site database
#
echo -n "Enabling all LC modules (site database)..."

RESULT=`$PHP ${BASE_DIR}/insert_modules.php ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce config.php 2>&1`

[ "x${RESULT}" != "x" ] && die "\n$RESULT"
echo -e "ok\n"

echo -n "Adjust DrupalConnector module options (site database)..."
SQL_QUERY="REPLACE INTO xlite_config (name, comment, value, category, type) VALUES('drupal_root_url', 'Drupal URL', '${SITE_URL}', 'DrupalConnector', 'text');"
RESULT=`echo $SQL_QUERY | $MYSQL_SITE_CMD 2>&1`
[ "x${RESULT}" != "x" ] && die "\nMySQL error: $RESULT"
echo -e "ok\n"

#
# Enable all existing modules in LC database
#
echo -n "Enabling all LC modules (LC admin database)..."

RESULT=`$PHP ${BASE_DIR}/insert_modules.php ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce config.demo.php 2>&1`

[ "x${RESULT}" != "x" ] && die "\n$RESULT"
echo -e "ok\n"

echo -n "Adjust DrupalConnector module options (LC admin database)..."
SQL_QUERY="REPLACE INTO xlite_config (name, comment, value, category, type) VALUES('drupal_root_url', 'Drupal URL', '${SITE_URL}', 'DrupalConnector', 'text');"
RESULT=`echo $SQL_QUERY | $MYSQL_LC_CMD 2>&1`
[ "x${RESULT}" != "x" ] && die "\nMySQL error: $RESULT"
echo -e "ok\n"


#
# Generate LC cache
#
echo -n "LiteCommerce cache generating..."

rm -rf ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/var/run
RESULT=`$PHP ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/includes/prepend.php 2>&1`
[ "x${RESULT}" != "xRe-building cache, please wait..." ] && die "\n$RESULT"
echo -e "ok\n"


#
# Setup permissions
#
if [ "x${SETUP_PERMISSIONS}" != "x" ]; then

	echo -n "Permissions setting up..."

	# Setup secure permissions on entire $DEPLOYMENT_DIR
	find ${DEPLOYMENT_DIR} -type d -exec chmod 755 {} \;
	find ${DEPLOYMENT_DIR} -type f -exec chmod 644 {} \;

	# Setup writable permissions on somw directories/files
	chmod 777 ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/var ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/images
	find ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/var -type d -exec chmod 777 {} \;
	find ${DEPLOYMENT_DIR}/modules/lc_connector/litecommerce/var -type f -not -name ".*" -exec chmod 666 {} \;

	echo -e "ok\n"

fi


#
# Calculate and display elapsed time
#
_php_code='@date_default_timezone_set(@date_default_timezone_get()); $s=mktime()-'$START_TIME'; echo sprintf("%d:%02d:%02d", ($s1=intval($s/3600)), ($s2=intval(($s-$s1*3600)/60)), ($s-$s1*3600-$s2*60));'
_elapsed_time=`eval $PHP" -qr '"$_php_code"'"`

echo -e "\nTime elapsed: ${_elapsed_time}\n"

