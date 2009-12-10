<?php

unset($action);
unset($access_key);
unset($access_type);
unset($shop_url);
unset($confirm);

switch(strtoupper($_SERVER["REQUEST_METHOD"]))
{
	case "POST":
		$REQUEST = $HTTP_POST_VARS;
	break;
}

if (isset($REQUEST["action"]))
{
	$action = $REQUEST["action"];

    if (is_callable("action_" . $action, false)) 
    {
    	$action = "action_" . $action;
    	if (!$action()) {
    		die("<FONT color=red><B><HR>ERROR!!!</B></FONT>");
    	}
    } else {
    	die("<FONT color=red><B><HR>ERROR!!!</B></FONT>");
    }
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>
<HEAD>
<META http-equiv="Content-Type" content="text/html; charset=windows-1251">
<META http-equiv="Pragma" content="no_cache">
<TITLE>Licence managing</TITLE>
<STYLE type=text/css>
BODY, TD, TH, TABLE, UL, OL {font-size: 10pt; font-family: Verdana,Arial,Helvetica,Tahoma; }
A:link { COLOR: #000000; TEXT-DECORATION: none;}
A:visited { COLOR: #000000; TEXT-DECORATION: none;}
A:hover { COLOR: #0000FF; TEXT-DECORATION: underline;}
A:active  { COLOR: #000000; TEXT-DECORATION: none;}
</STYLE>
<SCRIPT language="JavaScript1.2">
function SubmitForm()
{
	if (!document.manage_form.confirm.checked) {
		alert("Please confirm your action!");
		return;
	}

	shop_url = document.manage_form.shop_url.value;
	if (shop_url.length == 0) {
		alert("Please enter the valid Shop URL!");
		return;
	}

	access_key = document.manage_form.access_key.value;
	if (access_key.length == 0) {
		alert("Please enter the valid Access Key!");
		return;
	}

	if (document.manage_form.access_type.selectedIndex == 0) {
		alert("Please select the valid Action!");
		return;
	}

	if (confirm("Are you sure you want to " + document.manage_form.access_type.value + " this store?")) {
		document.manage_form.submit();
	}
}
</SCRIPT>
</HEAD>
<BODY bgcolor=#FFFFFF link=#0000FF alink=#4040FF vlink=#800080>

<TABLE cellpadding=0 cellspacing=0 border=0>
<FORM method="POST" name="manage_form">
<INPUT type=hidden name=action value="process">
	<TR>
		<TD>
			<TABLE cellpadding=0 cellspacing=0 border=0>
            	<TR>
            		<TD>
            			<TABLE bgcolor=blue cellpadding=2 cellspacing=0 border=0>
            				<TR>
            					<TD>
            						<TABLE bgcolor=blue cellpadding=1 cellspacing=0 border=0>
            							<TR>
            								<TD nowrap bgcolor=blue color=white style="bgcolor=blue; color=white;"><B>&nbsp;Licence Activate/Deactivate&nbsp;</TD>
                                        </TR>
                                    </TABLE>
								</TD>
							</TR>
                        </TABLE>
					</TD>
				</TR>
            	<TR>
            		<TD>
            			<TABLE bgcolor=blue cellpadding=2 cellspacing=0 border=0 width=100%>
            				<TR>
            					<TD width=100%>
            						<TABLE bgcolor=white cellpadding=1 cellspacing=0 border=0 width=100%>
            							<TR>
            								<TD width=100%>
                        						<TABLE cellpadding=1 cellspacing=0 border=0 width=100%>
                        							<TR>
                        								<TD rowspan=20>&nbsp;&nbsp;&nbsp;</TD>
                        								<TD align=right>Shop Url</TD>
                        								<TD>&nbsp;</TD>
                        								<TD><INPUT type=text name=shop_url size=50></TD>
                        								<TD rowspan=20>&nbsp;&nbsp;&nbsp;</TD>
                                                    </TR>
                        							<TR>
                        								<TD align=right>Action</TD>
                        								<TD>&nbsp;</TD>
                        								<TD>
                        									<SELECT name=access_type>
                        									<OPTION value="">- none-</OPTION>
                        									<OPTION value="activate">activate</OPTION>
                        									<OPTION value="deactivate">deactivate</OPTION>
                        									</SELECT>
                        								</TD>
                                                    </TR>
                        							<TR>
                        								<TD align=right>Access key</TD>
                        								<TD>&nbsp;</TD>
                        								<TD><INPUT type=text name=access_key size=30></TD>
                                                    </TR>
                        							<TR>
                        								<TD align=right>Confirmation</TD>
                        								<TD>&nbsp;</TD>
                        								<TD><INPUT type=checkbox name=confirm value=1 onClick="this.blur()"></TD>
                                                    </TR>
                        							<TR>
                        								<TD align=center align=center colspan=3>
                        								<BR>
                                                        <INPUT type=button value=" Submit " onClick="SubmitForm()">
                        								</TD>
                                                    </TR>
                                                </TABLE>
            								</TD>
                                        </TR>
                                    </TABLE>
								</TD>
							</TR>
                        </TABLE>
					</TD>
				</TR>
            </TABLE>
		</TD>
	</TR>
</FORM>
</TABLE>
</BODY>
</HTML>
<?php

exit;

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

function action_process()
{
	global $REQUEST;

	if (isset($REQUEST["access_key"])) {
		global $access_key;
        $access_key = $REQUEST["access_key"];
	} else {
		return false;
	}
	if (isset($REQUEST["access_type"])) {
		global $access_type;
        $access_type = $REQUEST["access_type"];
	} else {
		return false;
	}
	if (isset($REQUEST["shop_url"])) {
		global $shop_url;
        $shop_url = $REQUEST["shop_url"];
	} else {
		return false;
	}
	if (isset($REQUEST["confirm"])) {
		global $confirm;
        $confirm = $REQUEST["confirm"];
	} else {
		return false;
	}

	if (!(isset($access_key) && isset($access_type) && isset($shop_url) && isset($confirm) && $confirm == 1 && ($access_type == "activate" || $access_type == "deactivate")))
	{
		return false;
	}

	$url_info = parse_url($shop_url);
	list($http_header, $result, $status) = func_http_post_request($url_info["host"], $url_info["path"], "access_key=$access_key&access_type=$access_type");
	if (!$status) {
    	die("<FONT color=red><B><HR>ERROR!</B></FONT> Cannot connect to: " . $url_info["host"] . $url_info["path"]);
	} else {
    	if (strpos($result, "activated") !== false) {
    		$result = substr($result, 0, strpos($result, "activated") + 9);
    		echo $result;
    	}

    	die("<FONT color=green><B><HR>OK</B></FONT>");
    }
}

function func_http_post_request($host, $post_url, $post_str) 
{
	$hp = explode(':',$host);

	$result = "";
	$header_passed = false;

	if( !isset($hp[1]) || !is_numeric($hp[1]) ) $hp[1] = 80;
	$host = implode(':', $hp);

	$fp = fsockopen($hp[0], $hp[1], $errno, $errstr, 30);
	if (!$fp) {
		return array ("", "", false);
	} else {
        fputs($fp, "POST http://$host$post_url HTTP/1.0\r\n");
        fputs($fp, "Host: $host\r\n");

		fputs($fp, "User-Agent: Mozilla/4.5 [en]\r\n");
		fputs($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-Length: ".strlen($post_str)."\r\n");
		fputs($fp, "\r\n");
		fputs($fp, $post_str."\r\n\r\n");

		$http_header = array();
		$http_header["ERROR"] = chop(fgets($fp,4096));

		while (!feof($fp)) {
			$line = fgets($fp,4096);

			if ($header_passed == false && ($line == "\n" || $line == "\r\n")) {
				$header_passed = true;
				continue;
			}

			if ($header_passed == false) {
				$header_line = explode(": ", $line, 2);
				$header_line[0] = strtoupper($header_line[0]);
				$http_header[$header_line[0]] = chop($header_line[1]);
				continue;
			}
			$result .= $line;
		}

		fclose ($fp);
	}

	return array($http_header, $result, true);
}

?>
