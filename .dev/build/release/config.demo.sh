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

# LiteCommerce files that must be removed from all distributives
XLITE_FILES_TODELETE="
install.php
restoredb
sql/xlite_lng_de.sql
sql/local
"

# Drupal files that must be removed from all distributives
DRUPAL_FILES_TODELETE="
profiles/default
profiles/litecommerce_site
profiles/litecommerce
includes/install.pgsql.inc
"

LC_SEO_PHRASES=""

DRUPAL_SEO_PHRASES=""

