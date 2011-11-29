{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * States lsit controller
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
{foreach:getCountriesStates(),countryCode,data}
  {if:data}
    statesList['{countryCode}'] = [];
    {foreach:data,stateId,state}
      statesList['{countryCode}'][statesList['{countryCode}'].length] = { id: '{stateId}', state: '{state}' }
    {end:}
  {end:}
{end:}
