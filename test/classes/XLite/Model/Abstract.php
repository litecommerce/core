<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Delimiters definitions. Used for import / export store data.
*/
$GLOBALS['DATA_DELIMITERS'] = array("semicolon"=>";","comma"=>",","tab"=>"\t");
$GLOBALS['TEXT_QUALIFIERS'] = array("double_quote"=>'"',"single_quote"=>"'");

/**
* Base class is an abstract class for all database-mapped objects.
*
* @package Base
* @access public
* @version $Id$
*/
class XLite_Model_Abstract extends XLite_Base
{
    // properties {{{

    /**
    * The SQL database table alias for object
    * @var string $alias
    */
    protected $alias;

    /**
    * Object properties: field => default value
    * @var array $fields
    */
    protected $fields = array();

    /**
    * Object properties values
    * @var array $properties
    */
    protected $properties = array();

    /**
    * Primary key field names
    * @var array $primaryKey
    */
    protected $primaryKey = array(); 

    /**
    * Auto-increment field name. Used as a primary key too.
    * @var string $autoIncrement
    */
    protected $autoIncrement = null;
    
    /**
    * Shows whether the object data have been read from DB or not
    * @var boolean $isRead
    */	
    public $isRead = false;

    /**
    * Checks whether the object data exists in DB
    * @var boolen $isPersistent
    */	
    public $isPersistent = false;
    
    /**
    * Contains SQL ORDER clause used by default by findAll() if no order
    * is specified.
    * @var string $defaultOrder
    */
    protected $defaultOrder;

    /**
    * Contains SQL WHERE clause used by default by findAll()
    * @var string $_range
    */	
    public $_range;

    /**
    * If set to true, findAll will fetch only primary keys (isRead=false)
    */	
    public $fetchKeysOnly = false;

    /**
    * If set to true, findAll will fetch only object' indexes
    */	
    public $fetchObjIdxOnly = false;
    
    // }}}

    /**
    * Constructs a new database object. The options argument list
    * is a primary key value. If it is specified, the object is
    * created as isPersistent, otherwise - !isPersistent.
    *
    * @access public
    */
    public function __construct() // {{{
    {
        parent::__construct(); 
        if (!empty($this->autoIncrement)) {
            // if auto-increment is specified, make it a primary key
            // of this table
            $this->primaryKey = array($this->autoIncrement);
        }
        $args = func_get_args();
        if (count($args)) {
            for ($i=0; $i<count($this->primaryKey); $i++) {
                if (isset($args[$i]) && !is_null($args[$i])) {
					if ($this->primaryKey[$i] == $this->autoIncrement) {
						if (!is_numeric($args[$i])) $args[$i] = 0;
					}
                    $this->set($this->primaryKey[$i], $args[$i]);
                }
            }
        }
    } // }}}

    /**
    * Returns the SQL database table name for this object. Uses "alias"
    * property as a key.
    *
    * @access public
    * @return string The SQL database table name
    */
    function getTable() // {{{
    {
        return XLite_Model_Database::getInstance()->getTableByAlias($this->alias);
    } // }}}

    /**
    * Returns the properties of this object. Reads the object from database
    * if necessary.
    *
    * @access public
    * @return array The properties array
    */
    function getProperties() // {{{
    {
        if (!$this->isRead && $this->isPersistent) {
            $this->isRead = $this->read();
        }
        return $this->properties;
    } // }}}
    
    /**
    * Attempts to find the database record for this object and fill
    * the object properties with data found.
    *
    * @access public
    * @param string $where The SQL WHERE statement to find object for
    * @return boolean True if database record for this object found,
    *                 false otherwise
    */
    function find($where, $order = null) // {{{
    {
        $where = $this->_buildWhere($where);
        $this->sql = $this->_buildSelect($where, $order); // build select query
        $result = $this->db->getRow($this->sql);
        if (is_null($result)) {
            return false;
        } else {
            $this->_updateProperties($result);
            return $this->filter();
        }    
    } // }}}

    function filter()
    {
        return true;
    }

