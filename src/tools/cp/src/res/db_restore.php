<?php
	set_time_limit(2000);
	if ($_POST['password'] != '%PASSWORD%') die("ERROR: Incorrect Database restore password");

	$options = parse_ini_file("etc/config.php");
	if (file_exists("etc/config.local.php")) {
		$options = array_merge($options, parse_ini_file("etc/config.local.php"));
	}
	if (!mysql_connect($options["hostspec"], $options["username"], $options["password"])) {
		die("ERROR: " . mysql_error());
	}
	
	if(!mysql_select_db($options["database"])) {
		die("ERROR: " . mysql_error());
	}
	query_upload("var/backup/sqldump.sql.php");

function status($ok) {
	if (!$ok) return "ERROR: ";
	else return "";
}

function query_upload($filename) { // {{{
	if (!$fp = @fopen($filename, "rb")) {
        echo status(false)." Failed to open $filename\n";
        return false;
    }

	$command = "";
	$counter = 1;

	echo "Please wait ...\n";

	while (!feof($fp)) {
		$c = chop(fgets($fp, 1500000));
		$c = ereg_replace("^[ \t]*--.*", "", $c);

		$command .= $c;

		if (ereg(";$", $command)) {
			$command = ereg_replace(";$", "", $command);

			if (ereg("CREATE TABLE ", $command)) {
				$table_name = ereg_replace(" .*$", "", eregi_replace("^.*CREATE TABLE ", "", $command));
				echo "Creating table: [$table_name] ... "; flush();

				mysql_query($command);

				$myerr = mysql_error();
				if (!empty($myerr))
					break;
				else
					echo status(true)."\n";
			} else {
				mysql_query($command);

				$myerr = mysql_error();
				if (!empty($myerr))
					break;
				else {
                    // do not count drop table
                    if (!ereg("DROP TABLE ", $command)) {
					    $counter++;
                    }    

					if (!($counter % 20)) {
						echo "."; flush();
					}
				}
			}

			$command = "";
			flush();
		}
	}

	fclose($fp);

	if (!empty($myerr))
		echo status(false)." ".$myerr."\n";
	else {
		if ($counter > 19) echo "\n";
		echo status(empty($myerr))."\n";
        @unlink($filename);
	}

	return empty($myerr);
} // }}}


?>
