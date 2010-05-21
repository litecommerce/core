#
# Makefile for SQL database
#
# $Id: Makefile,v 1.33 2006/05/29 13:11:46 sheriff Exp $
#
MYSQL_HOST  ?= mysql
MYSQL_BASE        ?= ${USER}
PASS        = `cat ~/.mysql_password`
MYSQL       = /usr/local/bin/mysql
DUMP        = /usr/local/bin/mysqldump
TABLES      = xlite_tables.sql
DROPS       = dbclear.sql
DATA        = xlite_data.sql
DEMO        = xlite_demo.sql
MODULES		= xlite_modules.sql
DUMP_CMD    = ${DUMP} -u${USER} -h${MYSQL_HOST} -p${PASS}
CMD         = ${MYSQL} -u${USER} -h${MYSQL_HOST} -p${PASS}
FILTER      = egrep -v "^\#|^--"
DROP_FILTER = grep "^DROP TABLE"
DATA_FILTER = egrep -v "INSERT INTO xlite_categories|INSERT INTO xlite_products|INSERT INTO xlite_product_links|INSERT INTO xlite_states|INSERT INTO xlite_modules"
DEMO_FILTER = egrep "INSERT INTO xlite_categories|INSERT INTO xlite_products|INSERT INTO xlite_product_links"
MODULES_FILTER = grep "INSERT INTO xlite_modules"
STATES_TABLE= xlite_states
STATES      = US GB CA

all:
	@echo Usage:
	@echo make [cleandb\|dump\|restore]

cleandb:
	@echo Clean database runtime data..

bit-bucket:
	@echo Create default user..
	-${CMD} -e "REPLACE INTO xlite_profiles (profile_id, login, password, access_level, billing_title, billing_firstname, billing_lastname, billing_phone, billing_address, billing_city, billing_state, billing_country, billing_zipcode, shipping_title, shipping_firstname, shipping_lastname, shipping_phone, shipping_address, shipping_city, shipping_state, shipping_country, shipping_zipcode, first_login, last_login, status) VALUES (1,'bit-bucket@rrf.ru','202cb962ac59075b964b07152d234b70',100,'Mr.','Bit','Bucket','0123456789','Billing street, 1','Edmond',38,'US','73003','Mr.','Bit','Bucket','9876543210','Shipping street, 1','Edmond',38,'US','73003',1053689339,1058449247,'E')" ${MYSQL_BASE}

#
# DUMP secton
#
dump: cleandb dump-tables dump-data dump-modules dump-demo dump-states

dump-tables:
	@echo Dumping tables..
	@${DUMP_CMD} --add-drop-table -d ${MYSQL_BASE} | ${FILTER} > ${TABLES}
	@${DUMP_CMD} --add-drop-table -d ${MYSQL_BASE} | ${FILTER} | ${DROP_FILTER} > ${DROPS}

dump-data:
	@echo Dumping data..
	@${DUMP_CMD} -t ${MYSQL_BASE} | ${FILTER} | ${DATA_FILTER} > ${DATA}

dump-modules:
	@echo Dumping modules..
	@${DUMP_CMD} -t ${MYSQL_BASE} | ${FILTER} | ${MODULES_FILTER} > ${MODULES}

dump-demo:
	@echo Dumping demo data..
	@${DUMP_CMD} -t ${MYSQL_BASE} | ${FILTER} | ${DEMO_FILTER} > ${DEMO}

dump-states:
	@echo Dumping states..
.for state in ${STATES}    
	@${DUMP_CMD} -t --where="country_code='${state}'" ${MYSQL_BASE} ${STATES_TABLE} | ${FILTER} > states_${state}.sql
.endfor

#
# RESTORE section
#
restore: restore-tables restore-data restore-modules restore-demo restore-states

restore-tables:
	@echo Restore tables..
	@${CMD} ${MYSQL_BASE} < ${TABLES}

restore-data:
	@echo Restore data..
	@${CMD} ${MYSQL_BASE} < ${DATA}

restore-modules:
	@echo Restore modules..
	@${CMD} ${MYSQL_BASE} < ${MODULES}
	
restore-demo: bit-bucket
	@echo Restore demo data..
	@${CMD} ${MYSQL_BASE} < ${DEMO}

restore-states:
	@echo Restore states..
.for state in ${STATES}    
	@${CMD} ${MYSQL_BASE} < states_${state}.sql
.endfor    
