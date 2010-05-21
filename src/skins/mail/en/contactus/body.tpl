{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<html>
<body>
<p>Customers need help!

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
</html>
