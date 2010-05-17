<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_XCartImport_Controller_Admin_XcartImport extends XLite_Controller_Admin_Abstract
{
    public $importedProductOptions;
    
    function fillForm()
    {
        parent::fillForm();
        if ($this->config->getComplex('XCartImport.params')) {
            $this->set('properties', $this->config->getComplex('XCartImport.params'));
        } else {
            // default params
            foreach (array('hostspec',"database","username","password") as $param) {
                $this->setComplex($param, XLite::getInstance()->getOptions(array('database_details', $param)));
            }
            $this->import_users = true;
            $this->import_catalog = true;
            $this->db_version = 3;
        }
    }

    function action_cleanup()
    {
        $this->startDump();
        if (!$this->connect()) {
            echo "<br><font color=red>" . $this->error . "</font><br>";
            $this->valid = false; // invalid form
        } else {
            $res = mysql_query('show tables', $this->_connection);
            while (list($table) = mysql_fetch_row($res)) {
                if (substr($table, 0, 6) == 'xcart_') {
                    // drop table
                    echo "removing $table<br>\n";
                    func_flush();
                    mysql_query("drop table $table");
                }
            }
        }
?>
X-Cart data has been removed.<br>
<?php
            echo "<hr><a href=\"admin.php?target=xcart_import\">Return to admin interface.</a>";
            $this->set('silent', true);
    }

    function action_import()
    {
        // checkboxes
        if (!isset($this->import_catalog)) {
            $this->import_catalog = false;
        }
        if (!isset($this->import_users)) {
            $this->import_users = false;
        }
        $this->startDump();
        // remeber parameters
        $c = new XLite_Model_Config();
        $c->set('category', "XCartImport");
        $c->set('name', "params");
        $c->set('value', serialize($_POST));
        $c->update();
        if (!$this->connect()) {
            echo "<br><font color=red>" . $this->error . "</font><br>";
            $this->valid = false; // invalid form
            echo "<hr><a href=\"admin.php?target=xcart_import\">Return to admin interface.</a>";
            $this->set('silent', true);
            return;
        } else {
            $this->memberships = array();
            
            foreach ($this->config->getComplex('Memberships.memberships') as $ms) {
                $this->memberships[$ms] = true;
            }
            if ($this->import_users) {
                $this->importUsers();
            }
            if ($this->import_catalog) {
                $this->importCatalog();
            }
            // save memberships
            $ms = array_keys($this->memberships);
            $c = new XLite_Model_Config();
            $c->db->connect();
            $c->set('category', "Memberships");
            $c->set('name', "memberships");
            $c->set('value', serialize($ms));
            $c->update();
            ?>
<br>Import complete. <?php echo "<hr><a href=\"admin.php?target=xcart_import\">Return to admin interface.</a>"; ?><br><br>
You might want to remove X-Cart tables from your X-Cart database. To do this, turn the checkbox below on and click "Remove":
<form action="admin.php" method="POST" name="cleanup_form">
<input type="hidden" name="target" value="xcart_import">
<input type="hidden" name="action" value="cleanup">
<input type="hidden" name="hostspec" value="<?php echo $this->hostspec; ?>">
<input type="hidden" name="database" value="<?php echo $this->database; ?>">
<input type="hidden" name="username" value="<?php echo $this->username; ?>">
<input type="hidden" name="password" value="<?php echo $this->password; ?>">
<input type="checkbox" name="confirm"> I want to remove X-Cart tables (tables named with 'xcart_' prefix) from database <b><?php echo $this->database; ?></b> at <b><?php echo $this->hostspec; ?></b>
<p>
<input type="submit" onclick="if (!document.cleanup_form.confirm.checked) return false;" value=" Remove ">
            <?php
        }
    }

    function connect()
    {
        echo "Establishing connection to MySQL server <b>".$this->get('hostspec')."</b> as user <b>".$this->get('username')."</b> ...<br>";
        $this->_connection = @mysql_connect($this->get('hostspec'), $this->get('username'), $this->get('password'));
        if ($this->_connection === false) {
            $this->error = "Can't connect to <b>" . $this->get('hostspec') . "</b> using user name <b>" . $this->get('username') . "</b>";
        } else {
        	echo "Trying to use database <b>".$this->get('database')."</b> ...<br>";
            $db_selected = mysql_select_db($this->get('database'), $this->_connection);
            if ($db_selected === false) {
            	$this->error = "Can't connect to <b>" . $this->get('database') . "</b> database (".mysql_error().")";
            	$this->_connection = false;
            }
        }
        return $this->_connection;
    }
    
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->boolean_map = array(
            "Y" => 1,
            "y" => 1,
            "N" => 0,
            "n" => 0);

        $this->map = array(
          "category" => array(
            "categoryid" => "category_id",
            "image_x" => "image_width",
            "image_y" => "image_height",
            "category" => "name",
            "image" => "image->data",
            "image_type" => "image->type",
            "avail" => "enabled"), 
          "category.enabled" => $this->boolean_map,
          "category.membership" => "map_membership",
          "product.categoryid" => "map_categoryid",
          "product.categoryid1" => "map_categoryid",
          "product.categoryid2" => "map_categoryid",
          "product.categoryid3" => "map_categoryid",
          "product.enabled" => $this->boolean_map,
          "product.free_tax" => array(
            "Y" => "Tax free",
            "N" => ""),
          "product" => array(
            "productid" => "product_id",
            "productcode" => "sku",
            "product" => "name",
            "descr" => "brief_description", 
            "fulldescr" => "description",
            "forsale" => "enabled",
            "orderby" => "order_by",
            "free_tax" => "tax_class",
            "image" => "thumbnail->data",
            "image_type" => "thumbnail->type",
            "image2" => "image->data",
            "image2_type" => "image->type", 
           ),
          "productoption" => array(
            "classid"     => "option_id",
            "productid"   => "product_id",
            "class"       => "optclass",
            "classtext"   => "opttext",
            "orderby"     => "orderby",
            "is_modifier" => "opttype",
            "option_name" => "options"
           ),
          "productoption.product_id" => "map_productid", 
          "optionexception" => array(
            "productid" => "product_id"
           ),
          "optionexception.product_id" => "map_productid", 
          "detailedimage" => array(
            "id" => "product_id",
            "productid" => "product_id",
            "avail" => "enabled",
            "orderby" => "order_by",
            "image" => "image->data"
           ),
          "detailedimage.enabled" => $this->boolean_map,
          "detailedimage.product_id" => "map_productid", 
          "profile" => array(
            "login" => "IGNORE",
            "b_address" => "billing_address",
            "b_city" => "billing_city",
            "b_state" => "billing_state",
            "b_country" => "billing_country",
            "b_zipcode" => "billing_zipcode",
            "phone" => "billing_phone", 
            "fax" => "billing_fax",
            "title" => "billing_title",
            "firstname" => "billing_firstname",
            "lastname" => "billing_lastname",
            "s_address" => "shipping_address",
            "s_city" => "shipping_city",
            "s_state" => "shipping_state",
            "s_country" => "shipping_country",
            "s_zipcode" => "shipping_zipcode",
            "email" => "login",
            "company" => "billing_company",
          ),
          "profile.new_fields" => array(
            "billing_phone" => "shipping_phone",
            "billing_fax" => "shipping_fax",
            "billing_title" => "shipping_title",
            "billing_firstname" => "shipping_firstname",
            "billing_lastname" => "shipping_lastname",
            "billing_company" => "shipping_company"),
          "profile.password" => "map_password",
          "profile.status" => array("Y" => "E","N" => "D", "y" => "E", "n" => "D"),
          "products_categories.categoryid" => "map_categoryid",
          "products_categories.productid" => "map_productid",
        );
    }

    function importCatalog()
    {
        $this->categoriesHash = array();
    	$sqlQuery = "SELECT *, c.categoryid FROM xcart_categories c LEFT OUTER JOIN";
        
        if ($this->get('db_version') == '41') {
            $sqlQuery .= " xcart_images_C i ON c.categoryid=i.id LEFT OUTER JOIN xcart_category_memberships cm ON c.categoryid=cm.categoryid LEFT OUTER JOIN xcart_memberships m ON m.membershipid=cm.membershipid";
        } else {
            $sqlQuery .= " xcart_icons i ON c.categoryid=i.categoryid";
        }
       
        if ($this->get('db_version') != 3) {
        $sqlQuery .= " order by c.categoryid_path";
        }
        $this->importTable($sqlQuery, "category");
        
        $sqlQuery = "SELECT p.*, t.*, t.image as image2, t.image_type as image2_type, pr.price, p.productid FROM xcart_products as p LEFT OUTER JOIN";
        if ($this->get('db_version') == '41') {
            $sqlQuery .= " xcart_images_T as t ON p.productid=t.id LEFT OUTER JOIN xcart_pricing as pr ON p.productid=pr.productid AND pr.quantity=1 AND pr.membershipid='0'";
        } else {
            $sqlQuery .= " xcart_thumbnails t on p.productid=t.productid left outer join xcart_pricing pr on p.productid=pr.productid and pr.quantity=1 and pr.membership=''";
        }
        $this->importTable($sqlQuery, "product");

        if ($this->get('db_version') != 3) {
        	$this->importTable("select * from xcart_products_categories", "products_categories");
        }

        if (!is_null($this->xlite->getComplex('mm.activeModules.ProductOptions'))) {
            $this->importedProductOptions = array();
            $this->importTable("SELECT c.classid, c.productid, c.class, c.classtext, c.orderby, c.is_modifier, o.option_name FROM xcart_classes as c LEFT OUTER JOIN xcart_class_options as o ON c.classid = o.classid;", "productoption");
            $this->importTable("select * from xcart_product_options_ex", "optionexception");
        }
        if (!is_null($this->xlite->getComplex('mm.activeModules.DetailedImages'))) {
            if ($this->get('db_version') == '41') {
                $this->importTable("select * from xcart_images_D", "detailedimage");
            } else {
                $this->importTable("select * from xcart_images", "detailedimage");
            }
        }
    }

    function importUsers()
    {
         $this->importTable("select * from xcart_customers", "profile");
    }

    function splitCategoryName($name) 
    {
        $pos = strrpos($name, '/');
        if ($pos) {
            // compound name
            $parentName = substr($name,0,$pos);
            $name = substr($name, $pos+1);
            return array($parentName, $name);
        }
        return false;
    }

    function importTable($sql, $path)
    {
        echo "<br><b>Importing $path: </b>";
        $result = mysql_query($sql, $this->_connection);
        if (!$result) {
            echo mysql_error($this->_connection) . "\n";
            return false;
        }
        while ($row = mysql_fetch_assoc($result)) {
            // uniform
            $data = array(
                    "value" => $path,
                    "children" => $this->childrenFromAssoc($row));
            $this->data = $data;
            $this->mapData($data, $path);
            $this->saveRow($this->childrenToAssoc($data['children']), $path);
        }
        echo "<b> Done.</b><br>";
        return true;
    }

    function childrenFromAssoc($assoc)
    {
        $data = array();
        foreach ($assoc as $key => $value) {
            $data[] = array("value" => $key, "children" => array(array("value" => $value, "children" => array())));
        }
        return $data;
    }

    function childrenToAssoc($children)
    {
        $assoc = array();
        foreach ($children as $child) {
            $assoc[$child['value']] = $child['children'][0]['value'];
        }
        return $assoc;
    }

    function saveRow($row, $path)
    {
        if ($path != 'products_categories') {
            $obj = new $path();
    		if (is_object($obj)) {
    			// connecting to LC database
    			$obj->db->connect();
    		}
        }

        if ($path == 'products_categories') {
            $obj = new XLite_Model_Product();
    		if (is_object($obj)) {
    			// connecting to LC database
    			$obj->db->connect();
    		}
            if ($obj->find("product_id='" . $row['productid'] . "'")) {
            	$c = new XLite_Model_Category();
            	if ($c->find("category_id='" . $row['categoryid'] . "'")) {
        			$obj->addCategory($c);
                    $obj->update();
            	}
            }
            unset($obj);
        } else if ($path == 'product') {
            if (empty($row['sku'])) {
                $cond = "name='".addslashes($row['name'])."'";
            } else {
                $cond = "name='".addslashes($row['name'])."' AND sku='' OR sku='".addslashes($row['sku'])."'";
            }
        } else if ($path == 'category'){
        	if ($this->get('db_version') != 3) {
        		$this->categoriesHash[$row['category_id']] = $row['name'];
        		if (strcmp(strval($row['categoryid_path']), strval($row['category_id'])) != 0) {
        			$categories_path = explode('/', $row['categoryid_path']);
        			foreach ($categories_path as $cat_path_key => $cat_path) {
        				$categories_path[$cat_path_key] = $this->categoriesHash[$cat_path];
        			}
                    $row['name'] = implode('/', $categories_path);
        		}
        	}
            $categoryName = $row['name'];
            if (substr($categoryName, strlen($categoryName) - 1, 1) != "/") {
            	$categoryName .= "/";
            }
            $obj = $obj->createRecursive($categoryName);
            if ($obj->get('name')=='') {
                die("name=".$row['name']." catid=".$obj->get('category_id'));
            }
            $row['name'] = $obj->get('name');
            $cond = "category_id='".addslashes($obj->get('category_id'))."'";
        } else if ($path == 'profile'){
            foreach ($row as $key=>$val) {
                $key = trim($key);

                if (in_array($key, array('billing_state', "shipping_state"))) {
                    if (method_exists($obj, "_convertState")) {
                        $state_code = $obj->_convertState($val);

                        if ($state_code < 0) {
                            // If import state code not exists, set custom_state for profile
                            preg_match("/^(\w+)_state$/", $key, $out);
                            $prefix = $out[1];
                            $row[$prefix."_custom_state"] = $val;
                        }
                    } else {
                        // for LC version lower than 2.2
                        $state = new XLite_Model_State();
                        if ($state->find("code='$val'") || $state->find("state='$val'")) {
                            $state_code = $state->get('state_id');
                        } else {
                            $state_code = -1;
                        }
                    }

                    $row[$key] = $state_code;
                }
            }

            $cond = "login='".addslashes($row['login'])."'";
        } else if ($path == 'productoption') {
            $optid = $row['option_id'];
            $cond  = "product_id='$row[product_id]' AND optclass='$row[optclass]'";
            if (in_array($optid, $this->importedProductOptions) && $obj->find($cond)) {
                $obj->set('options', $obj->get('options')."\n".$row['options']);
                $obj->update();
                unset($obj);
            } else {
                $this->importedProductOptions[] = $optid;
            }
            $row['opttype'] = $row['options']=='' ? 'Text' : 'SelectBox';
            $row['cols'] = 25;
        } else if ($path == 'optionexception') {
            $cond = "product_id='$row[product_id]' AND exception='$row[exception]'";
        } else if ($path == 'detailedimage') {
            $cond = "";
        } else {
            XLite::getInstance()->doGlobalDie("Incorrect path $path");
        }

        if (is_object($obj)) {
            if (isset($row[$obj->autoIncrement])) {
                $id = $row[$obj->autoIncrement];
    			if (isset($row[$obj->autoIncrement])) {
    				unset($row[$obj->autoIncrement]);
    	        }
            } else {
                $id = 0;
            }
    		if (array_key_exists($obj->autoIncrement, $row)) {
    			if (isset($row[$obj->autoIncrement])) {
    				unset($row[$obj->autoIncrement]);
    			}
    		}
            if ($cond && $obj->find($cond)) {
                if ($id) {
                    $this->id_map[$path][$id] = $obj->get($obj->autoIncrement);
                }
                $obj->set('properties', $row);
                $obj->update();
            } else {
                $obj->set('properties', $row);
                $obj->create();
                if ($id) {
                    $this->id_map[$path][$id] = $obj->get($obj->autoIncrement);
                }
            }
            foreach ($row as $key => $value) {
                if (strpos($key, '->')) {
                    list($prop, $key) = explode('->', $key);
                    $getter = "get$prop";
                    $o = $obj->$getter();
                    $o->setComplex($key, $value);
                    $o->update();
                }
            }
            if ($path == 'product') {
                foreach ($this->category_links as $cat_id) {
                    $obj->addCategory( new XLite_Model_Category($cat_id));
                }
                $this->category_links = array();
            }
            if ($id) {
                echo "$id,\n";
            }
        }

        // restoring DB connection
        $this->connect();
    }

    function mapData(&$data,$path)
    {
        $newfields = array();
        if (array_key_exists($path, $this->map)) {
            $map = $this->map[$path];
            if (isset($this->map[$path.".new_fields"])) {
                $newfields = $this->map[$path.".new_fields"];
            }
            if (is_array($map)) {
                // map each element of $data
                $children = $data['children'];
                for ($i=0; $i<count($children); $i++) {
                    if (array_key_exists($children[$i]['value'], $map)) {
                        // there is a mapping for this value
                        $this->mapScalar($children[$i], $map[$children[$i]['value']]);
                    }
                    if (array_key_exists($children[$i]['value'], $newfields)) {
                        $child = array('value', $newfields[$children[$i]['value']], "children" => $children[$i]['children']);
                        $children[] = $child;
                    }
                }
            } else {
                // map a single value
                $this->mapScalar($data, $map);
            }
        }
        $children = $data['children'];

        foreach ($newfields as $originalField => $newField) {
            for ($i=0; $i<count($children); $i++) {
                if ($children[$i]['value'] == $originalField) {
                	$childrenNew = $children[$i];
                	$childrenNew['value'] = $newField;
                	$children[] = $childrenNew;
                	break;
                }
            }
        }

        for ($i=0; $i<count($children); $i++) {
            $childPath = $path . "." . $children[$i]['value'];
            $this->mapData($children[$i], $childPath);
        }
    }

    function mapScalar(&$value, $mapping)
    {
        if (substr($mapping,0,4) == 'map_') {
            $this->$mapping($value); // call a mapping function
            return;
        }
        $value['value'] = $mapping; // replace by constant
    }

    function map_categoryid(&$id)
    {
        $this->map_id('category', $id);
        if ($id['children'][0]['value']) {
            $this->category_links[] = $id['children'][0]['value'];
        }
    }
    
    function map_productid(&$id)
    {
        $this->map_id('product', $id);
    }

    function map_profileid(&$id)
    {
        return $this->map_id('profile', $id);
    }
    
    function map_id($class, &$v) 
    {
        $id = $v['children'][0]['value']; // get a single value
        if ($id) {
            if (isset($this->id_map[$class][$id])) {
                $v['children'][0]['value'] = $this->id_map[$class][$id];
            }
        }
    }
    
    public $category_links = array();

    function map_membership(&$data)
    {
        $ms = $data['value'];
        $this->memberships[$ms] = true;
    }
    
    function map_password(&$data)
    {
        $password = $this->text_decrypt($data['children'][0]['value']);
        $data['children'][0]['value'] = md5($password);
    }

    function text_decrypt_symbol($s, $i) 
    {
        // $s is a text-encoded string, $i is index of 2-char code. function returns number in range 0-255
        global $START_CHAR_CODE;

        return (ord(substr($s, $i, 1)) - $START_CHAR_CODE)*16 + ord(substr($s, $i+1, 1)) - $START_CHAR_CODE;
    }

    function text_decrypt($s) 
    {
        global $START_CHAR_CODE, $CRYPT_SALT;
        $START_CHAR_CODE = 100;
        $CRYPT_SALT = 85;
        if ($s == "")
            return $s;
        $enc = $CRYPT_SALT ^ $this->text_decrypt_symbol($s, 0);
        $result = '';
        for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
            $result .= chr($this->text_decrypt_symbol($s, $i) ^ $enc++);
            if ($enc > 255)
                $enc = 0;
        }
        return $result;
    }

}
