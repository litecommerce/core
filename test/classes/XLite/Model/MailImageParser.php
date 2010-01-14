<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

class XLite_Model_MailImageParser extends XLite_Model_FlexyCompiler
{	
    public $webdir;	
    public $images;	
    public $counter;

	function flexy() { }

    function postprocess() // {{{
    {
        $this->images = array();
        $this->counter = 1;
        // find images, e.g. background=..., src=..., style="...url('...')"
        for ($i=0; $i<count($this->tokens); $i++) {
            $token = $this->tokens[$i];
            if ($token["type"] == "attribute") {
                $name = strtolower($token["name"]);
            } else if ($token["type"] == "attribute-value") {
                $val = $this->getTokenText($i);
                if ($name == 'style') {
                    $pos = strpos($val, 'url(');
                    if ($pos!==false) {
                        $this->substImage($pos+5+$token["start"], strpos($val, ')')+$token["start"] -1 /* closing quote */);
                    }
                } else if ($name == 'background' || $name == 'src') {
                    $this->substImage($token["start"], $token["end"]);
                }
                $name = '';
            } else {
                $name = '';
            }
        }
        $this->result = $this->substitute();
    } // }}}
    
    function substImage($start, $end) // {{{
    {
        $img = substr($this->source, $start, $end-$start);
        if (strcasecmp(substr($img, 0, 5), 'http:')) {
            $img = $this->webdir . $img; // relative URL
        }
        $img = str_replace('&amp;', '&', $img);
        $img = str_replace(' ', '%20', $img);
        $this->subst($start, $end,  $this->getImgSubstitution($img));
    } // }}}

    function getImgSubstitution($img) // {{{
    {
        if (!isset($this->images[$img])) {
            // fetch image
            if (($fd = @fopen($img, "rb"))) {
                $image = '';
                while(!feof($fd)) {
                    $image .= fgets($fd, 10000);
                }
                fclose($fd);
                $info = getimagesize($img);
                $this->images[$img] = array(
                    'name' => basename($img),
                    'data' => $image,
                    'mime' => $info['mime']
                    );
                $this->counter++;
            } else {
                // can't fetch
                return $img;
            }
        }
        return 'cid:'.$this->images[$img]['name'].'@mail.lc';
    } // }}}
}

