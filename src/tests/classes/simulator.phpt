<?php
require_once "HTTP/Request.php";

class Simulator
{
	var $url;
	var $cookie = array();
	var $pages = 0;
	var $queries = 0;
	var $logging = false;
	var $totals = null;
	var $count = 0;
	
	function Simulator($url = null) // {{{
	{
		global $options;
		if (!isset($url)) {
			$this->url = 'http://'.$options['host_details']['http_host'].$options['host_details']['web_dir'];
		} else {
			$this->url = $url;
		}
	} // }}}
	
	function login() // {{{
	{
		$this->requestImg("cart.php", array());
		$this->requestImg("cart.php", array(),array('login' => "bit-bucket@rrf.ru",'password' => "123",'target' => "login",'action' => "login",'x' => "0",'y' => "0"));
	} // }}}
    
	function stressPass() // {{{
	{
		$matches = $this->assertRE('/category_id=([0-9]*)/', true, $this->lastPage);
		$count = 0;
		for ($i=0; $i<count($matches[1]); $i++) {
			$cat_id = $matches[1][$i];
			if (rand() % (int)((count($matches[1])-$i-1)/(6-$count)+1) == 0) {
				$count++;
				$this->requestImg("cart.php", array('target' => "category",'category_id' => $cat_id));
				if ($count==6) break;
			}
		}
		$this->requestImg("cart.php", array('target' => "category",'category_id' => "89"));
		$this->requestImg("cart.php", array('target' => "product",'product_id' => "195",'category_id' => "89"));
		$this->requestImg("cart.php", array('target' => "cart",'action' => "add",'product_id' => "195",'category_id' => "89"));
		$this->requestImg("cart.php", array('target' => "cart",'action' => "clear"));

		// search
		//			$this->requestImg("cart.php", array('target' => "search",'substring' => "as"));
		//$this->requestImg("cart.php", array('target' => "search",'substring' => "as",'pageID' => "2"));
	} // }}}

	function stressTest() // {{{
	{
		// $this->login();
		for($i = 1; $i <= 5; $i++) {
			print "PASS $i\n";
			$this->stressPass();
		}
	} // }}}

	function measure() // {{{
	{
		$this->logging = true;
		$this->login();
		// $this->stressPass();
		$this->stressTest();
		$this->logAvg();
	} // }}}

