{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}
--<br />
<div>{t(#Thank you for using LiteCommerce services#,_ARRAY_(#LiteCommerce#^config.Company.company_name))}</div>

<br />

<div IF="config.Company.company_phone">Phone: {config.Company.company_phone}</div>

<div IF="config.Company.company_fax">Fax: {config.Company.company_fax}</div>

<div IF="config.Company.company_website">Website: {config.Company.company_website}</div>
