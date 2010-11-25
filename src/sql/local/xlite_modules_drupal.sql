UPDATE xlite_modules SET enabled=0;
UPDATE xlite_modules SET enabled=1 WHERE name IN (
'AustraliaPost',
'AuthorizeNet',
'Bestsellers',
'DrupalConnector',
'FeaturedProducts',
-- 'GiftCertificates',
-- 'GoogleCheckout',
-- 'InventoryTracking',
-- 'PayPalPro',
-- 'ProductAdviser',
'ProductOptions',
'Quantum'
-- 'UPSOnlineTools',
-- 'USPS',
-- 'WishList',
-- 'WholesaleTrading',
-- 'XPaymentsConnector'
);
