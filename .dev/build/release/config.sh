#
# Data definition for LiteCommerce release script
#

# LiteCommerce version (no spaces allowed as it's used as part of distributive name)
XLITE_VERSION="3.x-dev"

# LiteCommerce repository URL
XLITE_REPO="https://github.com/litecommerce/core/tarball/master-dev"

# Drupal repository URL
DRUPAL_REPO="https://github.com/litecommerce/drupal/tarball/7.x-master-dev"

# LC Connector module repository URL
LC_CONNECTOR_REPO="https://github.com/litecommerce/lc_connector/tarball/7.x-1.x"

# LC3 Clean theme repository URL
LC3_CLEAN_REPO="https://github.com/litecommerce/lc3_clean/tarball/7.x-1.x"

# Output directory name
OUTPUT_DIR="output"

# Flag: recreate output directory if it is exists (remove all data within)
CLEAR_OUTPUT_DIR=1

# Directory names within distribution packages
LITECOMMERCE_DIRNAME="litecommerce"
DRUPAL_DIRNAME="drupal"

# LiteCommerce modules for including to the distributives
XLITE_MODULES="
CDev/Bestsellers
CDev/FeaturedProducts
CDev/FileAttachments
CDev/ProductOptions
"

# LiteCommerce modules which should be packed as separate distributives
XLITE_SEPARATE_MODULES="
CDev/AustraliaPost
CDev/AuthorizeNet
CDev/Bestsellers
CDev/DrupalConnector
CDev/FeaturedProducts
CDev/FileAttachments
CDev/GoogleAnalytics
CDev/MarketPrice
CDev/Moneybookers
CDev/PaypalWPS
CDev/ProductOptions
CDev/Quantum
CDev/SalesTax
CDev/TinyMCE
CDev/TwoCheckout
CDev/VAT
CDev/USPS
CDev/XMLSitemap
CDev/XMLSitemapDrupal
"

# LiteCommerce paid modules which should be packed as separate distributives
XLITE_SEPARATE_MODULES=$XLITE_SEPARATE_MODULES"
CDev/Egoods
CDev/ProductAdvisor
"

# LiteCommerce files that must be removed from all distributives
XLITE_FILES_TODELETE="
"

# LiteCommerce files that are required for night builds
XLITE_FILES_TESTMODE="
restoredb
sql/local
sql/demo
sql/modules
"

# LiteCommerce files that are required for night builds
DRUPAL_FILES_TESTMODE="
profiles/litecommerce_test
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
themes/lccms
"

# Category images in 'public' directory which should be removed
CATEGORY_IMAGES_LIST="
apparel.png
downloadables.png
igoods.png
store-index-banner.png
toys.png
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


