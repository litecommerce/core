UPDATE xlite_modules SET enabled = 0;
UPDATE xlite_modules SET enabled = 1 WHERE name IN (
'AustraliaPost',
'AuthorizeNet',
'Bestsellers',
'DrupalConnector',
'Quantum',
'FeaturedProducts',
-- 'GiftCertificates',
-- 'GoogleCheckout',
-- 'InventoryTracking',
-- 'PayPalPro',
-- 'ProductAdviser',
'ProductOptions'
-- 'UPSOnlineTools',
-- 'USPS',
-- 'WishList',
-- 'WholesaleTrading',
-- 'XPaymentsConnector'
);
