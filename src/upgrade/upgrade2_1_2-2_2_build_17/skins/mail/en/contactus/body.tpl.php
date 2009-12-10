<?php
    $find_str = <<<EOT
<p>Customer information:
<br><br>
First Name: {firstname}<br>
Last Name: {lastname}<br>

<p>Address information:
<br><br>
Address:    {address}<br>
City:       {city}<br>
State:      {state}<br>
Country:    {country}<br>
Zip code:   {zipcode}<br>

<p>
Phone:        {phone}<br>
Fax:          {fax}<br>
E-mail:        {email}<br>

<p>
Department: {department}<br>
Subject:    {subj}<br>
Message:<br>
<p>{body}</p>

<p>{signature:h}
</body>
EOT;
    $replace_str = <<<EOT
<p>Customer information:
<br><br>
First Name: {firstname:h}<br>
Last Name: {lastname:h}<br>

<p>Address information:
<br><br>
Address:    {address:h}<br>
City:       {city:h}<br>
State:      {state:h}<br>
Country:    {country:h}<br>
Zip code:   {zipcode:h}<br>

<p>
Phone:        {phone:h}<br>
Fax:          {fax:h}<br>
E-mail:        {email:h}<br>

<p>
Department: {department:h}<br>
Subject:    {subj:h}<br>
Message:<br>
<p>{body:h}</p>

<p>{signature:h}
</body>
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
