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

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* Profiler
*
* @package Kernel
* @access public
* @version $Id$
*/
class XLite_Model_Profiler extends XLite_Base implements XLite_Base_ISingleton 
{	
    public $queries = array();	
    public $query_time = array();	
    public $enabled = false;

	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

    function log($timePoint)
    {
    	if (!isset($this->latest_point)) {
        	$this->latest_point = $this->start_time;
    	} else {
        	$this->latest_point = $this->new_latest_point;
    	}
        $this->new_latest_point = microtime(true);
        $this->$timePoint = $this->new_latest_point - $this->start_time;
        $timePointDelta = $timePoint . "_delta";
        $this->$timePointDelta = $this->new_latest_point - $this->latest_point;
    }
    
    function start($start)
    {
        $this->enabled = !empty($start);
        $this->start_time = microtime(true);
    }
    
    function stop()
    {
        if (!$this->enabled) return;

        $this->stop_time = microtime(true);
        global $parserTime;
        $this->parserTime = $parserTime;
        $this->includedFilesCount = count(get_included_files());
        $this->includedFiles = array();
        $this->includedFileSizes = array();
        $this->includedFilesTotal = 0;
        foreach (get_included_files() as $file) {
            $this->includedFiles[] = $file;
            $size = @filesize($file);
            $this->includedFileSizes[] = $size;
            $this->includedFilesTotal += $size;
        }
		$this->includedFilesTotal /= 1000;
        array_multisort($this->includedFileSizes,SORT_DESC, $this->includedFiles);
        for ($i=0; $i<count($this->includedFiles); $i++) {
            $a = new StdClass();
            $a->name = $this->includedFiles[$i];
            $a->size = $this->includedFileSizes[$i];
            $this->includedFiles[$i] = $a;
        }

        $this->display();
    }
    
    function display()
    {
    	$this->total_time_sum = 0;
?>
<p align=left>
<table border=0>
<tr><td style="FONT-WEIGHT: bold;">DB</td><td><?php print $this->db_time; ?> (<?php print $this->db_time_delta; $this->total_time_sum += $this->db_time_delta; ?>)</td></tr>
<tr><td style="FONT-WEIGHT: bold;">CFG</td><td><?php print $this->cfg_time; ?> (<?php print $this->cfg_time_delta; $this->total_time_sum += $this->cfg_time_delta; ?>)</td></tr>
<tr><td style="FONT-WEIGHT: bold;">SESSION</td><td><?php print $this->ss_time; ?> (<?php print $this->ss_time_delta; $this->total_time_sum += $this->ss_time_delta; ?>)</td></tr>
<tr><td style="FONT-WEIGHT: bold;">MM</td><td><?php print $this->mm_time; ?> (<?php print $this->mm_time_delta; $this->total_time_sum += $this->mm_time_delta; ?>)</td></tr>
<tr><td style="FONT-WEIGHT: bold;">INIT TOTAL</td><td><?php print $this->init_time; ?> (<?php print $this->init_time_delta; $this->total_time_sum += $this->init_time_delta; ?>)</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td style="FONT-WEIGHT: bold;">REQUEST</td><td><?php print $this->request_time; ?> (<?php print $this->request_time_delta; $this->total_time_sum += $this->request_time_delta; ?>)</td></tr>
<tr><td style="FONT-WEIGHT: bold;">RUN (dialog init)</td><td><?php print $this->dialog_init_time; ?> (<?php print $this->dialog_init_time_delta; $this->total_time_sum += $this->dialog_init_time_delta; ?>)</td></tr>
<tr><td style="FONT-WEIGHT: bold;">RUN (dialog request)</td><td><?php print $this->dialog_handleRequest_time; ?> (<?php print $this->dialog_handleRequest_time_delta; $this->total_time_sum += $this->dialog_handleRequest_time_delta; ?>)</td></tr>
<tr><td style="FONT-WEIGHT: bold;">RUN (TOTAL)</td><td><?php print $this->run_time; ?> (<?php print ($this->dialog_init_time_delta + $this->dialog_handleRequest_time_delta + $this->run_time_delta); $this->total_time_sum += $this->run_time_delta; ?>)</td></tr>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td style="FONT-WEIGHT: bold; COLOR: red ">TOTAL TIME</td><td><?php print $this->stop_time - $this->start_time; ?> (<?php print $this->total_time_sum; ?>)</td></tr>
<?php 
if (function_exists('memory_get_usage')) {
?>
<tr><td style="FONT-WEIGHT: bold; COLOR: red ">MEMORY USAGE</td><td><?php printf("%.2f Mb used", $GLOBALS['memory_usage']); ?></td></tr>
<?php 
}
?>
<tr><td colspan=2>&nbsp;</td></tr>
<tr><td style="FONT-WEIGHT: bold;">TOTAL QUERIES</td><td><?php print $this->getTotalQueries(); ?></td></tr>
<tr><td style="FONT-WEIGHT: bold;">TOTAL QUERIES TIME</td><td><?php print $this->getTotalQueriesTime(); ?></td></tr>
</table>

<p>Queries log:
<p>
<?php
foreach ($this->queries as $query => $count) {
    echo "[" . ($count>3?"<font color=red>$count</font>":$count)."] $query<br>\n";
}
?>
<!--
<b>Total time:</b> <?php print $this->getTotalTime(); ?> sec.<br>
<br>
<b>XLite startup time:</b>
PHP parser time: {profiler.parserTime} sec.,
Included files: {profiler.includedFilesCount},
Included files total size: {profiler.includedFilesTotal},
Database connect time: {profiler.dbConnectTime} sec.
<br>
<b>XLite init time:</b><br>
read config: <?php print $this->read_cfg_time; ?> sec.,<br>
modules manager: <?php print $this->mm_init_time; ?> sec.,<br>
session: <?php print $this->ss_time; ?> sec.<br>
<br>
<b>Xlite init total time:</b> <?php print $this->init_time; ?> sec.<br>
<b>Run time:</b> <?php print $this->exec_time - $this->start_time; ?> sec.<br>
<b>Display time:</b> <?php print $this->run_time; ?> sec.<br>
<br>
<b>SQL total queries:</b> {profiler.getTotalQueries()}<br>
<b>SQL total queries time:</b> {profiler.getTotalQueriesTime()} sec.<br>
<b>SQL queries statistics:</b><br>
<span FOREACH="profiler.queries,query,count">
<b>Total:</b> {count}, <b>Time:</b> {profiler.getQueryTime(query)}, <b>Query:</b> {query:h}<br>
</span>
<b>.</b>

Included file sizes: <table>{foreach:profiler.includedFiles,file} <tr><td>{file.name}</td><td>{file.size}</td></tr>{end:} </table>
</p>
-->
<?php
    }

    function displayTime()
    {
        return microtime(true) - $this->displayTime;
    }
    
    function getTotalTime()
    {
        return sprintf("%.03f", $this->stop_time - $this->start_time);
    }

    function addQuery($query)
    {
        if (strlen($query)>300) {
            $query = substr($query, 0, 300) . ' ...';
        }
        if (isset($this->queries[$query])) {
            $this->queries[$query]++;
        } else {
            $this->queries[$query] = 1;
        }
    }

    function getTotalQueries()
    {
        return array_sum($this->queries);
    }

    function getTotalQueriesTime()
    {
        return sprintf("%.03f", array_sum($this->query_time));
    }
    
    function setQueryTime($query, $time)
    {
        $this->query_time[$query] = $time;
    }
    
    function getQueryTime($query)
    {
        return sprintf("%.03f", $this->query_time[$query]);
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
