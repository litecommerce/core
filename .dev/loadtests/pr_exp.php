<?php

$fp = fopen('data20000.csv', 'w');
//fwrite($fp, '');
fwrite($fp, 'productId,sku,name,description,briefDescription,metaTags,metaDesc,metaTitle,price,enabled,weight,freeShipping,cleanUrl,arrivalDate,categories,images,inventoryEnabled,lowLimitEnabled,lowLimitAmount,amount,classes' . "\n");
$cats = array();

for ($i = 1; $i <= 10; $i++) {
    $cats[] = "Category-" . $i;
    for ($j = 1; $j <= 10; $j++) {
        $cats[] = "Category-" . $i . '/Category-' . $i . $j;
        for ($k = 1; $k <= 10; $k++) {
            $cats[] = "Category-" . $i . '00/Category-' . $i . $j . '0' . '/Category-' . $i . $j . $k;
        }
    }
}
$i = 0;
foreach ($cats as $cat) {
    for ($j = 1; $j <= 20; $j++) {
        fwrite($fp, $i . ',100' . $i . ',Apple,"<h5>Apple</h5><p>The apple is the pomaceous fruit of the apple tree, species Malus domestica in the rose family Rosaceae. It is one of the most widely cultivated tree fruits. The tree is small and deciduous, reaching 3 to 12 metres (9.8 to 39 ft) tall, with a broad, often densely twiggy crown. The leaves are alternately arranged simple ovals 5 to 12 cm long and 3&ndash;6 centimetres (1.2&ndash;2.4 in) broad on a 2 to 5 centimetres (0.79 to 2.0 in) petiole with an acute tip, serrated margin and a slightly downy underside. Blossoms are produced in spring simultaneously with the budding of the leaves. The flowers are white with a pink tinge that gradually fades, five petaled, and 2.5 to 3.5 centimetres (0.98 to 1.4 in) in diameter. The fruit matures in autumn, and is typically 5 to 9 centimetres (2.0 to 3.5 in) diameter. The center of the fruit contains five carpels arranged in a five-point star, each carpel containing one to three seeds.</p><p>The tree originated from Central Asia, where its wild ancestor is still found today. There are more than","500 known cultivars of apples resulting in a range of desired characteristics. Cultivars vary in their yield and the ultimate size of the tree, even when grown on the same rootstock.</p><p>vAt least 55 million tonnes of apples were grown worldwide in 2005, with a value of about $10 billion. China produced about 35% of this total. The United States is the second leading producer, with more than 7.5% of the world production. Turkey, France, Italy, and Iran are also among the leading apple exporters.</p><p>&nbsp;</p><div style=""padding: 24px 24px 24px 21px; display: block; background-color: #ececec;"">From <a style=""color: #1e7ec8; text-decoration: underline;"" title=""Wikipedia"" href=""http://en.wikipedia.org"">Wikipedia</a>, the free encyclopedia</div>\'",,,,1.9900,Y,0.3200,N,,"Thu, 01 Jan 1970 00:00:00 +0000",' . $cat . ',/var/www/xlite/xlite/src/images/product/demo_store_p4059.jpeg,,,10,500,' . "\n");
        $i++;
    }
    //fwrite($fp, '23');
}
fclose($fp);
?>