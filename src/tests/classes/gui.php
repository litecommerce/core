<?php
//
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2002 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Alexander Merz <alexmerz@php.net>                            |
// +----------------------------------------------------------------------+
//
// $Id: gui.php,v 1.1.1.1 2003/05/23 11:03:34 novik Exp $
//


// do some calculations
$per = 100/$result->runCount();
$notOkWidth = ($per*$result->errorCount())+($per*$result->failureCount());
$okWidth = 100 - $notOkWidth ; 

?>

<html>
<head>
    <title>PHPUnit - GUI</title>
</head>
<body>
    <h1>PHPUnit GUI</h1>
    <table>
        <tr><td>Runs:</td><td> <?php echo $result->runCount(); ?></td></tr>
        <tr><td>Errors:</td><td> <?php echo $result->errorCount(); ?></td></tr>        
        <tr><td>Failures: </td><td><?php echo $result->failureCount(); ?></td></tr>
    </table>
    <table width="50%">
        <tr>
            <td width="20%" align="left">0%</td>
            <td width="20%" align="center">25%</td>
            <td width="20%" align="center">50%</td>
            <td width="20%" align="center">75%</td>            
            <td width="20%" align="right">100%</td>                                    
        </tr>
    </table>    
    <table width="50%" height="30px" cellspan="0" cellpadding="0">
        <tr>
            <td width="<?php echo $okWidth; ?>%" bgcolor="green"></td>
            <td width="<?php echo $notOkWidth; ?>%" bgcolor="red"></td>            
        </tr>
    </table>
    <h3>Failures</h3>
        <form>
            <textarea wrap="physical" cols="80" rows="10">
<?php
// print the failures 
$fails = $result->failures();
foreach($fails as $failure) {
   echo $failure->toString();
   echo "-------------------------------\n";   
}
?>            
            </textarea>
        </form>        
    <h3>Errors</h3>
        <form>
            <textarea wrap="physical" cols="80" rows="10">
<?php
// print the errors 
$errs = $result->errors();
foreach($errs as $error) {
   echo $errors->toString();
   echo "-------------------------------\n";
}
?>            
            </textarea>
        </form>        
</body>
</html>
