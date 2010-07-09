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
class XLite_Controller_Admin_ChangeSkin extends XLite_Controller_Admin_AAdmin
{
    public $_currentSkin = null;
    public $currentSkinName = null;
    public $_templatesRepository = "skins_original";
    public $_schemasRepository = "schemas";
    public $_skins = null;
    public $_templatesDirectory = "skins";
    public $_modulesPath = "classes/modules";

    function getDirectoriesToCreate()
    {
        $dirs = array('var', $this->_templatesDirectory, "catalog","images");
        $dirs[] = "var/backup";
        $dirs[] = "var/log";
        $dirs[] = "var/html";
        $dirs[] = "var/run";
        $dirs[] = "var/tmp";

        return $dirs;
    }

    function getCurrentSkin()
    {
        if (isset($this->_currentSkin)) {
            return $this->_currentSkin;
        }
        
        $this->currentSkinName = $this->config->Skin->skin;
        $this->_currentSkin = str_replace('_', " ", $this->currentSkinName);

        return $this->_currentSkin;
    }

    function isDisplayWarning()
    {
        $skins = array("3-columns_classic", "3-columns_modern", "2-columns_classic", "2-columns_modern");
        if (!in_array($this->config->Skin->skin, $skins)) {
            if (!array_key_exists($this->config->Skin->skin, $this->get('skins'))) {
                return true;
            }
        }

        return false;
    }

    function getSchemasList()
    {
        $node['name'] = "Standard";
        $node['path'] = $this->_schemasRepository;
        $list[] = $node;

        return $list;
    }

    function getSchemasRepository()
    {
        return $this->_schemasRepository;
    }

    function getSkins()
    {
        if (isset($this->_skins)) {
            return $this->_skins;
        }
    
        $this->_skins = array();
        foreach ($this->get('schemasList') as $schema) {
            if ($dir = @opendir($schema['path'] . "/templates")) {
                $files = array();
    	    	$orig_files = array();
        	    while (($file = readdir($dir)) !== false) {
        			if (!($file == "." || $file == "..")) {
        				$orig_files[] = $file;
            		}
    	    	}
        	    closedir($dir);

            	asort($orig_files);
    	    	$reverse_sorting = false;
        		$files = array();
        		$preferential = array();
            	foreach ($orig_files as $key => $file) {
    	    		if (strpos($file, "_modern") !== false) {
        				$preferential[] = $file;
        				unset($orig_files[$key]);
            		}
    	    	}
        		if (!$reverse_sorting) {
            		foreach ($preferential as $file) {
                		$files[$file] = array("name" => $file);
    	        	}
        	    }
        		foreach ($orig_files as $file) {
        			$files[$file] = array("name" => $file);
            	}
    	    	if ($reverse_sorting) {
        	    	foreach ($preferential as $file) {
            			$files[$file] = array("name" => $file);
            		}
                }
    			foreach ($files as $key => $value) {
        			$preview = $schema['path'] . "/templates/".$value['name']."/preview.gif";
    				if (is_readable($preview)) {
    					$files[$key]['preview'] = $preview;
    				}
        			$files[$key]['name'] = str_replace('_', " ", $value['name']);
        		}

                $this->_skins = array_merge($this->_skins, $files);
            }
        }

        return $this->_skins;
    }

    function createDirs($dirs)
    {
        $status = true;

        foreach ($dirs as $val) {
            echo "Creating directory: [$val] ... ";

            if (!file_exists($val)) {
                $res = @mkdir($val, get_filesystem_permissions(0777));
                $status &= $res;

                echo $this->showStatus($res);
             } else {
             	echo "[Already exists]";
             }

            echo "<BR>\n"; flush();
        }

        return $status;
    }

    function checkBeforeChange()
    {
        $this->checkFiles($this->_templatesRepository, "", $this->_templatesDirectory);
        $log = $this->checkFiles($this->get('schemasRepository')."/templates/".$this->layout, "", $this->_templatesDirectory);

        foreach ($log as $k=>$v)
            $log[$k] = array_unique($log[$k]);

        if ( count($log['write']) > 0 ) {
            echo "<font color='red'><b>The following files have insufficient write permissions and cannot be overwritten:</b></font><br>";
            foreach ($log['write'] as $v) {
                echo "<font color='black'>$v</font><BR>";
            }
            echo "<br>";
        }

        if ( count($log['read']) > 0 ) {
            echo "<font color='red'><b>The following files have insufficient read permissions and cannot be read:</b></font><br>";
            foreach ($log['read'] as $v) {
                echo "<font color='black'>$v</font><BR>";
            }
            echo "<br>";
        }

        if ( count($log['read']) > 0 || count($log['write']) > 0 )
            return false;

        return true;
    }