    /**
    * Attempts to read All database records for this class.
    *
    * @access public
    * @param string $where The SQL WHERE statement to find data
    * @param string $orderby The SQL ORDER BY statement to sort data
    * @return array The array of this class objects.
    */
    function findAll($where = null, $orderby = null, $groupby = null, $limit = null) // {{{
    {
        // apply the default order
        if (empty($orderby)) {
            if (!empty($this->defaultOrder))
                $orderby = $this->defaultOrder;
        }
        $where = $this->_buildWhere($where);
        // build select query
        $this->sql = $this->_buildSelect($where, $orderby, $groupby, $limit); 
        $result = $this->db->getAll($this->sql);
        if (!is_array($result)) {
            $this->_die ($this->sql.": wrong result");
        }
        $objects = array();
        // create class instance for every row found
        $rows_number = count($result);
        for ($row_key=0; $row_key<$rows_number; $row_key++) {
            $class = get_class($this);
            $object = new $class();
            if ($this->fetchKeysOnly) {
                $object->isPersistent = true;
                $object->isRead = false;
                $object->properties = $result[$row_key];
                $object_key = array("class" => $class, "data" => $result[$row_key]);
            } else {
                $object->isPersistent = true;
                $object->isRead = true;
                $instance = $object->_updateProperties($result[$row_key]);
				if (is_object($instance)) {
					$instance->_updateProperties($result[$row_key]);
			        $object = $instance;
				} 
            }
            if ($object->filter()) {
            	if ($this->fetchKeysOnly) {
            		if ($this->fetchObjIdxOnly) {
                    	unset($object);
                    	$objects[] = $object_key;
                    	unset($object_key);
                    } else {
                    	$objects[] = $object;
                    	unset($object);
                    }
                } else {
                	$objects[] = $object;
                	unset($object);
                }
            }
        }
        return $objects;
    } // }}}

    function isObjectDescriptor(&$descriptor) {
        if (is_array($descriptor) && isset($descriptor["class"]) && isset($descriptor["data"])) {
        	return true;
        }
		
		return false;
    }

    function descriptorToObject(&$descriptor) {
        if (is_array($descriptor) && isset($descriptor["class"]) && isset($descriptor["data"])) {
        	$object = new $descriptor["class"];
            $object->isPersistent = true;
            $object->isRead = false;
            $object->properties = $descriptor["data"];
            return $object;
        }

        return null;
    }

    function iterate($where = null, $orderby = null, $groupby = null, $limit = null) // {{{
    {
        // apply the default order
        if (empty($orderby)) {
            if (!empty($this->defaultOrder))
                $orderby = $this->defaultOrder;
        }
        $where = $this->_buildWhere($where);
        // build select query
        $this->sql = $this->_buildSelect($where, $orderby, $groupby, $limit); 
		$result = XLite_Model_Database::getInstance()->getAll($this->sql);
        if (!is_array($result)) {
            $this->_die ($this->sql.": ".$result->getMessage());
        }
		return $result;
	}

	function next(&$result)
    {
        do {
            $row = array_shift($result);
            if ($row === null) {
                return false;
            }
            if ($this->fetchKeysOnly) {
                $this->isPersistent = true;
                $this->isRead = false;
                $this->properties = $row;
            } else {
                $this->properties = array();
	            $this->_updateProperties($row);
            }
        } while (!$this->filter());
        return true;
    } // }}}

    /**
    * Wrapps findAll() method with the default arguments.
    *
    * @access public
    * @return array The array of class objects
    */
    function readAll() // {{{
    {
        return $this->findAll();
    } // }}}
    
    /**
    * Reads the database data for this object. Dies for non-persistens
    * objects (object which are not exist in database)
    *
    * @access public
    * @return boolean True if data found / false otherwise
    */
    function read() // {{{
    {
        // read data for persisten object
        if ($this->isPersistent) {
            // build select query
            $this->sql = $this->_buildRead();
            $result = $this->db->getRow($this->sql);
            if (!is_null($result)) {
                $this->_updateProperties($result);
                if (!$this->filter()) {
                    $this->properties = $this->fields; // default properties
                } else {
                    return true;
                }
            }
            return false;
        }
        // die otherwise
        else {
            $this->_die("Unable to read unspecified row for $this->alias");
       }
    } // }}}
        
    function _aggregate($field, $aggregate, $where = null) // {{{
    {
        $sql = "SELECT $aggregate($field) FROM " . $this->getTable();
        if (isset($where)) {
            $sql .= " WHERE $where";
        }
        return $this->db->getOne($sql);
    } // }}}

