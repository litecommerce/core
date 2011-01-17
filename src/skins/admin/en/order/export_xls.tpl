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
{startXml:h}
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Author>{author:t}</Author>
  <LastAuthor>{author:t}</LastAuthor>
  <Created>{create_date}T{create_time}Z</Created>
  <LastSaved>{create_date}T{create_time}Z</LastSaved>
  <Company>{config.Company.company_name:t}</Company>
  <Version>10.2625</Version>
 </DocumentProperties>
 <OfficeDocumentSettings xmlns="urn:schemas-microsoft-com:office:office">
  <DownloadComponents/>
 </OfficeDocumentSettings>
 <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel">
  <WindowHeight>9345</WindowHeight>
  <WindowWidth>11340</WindowWidth>
  <WindowTopX>480</WindowTopX>
  <WindowTopY>60</WindowTopY>
  <ActiveSheet>0</ActiveSheet>
  <ProtectStructure>False</ProtectStructure>
  <ProtectWindows>False</ProtectWindows>
 </ExcelWorkbook>
 <Styles>
  <Style ss:ID="Default" ss:Name="Normal">
   <Alignment ss:Vertical="Bottom"/>
   <Borders/>
   <Font ss:FontName="Arial"/>
   <Interior/>
   <NumberFormat/>
   <Protection/>
  </Style>
  <Style ss:ID="s21" ss:Name="Link">
   <Font ss:FontName="Arial" ss:Color="#0000FF"
    ss:Underline="Single"/>
  </Style>
  <Style ss:ID="s28">
   <Font ss:FontName="Arial" ss:Bold="1"/>
  </Style>
  <Style ss:ID="s32">
   <NumberFormat ss:Format="[$$-409]#,##0.00"/>
  </Style>
  <Style ss:ID="s35">
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s37">
   <Font ss:FontName="Arial" ss:Bold="1"/>
   <NumberFormat ss:Format="Short Date"/>
  </Style>
  <Style ss:ID="s39">
   <Font ss:Bold="1"/>
   <NumberFormat ss:Format="[$$-409]#,##0.00"/>
  </Style>
 </Styles>
 <Worksheet ss:Name="orders">
  <Table ss:ExpandedColumnCount="{ColumnCount}" ss:ExpandedRowCount="{RowCount}" x:FullColumns="1"
   x:FullRows="1">
   <Column ss:AutoFitWidth="0" ss:Width="35.25"/>
   <Column ss:AutoFitWidth="0" ss:Width="46.5"/>
   <Column ss:StyleID="s35" ss:AutoFitWidth="0" ss:Width="144"/>
   <Column ss:AutoFitWidth="0" ss:Width="225.75"/>
   <Column ss:StyleID="s32" ss:Width="53.25"/>
   <Column ss:StyleID="s32" ss:AutoFitWidth="0" ss:Span="1"/>
   <Column ss:Index="8" ss:StyleID="s32" ss:AutoFitWidth="0" ss:Width="52.5"/>
   <Row>
    <Cell ss:StyleID="s28"><Data ss:Type="String">{config.Company.company_name:t} orders for period {formatDate(startDate)} - {formatDate(endDate)}</Data></Cell>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s35"/>
    <Cell ss:StyleID="s28"/>
    <Cell ss:StyleID="s28"><Data ss:Type="String">Subtotal</Data></Cell>
    <Cell ss:StyleID="s28"><Data ss:Type="String">Tax</Data></Cell>
    <Cell ss:StyleID="s28"><Data ss:Type="String">Shipping</Data></Cell>
    <Cell ss:StyleID="s28"><Data ss:Type="String">Total</Data></Cell>
   </Row>
   <Row FOREACH="orders,order">
    <Cell ss:StyleID="s21" ss:HRef="{getShopUrl(#/admin.php?target=order#)}&amp;order_id={order.order_id}"><Data
      ss:Type="Number">{order.order_id}</Data></Cell>
    <Cell><Data ss:Type="String"><widget template="common/order_status.tpl"></Data></Cell>
    <Cell ss:StyleID="s35"><Data ss:Type="String">{formatTime(order.date)}</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.title:t} {order.profile.billing_address.firstname:t} {order.profile.billing_address.lastname:t}</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.subtotal}</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.tax}</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.shipping_cost}</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.total}</Data></Cell>
    <Cell ss:StyleID="s21" ss:HRef="#'{order.order_id}'!A1"><Data ss:Type="String">details</Data></Cell>
   </Row>
   <Row ss:StyleID="s28">
    <Cell><Data ss:Type="String">Total</Data></Cell>
    <Cell ss:Index="3" ss:StyleID="s37"/>
    <Cell ss:Index="5" ss:StyleID="s39" ss:Formula="=SUM(R2C:R{endRow}C)"><Data
      ss:Type="Number">0</Data></Cell>
    <Cell ss:StyleID="s39" ss:Formula="=SUM(R2C:R{endRow}C)"><Data ss:Type="Number">0</Data></Cell>
    <Cell ss:StyleID="s39" ss:Formula="=SUM(R2C:R{endRow}C)"><Data ss:Type="Number">0</Data></Cell>
    <Cell ss:StyleID="s39" ss:Formula="=SUM(R2C:R{endRow}C)"><Data ss:Type="Number">0</Data></Cell>
   </Row>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <PageMargins x:Bottom="0.984251969" x:Left="0.78740157499999996"
     x:Right="0.78740157499999996" x:Top="0.984251969"/>
   </PageSetup>
   <Print>
    <ValidPrinterInfo/>
    <PaperSizeIndex>9</PaperSizeIndex>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
     <ActiveCol>1</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
 <Worksheet FOREACH="orders,order" ss:Name="{order.order_id}">
  <Table ss:ExpandedColumnCount="{columnCount(order)}" ss:ExpandedRowCount="{rowCount(order)}" x:FullColumns="1"
   x:FullRows="1">
   <Column ss:AutoFitWidth="0" ss:Width="93.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="54.75"/>
   <Column ss:AutoFitWidth="0" ss:Width="144"/>