    // TESTS {{{
	function functionTest() // {{{
	{
$this->request("admin.php", array());
$this->request("admin.php", array(),array('login' => 'bit-bucket@rrf.ru','password' => '123','target' => 'login','action' => 'login'));
$this->assertRE('/You are in your personal administrator area !/');

// Add Test CD product
$this->request("admin.php", array('target' => 'add_product'));
$this->assertRE('/Add New Product/');
$this->request("admin.php", array(),array('target' => 'add_product','action' => 'add','category_id' => '105','sku' => 'SKU-FOR-TEST-CD','name' => 'Test CD','brief_description' => 'Test CD \'"\\','description' => 'Test CD \'"\\ - detailed','price' => '123','enabled' => '1','tax_class' => 'Tax free'));
list($a, $pid) = $this->assertRE('/Product #([0-9]+) has been added/');
$this->request("admin.php", array('target' => 'product','product_id' => $pid));
$this->assertRE('/Test CD/');
$this->assertRE('/SKU-FOR-TEST-CD/');
$this->assertRE("/Test CD '\"\\\\/");
$this->assertRE('/Tax free/');

// set taxes
$this->request("admin.php", array('target' => 'taxes'));
$this->assertRE('/Rates\/Conditions/');
$this->request("admin.php", array(),array('target' => 'taxes','action' => 'update_options','use_billing_info' => 'N','pos' => array('0' => '10'),'name' => array('0' => 'Tax'),'display_label' => array('0' => 'Local Tax'),'new_pos' => '100','new_name' => '','new_display_label' => '','prices_include_tax' => 'N'));
$this->assertRE('/Local Tax/');
$this->request("admin.php", array(),array('target' => 'taxes','action' => 'update_rates','varvalue' => array('0' => '10','1' => '0','2' => '0'),'pos' => array('0' => '10','1' => '20','2' => '30')));
$this->request("admin.php", array(),array('target' => 'taxes','action' => 'add_submit','add_another' => '0','ind' => '','country' => '','select_country' => '-- select --','state' => '','select_state' => '-- select --','city' => 'Ulyanowsk','select_city' => '-- select --','pm' => '','select_pm' => '-- select --','pclass' => '','select_pclass' => '-- select --','membership' => '','select_membership' => '-- select --','taxName' => 'Tax','select_taxName' => '-- select --','taxValue' => '15'));
$this->assertRE('/city=Ulyanowsk/');

$this->request("admin.php", array(),array('target' => 'taxes','action' => 'update_rates','varvalue' => array('0' => '10','1' => '0','2' => '0','3' => '15'),'pos' => array('0' => '10','1' => '20','2' => '30','3' => '15')));
$this->assertRE('/city=Ulyanowsk/');

// set shipping
$this->request("admin.php", array('target' => 'shipping_methods'));
$this->assertRE('/Manually defined shipping methods/');
$this->request("admin.php", array('target' => 'shipping_rates'));
$this->assertRE('/Shipping charges/');
$this->request("admin.php", array(),array('target' => 'shipping_rates','action' => 'update','shipping_zone_range' => '','shipping_id_range' => '','deleted_rate' => '','rate' => array('0' => array('shipping_id' => '2','min_weight' => '0.00','max_weight' => '999999.00','flat' => '0.00','shipping_zone' => '-1','min_total' => '0.00','max_total' => '999999.00','per_item' => '10','min_items' => '0','max_items' => '999999'))));
$this->assertRE('/Per item.*10\.00/m');
$this->request("admin.php", array(),array('target' => 'shipping_rates','action' => 'add','shipping_zone_range' => '','shipping_id_range' => '','shipping_id' => '3','min_weight' => '0','max_weight' => '999999','flat' => '15','shipping_zone' => '-1','min_total' => '0','max_total' => '150','per_item' => '0','min_items' => '0','max_items' => '999999'));
$this->assertRE('/Flat.*15\.00/m');
$this->request("admin.php", array(),array('target' => 'shipping_rates','action' => 'add','shipping_zone_range' => '','shipping_id_range' => '','shipping_id' => '-1','min_weight' => '0','max_weight' => '999999','flat' => '25','shipping_zone' => '-1','min_total' => '150','max_total' => '999999','per_item' => '0','min_items' => '0','max_items' => '999999'));
$this->request("admin.php", array(),array('target' => 'shipping_rates','action' => 'update','shipping_zone_range' => '','shipping_id_range' => '','deleted_rate' => '','rate' => array('0' => array('shipping_id' => '3','min_weight' => '0.00','max_weight' => '999999.00','flat' => '25.00','shipping_zone' => '-1','min_total' => '150.00','max_total' => '999999.00','per_item' => '0.00','min_items' => '0','max_items' => '999999'),'1' => array('shipping_id' => '2','min_weight' => '0.00','max_weight' => '999999.00','flat' => '0.00','shipping_zone' => '-1','min_total' => '0.00','max_total' => '999999.00','per_item' => '10.00','min_items' => '0','max_items' => '999999'),'2' => array('shipping_id' => '3','min_weight' => '0.00','max_weight' => '999999.00','flat' => '15.00','shipping_zone' => '-1','min_total' => '0.00','max_total' => '150.00','per_item' => '0.00','min_items' => '0','max_items' => '999999'))));
$this->assertRE('/150\.00[^-]*-[^-]*999999\.00/m');

// add to cart as bit-bucket

$this->request("cart.php", array('target' => 'category','action' => 'view','category_id' => '105','pageID' => '2'));
$this->assertRE('/Test CD \'"\\\\/');
$this->assertRE("/product_id=$pid/");
$this->request("cart.php", array('target' => 'product','action' => 'view','product_id' => $pid,'category_id' => '105'));
$this->assertRE('/SKU-FOR-TEST-CD/');
$this->assertRE('/Price:[^$]*\$[^0-9]123\.00/m');
$this->request("cart.php", array('target' => 'cart','action' => 'add','product_id' => $pid,'category_id' => '105'));
$this->assertRE('/Shipping:[^0-9]*10\.00/m');
$this->assertRE('/Order total:[^0-9]*133\.00/m');
$this->request("cart.php", array(),array('target' => 'cart','action' => 'update','amount' => array('0' => '2'),'shipping' => '2'));
$this->assertRE('/Order total:[^0-9]*266\.00/m'); // 146 subtotal + 20 shipping

$this->request("admin.php", array(),array('active_modules' => array('1010' => 'on'),'Update' => 'Update','target' => 'modules','action' => 'update','module_id' => '','module_name' => ''));
$this->assertRE('/PayPal/');
$this->request("admin.php", array('target' => 'payment_method','payment_method' => 'paypal'));
$this->assertRE('/PayPal settings/');
$this->request("admin.php", array(),array('target' => 'payment_method','action' => 'update','payment_method' => 'paypal','params' => array('login' => 'test','image_url' => '','prefix' => '','currency' => 'USD','url' => 'https://rrf.ru/~ndv/x-lite/tests/classes/modules/PayPal/paypal.php')));
$this->assertRE('/PayPal settings were successfully changed/');
$this->request("admin.php", array(),array('data' => array('credit_card' => array('payment_method' => 'credit_card','name' => 'Credit Card','details' => 'Visa, Mastercard, American Express','orderby' => '10','enabled' => 'on'),'purchase_order' => array('payment_method' => 'purchase_order','name' => 'Purchase Order','details' => '','orderby' => '20','enabled' => 'on'),'phone_ordering' => array('payment_method' => 'phone_ordering','name' => 'Phone Ordering','details' => 'Phone: (555) 555-5555','orderby' => '30','enabled' => 'on'),'paypal' => array('payment_method' => 'paypal','name' => 'PayPal','details' => '','orderby' => '40','enabled' => 'on'),'echeck' => array('payment_method' => 'echeck','name' => 'Check','details' => 'Check payment','orderby' => '50','enabled' => 'on'),'cod' => array('payment_method' => 'cod','name' => 'COD','details' => 'Cache On Delivery','orderby' => '60','enabled' => 'on')),'target' => 'payment_methods','action' => 'update','update' => 'update'));
$this->assertRE('/paypal.*checked/');

// checkout
$this->request("cart.php", array('target' => 'checkout'));
$this->request("cart.php", array(),array('payment_id' => 'paypal','target' => 'checkout','action' => 'payment'));
$this->request("cart.php", array(),array('target' => 'checkout','action' => 'update','amount' => array('0' => '1'),'shipping' => '2'));
$this->assertRE('/Subtotal:[^0-9]*123\.00/m');
$this->assertRE('/PayPal/');
$this->assertRE('/Phone:[^0-9]*9876543210/m');

// make request to paypal test server
list($a, $url) = $this->assertRE('/action="([^"]*)".*paypal_form/');
$form_fields = array('cmd','invoice','redirect_cmd','email','first_name','last_name','address1','city','state','zip','day_phone_a','day_phone_b','day_phone_c','business','item_name','amount','currency_code','return','cancel_return','notify_url','image_url');
$postData = array();
foreach ($form_fields as $field) {
	list($a, $value) = $this->assertRE("/type=hidden name=\"?$field\"? value=\"([^\"]*)\"/");
	$postData[$field] = $value;
}
$this->request($url, array(), $postData);
list ($a, $url) = $this->assertRE("/return_form.*action=\"([^\"]*)\"/");
$re = $this->assertRE('/hidden.*name="?([^"]*)"?.*value="?([^"]*)"?/', true);
$postData = array();
for ($i=0; $i<count($re[1]); $i++) {
	$postData[$re[1][$i]] = $re[2][$i];
}
$this->request($url, array(), $postData);
$this->assertRE('/Congratulations/');
$this->assertRE('/Invoice/');
$this->assertRE('/Cart is empty/');

// registering from Ulyanowsk
$this->request("cart.php", array('target' => "profile",'action' => "register"));
$this->assertRE('/New member/m');
$this->request("cart.php", array(),array('registration_form' => array('login' => "bit-bucket@x-cart.com",'password' => "\"'\\",'confirm_password' => "\"'\\",'password_hint' => "",'password_hint_answer' => "",'billing_title' => "Mrs.",'billing_firstname' => "\"'\\ first name",'billing_lastname' => "\"'\\ last name",'billing_company' => "\"'\\ comp",'billing_phone' => "\"'\\ ph",'billing_fax' => "",'billing_address' => "\"'\\ address",'billing_city' => "Ulyanowsk",'billing_state' => "0",'billing_country' => "RU",'billing_zipcode' => "432002",'shipping_title' => "Mr.",'shipping_firstname' => "",'shipping_lastname' => "",'shipping_company' => "",'shipping_phone' => "",'shipping_fax' => "",'shipping_address' => "",'shipping_city' => "",'shipping_state' => "0",'shipping_country' => "",'shipping_zipcode' => ""),'target' => "profile",'action' => "register",'form' => "registration_form",'x' => "10",'y' => "7"));
$this->assertRE('/Registration success/');
$this->assertRE('/bit-bucket@x-cart\.com[^L]*Logged in!/m');
$this->request("cart.php", array('target' => "search",'substring' => "Test"));
$this->assertRE('/Test CD/m');
$this->request("cart.php", array('target' => "product",'action' => "view",'product_id' => $pid,'substring' => "Test"));
$this->assertRE('/Search Result.*::.*Test CD/m');
$this->request("cart.php", array('target' => "cart",'action' => "add",'product_id' => $pid,'category_id' => ""));
$this->request("cart.php", array('target' => "category",'action' => "view",'category_id' => "79"));
$this->request("cart.php", array('target' => "product",'action' => "buynow",'product_id' => "150",'category_id' => "79"));
$this->assertRE('/International shipping/');
$this->assertRE('/Subtotal:[^0-9]*573\.00/m');
$this->assertRE('/Shipping:[^0-9]*25\.00/m');
$this->assertRE('/Local Tax:[^0-9]*67\.50/m');
$this->assertRE('/Order total:[^0-9]*665\.50/m');

// setup AuthorizeNet
$this->request("admin.php", array(),array('login' => "bit-bucket@rrf.ru",'password' => "123",'target' => "login",'action' => "login",'x' => "0",'y' => "0"));
$this->request("admin.php", array('target' => "modules",'action' => "list"));
$this->assertRE('/AuthorizeNet/');
$this->request("admin.php", array(),array('active_modules' => array('1000' => "on",'1010' => "on"),'Update' => "Update",'target' => "modules",'action' => "update",'module_id' => "",'module_name' => ""));
$this->request("admin.php", array('target' => "payment_methods"));
$this->assertRE('/authorizenet_cc/');
$this->request("admin.php", array(),array('data' => array('credit_card' => array('payment_method' => "credit_card",'name' => "Credit Card",'details' => "Visa, Mastercard, American Express",'orderby' => "10",'enabled' => "on"),'authorizenet_cc' => array('payment_method' => "authorizenet_cc",'name' => "Credit Card",'details' => "Visa, Mastercard, American Express",'orderby' => "10",'enabled' => "on"),'purchase_order' => array('payment_method' => "purchase_order",'name' => "Purchase Order",'details' => "",'orderby' => "20",'enabled' => "on"),'phone_ordering' => array('payment_method' => "phone_ordering",'name' => "Phone Ordering",'details' => "Phone: (555) 555-5555",'orderby' => "30",'enabled' => "on"),'paypal' => array('payment_method' => "paypal",'name' => "PayPal",'details' => "",'orderby' => "40",'enabled' => "on"),'authorizenet_ch' => array('payment_method' => "authorizenet_ch",'name' => "Check",'details' => "Check payment",'orderby' => "50"),'echeck' => array('payment_method' => "echeck",'name' => "Check",'details' => "Check payment",'orderby' => "50",'enabled' => "on"),'cod' => array('payment_method' => "cod",'name' => "COD",'details' => "Cache On Delivery",'orderby' => "60",'enabled' => "on")),'target' => "payment_methods",'action' => "update",'update' => "update"));
$this->request("admin.php", array('target' => "payment_method",'payment_method' => "authorizenet_cc"));
$this->assertRE('/Authorize\.Net settings/');
$this->request("admin.php", array(),array('target' => "payment_method",'action' => "update",'payment_method' => "authorizenet_cc",'params' => array('url' => "https://rrf.ru/~ndv/x-lite/tests/classes/modules/AuthorizeNet/authorize_net.php",'login' => "test",'key' => "test",'test' => "TRUE",'cvv2' => "0",'md5HashValue' => "",'prefix' => "X-Cart-",'currency' => "USD")));
$this->assertRE('/Authorize\.Net parameters were successfully changed/m');

$this->request("cart.php", array('target' => "login",'action' => "logoff"));
$this->request("cart.php", array(),array('login' => "bit-bucket@x-cart.com",'password' => "\"'\\",'target' => "login",'action' => "login",'x' => "0",'y' => "0"));
$this->assertRE('/bit-bucket@x-cart\.com[^L]*Logged in!/m');
$this->assertRE('/Items:.*2/');
$this->assertRE('/Total:.*665\.50/');

$this->request("cart.php", array('target' => "checkout"));
$res = $this->assertRE('/Credit Card/', true);
if (count($res[0])!=2) {
	$this->error("Must be two cc processors");
}
$this->request("cart.php", array(),array('payment_id' => "authorizenet_cc",'target' => "checkout",'action' => "payment"));
$this->assertRE('/Credit Card/');
$this->assertRE('/Order total:.*665\.50/');
$this->assertRE('/First Name:.*(&quot;|")\'\\\\ first name/');
$this->assertRE('/Credit card type/');
$this->request("cart.php", array(),array('target' => "checkout",'action' => "checkout",'cc_info' => array('cc_type' => "VISA",'cc_name' => "Dmitry Negoda",'cc_number' => "4111111111111111",'cc_date' => "1203",'cc_cvv2' => "123")));
$this->assertRE('/Congratulations/');
$this->assertRE('/Total[^0-9]*665\.50/m');
$fd = fopen("TST", "w");
fwrite($fd, $this->response);
fclose($fd);
	} // }}}

