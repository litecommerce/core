UPDATE xlite_modules SET enabled=0;
UPDATE xlite_modules SET enabled=1 WHERE name IN (
'AdvancedSearch',
'AuthorizeNet',
'Bestsellers',
'DetailedImages',
'FeaturedProducts',
'GiftCertificates',
'GoogleCheckout',
'InventoryTracking',
'PayPalPro',
'ProductAdviser',
'ProductOptions',
'UPSOnlineTools',
'USPS',
'WishList',
'WholesaleTrading',
'XPaymentsConnector'
);
