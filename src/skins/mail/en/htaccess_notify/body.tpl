{* E-mail sent to customer when he requests lost account information *}
<html>
<body>

<p>You are receiving this e-mail message because some of the .htaccess files were missing or failed the security files verification procedure. The list of these files is included:</p>
<br />
<br />
{foreach:errors,error}
{error.file} : [{error.error}]<br />
{end:}
<br />
<p>For more information on security files verification procedure consult the LiteCommerce reference manual.</p>

<p>{signature:h}
</body>
</html>