    function moduleTest($mod) // {{{
    {
        print "\nTesting $mod...\n";
        $func = "test$mod";
        $this->$func();
    } // }}}

    function testPromotion() // {{{
    {
$this->request("admin.php",array(),array("login" => "bit-bucket@rrf.ru","password" => "123","target" => "login","action" => "login","x" => "0","y" => "0",));
$this->request("admin.php",array("target" => "modules","action" => "list",),array());
$this->assertRE('/Modules - Installed modules/');
$this->request("admin.php",array(),array("active_modules" => array("3000" => "on",),"Update" => "Update","target" => "modules","action" => "update","module_id" => "","module_name" => "",));
$this->assertRE('/Modules - Installed modules/');
$this->request("admin.php",array("target" => "SpecialOffers",),array());
$this->assertRE('/Add new special offer/');
$this->request("admin.php",array("target" => "SpecialOffer","offer_id" => "",),array());
$this->assertRE('/Offer type/');
$this->request("admin.php",array(),array("target" => "SpecialOffer","action" => "update1","offer_id" => "","conditionType" => "productAmount","bonusType" => "discounts","title" => "test",));
$this->assertRE('/On the following category/');
$this->request("admin.php",array(),array("target" => "SpecialOffer","action" => "update2","offer_id" => "1","product_id" => "","category_id" => "23","amount" => "5","bonusAmount" => "10","bonusAmountType" => "%","addBonusProduct_id" => "","bonusCategory_id" => "23",));
$this->assertRE('/test/');
$this->assertRE('/Special offers/');
$this->request("admin.php",array("target" => "DiscountCoupons",),array());
$this->assertRE('/Add new coupon/');
$this->request("admin.php",array("target" => "product_popup","formName" => "coupon_form","spanName" => "AdminDiscountCouponProductPopup_label","formField" => "product",),array());
$this->assertRE('/Search product/');
$this->request("admin.php",array("target" => "product_popup","action" => "default","search" => "1","formName" => "coupon_form","formField" => "product","spanName" => "AdminDiscountCouponProductPopup_label","search_productsku" => "","substring" => "","search_category" => "23","subcategory_search" => "on",),array());
$this->assertRE('/Search product/');
$this->assertRE('/HTML 4 for the World Wide Web/');
$this->request("admin.php",array(),array("target" => "DiscountCoupons","action" => "add","coupon" => "NWW7AV7W","times" => "2","status" => "A","discount" => "10","type" => "percent","month" => "11","day" => "17","year" => "2005","minamount" => "0.00","applyTo" => "product","product_id" => "68","category_id" => "3",));
$this->assertRE('/contain product .*HTML 4 for the World Wide Web/');
list($page, $couponCode) = $this->assertRE('/<b>([A-Z0-9]{8,8})<\\/b>/');
$this->request("cart.php",array("target" => "product","action" => "buynow","product_id" => "67","category_id" => "23",),array());
$this->request("cart.php",array("target" => "product","action" => "buynow","product_id" => "69","category_id" => "23",),array());
$this->request("cart.php",array("target" => "product","action" => "buynow","product_id" => "70","category_id" => "23",),array());
$this->request("cart.php",array("target" => "product","action" => "buynow","product_id" => "65","category_id" => "23",),array());
$this->request("cart.php",array("target" => "cart",),array());
$this->assertRE('/186\\.98/');
$this->request("cart.php",array("target" => "cart","action" => "discount_coupon","coupon" => $couponCode,),array());
$this->assertRE('/-&nbsp;10\\.00&nbsp;\\%/');
$this->assertRE('/product_id=68/');
$this->request("cart.php",array("target" => "product","action" => "view","product_id" => "68",),array());
$this->request("cart.php",array("target" => "cart","action" => "add","product_id" => "68","category_id" => "",),array());
$this->assertRE('/Bonus price/');
$this->assertRE('/35\\.55/');
$this->assertRE('/53\\.99/');
$this->assertRE('/24\\.75/');
$this->assertRE('/53\\.99/');
$this->assertRE('/16\\.19/');

$this->request("cart.php",array(),array("target" => "cart","action" => "checkout","amount" => array("0" => "1","1"=>"1",2=>1,3=>1,4=>1), "shipping" => "2",));
$this->assertRE('/You have got bonus!/');
$this->assertRE('/You have ordered.*5.*or more items from the.*"Internet".*category/');
$this->request("cart.php",array("target" => "checkout",),array());
$this->assertRE('/184\\.47/');
$this->request("cart.php",array(),array("payment_id" => "phone_ordering","target" => "checkout","action" => "payment",));
$this->request("cart.php",array(),array("target" => "checkout","action" => "checkout","notes" => "",));
$this->assertRE('/Congratulations ! Your order has been successfully placed/');
$this->request("cart.php",array("target" => "checkout","action" => "success","order_id" => "1",),array());
$this->assertRE('/#1/');
$this->request("admin.php",array(),array("profile_form" => array("login" => "bit-bucket@rrf.ru","password" => "","confirm_password" => "","password_hint" => "","password_hint_answer" => "","access_level" => "100","status" => "E","pending_membership" => "","membership" => "","billing_title" => "Mr.","billing_firstname" => "Bit","billing_lastname" => "Bucket","billing_company" => "","billing_phone" => "0123456789","billing_fax" => "","billing_address" => "Billing street, 1","billing_city" => "Edmond","billing_state" => "38","billing_country" => "US","billing_zipcode" => "73003","shipping_title" => "Mr.","shipping_firstname" => "Bit","shipping_lastname" => "Bucket","shipping_company" => "","shipping_phone" => "9876543210","shipping_fax" => "","shipping_address" => "Shipping street, 1","shipping_city" => "Edmond","shipping_state" => "38","shipping_country" => "US","shipping_zipcode" => "73003","bonusPoints" => "100",),"profile_id" => "1","target" => "profile","action" => "modify","form" => "profile_form",));
$this->assertRE('/Profile has been updated successfully/');
$this->assertRE('/Bonus points/');
$this->request("cart.php",array("target" => "category","action" => "view","category_id" => "243",),array());

// Pay by bonus points
$this->request("cart.php",array("target" => "product","action" => "buynow","product_id" => "16123","category_id" => "243",),array());
$this->request("admin.php",array("target" => "settings","page" => "Promotion",),array());
$this->request("cart.php",array("target" => "cart",),array());
$this->request("admin.php",array(),array("bonusPointsCost" => "0.1","earnBonusPointsRate" => "0","showBonusList" => "on","allowDC" => "on","target" => "settings","action" => "update","page" => "Promotion",));

$this->request("cart.php",array(),array("payment_id" => "bonus_points","target" => "checkout","action" => "payment",));
$this->assertRE('/Pay by bonus points/');
$this->assertRE('/Please enter the number of bonus points/');
$this->assertRE('/420/');
$this->request("cart.php",array(),array("target" => "checkout","action" => "checkout","payedByPoints" => "100","priceFormat" => "$ %s",));
$this->assertRE('/31\\.95/'); // Order total:
$this->assertRE('/10\\.00/'); // Payed by bonus points:

$this->request("cart.php",array(),array("payment_id" => "phone_ordering","target" => "checkout","action" => "payment",));
$this->request("cart.php",array(),array("target" => "checkout","action" => "checkout","notes" => "hi",));
$this->request("cart.php",array("target" => "checkout","action" => "success","order_id" => "2",),array());
$this->assertRE('/#2/'); // Order id
$this->assertRE('/Bonus points discount/');
$this->assertRE('/10\\.00/');
$this->request("admin.php",array("target" => "order_list",),array());
$this->assertRE('/31\\.95/');
$this->request("admin.php",array("target" => "order","order_id" => "2",),array());
$this->assertRE('/10\\.00/');
    } // }}}

