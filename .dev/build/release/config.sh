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

# LC Connector URL on drupal.org site
# LC_CONNECTOR_URL="http://ftp.drupal.org/files/projects/lc_connector-7.x-1.1.tar.gz"

# LC Connector file (tar.gz) downloaded from $LC_CONNECTOR_URL and stored locally
# LC_CONNECTOR_URL="path-to-file/lc_connector-7.x-1.1.tar.gz"

# LC3 Clean theme repository URL
LC3_CLEAN_REPO="https://github.com/litecommerce/lc3_clean/tarball/7.x-1.x"

# LC3 Clean URL on drupal.org site
# LC3_CLEAN_URL="http://ftp.drupal.org/files/projects/lc3_clean-7.x-1.1.tar.gz"

# LC3 Clean file (tar.gz) downloaded from $LC3_CLEAN_URL and stored locally
# LC3_CLEAN_URL="path-to-file/lc3_clean-7.x-1.1.tar.gz"

# Output directory name
OUTPUT_DIR="output"

# Flag: recreate output directory if it is exists (remove all data within)
CLEAR_OUTPUT_DIR=1

# Directory names within distribution packages
LITECOMMERCE_DIRNAME="litecommerce"
DRUPAL_DIRNAME="drupal"

# These parameters are used as as part of distributive package file name
LITECOMMERCE_DISTR_NAME="litecommerce3"
LITECOMMERCE_CORE_DISTR_NAME="lc-core"

# Title of LiteCommerce (used only in progress messages)
LITECOMMERCE_TITLE="LiteCommerce"

# Set this to non-empty value if you need generate lc_connector module and lc3_clean theme
PACK_DRUPAL_MODULES=""

# Set this to empty value if you don't want  to generate ECMS package
BUILD_DRUPAL_PACKAGE="Y"

# LiteCommerce modules for including to the distributives
XLITE_MODULES="
CDev/Bestsellers
CDev/FeaturedProducts
CDev/FileAttachments
CDev/ProductOptions
"

# LiteCommerce modules which should be packed as separate distributives
XLITE_SEPARATE_MODULES="
CDev/AmazonS3Images
CDev/AustraliaPost
CDev/AuthorizeNet
CDev/Bestsellers
CDev/Catalog
CDev/ContactUs
CDev/DrupalConnector
CDev/FeaturedProducts
CDev/FileAttachments
CDev/GoogleAnalytics
CDev/GoSocial
CDev/MarketPrice
CDev/Moneybookers
CDev/Paypal
CDev/PaypalWPS
CDev/ProductOptions
CDev/Quantum
CDev/SalesTax
CDev/Sale
CDev/SocialLogin
CDev/SimpleCMS
CDev/TinyMCE
CDev/TwoCheckout
CDev/VAT
CDev/UserPermissions
CDev/USPS
CDev/XMLSitemap
CDev/XMLSitemapDrupal
CDev/DeTranslation
CDev/FrTranslation
CDev/RuTranslation
"

# LiteCommerce paid modules which should be packed as separate distributives
XLITE_SEPARATE_MODULES=$XLITE_SEPARATE_MODULES"
CDev/Coupons
CDev/Egoods
CDev/ProductAdvisor
CDev/VolumeDiscounts
CDev/Wholesale
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

REPLACE_HEADERS_SETTINGS=""

CHECK_HEADERS_SETTINGS="./files/headers/settings.lc3.php"

