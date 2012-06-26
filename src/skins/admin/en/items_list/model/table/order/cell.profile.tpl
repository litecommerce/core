{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Profile cell
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.24
 *}

{if:entity.origProfile&entity.profile.getProfileId()=entity.origProfile.getProfileId()}
  {getColumnValue(column,entity)}
{else:}
  <a href="{buildURL(#profile#,##,_ARRAY_(#profile_id#^entity.origProfile.getProfileId()))}">{getColumnValue(column,entity)}</a>
{end:}