    function testGiftCertificates() // {{{
    {
$this->request("admin.php",array(),array("login" => "bit-bucket@rrf.ru","password" => "123","target" => "login","action" => "login","x" => "0","y" => "0",));
$this->request("admin.php",array(),array("active_modules" => array("4000" => "on",),"Update" => "Update","target" => "modules","action" => "update","module_id" => "","module_name" => "",));
$this->assertRE('/Gift certificate e-Cards/');
$this->assertRE('/Gift certificates/');
$this->request("admin.php",array("target" => "image_files",),array());
$this->assertRE('/e-Card thumbnails/');
$this->assertRE('/e-Card images/');
$this->request("admin.php",array("target" => "gift_certificate_ecards",),array());
$this->assertRE('/target=gift_certificate_select_ecard&action=thumbnail&ecard_id=1/');
$this->assertRE('/target=gift_certificate_select_ecard&action=thumbnail&ecard_id=2/');
$this->assertRE('/target=gift_certificate_select_ecard&action=thumbnail&ecard_id=3/');

$this->request("admin.php",array("target" => "gift_certificate_ecard",),array());
$this->assertRE('/Upload to file system/');
$this->assertRE('/Thumbnail/');
$this->assertRE('/center_image/');
$this->assertRE('/left_image/');
$this->request("admin.php",array("target" => "gift_certificates",),array());
$this->assertRE('/No gift certificates found/');
$this->request("cart.php",array("target" => "add_gift_certificate",),array());
$this->assertRE('/Add to cart/');
$this->assertRE('/Verify certificate/');
$this->assertRE('/E-Card/');
$this->request("cart.php",array(),array("target" => "add_gift_certificate","action" => "select_ecard","gcid" => "","purchaser" => "ndv","recipient" => "ndv","message" => "Hi!","amount" => "15","send_via" => "E","recipient_email" => "","recipient_firstname" => "","recipient_lastname" => "","recipient_address" => "","recipient_city" => "","recipient_zipcode" => "","recipient_state" => "0","recipient_country" => "US","recipient_phone" => "",));
list ($page, $gcid) = $this->assertRE('/gcid.*value="([A-Z0-9]{8,8})"/');
$this->request("cart.php",array("target" => "gift_certificate_ecards","gcid" => $gcid,),array());
$this->assertRE('/target=gift_certificate_ecards&action=thumbnail&ecard_id=1/');
$this->assertRE('/target=gift_certificate_ecards&action=thumbnail&ecard_id=2/');
$this->assertRE('/target=gift_certificate_ecards&action=thumbnail&ecard_id=3/');
$this->request("cart.php",array(),array("target" => "gift_certificate_ecards","action" => "update","gcid" => $gcid,"ecard_id" => "2",));
$this->assertRE("/gcid.*$gcid/");
$this->request("cart.php",array(),array("target" => "add_gift_certificate","action" => "preview_ecard","gcid" => $gcid,"purchaser" => "tester","recipient" => "ndv","message" => "Hi!","amount" => "15.00","send_via" => "E","recipient_email" => "ndv@rrf.ru","greetings" => "Hi,","farewell" => "From","border" => "lights","recipient_firstname" => "","recipient_lastname" => "","recipient_address" => "","recipient_city" => "","recipient_zipcode" => "","recipient_state" => "0","recipient_country" => "US","recipient_phone" => "",));
$this->request("cart.php",array("target" => "preview_ecard","gcid" => $gcid,),array());
$this->assertRE('/tester sent you a Gift Certificate for \\$ 15\\.00/');
$this->assertRE("/target=add_gift_certificate&gcid=$gcid/");
$this->request("cart.php",array("target" => "add_gift_certificate","gcid" => $gcid,),array());
$this->request("cart.php",array(),array("target" => "add_gift_certificate","action" => "add","gcid" => $gcid,"purchaser" => "tester","recipient" => "ndv","message" => "Hi!","amount" => "15.00","send_via" => "E","recipient_email" => "ndv@rrf.ru","greetings" => "Hi,","farewell" => "From","border" => "lights","recipient_firstname" => "","recipient_lastname" => "","recipient_address" => "","recipient_city" => "","recipient_zipcode" => "","recipient_state" => "0","recipient_country" => "US","recipient_phone" => "",));
$this->assertRE('/Amount:.*\\$ 15\\.00/');

$this->request("cart.php",array(),array("target" => "cart","action" => "checkout",));
$this->request("cart.php",array(),array("payment_id" => "phone_ordering","target" => "checkout","action" => "payment",));
$this->request("cart.php",array(),array("target" => "checkout","action" => "checkout","notes" => "hi! a test GC order here",));
$this->assertRE('/15\\.00/');
$this->assertRE("/Gift certificate # $gcid/");
$this->request("admin.php",array("target" => "order_list",),array());
$this->request("admin.php",array("target" => "order","order_id" => "1",),array());
$this->assertRE("/target=gift_certificate&gcid=$gcid/");
$this->assertRE("/hi! a test GC order here/");
$this->request("admin.php",array("target" => "gift_certificates",),array());
$this->assertRE('/selected.*Pending/');
$this->request("admin.php",array(),array("target" => "order","action" => "update","order_id" => "1","status" => "P","notes" => "hi! a test GC order here",));
$this->request("admin.php",array("target" => "gift_certificates",),array());
$this->assertRE('/selected.*Active/');
$this->request("cart.php",array("target" => "category","action" => "view","category_id" => "243",),array());
$this->request("cart.php",array("target" => "product","action" => "buynow","product_id" => "16123","category_id" => "243",),array());
$this->request("cart.php",array("target" => "cart","action" => "add","product_id" => "16123","category_id" => "243",),array());
$this->request("cart.php",array("target" => "cart",),array());
$this->request("cart.php",array(),array("target" => "cart","action" => "checkout","amount" => array("0" => "1",),"shipping" => "2",));
$this->request("cart.php",array(),array("payment_id" => "gift_certificate","target" => "checkout","action" => "payment",));
$this->request("cart.php",array("target" => "checkout",),array());
$this->request("cart.php",array(),array("target" => "checkout","action" => "checkout","gcid" => $gcid,));
$this->assertRE('/Remove GC/');
$this->assertRE('/Payed by GC/');
$this->assertRE('/15\\.00/'); // GC
$this->assertRE('/26\\.95/'); // total
$this->request("cart.php",array(),array("payment_id" => "phone_ordering","target" => "checkout","action" => "payment",));
$this->request("cart.php",array(),array("target" => "checkout","action" => "checkout","notes" => "hi",));
$this->request("admin.php",array("target" => "gift_certificates",),array());
$this->assertRE('/\\$ 0\\.00\\/\\$ 15\\.00/');
$this->assertRE('/selected.*Used/');
    } // }}}
    // }}}

