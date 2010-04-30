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

<head>
  <script type="text/javascript">
    if (document.getElementById("rebuild_cache_block")) document.getElementById("rebuild_cache_block").style.display = "none";
  </script>

  <title>LiteCommerce online store builder{if:getTitle()} - {getTitle()}{end:}</title>
  <meta http-equiv="Content-Type" content="text/html; charset={charset}" />
  <meta name="ROBOTS" content="NOINDEX" />
  <meta name="ROBOTS" content="NOFOLLOW" />
  <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />

  <link href="{getSkinURL(#style.css#)}" rel="stylesheet" type="text/css" />
  <link FOREACH="getCSSResources(),file" href="{file}" rel="stylesheet" type="text/css" />

  <script type="text/javascript" src="{getSkinURL(#js/jquery-1.3.2.js#)}"></script>
  <script type="text/javascript" src="{getSkinURL(#js/jquery-ui.1.7.2.js#)}"></script>
  <script FOREACH="getJSResources(),file" type="text/javascript" src="{file}"></script>
</head>
