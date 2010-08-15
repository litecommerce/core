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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

define('PATCH_APPLIED', 1);
define('ALREADY_PATCHED', 2);
define('CANT_PATCH', 3);
define('CUSTOMER_SKINS', '');
define('ADMIN_SKINS', 'admin');
define('MAIL_SKINS', 'mail');

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Upgrade extends \XLite\Model\AModel
{
    public $fields = array(
        "from_ver" => "",
        "to_ver" => "",
        "date" => "0");
    public $alias = "upgrades";
    public $primaryKey = array('from_ver', "to_ver");
    public $_interactive = true; // interactive upgrade; show upgrade messages	
    public $failed = false;

    function setInteractive($bool) 
    {
        $this->_interactive = $bool;
    }
    
    function getInteractive()
    {
        return $this->_interactive;
    }

    /**
    * Syntax:
    * $upgrade = new \XLite\Model\Upgrade();
    * $upgrade->set('from_ver', "1.2.3");
    * $upgrade->set('to_ver', "2.3.4");
    * $upgrade->doUpgrade();
    */
    function doUpgrade() 
    {
        $from_ver = $this->get('from_ver');
        $to_ver = $this->get('to_ver');
        $from_ver = str_replace(' build ', ".", $from_ver);
        $configVersion = $this->config->Version->version;
        $configVersion = str_replace(' build ', ".", $configVersion);

        echo "<pre>Continuing $from_ver to $to_ver LiteCommerce upgrade:<br><br>\n";

        if ($from_ver != $configVersion) {
        	if ($configVersion != "2.2.17") {
            	die("<font color=red>Can't apply this patch to LiteCommerce version ".$configVersion.": patch version doesn't match or patch has been applied already</font>");
            }
        }
        if (include("upgrade/upgrade".$from_ver."-".$to_ver.".php")) {
            if (!$this->failed) {
                $this->success();
            } else {
                $this->failure();
            }
        }
    }

    function failure()
    {
        if ($this->getInteractive()) {
            if ($this->failed) {
?>
<font color=red>Could not upgrade LiteCommerce from version <?php echo $this->get('from_ver'); ?> to <?php echo $this->get('to_ver'); ?>.</font><br><br>
Please correct errors above and click reload or click the button below to force upgrade to <?php echo $this->get('to_ver'); ?>.<br><br>
<b>Note:</b> you will not be able to repeat this procedure after you click 'Force upgrade'.
</pre>
<center><input type="button" value=" Force upgrade " onclick="document.location='admin.php?target=upgrade&action=upgrade_force&from_ver=<?php echo $this->get('from_ver'); ?>&to_ver=<?php echo $this->get('to_ver'); ?>'"></center>
<?php
            }
        }
    }

    function success()
    {
        $from_ver = $this->get('from_ver');
        $from_ver = str_replace(' build ', ".", $from_ver);
        $configVersion = $this->config->Version->version;
        $configVersion = str_replace(' build ', ".", $configVersion);

        echo "\n\n<br><br><font color=blue><b>LiteCommerce has been successfully upgraded to version " . $this->get('to_ver') . "</b></font>";
        
        if ($from_ver == $configVersion) {
            $this->set('date', time());
            if ($this->find("from_ver='" . $this->get('from_ver') . "' AND to_ver='" . $this->get('to_ver') . "'")) {
                $this->update();
            } else {
                $this->create();
            }

            // set current version in config->Version->version
            \XLite\Core\Database::getRepo('\XLite\Model\Config')->createOption(
                array(
                    'category' => 'Version',
                    'name'     => 'version',
                    'value'    => $this->get('to_ver')
                )
            );
        }
    }

    function copyFile($from, $to)
    {
        $this->log("Copying $from to $to");
        if (file_exists($to)) {
            if (!copyFile($to, $to . ".bak")) {
                $this->status(false, ": can't save backup copy of $to to $to.bak");
                $this->failed = true;
                return false;
            }
        }
        $this->status($result = copyFile($from, $to));
        if (!$result) {
            $this->failed = true;
        }
        return $result;
    }

    function createDir($dir)
    {
        $this->log("Create directory $dir");
        if (is_dir($dir)) {
            $this->status(true, ": Already exists");
        } else {
            @umask(0);
            $this->status(@mkdir($dir, get_filesystem_permissions(0755)));
        }
    }

    function patchSQL($sql)
    {
        $this->log("Applying SQL patch: $sql");
        $this->connection->query($sql);
    }

    /**
    * Patch a file named $file with the patch $path.
    * If $re is specified, it contains a regular expression to search for.
    * If found, than the file has already been patched. patchFile returns
    * ALREADY_PATCHED in this case.
    * $patch as an array of patches. Each element is
    * in form of array(COMMAND, ARGUMENT)
    *  COMMAND is:
    *    "replace" - takes two arguments: line to search and line to replace with
    *    "insert before" - takes two arguments: line to search and line to insert before the found line
    *    "insert after" - takes two arguments: line to search and line to insert after the found line
    *    "remove" - takes one argument: line to search and remove
    *    "insert end" - takes one argument: line to insert at the end of the file
    *    "insert start" - takes one argument: line to insert at the start of the file
    */
    function patchFile($file, $patch, $re = '')
    {
        $lines = file($file);
        // find if patched
        if ($re) {
            foreach ($lines as $line) {
                if (preg_match($re, $line)) {
                    return ALREADY_PATCHED;
                }
            }
        }
        $patched = false;
        for ($i=0; $i < count($lines)+1; $i++) {
            if ($i < count($lines)) {
                $lines[$i] = rtrim($lines[$i]); // chop
                $line = trim($lines[$i]);
            } else {
                $line = '';
            }
            for ($n=0; $n<count($patch); $n++) {
                $command = $patch[$n];
                switch ($command[0]) {
                    case "insert start":
                        if ($i == 0) {
                            array_unshift($lines, $command[1]);
                            $i++;
                            $patched = true;
                        }
                    break;
                    case "insert end":
                        if ($i == count($lines)) {
                            array_push($lines, $command[1]);
                            $i++;
                            $patched = true;
                        }
                    break;
                    case "insert before":
                        if ($this->_compareLines($lines, $i, $command[1])) {
                            array_splice($lines, $i, 0, array($command[2]));
                            $i++;
                            $patched = true;
                        }
                    break;
                    case "insert after":
                        if ($this->_compareLines($lines, $i, $command[1])) {
                            if (is_array($command[1])) $delta = count($command[1]);
                            else $delta = 1;
                            array_splice($lines, $i+$delta, 0, array($command[2]));
                            $i+=$delta+1;
                            $patched = true;
                        }
                    break;
                    case "replace":
                        if ($this->_compareLines($lines, $i, $command[1])) {
                            $lines[$i] = $command[2];
                            $patched = true;
                        }
                    break;
                    case "replace substr":
                        $line = str_replace($command[1], $command[2], $lines[$i]);
                        if ($line != $lines[$i]) {
                            $lines[$i] = $line;
                            $patched = true;
                        }
                    break;
                    case "remove":
                        if ($this->_compareLines($lines, $i, $command[1])) {
                            array_splice($lines, $i, 1);
                            $i--;
                            $patched = true;
                        }
                        break;
                }
            }
        }
        if ($patched) {
            // write the file down
            $fd = fopen($file, "w");
            fwrite($fd, join("\n", $lines));
            fclose($fd);
            return PATCH_APPLIED;
        } else {
            return CANT_PATCH;
        }
    }

    // FIXME - not needed?
    function createConfig($name, $comment, $value, $category, $orderby, $type) 
    {
        echo "Creating config option $name ($category) ... ";
        $this->config_table = $this->connection->getTableByAlias('config');
        // delete old config value
        $sql = "DELETE FROM $this->config_table WHERE name='$name' AND category='$category'";
        if (mysql_query($sql, $this->connection->connection) === false) {
            echo "[FAILURE:" . mysql_error($connection->connection->connection) . "]\n";
            $this->failed = true;
            return false;
        }
        // insert config options
        $sql = "INSERT INTO $this->config_table (name, comment, value, category, orderby, type) VALUES ('$name' , '$comment' , '$value' , '$category' , $orderby , '$type')";
        if (mysql_query($sql, $connection->connection->connection) === false) {
            echo "[FAILURE:" . mysql_error($connection->connection->connection) . "]\n";
            $this->failed = true;
            return false;
        }
        echo "[OK]\n";
        return true;
    }

    // FIXME - not needed?
    function dropConfig($name, $category) 
    {
        echo "Deleting config option $name ($category) ...";
        $this->config_table = $this->connection->getTableByAlias('config');
        // delete option

        $sql = "DELETE FROM $this->config_table WHERE name='$name' AND category='$category'";
        if (mysql_query($sql, $connection->connection->connection) === false) {
            echo "[FAILURE:" . mysql_error($connection->connection->connection) . "]\n";
            $this->failed = true;
            return false;
        }
        echo "[OK]\n";
        return true;
    }

    // FIXME - not needed?
    function patchTemplate($zone, $template, $patch, $re = '')
    {
        $layout = Layout::getInstance();
        if ($zone == CUSTOMER_SKINS) {
            $skins = $layout->getSkins();
        } else {
            $skins = array($zone);
        }
        foreach ($skins as $skin) {
            foreach ($layout->getLocales($skin) as $locale) {
                $file = "skins" . DIRECTORY_SEPARATOR . $skin . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . $template;
                if (file_exists($file)) {
                    echo "Patching file $file... ";
                    if (is_writable($file)) {
                        $result = $this->patchFile($file, $patch, $re);
                        if ($result == ALREADY_PATCHED) {
                            $this->status(true, ": already patched");
                        } else if ($result == CANT_PATCH) {
                            $this->status(false, ": template might have been changed.");
                            $this->print_msg("Cannot apply the following changes:\n" . $this->getPatchDescription($patch));
                            return false;
                        } else if ($result == PATCH_APPLIED) {
                            $this->status(true);
                        }
                    } else {
                        $this->status(false, " file is not writable");
                        $this->failed = true;
                        return false;
                    }
                }
            }
        }
        return true;
    }

    function getPatchDescription($patch)
    {
        $des = "";
        foreach ($patch as $command) {
            $des .= "<b>" . $command[0] . "</b> "; // command
            if (is_array($command[1])) {
                $des .= "<b>lines\n{</b>\n";
                foreach ($command[1] as $line) {
                    $des .= htmlspecialchars($line) . "\n";
                }
                $des .= "\n<b>}</b>\n";
            } else {
                $des .= "\n" . htmlspecialchars($command[1]) . "\n";
            }
            if (isset($command[2])) {
                if (substr($command[0], 0, 7) == "replace") {
                    $des .= "<b>with</b> '" . htmlspecialchars($command[2]) . "'\n";
                } else {
                    $des .= "<b>the line</b> '" . htmlspecialchars($command[2]) . "'\n";
                }
            }
        }
        return $des;
    }

    function _compareLines(&$fileLines, $i, $patchLine)
    {
        if (!is_array($patchLine)) {
            $patchLines = array($patchLine);
        } else {
            $patchLines = $patchLine;
        }
        foreach ($patchLines as $patchLine) {
            if ($patchLine{0} == '/') {
                if (!preg_match($patchLine, $fileLines[$i])) {
                    return false;
                }
            } else {
                if (trim($fileLines[$i]) != trim($patchLine)) {
                    return false;
                }
            }
            $i++;
        }
        return true;
    }

    function log($msg)
    {
        if ($this->getInteractive()) {
            echo $msg . "... ";
        }
    }

    function status($bool, $text = "") {
        if ($this->getInteractive()) {
            if ($bool) {
                echo "<font color=green>[OK$text]</font>\n";
            } else {
                echo "<font color=red>[FAILED$text]</font>\n";
                $this->failed = true;
            }
            flush();
        }
    }

    function print_msg($msg)
    {
        print "$msg\n";
        \Includes\Utils\Operator::flush();
    }
}
