UPDATE xlite_modules SET enabled=0;
UPDATE xlite_modules SET enabled=1 WHERE NOT name IN (
	'DemoMode',
	'PayPal',
	'ProtxDirect',
	'ProtxForm',
	'UPS',
	'ShowcaseOrganizer'
);