    function checkFiles($source_dir, $parent_dir, $destination_dir)
    {
        static $log = array("read"=>array(), "write"=>array());

        if ( !$handle = @opendir($source_dir) ) {
            echo $this->showStatus(false)."<BR>\n";
            return false;
        }

        while ( ($file = readdir($handle)) !== false ) {
            if ( is_file($source_dir."/".$file) ) {
                if ( !is_readable("$source_dir/$file") ) {
                    $log['read'][] = "$source_dir$parent_dir/$file";
                }
                if (file_exists("$destination_dir$parent_dir/$file") && !is_writeable("$destination_dir$parent_dir/$file") ) {
                    $log['write'][] = "$destination_dir$parent_dir/$file";
                }
            } else if ( is_dir($source_dir."/".$file) && $file != "." && $file != ".." ) {
                if ( !file_exists("$destination_dir$parent_dir/$file") ) {
                    if ( !is_writeable("$destination_dir$parent_dir") )
                        $log['write'][] = "$destination_dir$parent_dir";
                        continue;
                } else {
                    if (file_exists("$destination_dir$parent_dir/$file") && !is_writeable("$destination_dir$parent_dir/$file") ) {
                        $log['write'][] = "$destination_dir$parent_dir/$file";
                        continue;
                    }
                }

                $this->checkFiles($source_dir."/".$file, $parent_dir."/".$file, $destination_dir);
            }
        }

        closedir($handle);

        return $log;
    }

    function copyFiles($source_dir, $parent_dir, $destination_dir)
    {
        $status = true;

        if ( !$handle = @opendir($source_dir) ) {
            echo $this->showStatus(false)."<BR>\n";
            return false;
        }
 
        while ( ($file = readdir($handle)) !== false ) {
            if ( is_file($source_dir."/".$file) ) {
                if ( !@copyFile("$source_dir/$file", "$destination_dir$parent_dir/$file") ) {
                    echo "Copying $source_dir$parent_dir/$file to $destination_dir$parent_dir/$file ... ".$this->showStatus(false)."<BR>\n";
                    $status &= false;
                }

                flush();

            } else if ( is_dir($source_dir."/".$file) && $file != "." && $file != ".." ) {
                echo "Creating directory $destination_dir$parent_dir/$file ... ";

                if ( !file_exists("$destination_dir$parent_dir/$file") ) {
                    if ( !@mkdir("$destination_dir$parent_dir/$file", get_filesystem_permissions(0777)) ) {
                        echo $this->showStatus(false);
                        $status &= false;
                    } else {
                        echo $this->showStatus(true);
                    }
                } else {
                    echo "[Already exists]";
                }

                echo "<BR>\n"; flush();

                $status &= $this->copyFiles($source_dir."/".$file, $parent_dir."/".$file, $destination_dir);
            }
        }

        closedir($handle);

        return $status;
    }

    function updateModulesSkins()
    {
        $module = new XLite_Model_Module();
        $result = $module->iterate();
        while ($module->next($result)) {
            $name = $module->get('name');
            if (file_exists("./".$this->_modulesPath."/".$name."/install.php")) {
                echo "Changing some skins to work with " .$name. " module correctly...<br>";
                @include_once LC_MODULES_DIR . $name . 'install.php';
            }
        }
    }

    function fatalError($message)
    {
?>
<P>
<B><FONT color=red>Fatal error: <b><?php echo $message; ?></b>.<BR></FONT></B>
This unexpected error has canceled the installation.<BR>
To install the selected skin, please correct the problem and start the installation again.
</P>
<?php
    }

    function warningMsg($msg)
    {
?>
<p>
<b><font color="red">Warning: <?php echo $msg; ?></font></b>
<?php
    }

    function showStatus($var)
    {
        return ($var ? "<FONT color=green>[OK]</FONT>" : "<FONT color=red>[FAILED]</FONT>");
    }

    function action_update()
    {
        $this->set('silent', true);

        $this->startDump();
        echo "<H1>Installing skin: " . $this->layout . "</H1>";

        $ck_res = 1;

        if ( $this->ignore_errors != "yes" ) {
            if ( !$this->CheckBeforeChange() ) {
                echo '<font color="red"><b>Note: The files listed above do not have sufficient write permissions.</b></font><br> For further details on changing file permissions, run "man chmod" command in your UNIX system or see your SSH/FTP/Shell client reference manual.<br>';
                echo '<p><a href="'.$this->get('url').'"><u><b>Return to admin zone</b></u></a>';
                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="admin.php?target=change_skin&action=update&layout='.$this->layout.'&ignore_errors=yes&xlite_form_id='.$this->get('xliteFormID').'><u>Continue anyway</u></a>';
                func_refresh_end();
                exit();
            }
        }

    	echo "<BR><B>Creating directories...</B><BR>\n";

    	$ck_res &= $this->createDirs($this->get('directoriesToCreate'));

        $teDialog = new XLite_Controller_Admin_TemplateEditor();
        $teDialog->getExtraPages();

    	echo "<BR><B>Copying templates...</B><BR>\n";

    	$ck_res &= $this->copyFiles($this->_templatesRepository, "", $this->_templatesDirectory);
     	
    	echo "<BR><B>Installing layout skin...</B><BR>\n";
        // switch templates_repository to layout folder
    	$ck_res &= $this->copyFiles($this->get('schemasRepository')."/templates/".$this->layout, "", $this->_templatesDirectory);

        echo "<br><br>";
 
 		echo "<div>";
    	$this->updateModulesSkins();
        echo "</div>";

        $teDialog->action_reupdate_pages();

        echo "<br><br><b>Cleanup cache...</b><br>";
        func_cleanup_cache('skins', true);

        XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
            array(
                'category' => 'Skin',
                'name'     => 'skin',
                'value'    => $this->layout
            )
        );
        
        echo "<br><b>Task completed.</b><br>";

    	if (!$ck_res) {
            $this->warningMsg("Files marked [FAILED] have not been re-writen.");
    	}
    }

    function getPageReturnUrl()
    {
        return array('<a href="'.$this->get('url').'"><u>Return to admin zone</u></a>');
    }
}