	function requestImg($SELF, $GET, $POST = null) // {{{
	{
		$this->pages ++;
		$time = getmicrotime();
		$this->request($SELF, $GET, $POST);
		$this->lastPage = $this->response;
		$this->lastScript = $SELF;
		$this->loadTime = getmicrotime() - $time;
		if ($this->logging) {
			$this->log();
		}
		$images = array();
		if (preg_match_all('/src="([^"]*)"/i', $this->response, $matches)) {
			foreach ($matches[1] as $img) {
				if (substr($img, 0, 4) != 'http') {
					if (!isset($images[$img])) {
                        if (!$this->logging) echo "GET IMG $img\n";
						$this->request($img, null, null);
					}
					$images[$img] = true;
				}
			}
		}
	} // }}}

	function parseCounter($name) // {{{
	{
		if (preg_match("/<tr><td style=\"FONT-WEIGHT: bold; COLOR: red \">$name<\/td><td>([0-9.]*)<\/td><\/tr>/", $this->lastPage, $matches)) {
		//if (preg_match("/$name:[^0-9]*([0-9.]*)/", $this->lastPage, $matches)) {
			return $matches[1];
		} else {
			return 0;
		}
	} // }}}

	function log() // {{{
	{
		if ($this->lastScript == 'admin.php') return;
//		if (!isset($this->logFile)) {
//			$this->logFile = fopen("LOG", "w");
//		}
		// $counterNames = array("Total time", "PHP parser time", "Included files total size", "License check time", "Database connect time", "read config", "modules manager", "session", "Run time", "Init view time", "Display time");
		$counterNames = array("TOTAL TIME");
		$timing = array("LOAD TIME" => $this->loadTime);
		foreach ($counterNames as $name) {
			$timing[$name] = $this->parseCounter($name);
		}
		$this->totals = $this->sum($this->totals, $timing);
		$this->count ++;
//		$this->outputRow($this->logFile, $timing);
	} // }}}
	
