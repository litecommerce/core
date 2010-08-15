UPDATE xlite_modules SET enabled=0;
UPDATE xlite_modules SET enabled=1 WHERE name IN (
--'AdvancedSearch',
--'AuthorizeNet',
--'Bestsellers',
'DetailedImages',
'DrupalConnector',
--'FeaturedProducts',
--'GiftCertificates',
--'GoogleCheckout',
--'InventoryTracking',
--'PayPalPro',
--'ProductAdviser',
'ProductOptions'
--'UPSOnlineTools',
--'USPS',
--'WishList',
--'WholesaleTrading',
--'XPaymentsConnector'
);

--UPDATE xlite_modules SET enabled=0 WHERE name != 'DrupalConnector';

--UPDATE xlite_modules SET enabled=0;
--UPDATE xlite_modules SET enabled=1 WHERE NOT name IN (
--	'PayPal',
--	'UPS',
--	'Demo'
--);
