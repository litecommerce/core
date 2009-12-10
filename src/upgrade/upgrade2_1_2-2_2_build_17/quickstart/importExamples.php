<?php include "start.php"; ?>
<h1>Catalog Import Examples</h1>

<p>This page describes catalog import procedures using two sample CSV files:
<a href="importExample1.csv" title="Right-click to save this file">'importExample1.csv'</a> and
<a href="importExample2.csv" title="Right-click to save this file">'importExample1.csv'</a>.
You can save them on your computer and open them in a text editor or in MS Excel to see their contents. 
Both samples were prepared and exported into CSV format using MS Excel<sup><font size="-2">TM</font></sup>.</p>

<p>To import catalog data from a CSV file, go to the 
<a href="../admin.php?target=import_catalog" target="right"><strong>Catalog / Import catalog</strong></a> 
section of the Administrator Zone and specify the order of data fields as they appear in the CSV file.</p>
<p> The following is the order of data fields in the <a href="importExample1.csv" title="Right-click to save this file">sample 
CSV file 'importExample1.csv'</a>:</p>
<ul>
<li><em>sku</em> - unique product code; optional but highly recommended</li>
<li><em>name</em> - product name as it will appear in your catalog</li>
<li><em>price</em> - product price</li>
<li><em>category</em> - category under which the product will be listed (can contain subcategories, which are delimited by the slash symbol
	'/', just like subfolders are delimited in URLs)</li>
</ul>
<p><a href="importExample2.csv" title="Right-click to save this file">Sample CSV file 'importExample2.csv'</a> has the following 
field order:</p>
<ul>
<li><em>name</em> - product name as it will appear in your catalog</li>
<li><em>price</em> - product price</li>
<li><em>brief description</em> - short product description used in the catalog pages</li>
</ul>
<p><strong>Note:</strong> Other fields must be set to <em>'NULL'</em>.</p>

<p>After you have specified the order of the fields, set the delimiter and the text qualifier (sample CSV files 'importExample1.csv' 
and 'importExample2.csv' have <em>'semicolon'</em> as the field delimiter and <em>'double quotes'</em> as the text qualifier). Using the 
browse button next to the 'File (CSV) for upload' field locate and select the CSV file you want to import and click on the 
'Import products' button. </p>

<h2>Importing products into specific categories</h2>
<p>Sample CSV file 'importExample1.csv' demonstrates how to specify category names for products. </p>
<p>When you import this file into your store catalog, a category <em>'Test category'</em> and a its subcategory <em>'Subcategory'</em>
are created. The product described in the first line of the file is placed in the <em>'Subcategory'</em> and the other two are 
placed in the <em>'Test category'</em>.</p>

<h2>Importing product data with quotes in data fields</h2>
<p>Sample CSV file 'importExample2.csv' demonstrates how to import product data with quotes in data fields into the default category. 
Since the file does not contain the <em>'category'</em> field, all products are placed into the 'Default category'
when you import data from this file. Please see how double quotes are used in the CSV file and how they appear in the product 
pages at the Customer Zone after data import.</p>

<p><a href="migrate.php"><img src="go.gif" width="13" height="13" border="0" align="absmiddle"> Back</a></p>

<?php include "end.php"; ?>