	function outputRow($r) // {{{
	{
		foreach ($r as $i => $ri) {
			//fwrite($fd, "$i=" . sprintf("%.4f", $ri) . "\n");
			print "$i=" . sprintf("%.4f", $ri) . "\n";
		}
//		fwrite($fd, "\n");
//		flush($fd);
	} // }}}
	
	function logAvg() // {{{
	{
//		fwrite($this->logFile, str_repeat("-", 78) . "\n");
		$avg = array();
		foreach ($this->totals as $i => $ti) {
			$avg[$i] = $this->totals[$i] / $this->count;
		}
		$this->outputRow($avg);
	} // }}}
	
	function sum($a, $b) // {{{
	{
		if (!$b) return $a;
		if (!$a) return $b;
		foreach($a as $i => $ai) {
			if (isset($b[$i])) {
				$a[$i] = sprintf("%.4f", $ai+$b[$i]);
			}
		}
		return $a;
	} // }}}
	
	function request($SELF, $GET, $POST = null) // {{{
	{
		$this->queries ++;
		if ($GET) {
			$query = array();
			foreach ($GET as $name => $value) {
				$query[] = "$name=" . urlencode(stripslashes($value));
			}
			$queryString = "?" . join("&", $query);
		} else {
			$queryString = "";
		}
		if (substr($SELF, 0, 7) == "http://" || substr($SELF, 0, 7) == "https:/") {
			$url = $SELF . $queryString;
			if (substr($SELF, 0, 7) == "https:/") {
				$https = func_new("HTTPS");
				$https->method = is_null($POST) ? 'GET' : 'POST';
				$https->url = $url;
				$https->data = $POST;
				if (!$this->logging) print "https request $url\n";
				if ($https->request() == HTTPS_ERROR) {
					$this->error ($https->error);
				}
				$this->response = $https->response;
				return;
			}
		} else {
			$url = $this->url . "/" . $SELF . $queryString;
		}
		$http = new HTTP_Request($url);
		if (!is_null($POST) && count($POST)) {
			$http->_method = HTTP_REQUEST_METHOD_POST;
			if (!$this->logging) {
				print "POST PAGE $url";
				if (isset($POST["target"])) print " target=".$POST["target"];
				if (isset($POST["action"])) print " action=".$POST["action"];
				print "\n";
			}
			$this->addPostData($http, $POST);

		} else {
			if (!(strstr($url, 'image') || strstr($url, 'gif'))) {
				if (!$this->logging) {
                    // browser output
                    print "GET PAGE $url\n";
                    // FIXME: logging
                    /*
                    if ($fd = fopen("LOG.".$_ENV["UNIQUE_ID"], "a")) {
                        fwrite($fd, "GET $url\n");
                        fclose($fd);
                    }
                    */
                }    
			}
		}
		if ($this->cookie) {
			foreach ($this->cookie as $name => $value) {
				$http->addCookie($name, $value);
			}
		}
		$res = $http->sendRequest();
		if (PEAR::isError($res)) {
			$msg = $res->getMessage();
		} else {
			$msg = '';
		}
		$this->assert(!PEAR::isError($res), "Error making query to $url: $msg");
		$this->response = $http->getResponseBody();
//		$this->cookie = array();
		$cc = $http->getResponseCookies();
		if ($cc) {
			foreach ($cc as $c) {
				$this->cookie[$c["name"]] = $c["value"];
			}
		}
		if (preg_match('/^<b>(Notice:|Warning:)<\/b> (.*)$/', $this->response, $matches)) {
			$this->error($matches[0]);
		}
	} // }}}

	function assert($condition, $message = '')  // {{{
	{
		if (!$condition) $this->error($message);
	} // }}}

	function error($message) // {{{
	{
		die($message);
	} // }}}

	function assertRE($re, $findAll = false, $page = null) // {{{
	{
		if (is_null($page)) {
			$r = $this->response;
		} else {
			$r = $page;
		}
		$func = $findAll ? "preg_match_all" : "preg_match";
		if (!$func($re, $r, $matches)) {
			$fd = fopen("PAGE", "w");
			fwrite($fd, $this->response);
			fclose($fd);
			$this->error("RE doesn't match: $re");
		}
		return $matches;
	} // }}}

	function addPostData (&$http, $POST, $prefix = '') // {{{
	{
		foreach ($POST as $name => $value) {
			if ($prefix) $name = $prefix . "[$name]";
			if (is_array($value)) {
				$this->addPostData($http, $value, $name);
			} else {
				$http->addPostData($name, $value);
			}
		}
	} // }}}
}
?>
