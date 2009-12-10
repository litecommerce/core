<?php
    $find_str = <<<EOT
Main template main.tpl wallpaper.
*/
.Wallpaper {
    background-image: url("images/head_back_long.gif"); background-repeat: no-repeat; background-COLOR: #4F6D92;
}

A:link {
EOT;
    $replace_str = <<<EOT
Main template main.tpl wallpaper.
*/
.Wallpaper {
	BACKGROUND-IMAGE: url("images/head_back_long.gif"); BACKGROUND-REPEAT: no-repeat; BACKGROUND-COLOR: #4F6D92;
}

A:link {
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
Titles of sidebar menu boxes
*/
.SidebarTitle {
	FONT-WEIGHT: bold; COLOR: #000000; BACKGROUND-COLOR: #e5ebefe
}

/*
EOT;
    $replace_str = <<<EOT
Titles of sidebar menu boxes
*/
.SidebarTitle {
	FONT-WEIGHT: bold; COLOR: #000000; BACKGROUND-COLOR: #e5ebef
}

/*
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);


    $find_str = <<<EOT
	BACKGROUND-COLOR: #E5EBEF
}

/*
Bottom text
*/
EOT;
    $replace_str = <<<EOT
	BACKGROUND-COLOR: #E5EBEF
}

.AomTableHead {
	BACKGROUND-COLOR: #E5EBEF; FONT-SIZE: 12px; FONT-WEIGHT: bold;
}

.Input {
	BORDER : solid; BORDER-WIDTH : 1px; BORDER-COLOR : #B2B2B3; WIDTH : 100%;
}

.OrderTitle {
	COLOR : #516176; FONT-WEIGHT: bold;
}

A.AomMenu:link {
	color: #466479; TEXT-DECORATION: none; font-size : 11px
}

A.AomMenu:visited {
	color: #466479; TEXT-DECORATION: none; font-size : 11px
}

A.AomMenu:hover {
	color: #466479; TEXT-DECORATION: underline; font-size : 11px
}

A.AomMenu:active {
	color: #466479; TEXT-DECORATION: none; font-size : 11px;
}

.AomProductDetailsTitle {
	COLOR: #000000; FONT-WEIGHT: bold; FONT-SIZE: 10px;
}

/*
Bottom text
*/
EOT;
    $source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);
?>
