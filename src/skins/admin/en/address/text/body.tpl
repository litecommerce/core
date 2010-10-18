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

{foreach:getSchemaFields(),fieldName,fieldData}
<li class="address-text-{fieldName}" IF="{getFieldValue(fieldName)}"><span class="address-text-label-{fieldName}">{fieldData.label}: </span>{getFieldValue(fieldName,1)}<span class="address-text-comma-{fieldName}">,</span> </li>
{end:}