    function count($where = null, $field = "*") // {{{
    {
        return $this->_aggregate($field, "COUNT", $where);
    } // }}}

    function min($field, $where = null) // {{{
    {
        return $this->_aggregate($field, "MIN", $where);
    } // }}}

    function max($field, $where = null) // {{{
    {
        return $this->_aggregate($field, "MAX", $where);
    } // }}}

    function avg($field, $where = null) // {{{
    {
        return $this->_aggregate($field, "AVG", $where);
    } // }}}

    /**
    * Creates an associative array with index = object primary key.
    * 
    * @access public
    * @param array $ar The array of objects
    * @param string field The field to use as an index
    * @return array The associative array
    */
    function _assocArray(&$ar, $field) // {{{
    {
        $result = array();
        for ($i=0; $i<count($ar); $i++) {
    		if (is_array($ar[$i]) && isset($ar[$i]["class"]) && isset($ar[$i]["data"])) {
        		$object = new $ar[$i]["class"];
                $object->isPersistent = true;
                $object->isRead = false;
                $object->properties = $ar[$i]["data"];
                $ar[$i] = $object;
    		}
            $result[$ar[$i]->get($field)] = $ar[$i];
        }
        return $result;
    } // }}}
 
    /**
    * Checks whether the database record exists for this object
    *
    * @access public
    * @return boolean True if record exists for object / false otherwise
    */
    function isExists() // {{{
    {
        if (!$this->isRead) {
        	$this->isRead = $this->read();
            return $this->isRead;
        }
        return true;
    } // }}}

    /**
    * Updated the database record for this object.
    *
    * @access public
    */
    function update() // {{{
    {
        $this->_beforeSave();
        // updated data for the persistent object
        if ($this->isPersistent) {
            $this->sql = $this->_buildUpdate();
            if ($this->sql !== false) {
                return $this->db->query($this->sql);
            }
            return false;
        }
        // die otherwise
        $this->_die("Unable to update unspecified row for " . $this->alias);
    } // }}}

    /**
    * Creates the database record for this object.
    *
    * @access public
    */
    function create() // {{{
    {
        $this->_beforeSave();
        // create record
        if (!$this->isPersistent || empty($this->autoIncrement)) {
            // build INSERT sql query
            $this->sql = $this->_buildInsert();
            $result = $this->db->query($this->sql);
            // get auto_increment field value
            if (!empty($this->autoIncrement)) {
                $this->set($this->autoIncrement, mysql_insert_id($this->db->connection));
            }
            // fill unspecified fields with default values
            foreach ($this->fields as $field => $default) {
                if (!array_key_exists($field, $this->properties)) {
                    $this->properties[$field] = $default;
                }
            }    
            $this->isPersistent = true;
            $this->isRead = true;
            return $result;
        }    
        // die otherwise
        $this->_die("Unable to insert duplicate row for " . $this->alias . " " . join(',', $this->primaryKey));
    } // }}}
    
    /**
    * Clones an existing record. Only available on 
    * auto-incremented primary keys.
    */
    function cloneObject() // {{{
    {
        if ($this->autoIncrement) {
            $this->isRead = $this->read();
			// FIXME - check this code
			$new = clone $this;
    		/*if (func_is_php5()) {
            	//$new = clone $this;
            	eval("\$new = clone \$this;");
            } else {
				$new = new self;
				$new->set("properties", $this->get("properties"));
            }*/
            $new->set($this->autoIncrement, null);
            $new->create();
            return $new;
        } else {
            $this->_die("Can't clone non-autoincremented object");
        }
    } // }}}

    /**
    * A function called at the start of each create() and update()
    * This function is empty in this implementation and overridden
    * in descendant classes
    */
    function _beforeSave() {}

    /**
    * Deletes the database record for this object.
    *
    * @access public
    */
    function delete() // {{{
    {
        // delete if object is persistent
        if ($this->isPersistent) {
            $this->sql = $this->_buildDelete();
            $this->db->query($this->sql);
            $this->isPersistent = false;
            $this->isRead = false;
            return;
        }
        // die otherwise
        $this->_die("Unable to delete unspecified row from " . $this->alias);
    } // }}}

