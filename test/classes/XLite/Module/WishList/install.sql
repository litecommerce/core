DROP TABLE IF EXISTS xlite_wishlist;
CREATE TABLE xlite_wishlist (
	   	wishlist_id int(11) auto_increment,
		profile_id	int(11) NOT NULL default 0,
		order_by	int(11)	NOT NULL default 0,
		date		int(11) default NULL,	
		PRIMARY KEY (wishlist_id)
) TYPE=MyISAM;

DROP TABLE IF EXISTS xlite_wishlist_products;
CREATE TABLE xlite_wishlist_products (
		item_id		varchar(255) NOT NULL default '',
		product_id  int(11) NOT NULL default 0,
		wishlist_id int(11) NOT NULL default 0,
		amount      int(11) NOT NULL default 0,
        purchased   int(11) NOT NULL default 0,
        options     text NOT NULL default '',
	    order_by    int(11) NOT NULL default 0,
		PRIMARY KEY (item_id,wishlist_id)
) TYPE=MyISAM;
