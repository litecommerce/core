# SVN: $Id$
#
# Data definition for LiteCommerce CMS deployment script
#

# Output directory name (absolute path)
DEPLOYMENT_DIR="/www/litecommerce"

# Database access data for Drupal+LiteCommerce
SITE_DBHOST="localhost"
SITE_DBSOCK=""
SITE_DBPORT=""
SITE_DBUSER="user"
SITE_DBPASS="password"
SITE_DBNAME="database"

SITE_URL="http://www.litecommerce.com"
SITE_WEB_DIR="/"

# Database access data for LiteCommerce admin interface demo
LC_DBHOST="localhost"
LC_DBSOCK=""
LC_DBPORT=""
LC_DBUSER="user"
LC_DBPASS="password"
LC_DBNAME="database"

# Host settings for LiteCommerce
LC_HTTP_HOST="www.litecommerce.com"
LC_HTTPS_HOST="www.litecommerce.com"
LC_WEB_DIR="/modules/lc_connector/litecommerce"

# Site administrator account data
SITE_ADMIN_USERNAME="siteadmin"
SITE_ADMIN_PASSWORD="890u7y9gjhw40gjw"
SITE_ADMIN_EMAIL="admin@litecommerce.com"

# LC admin demo: admin account email (must be different from site admin account)
LC_ADMIN_EMAIL="bit-bucket@litecommerce.com"

#
# SQL files
#
SITE_LC_SQL_BASE_FILES="
sql/xlite_tables.sql
sql/xlite_data.sql
sql/states_US.sql
sql/states_GB.sql
sql/states_CA.sql
"

SITE_LC_SQL_DEMO_DATA_FILES="
sql/xlite_demo_user.sql
sql/xlite_demo_data.sql
sql/xlite_demo_store.sql
"

SITE_DRUPAL_SQL_FILES="
clean.sql
diff.lcweb.sql
"

LC_SQL_BASE_FILES="
sql/xlite_tables.sql
sql/xlite_data.sql
sql/states_US.sql
sql/states_GB.sql
sql/states_CA.sql
"

LC_SQL_DEMO_DATA_FILES="
sql/xlite_demo.sql
sql/xlite_demo_user.sql
"

# Setup writable permission (need if php is running under user www)
SETUP_PERMISSIONS="1"

# Miscellaneous settings
PHP='/usr/local/php-530/bin/php'
MYSQL='/usr/local/bin/mysql'
MD5='/sbin/md5'