    /**
    * Returns the specified property of this object. Read the object data
    * from dataase if necessary.
    *
    * #access public
    * @param string $property The property name
    * @return mixed The property value
    */
    function get($property) // {{{
    {
        // default value
        $value = null;
        
        // check whether the property exists
        if (array_key_exists($property, $this->fields))
        {
            // return persisten object property value
            if ($this->isPersistent) {
                // read object data if necessary
                if (!$this->isRead && $property != $this->autoIncrement) {
                    $this->isRead = $this->read();
                }
            }
            // if object is not persisten but property set
            if (isset($this->properties[$property])) {
                $value = $this->properties[$property];
            }
            // return the default property value otherwise
            else {
                $value = $this->fields[$property];
            }
        } else {
            $value = parent::get($property);
        }
        return $value;
    } // }}}

    /**
    * Sets the specified property value/
    *
    * @access public
    * @param string $property The property name
    * @param mixed $value The property value
    */
    function set($property, $value) // {{{
    {
        if (!is_scalar($property)) {
            $this->_die($property . " not a scalar");
        }
        if (array_key_exists($property, $this->fields)) {
            // set isRead to FALSE if object has not been read yet
            if (array_search($property, $this->primaryKey)) {
                $this->isRead = false; 
            }
            if (is_null($value)) {
                if (isset($this->properties[$property])) {
                	unset($this->properties[$property]);
                }
            } else {    
                $this->properties[$property] = $value;
            }    
            $this->isPersistent = $this->_allKeysSet();
        } else {
            parent::set($property, $value);
        }
    } // }}}

    /**
    * Checks whether the all primary keys for this object are set or not.
    *
    * @access private
    * @return boolean True if all keys are set / false otherwise
    */
    function _allKeysSet() // {{{
    {
        foreach ($this->primaryKey as $field) {
            if (!isset($this->properties[$field]) || 
                strlen($this->properties[$field]) == 0) {
                return false;
            }    
        }
        return true;
    } // }}}
    
    /**
    * Builds the SQL INSERT statement query for this object properties.
    * 
    * @access private
    * @return string The INSERT sql statement
    */
    function _buildInsert() // {{{
    {
        $properties = $this->properties;
        if (!empty($this->autoIncrement)) {
            // remove auto increment field
            if (isset($properties[$this->autoIncrement])) {
            	unset($properties[$this->autoIncrement]);
            }
        }
        $fields = implode(", ", array_keys($properties));
        $values = array_values($properties);
        for ($i=0; $i<count($values); $i++) {
            $values[$i] = "'".addslashes($values[$i])."'";
        }
        $values = implode(',', $values);
        $table = $this->getTable();
        return "INSERT INTO $table ($fields) VALUES ($values)";
    } // }}}
    
