#
# Data definition for LiteCommerce release script
#

. config.sh

# Trigger to enable generation of demo version
DEMO_VERSION="1"

# Drupal directory name
DRUPAL_DIRNAME="drupal_demo"

#Directory with additional files for demo version
DEMO_FILES=""

# LiteCommerce modules for including to the distributives
XLITE_MODULES="
${XLITE_MODULES}
${XLITE_SEPARATE_MODULES}
CDev/Demo
"

XLITE_SEPARATE_MODULES=""

# The list of modules which should never be included into the demo version
XLITE_EXCLUDE_MODULES="
CDev/AustraliaPost
CDev/AuthorizeNet
CDev/Quantum
"

TMP_XLITE_MODULES=""

for i in $XLITE_MODULES; do

	found=""

	for j in $XLITE_EXCLUDE_MODULES; do
		if [ $i = $j ]; then
			found="found"
			break
		fi
	done

	[ "x${found}" = "x" ] && TMP_XLITE_MODULES=$TMP_XLITE_MODULES" "$i

done

XLITE_MODULES=$TMP_XLITE_MODULES

# LiteCommerce files that must be removed from all distributives
XLITE_FILES_TODELETE=""

# LiteCommerce files that are required for night builds - this var must be empty
XLITE_FILES_TESTMODE=""

# Drupal files that must be removed from all distributives
DRUPAL_FILES_TODELETE=""

LC_SEO_PHRASES=""

DRUPAL_SEO_PHRASES=""

