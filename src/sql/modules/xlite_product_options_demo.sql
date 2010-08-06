-- Product options
INSERT INTO xlite_option_groups SET group_id = 1, `product_id` = 15090, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_options SET option_id = 1, group_id = 1, enabled = 1;
INSERT INTO xlite_options SET option_id = 2, group_id = 1, enabled = 1;
INSERT INTO xlite_options SET option_id = 3, group_id = 1, enabled = 1;

INSERT INTO xlite_option_groups SET group_id = 2, `product_id` = 15090, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_options SET option_id = 4, group_id = 2, enabled = 1;
INSERT INTO xlite_options SET option_id = 5, group_id = 2, enabled = 1;

--INSERT INTO xlite_inventories SET `inventory_id` = '15121', `amount` = '2000', `enabled` = '1', `low_avail_limit` = '50';
INSERT INTO xlite_option_groups SET group_id = 3, `product_id` = 15121, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_options SET option_id = 6, group_id = 3, enabled = 1;
INSERT INTO xlite_options SET option_id = 7, group_id = 3, enabled = 1;

INSERT INTO xlite_option_groups SET group_id = 4, `product_id` = 15123, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_options SET option_id = 8, group_id = 4, enabled = 1;
INSERT INTO xlite_options SET option_id = 9, group_id = 4, enabled = 1;
INSERT INTO xlite_option_surcharges SET option_id = 9, `type` = 'price', modifier = 3,  modifier_type = '$';

INSERT INTO xlite_option_groups SET group_id = 5, `product_id` = 15068, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_options SET option_id = 10, group_id = 5, enabled = 1;
INSERT INTO xlite_options SET option_id = 11, group_id = 5, enabled = 1;
INSERT INTO xlite_options SET option_id = 12, group_id = 5, enabled = 1;

--INSERT INTO xlite_inventories SET `inventory_id` = '15091', `amount` = '1000', `enabled` = '1', `low_avail_limit` = '50';
INSERT INTO xlite_option_groups SET group_id = 6, `product_id` = 15091, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_options SET option_id = 13, group_id = 6, enabled = 1;
INSERT INTO xlite_options SET option_id = 14, group_id = 6, enabled = 1;
INSERT INTO xlite_options SET option_id = 15, group_id = 6, enabled = 1;

--INSERT INTO xlite_inventories SET `inventory_id` = '15067', `amount` = '500', `enabled` = '1', `low_avail_limit` = '0';
INSERT INTO xlite_option_groups SET group_id = 7, `product_id` = 15067, `view_type` = 'r', enabled = 1;
INSERT INTO xlite_options SET option_id = 16, group_id = 7, enabled = 1;
INSERT INTO xlite_options SET option_id = 17, group_id = 7, enabled = 1;

-- Product options translations
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (1, 'en', 'Color');
INSERT INTO xlite_option_translations (id, code, name) VALUES (1, 'en', 'Red');
INSERT INTO xlite_option_translations (id, code, name) VALUES (2, 'en', 'Green');
INSERT INTO xlite_option_translations (id, code, name) VALUES (3, 'en', 'Yellow');
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (2, 'en', 'Size');
INSERT INTO xlite_option_translations (id, code, name) VALUES (4, 'en', 'Small');
INSERT INTO xlite_option_translations (id, code, name) VALUES (5, 'en', 'Big');
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (3, 'en', 'Size');
INSERT INTO xlite_option_translations (id, code, name) VALUES (6, 'en', 'Small');
INSERT INTO xlite_option_translations (id, code, name) VALUES (7, 'en', 'Big');
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (4, 'en', 'Choice');
INSERT INTO xlite_option_translations (id, code, name) VALUES (8, 'en', 'Garden');
INSERT INTO xlite_option_translations (id, code, name) VALUES (9, 'en', 'Wild');
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (5, 'en', 'Size');
INSERT INTO xlite_option_translations (id, code, name) VALUES (10, 'en', 'Small');
INSERT INTO xlite_option_translations (id, code, name) VALUES (11, 'en', 'Medium');
INSERT INTO xlite_option_translations (id, code, name) VALUES (12, 'en', 'Big');
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (6, 'en', 'Size');
INSERT INTO xlite_option_translations (id, code, name) VALUES (13, 'en', 'Small');
INSERT INTO xlite_option_translations (id, code, name) VALUES (14, 'en', 'Medium');
INSERT INTO xlite_option_translations (id, code, name) VALUES (15, 'en', 'Big');
INSERT INTO xlite_option_group_translations (id, code, name) VALUES (7, 'en', 'Size');
INSERT INTO xlite_option_translations (id, code, name) VALUES (16, 'en', 'Small');
INSERT INTO xlite_option_translations (id, code, name) VALUES (17, 'en', 'Big');



INSERT INTO xlite_order_item_options SET item_id = '15068|Size:Medium', group_id = 5, option_id = 11, name = 'Size', value = 'Medium';
INSERT INTO xlite_order_item_options SET item_id = '15090|Color:Red|Size:Big', group_id = 1, option_id = 1, name = 'Color', value = 'Red';
INSERT INTO xlite_order_item_options SET item_id = '15090|Color:Red|Size:Big', group_id = 2, option_id = 5, name = 'Size', value = 'Big';