    /**
    * Builds the SQL SELECT statement for this object.
    *
    * @access private
    * @param string $where The SQL WHERE statement for select
    * @param string $orderby the The SQL ORDER BY statement for select
    * @return string The SELECT statement
    */
    function _buildSelect($where = null, $orderby = null, $groupby = null, $limit = null) // {{{
    {
        if (!$this->fetchKeysOnly) {
			$fields = array_keys($this->fields);
            $fields = implode(",", $fields);
        } else {
            $fields = implode(",", $this->primaryKey);
        }
        $table = $this->getTable();
        $sql = "SELECT $fields FROM $table";
        if (!empty($where)) {
            $sql .= " WHERE $where";
        }
        if (!empty($groupby)) {
            $sql .= " GROUP BY $groupby";
        }
        if (!empty($orderby)) {
            $sql .= " ORDER BY $orderby";
        }
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }
        return $sql;
    } // }}}
    
    function _buildWhere($where) // {{{
    {
        // apply the default range
        if (!empty($this->_range)) {
            if (empty($where)) {
                $where = $this->_range;
            } else {
                $where = "$this->_range AND $where";
            }
        }    
        return $where;
    } // }}}
    
    /**
    * Builds SQL query for reading the database data for this object
    * by primary key.
    *
    * @access private
    * @return string the SQL SELECT statement
    */
    function _buildRead() // {{{
    {
        $condition = array();
        foreach ($this->primaryKey as $field) {
            $condition[] = "$field='".addslashes($this->properties[$field])."'";
        }
        $condition = implode(" AND ", $condition);
        $fko = $this->fetchKeysOnly;
        $this->fetchKeysOnly = false;
        $sql = $this->_buildSelect($condition);
        $this->fetchKeysOnly = $fko;
        return $sql;
    } // }}}

    /**
    * Builds the SQL DELETE statement to delete this object database record.
    *
    * @access private
    * @return string The SQL DELETE statement
    */
    function _buildDelete() // {{{
    {
        $condition = array();
        foreach ($this->primaryKey as $field) {
            $condition[] = "$field='".addslashes($this->properties[$field])."'";
        }
        $condition = implode(" AND ", $condition);

        $table = $this->getTable();
        return "DELETE FROM $table WHERE $condition";
    } // }}}

    /**
    * Builds the SQL UPDATE statement for updating this object database record.
    *
    * @access private
    * @return string The SQL DELETE statement
    */
    function _buildUpdate() // {{{
    {
        $properties = $this->properties;
        $condition = array();
        foreach ($this->primaryKey as $field) {
			$condition[] = $field . ' = \'' . (isset($properties[$field]) ? addslashes($properties[$field]) : '') . '\'';
			// remove primary keys
			unset($properties[$field]);
        }
        $condition = implode(" AND ", $condition);
        $values = array(); // compile 'set' clause
        if (is_array($properties)) {
            foreach ($properties as $field => $val) {
                $values[] = "$field='".addslashes($val)."'";
            }
        }
        if (!$values) {
            return false;
        }
        $values = implode(',', $values);
        $table = $this->getTable();
        return 'UPDATE ' . $table . ' SET ' . $values . ' WHERE ' . $condition;
    } // }}}
    
    /**
    * Updates the object properties with specified array values. Sets
    * persistent and read flags.
    *
    * @access private
    * @param array $properties The properties values array
    */
    function _updateProperties(array $properties = array()) // {{{
    {
        if (!empty($properties) && sizeof($properties)) {
            foreach ($properties as $key => $value) {
                if (array_key_exists($key, $this->fields) && 
                    !array_key_exists($key, $this->properties))
                {
                    $this->properties[$key] = $properties[$key];
                }
            }
            $this->isPersistent = true;
            $this->isRead = true;
        }
    } // }}}
    
    /**
    * Enables the object - sets "enabled' property to 1.
    * @access public
    */
    function enable() // {{{
    {
        $this->set("enabled", 1);
    } // }}}

    /**
    * Disables the object - sets "enabled' property to 0.
    * @access public
    */
    function disable() // {{{
    {
        $this->set("enabled", 0);
    } // }}}

    /**
    * Compares the property name specified by $prop with $val and
    * returns true if equals, false otherwise. Useful in templates.
    *
    * @access public
    */
    function isSelected($property, $value = null, $prop = null) // {{{
    {
        if (is_object($value)) {
            return $this->get($property) == $value->get($prop);
        }
        return $this->get($property) == $value; 
    } // }}}

    /**
    * Calculates MD5 hash based on the object properties.
    *
    * @access public
    */
    function md5() // {{{
    {
        return md5(implode('', $this->getProperties()));
    } // }}}

    /**
    * Prints HTML dump of object properties. Useful for debug.
    */
    function dump() // {{{
    {
        echo "<p><pre>"; print_r($this->getProperties()); echo "</pre></p>";
    } // }}}

    function toXML() // {{{
    {
        return $this->fieldsToXML();
    } // }}}

    function fieldsToXML() // {{{
    {
        $xml = "";
        $values = $this->getProperties();
        foreach ($values as $name => $value) {
            if (!strlen(trim($value))) {
                continue;
            } elseif (is_numeric($value)) {
            } else {
                $value = "<![CDATA[$value]]>";
            }
            $xml .= "<$name>$value</$name>\n";
        }
        return $xml;
    } // }}}

    function toCSV() // {{{
    {
    } // }}}

    function toString() // {{{
    {
    } // }}}

    // IMPORT/EXPORT methods {{{

    function import(array $options) // {{{
    {
        global $DATA_DELIMITERS, $TEXT_QUALIFIERS;

		$this->importError = "";
		is_array($options) or $this->importError = "Invalid import options.";
		if ($this->importError) {
			if ($options['return_error']) return false;
			else die($this->importError);
		}

        $file = $options["file"];
		$handle = fopen($file, 'r') or $this->importError = "Failed to open import file $file.";
		if ($this->importError) {
			if ($options['return_error']) return false;
			else die($this->importError);
		}

        if (!empty($options["delimiter"])) {
            $options["delimiter"] = $DATA_DELIMITERS[$options["delimiter"]];
        }
        $qualifier = null;
        if (!empty($options["text_qualifier"])) {
            $qualifier = $TEXT_QUALIFIERS[$options["text_qualifier"]];
        }
        $layout = $options["layout"];
        
        $this->lineNo = 1;
        $line_buffer = "";
        while ($line = fgets($handle, 4096)) {
            $error = "";
            if (strlen($line_buffer) > 0) {
            	$line = $line_buffer . $line;
            }
            $columns = func_parse_csv($line, $options["delimiter"], $qualifier, $error);
            if (is_null($columns) && $error == "Unexpected end of line; $qualifier expected") {
            	$line = str_replace("\r\n", " ", $line);
            	$line = str_replace("\n", " ", $line);
            	$line_buffer = $line;
            	continue;
            } elseif (is_null($columns)) {
				$this->importError = "CVS syntax error in line ".$this->lineNo.": $error.";
				if ($options['return_error']) return false;
				else die($this->importError);
            }
        	$line_buffer = "";
            $properties = array();
			$layout_idx = 0;
            for ($i = 0; $i < count($layout); $i++) {
                if ($layout[$i] != "NULL") {
					array_key_exists($layout_idx, $columns) or $this->importError = "Invalid CSV file: column count does not match.";
					if ($this->importError) {
						if ($options['return_error']) return false;
						else die($this->importError);
					}
                    $properties[$layout[$i]] = $columns[$layout_idx];
                    $layout_idx ++;
                }    
            }   
            $options["properties"] = $properties;
            $this->_import($options);
            $this->lineNo ++;
        }
    } // }}}

    function getImportFields($layout = null) // {{{
    {
        isset($this->importFields) or die("importFields property undefined");

        $fields = $this->importFields;
        $result = array();
        foreach ($fields as $field) {
            $result[] = $fields;
        }
        if (!is_null($layout)) {
            if (isset($this->config->ImportExport->$layout)) {
                $layout = explode(',', $this->config->ImportExport->$layout);
                foreach ($result as $id => $fields) {
                    if (isset($layout[$id])) {
                        $selected = $layout[$id];
                        if (array_key_exists($selected, $result[$id])) {
                            $result[$id][$selected] = true;
                        }    
                    }
                }
            }
        }
        return $result;
    } // }}}

    function _import(array $options) // {{{
    {
        $this->_die("Base::_import() method should be overridden");
    } // }}}
    
    function _export($layout, $delimiter) // {{{
    {
        $this->_die("Base::_export() method should be overridden");
    } // }}}
    
    function export($layout, $delimiter, $where = null, $orderby = null, $groupby = null) // {{{
    {
        $count = $this->count() or die("There is nothing to export (empty data)");
        $processed = 0;
        $limit = 10;
        do {
            $limit_sql = "$processed, $limit";
            $items = $this->findAll($where, $orderby, $groupby, $limit_sql);
            $items_number = count($items);
            for ($i=0; $i<$items_number; $i++) {
				print ($export_csv_string = func_construct_csv($items[$i]->_export($layout, $delimiter), $delimiter, '"'));
            	if (strlen($export_csv_string) > 0) {
                	print "\n";
                }
            }
            $processed += $limit;
            $items = array();
        } while($processed < $count);
        return true;
    } // }}}

    function _stripSpecials($value) // {{{
    {
        return $value;
    } // }}}

    function _unslashProperties(&$properties, $qualifier = null) // {{{
    {
        foreach ($properties as $name => $value) {
            if (!is_null($qualifier)) {
                // strip start/end quotes
                $value = preg_replace("/(^$qualifier)(.*)($qualifier$)/", "\\2", trim($value));
            }
            // remove double quotes
            $properties[$name] = str_replace("\"\"", "\"", $value);
        }
    } // }}}

    // END IMPORT/EXPORT methods }}}

    function formatCurrency($price)
    {
        return sprintf("%.02f", round(doubleval($price), 2));
    }

    /**
     * Sets the properties for this object from the specified array 
     * 
     * @param array $properties the associative array with propertie
     *  
     * @return void
	 * @access public
     * @since  3.0
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $field => $value) {
			if (isset($this->fields[$field])) {
                $this->set($field, $properties[$field]);
            }
        }
    }
}

