# SVN: $Id$
#
# Data definition for LiteCommerce release script
#

# LiteCommerce version
XLITE_VERSION="3.0"

# LiteCommerce SVN repository
XLITE_SVN="svn://svn.crtdev.local/repo/xlite/main/test"

# Drupal SVN repository
DRUPAL_SVN="svn://svn.crtdev.local/repo/xlite_cms/main/src"

# Output directory name
OUTPUT_DIR="output"

# Flag: recreate output directory if it is exists (remove all data within)
CLEAR_OUTPUT_DIR=1

# LiteCommerce modules for including to the distributives
XLITE_MODULES="
AdvancedSearch
AuthorizeNet
Bestsellers
DetailedImages
DrupalConnector
FeaturedProducts
GiftCertificates
GoogleCheckout
InventoryTracking
MultiCategories
PayPalPro
ProductAdviser
ProductOptions
UPSOnlineTools
USPS
WishList
WholesaleTrading
"

# LiteCommerce files that must be removed from all distributives
XLITE_FILES_TODELETE="
restoredb
sql/Makefile
sql/xlite_all_modules.sql
sql/xlite_demo_store.sql
sql/xlite_modules.sql
"

# Drupal files that must be removed from all distributives
DRUPAL_FILES_TODELETE="
profiles/default
includes/install.pgsql.inc
"

LC_SEO_PHRASES="
shopping cart
php shopping cart
shopping cart software
shopping cart system
ecommerce shopping cart
online shopping cart
ecommerce software
ecommerce solution
e-commerce software
e-commerce solution
"

DRUPAL_SEO_PHRASES="
e-commerce cms software
e-commerce cms solution
php e-commerce cms software
php e-commerce cms solution

"


