<?php
    $find_str = <<<EOT
<p>
REMOTE_ADDR: {REMOTE_ADDR}<br>
HTTP_X_FORWARDED_FOR: {HTTP_X_FORWARDED_FOR}<br>

</body>
</html>
EOT;
    $replace_str = <<<EOT
<p>
REMOTE_ADDR: {REMOTE_ADDR}<br>
HTTP_X_FORWARDED_FOR: {HTTP_X_FORWARDED_FOR}<br>
HTTP_REFERER: {HTTP_REFERER}<br>

</body>
</html>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
