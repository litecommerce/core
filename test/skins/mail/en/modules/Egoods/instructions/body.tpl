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

<span FOREACH="item.getEgoods(),egood">
<span IF="egood.delivery=#L#">
You have ordered a downloadable product: {egood.name}.
<br>
Please use the following link:  <a href="{egood.link:r}">{egood.link:r}</a> to download the product from our website.
<br>
<span IF="egood.expires=#T#">
<i>Note: this link will expire in {egood.exp_time} days so please download the product at your earliest convenience.</i>
</span>
<span IF="egood.expires=#D#">
<i>Note: this link will expire after {egood.downloads} download(s).</i>
</span>
<span IF="egood.expires=#B#">
<i>Note: this link will expire after {egood.downloads} download(s) or in {egood.exp_time} days so please download the product at your earliest convenience.</i>
</span>
<br><br>
</span>
</span>

</body>
</html>
