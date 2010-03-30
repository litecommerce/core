{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Define contries list
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
var CountriesStates = [];
{foreach:countriesStates,country_code,val}
CountriesStates.{country_code} = [
{if:val.number}
{foreach:val.data,state_code,v}
{ state_code: "{state_code}", state: "{v}" },
{end:}
{end:}
  false
];
{end:}
</script>
