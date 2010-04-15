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
To export the found orders into Peachtree Accounting follow the steps below<hr>

<p class="AdminHead">Step 1: Export of Customer List

<p align="justify">Click on "Export customer list" button below to export customer records for the found orders and save the resulting CSV file. You can name this file as you like to suit your requirements. 

<p align="justify">In Peachtree: To import the exported customer information, select "Select Import/Export" from the file menu.  Select "Accounts Receivable / Customer List" and click "Import". Click "Options" and select the saved export customer list file. Check "First Row Contains Headings" as an Import Options. Switch to "Fields" tab and make sure that the import fields layout confirms the customer list file format. Click on OK to import the customer list.

<p align="justify">To find the list of imported customers select Maintain :: Customers/Prospects from Peachtree main menu.

<p><input type="button" name="export" value="Export customer list..." onclick="javascript: document.location='{url:h}&action=export_pt&export_result=customer'">

<p><br>

<p class="AdminHead">Step 2: Export of Sales Journal

<p align="justify">Click on "Export sales journal" button below to export sales journal records for the found orders and save the resulting CSV file. You can name this file as you like to suit your requirements. 

<p align="justify"> Before you can export sales transactions you must specify a Receivable Account number and a General Ledger Sales Account number (both should be valid account numbers from your Chart of Accounts). Please refer to your Peachtree Manual for further assistance.

<form name="export_sales_form">
<table border=0>
<tr><td>Receivable Account number</td><td><font class="Star">*</font> <input type="text" name="receivable_account" value="{config.ImportExport.receivable_account}" size=7></td></tr>
<tr><td>G/L Account number</td><td><font class="Star">*</font> <input type="text" name="sales_account" value="{config.ImportExport.sales_account}" size=7></td></tr>
</table>
</form>

<p align="justify">In Peachtree: To import the sales journal, select "Select Import/Export" from the file menu. Select "Accounts Receivable / Sales Journal" and click on "Import". Click on "Options" and select the saved export sales list file as an "Import/Export File". Check "First Row Contains Headings" as Import Option. Switch to "Fields" tab and make sure that the import fields layout confirms the sales journal file format. Click on OK to import the sales journal. 

<p align="justify">To find the list of imported sales select "Tasks :: Sales / Invoicing" menu.

<p><input type="button" name="export" value="Export sales journal..." onclick="javascript: export_sales();">

<script language="Javascript">
function export_sales() {
    with (document.export_sales_form) {
        if (receivable_account.value == "") {
            alert("Please enter Receivable Account number!");
        } else if(sales_account.value == "") {
            alert("Please enter G/L Account number!");
        } else {
            url = "{url:h}&action=export_pt&export_result=sales";
            url += "&receivable_account=" + receivable_account.value;
            url += "&sales_account=" + sales_account.value;
            document.location = url;
        }
    }
}
</script>


<p><br>

<p class="AdminHead">Step 3: Export of Cash Receipts Journal

<p align="justify">Click on "Export cash receipts" button below to export cash receipts journal records for the found orders and save the resulting CSV file. You can name this file as you like to suit your requirements. 

<p align="justify">Before you can export cash receipts transactions you must specify a Cash Account number to which the total value of the received payments are allocated (it should be a valid account number from your Chart of Accounts). Please refer to your Peachtree Manual for further assistance.

<p align="justify">In Peachtree: To import the sales journal, select "Select Import/Export" from the file menu. Select "Accounts Receivable / Cash Receipts Journal" and click on "Import". Click on "Options" and select the saved export file as an "Import/Export File". Check "First Row Contains Headings" as Import Option. Switch to "Fields" tab and make sure that the import fields layout confirms the cash receipts journal file format. Click on OK to import the cash receipts journal.

<form name="export_payments_form">
Cash Account number <font class="Star">*</font> <input type="text" name="cash_account" value="{config.ImportExport.cash_account}" size=7>
</form>

<p><input type="button" name="export" value="Export cash receipts..." onclick="javascript: export_payments();">

<script language="Javascript">
function export_payments() {
    with (document.export_payments_form) {
        if(cash_account.value == "") {
            alert("Please enter Cash Account number!");
        } else {
            url = "{url:h}&action=export_pt&export_result=receipts";
            url += "&cash_account=" + cash_account.value;
            document.location = url;
        }
    } 
}
</script>

<p><br>

<p align="justify">After you pass all export steps successfully you will be able to manage the exported orders.

<p>Please refer to your Peachtree Manual for further assistance.

<p>
