UPDATE xlite_modules SET enabled = 0;
UPDATE xlite_modules SET enabled = 1 WHERE name IN (
--'AdvancedSearch',
'AustraliaPost',
'AuthorizeNet',
'Bestsellers',
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
