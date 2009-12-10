ALTER TABLE xlite_modules CHANGE version version varchar(12) NOT NULL DEFAULT '0';
INSERT INTO xlite_payment_methods VALUES ('verisignlink','Credit Card','Visa, Mastercard, American Express','verisignlink','',10,0);
