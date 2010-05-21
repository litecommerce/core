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
To export the found orders into MYOB Accounting 2005 follow the steps below<hr>

<p><font class="AdminHead">Step 1: Export of customer cards</font>
<p align="justify">Click on "Export customer cards" button below to export customer cards for the found orders and save the resulting CSV file. You can name this file as you like to suit your requirements.

<p align="justify">In MYOB: To import the exported customer cards, from the file menu select <b>Import Data</b> ---&gt; <b>Cards</b> ---&gt; <b>Customer Cards</b>. Then select Tab-delimited as an Input File Format, Header Records as a First Record and click on continue. Select the file created with "Export" button and match the Import Fields with the MYOB Matching Import Fields. Then select Import. MYOB will confirm the data that has been imported.

<p align="justify">To find the exported customer cards, click on Cards List in the Card File Command Center and select All Cards tab.

<p><input type="button" name="submit" value="Export customer cards.." onclick="javascript: document.location='{url:h}&action=export_myob&export_result=customer_cards'">

<p><br>

<p><font class="AdminHead">Step 2: Export of sales transaction</font>

<p align="justify"> Before you can import transactions into MYOB you must set up an Income Account Number to which the total value of the imported transactions is allocated. Must be valid, preexisting MYOB account number, 5 characters, numeric. May (optionally) have a non-numeric separator between the first digit and the last 4 digits (for example, 1-1234). Please refer to your MYOB Manual for further assistance.

<form name="order_sales_form">
Income Account Number <font class=Star>*</font> <input type="text" name="income_account_number" value="{config.ImportExport.income_account}" size=7>
</form>

<p align="justify">Click on "Export sales transaction" button below to export the found orders and save the resulting CSV file. You can name this file as you like to suit your requirements.

<p align="justify">In MYOB: To import the exported transactions, select <b>Import Data</b> ---&gt; <b>Sales</b> ---&gt; <b>Miscellaneous Sales</b> from the file menu. Then select Tab-delimited as an Input File Format, Header Records as a First Record and click on continue. Select the file created with "Export" button and match the Import Fields with the MYOB Matching Import Fields. Then select Import. MYOB will confirm the data that has been imported.

<p align="justify">There are several ways that MYOB Accounting 2005 provides to find exported transactions. For example, Click on Find Transactions in any command center, select the Invoice tab then then select All Invoices in the Search By. The list of exported item sale transaction will appear. Please refer to your MYOB Manual for further assistance on finding transactions.

<p><input type="button" name="submit" value="Export sales transaction.." onclick="javascript: export_sales(document.order_sales_form.income_account_number.value)">
<script language="Javascript">

function export_sales(income_account_number) {
    if (income_account_number == "") {
        alert("Please enter Income Account Number!");
    } else { 
        document.location='{url:h}&action=export_myob&export_result=sales&income_account=' + income_account_number;
    }    
}
</script>

<p><br>

<p><font class="AdminHead">Step 3: Export of received payments</font>

<p align="justify">Before you can import received payments into MYOB you must set up a Deposit Account to which the total value of the received payments is allocated. Must be valid, preexisting MYOB account number. 5 characters, numeric. May (optionally) have a non-numeric separator between the first digit and the last 4 digits (for example, 1-1234). Please refer to your MYOB Manual for further assistance.

<form name="received_payments_form">Deposit Account Number <font class=Star>*</font> <input type="text" name="deposit_account" value="{config.ImportExport.deposit_account}" size=7></form>

<p align="justify">Click on "Export received payments" button below to export the resulting CSV file. You can name this file as you like to suit your requirements.

<p align="justify">In MYOB: To import the received payments, from the file menu select <b>Import Data</b> ---&gt; <b>Receipts</b> ---&gt; <b>Receive Payments</b>. Then select Tab-delimited as an Input File Format, Header Records as a First Record and click continue. Select the file created with "Export" button and match the Import Fields with the MYOB Matching Import Fields. Then select Import. MYOB will confirm the data that has been imported.

<p align="justify">See Step 2 of the explanation on how to find exported transactions.

<p><input type="button" name="submit" value="Export received payments.." onclick="javascript: export_received_payments(document.received_payments_form.deposit_account.value);">

<script language="Javascript">
function export_received_payments(deposit_account) {
    if (deposit_account == "") {
        alert("Please enter Deposit Account Number!");
        return;
    }
    document.location = "{url:h}&action=export_myob&export_result=received_payments&deposit_account=" + deposit_account;
}
</script>

<p><br>

<p align="justify">After you successfully pass all export steps you will be able to manage the exported orders.

<p align="justify"><b>Note:</b> During the importing process, the Import Log report is created. This report (titled pluslog.txt) lists information about any problems that occurred during the importing process, as well as information about rejected duplicate records. This report is created as a text file, and can be opened in a text editor like Notepad. It is located in the same place as your company file. The errors and warnings that occurred during the import process are listed at the bottom of the report and correspond to the number in front of each record.

<p align="justify">Each time you import, a new Import Log report (titled pluslog.txt) is created, using the same name. If an Import Log report already exists when you create another one, the existing report will be removed.

<p>Please refer to your MYOB Manual for further assistance.

<p>
