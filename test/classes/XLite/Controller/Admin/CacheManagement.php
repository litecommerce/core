<?php

class XLite_Controller_Admin_CacheManagement extends XLite_Controller_Admin_Abstract
{
    public function action_rebuild()
    {
		XLite_Model_ModulesManager::getInstance()->rebuildCache();
		func_flush('<script language="javascript">document.getElementById("rebuild_cache_block").style.display = "none";</script>' . "\n");

		$this->displayPageHeader();
		die ('<br />&nbsp;&nbsp;Cache is cleaned up. <a href="admin.php"><u>Click here</u></a> to return to admin interface');
    }
}