<widget module="CDev\ProductOptions" template="modules/CDev/ProductOptions/order_export_xls/layout.tpl">
   <Row>
    <Cell><Data ss:Type="String">Order id:</Data></Cell>
    <Cell><Data ss:Type="Number">{order.order_id:h}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Order date:</Data></Cell>
    <Cell ss:StyleID="s35"><Data ss:Type="String">{formatTime(order.date)}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Order Status:</Data></Cell>
    <Cell><Data ss:Type="String"><widget template="common/order_status.tpl"></Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">E-mail:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.login:t}</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="21">
    <Cell ss:StyleID="s28"><Data ss:Type="String">Billing Info</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Name:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.title:t} {order.profile.billing_address.firstname:t} {order.profile.billing_address.lastname:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Phone:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.phone:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Address:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.street:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">City:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.city:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">State:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.state.state:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Country:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.country.country:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Zip code:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.billing_address.zipcode:t}</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="21.75">
    <Cell ss:StyleID="s28"><Data ss:Type="String">Shipping Info</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Name:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.shipping_address.title:t} {order.profile.shipping_address.firstname:t} {order.profile.shipping_address.lastname:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Phone:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.shipping_address.phone:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Address:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.shipping_address.street:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">City:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.shipping_address.city:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">State:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.shipping_address.state.state:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Country:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.shipping_address.country.country:t}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Zip code:</Data></Cell>
    <Cell><Data ss:Type="String">{order.profile.shipping_address.zipcode:t}</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="21">
    <Cell ss:StyleID="s28"><Data ss:Type="String">Products ordered</Data></Cell>
   </Row>
   <Row ss:StyleID="s28">
    <Cell ss:Index="2"><Data ss:Type="String">SKU</Data></Cell>
    <Cell><Data ss:Type="String">Product</Data></Cell>
<widget module="CDev\ProductOptions" template="modules/CDev/ProductOptions/order_export_xls/options_header.tpl">
    <Cell><Data ss:Type="String">Quantity</Data></Cell>
    <Cell><Data ss:Type="String">Price</Data></Cell>
    <Cell><Data ss:Type="String">Total</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="12" FOREACH="order.items,item">
    <Cell ss:Index="2"><Data ss:Type="String">{item.sku:t}</Data></Cell>
    <Cell><Data ss:Type="String">{item.name:t}</Data></Cell>
<widget module="CDev\ProductOptions" template="modules/CDev/ProductOptions/order_export_xls/options.tpl">
    <Cell><Data ss:Type="Number">{item.amount}</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{item.price}</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{item.total}</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="19.5">
    <Cell><Data ss:Type="String">Payment method</Data></Cell>
    <Cell><Data ss:Type="String">{order.paymentMethod.name:t}</Data></Cell>
   </Row>
   <Row ss:AutoFitHeight="0" ss:Height="22.5">
    <Cell><Data ss:Type="String">Delivery:</Data></Cell>
    <Cell><Data ss:Type="String">{if:order.shippingMethod}{order.shippingMethod.name:t}{else:}{if:order.shipping_id=#0#}Free{else:}N/A{end:}{end:}</Data></Cell>
   </Row>
   <Row>
    <Cell><Data ss:Type="String">Subtotal:</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.subtotal}</Data></Cell>
   </Row>
<widget module="CDev\Promotion" template="modules/CDev/Promotion/export_xls_discount.tpl">
   <Row>
    <Cell><Data ss:Type="String">Shipping cost:</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.shipping_cost}</Data></Cell>
   </Row>
<widget module="CDev\Promotion" template="modules/CDev/Promotion/export_xls.tpl">
   <Row FOREACH="order.getDisplayTaxes(),tax_name,tax">
    <Cell><Data ss:Type="String">{order.getTaxLabel(tax_name):t}:</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{tax}</Data></Cell>
   </Row>
<widget module="CDev\GiftCertificates" template="modules/CDev/GiftCertificates/export_xls.tpl">
   <Row>
    <Cell><Data ss:Type="String">Total:</Data></Cell>
    <Cell ss:StyleID="s32"><Data ss:Type="Number">{order.total}</Data></Cell>
   </Row>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <PageSetup>
    <PageMargins x:Bottom="0.984251969" x:Left="0.78740157499999996"
     x:Right="0.78740157499999996" x:Top="0.984251969"/>
   </PageSetup>
   <Print>
    <ValidPrinterInfo/>
    <PaperSizeIndex>9</PaperSizeIndex>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Selected/>
   <TopRowVisible>1</TopRowVisible>
   <Panes>
    <Pane>
     <Number>3</Number>
     <ActiveRow>1</ActiveRow>
     <ActiveCol>1</ActiveCol>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>
