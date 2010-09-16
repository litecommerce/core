# SVN: $Id$
#
# Data definition for LiteCommerce release script
#

# LiteCommerce version (no spaces allowed as it's used as part of distributive name)
XLITE_VERSION="3.x-dev"

# LiteCommerce SVN repository
XLITE_SVN="svn://svn.crtdev.local/repo/xlite/main/src"
XLITE_DEV_SVN="svn://svn.crtdev.local/repo/xlite/main/.dev"

# Drupal SVN repository
DRUPAL_SVN="svn://svn.crtdev.local/repo/xlite_cms/main/src"
DRUPAL_DEV_SVN="svn://svn.crtdev.local/repo/xlite_cms/main/.dev"

# Output directory name
OUTPUT_DIR="output"

# Flag: recreate output directory if it is exists (remove all data within)
CLEAR_OUTPUT_DIR=1

# Directory names within distribution packages
LITECOMMERCE_DIRNAME="litecommerce"
DRUPAL_DIRNAME="drupal"

# LiteCommerce modules for including to the distributives
XLITE_MODULES="
AdvancedSearch
AustraliaPost
AuthorizeNet
Bestsellers
DetailedImages
DrupalConnector
FeaturedProducts
GiftCertificates
GoogleCheckout
InventoryTracking
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
sql/xlite_lng_de.sql
sql/local
sql/demo
"

# Drupal files that must be removed from all distributives
DRUPAL_FILES_TODELETE="
profiles/default
profiles/litecommerce_site
includes/install.pgsql.inc
sites/all/modules/litecommerce_com
sites/all/modules/private_download
sites/all/modules/views_datasource
sites/all/modules/taxonomy_redirect
sites/all/modules/taxonomy_menu
sites/default/files
themes/lccms/templates/overrides/node-marketplace_listing.tpl.php
"

LC_SEO_PHRASES="
Powered by LiteCommerce [shopping cart]
Powered by LiteCommerce [shopping cart]
Powered by LiteCommerce [shopping cart software]
Powered by LiteCommerce [shopping cart software]
Powered by LiteCommerce [PHP shopping cart]
Powered by LiteCommerce [PHP shopping cart system]
Powered by LiteCommerce [eCommerce shopping cart]
Powered by LiteCommerce [online shopping cart] 
Powered by LiteCommerce [eCommerce software]
Powered by LiteCommerce [eCommerce software]
Powered by LiteCommerce [e-commerce software]
Powered by LiteCommerce [e-commerce software]
Powered by LiteCommerce [eCommerce solution]
Powered by LiteCommerce [eCommerce solution]
Powered by LiteCommerce [e-commerce solution]
Powered by LiteCommerce [e-commerce solution]
"

DRUPAL_SEO_PHRASES="
Powered by [e-commerce CMS]: LiteCommerce plus Drupal
Powered by [e-commerce CMS]: LiteCommerce plus Drupal
Powered by [e-commerce CMS]: LiteCommerce plus Drupal
Powered by [eCommerce CMS]: LiteCommerce plus Drupal
Powered by [eCommerce CMS]: LiteCommerce plus Drupal
Powered by [eCommerce CMS]: LiteCommerce plus Drupal
Powered by [e-commerce CMS software]: LiteCommerce plus Drupal
Powered by [eCommerce CMS software]: LiteCommerce plus Drupal
Powered by [e-commerce CMS solution]: LiteCommerce plus Drupal
Powered by [eCommerce CMS solution]: LiteCommerce plus Drupal
Powered by LiteCommerce [shopping cart] and Drupal CMS
Powered by LiteCommerce [shopping cart software] and Drupal CMS
Powered by LiteCommerce [eCommerce shopping cart] and Drupal CMS
Powered by LiteCommerce [eCommerce software] and Drupal CMS
Powered by LiteCommerce [eCommerce solution] and Drupal CMS
Powered by LiteCommerce [e-commerce software] and Drupal CMS
Powered by LiteCommerce [e-commerce solution] and Drupal CMS
"


