# SVN: $Id$
#
# Data definition for LiteCommerce release script
#

. config.sh

# Trigger to enable generation of demo version
DEMO_VERSION="1"

# Drupal directory name
DRUPAL_DIRNAME="drupal_demo"

# LiteCommerce modules for including to the distributives
XLITE_MODULES=${XLITE_MODULES}"
Demo
"

# The list of modules which should never be included into the demo version
XLITE_EXCLUDE_MODULES="
AustraliaPost
"

TMP_XLITE_MODULES=""

for i in $XLITE_MODULES; do

	found=""

	for j in $XLITE_EXCLUDE_MODULES; do
		if [ ! $i = $j ]; then
			found="found"
			break
		fi
	done

	[ ! "x${found}" = "x" ] && TMP_XLITE_MODULES=$TMP_XLITE_MODULES" "$i

done

XLITE_MODULES=$TMP_XLITE_MODULES

# LiteCommerce files that must be removed from all distributives
XLITE_FILES_TODELETE="
install.php
restoredb
sql/xlite_lng_de.sql
sql/local
"

# LiteCommerce files that are required for night builds - this var must be empty
XLITE_FILES_TESTMODE=""

# Drupal files that must be removed from all distributives
DRUPAL_FILES_TODELETE="
profiles/default
profiles/litecommerce_site
profiles/litecommerce
includes/install.pgsql.inc
"

LC_SEO_PHRASES=""

DRUPAL_SEO_PHRASES=""

