<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_CaptchaGenerator extends XLite_Base
{
    public function __construct()
    {
        $this->symbol_width = 20;
        $this->height = 20;
        $this->characters = array (
                                '1' => 0,
                                '2' => 1,
                                '3' => 2,
                                '4' => 3,
                                '5' => 4,
                                '6' => 5,
                                '7' => 6,
                                '8' => 7,
                                '9' => 8,
                                '0' => 9,
                                'A' => 10,
                                'B' => 11,
                                'C' => 12,
                                'D' => 13,
                                'E' => 14,
                                'F' => 15,
                                'G' => 16,
                                'H' => 17,
                                'I' => 18,
                                'J' => 19,
                                'K' => 20,
                                'L' => 21,
                                'M' => 22,
                                'N' => 23,
                                'O' => 24,
                                'P' => 25,
                                'Q' => 26,
                                'R' => 27,
                                'S' => 28,
                                'T' => 29,
                                'U' => 30,
                                'V' => 31,
                                'W' => 32,
                                'X' => 33,
                                'Y' => 34,
                                'Z' => 35
                            );
    }

    function wave($foreground_color, $background_color, $img, $width, $height) 
    {
        $center = $width / 2;
        
        // periods
        $rand1=mt_rand(750000,1200000)/10000000;
        $rand2=mt_rand(750000,1200000)/10000000;
        $rand3=mt_rand(750000,1200000)/10000000;
        $rand4=mt_rand(750000,1200000)/10000000;
        // phases
        $rand5=mt_rand(0,3141592)/500000;
        $rand6=mt_rand(0,3141592)/500000;
        $rand7=mt_rand(0,3141592)/500000;
        $rand8=mt_rand(0,3141592)/500000;
        // amplitudes
        $rand9=mt_rand(330,420)/110;
        $rand10=mt_rand(330,450)/110;

        $img2 = imagecreatetruecolor($width - 10, $height + 21);
        $bgcolor = imagecolorallocate($img2, $background_color['red'], $background_color['green'], $background_color['blue']);

        imagefilledrectangle($img2, 0, 0, imagesx($img2), imagesy($img2), $bgcolor);

        for ($x = 0; $x < $width; $x++) {
            for ($y = -10; $y < $height + 20; $y++) {
                $sx = $x + (sin($x * $rand1 + $rand5) + sin($y * $rand3 + $rand6)) * $rand9 - $width / 2 + $center + 1 - 8;
                $sy = $y + (sin($x * $rand2 + $rand7) + sin($y * $rand4 + $rand8)) * $rand10;
                if ($sx < 0 || $sy < 0 || $sx >= $width - 1 || $sy >= $height - 1) {
                    $color = 255;
                    $color_x = 255;
                    $color_y = 255;
                    $color_xy = 255;
                } else {
                    $color = imagecolorat($img, $sx, $sy) & 0xFF;
                    $color_x = imagecolorat($img, $sx+1, $sy) & 0xFF;
                    $color_y = imagecolorat($img, $sx, $sy+1) & 0xFF;
                    $color_xy = imagecolorat($img, $sx+1, $sy+1) & 0xFF;
                }

                if ($color == 0 && $color_x == 0 && $color_y == 0 && $color_xy == 0){
                    $newred = $foreground_color[0];
                    $newgreen = $foreground_color[1];
                    $newblue = $foreground_color[2];
                } elseif ($color == 255 && $color_x == 255 && $color_y == 255 && $color_xy == 255) {
                    $newred = $background_color[0];
                    $newgreen = $background_color[1];
                    $newblue = $background_color[2];
                } else {
                    $frsx = $sx - floor($sx);
                    $frsy = $sy - floor($sy);
                    $frsx1 = 1 - $frsx;
                    $frsy1 = 1 - $frsy;
                    $newcolor = (
                                $color * $frsx1 * $frsy1 +
                                $color_x * $frsx * $frsy1 +
                                $color_y * $frsx1 * $frsy +
                                $color_xy * $frsx * $frsy);

                    if ($newcolor > 255) $newcolor = 255;
                    $newcolor = $newcolor / 255;
                    $newcolor0 = 1 - $newcolor;

                    $newred = $newcolor0 * $foreground_color[0] + $newcolor * $background_color[0];
                    $newgreen = $newcolor0 * $foreground_color[1] + $newcolor * $background_color[1];
                    $newblue=$newcolor0*$foreground_color[2]+$newcolor*$background_color[2];
                }

                imagesetpixel($img2, $x, $y + 10, imagecolorallocate($img2,$newred,$newgreen,$newblue));
            }
        }
        return $img2;
    }

    function movePixels($col, $col_height, $dest, $size, $im)
    {
        if ($dest == "up") {
            for ($i = 0; $i < $col_height - $size; $i++) {
                $next_pixel_color = imagecolorat($im, $col, $i + $size);
                imagesetpixel($im, $col, $i, $next_pixel_color);
            }
        } elseif ($dest == "down") {
            for ($i = $col_height; $i >= $size; $i--) {
                $next_pixel_color = imagecolorat($im, $col, $i - $size);
                imagesetpixel($im, $col, $i, $next_pixel_color);
            }
        }

        return $im;
    }

    function drawLines($im, $width, $height)
    {
        
        $line_color = array (
                                'red' => 198,
                                'green' => 189,
                                'blue' => 165
                            );

        $linecolor = imagecolorallocate($im, $line_color['red'], $line_color['green'], $line_color['blue']);

        for ($i = 0; $i <= $height; $i += 10) {
            imagedashedline($im, 0, $i, $width, $i, $linecolor);
        }
        for ($i = 0; $i <= $width; $i += 10) {
            imagedashedline($im, $i, 0, $i, $height, $linecolor);
        }

        return $im;
    }

    function drawPixels($width, $height, $im)
    {
        for ($i = 0; $i < $width; $i++) {
            $color = imagecolorallocate($im, mt_rand(0,255),mt_rand(50,255), mt_rand(0, 50));
            imagesetpixel ( $im, rand(1, $width), rand(1, $height), $color);
        }

        return $im;
    }

    function getCharByIndexFromFont($index)
    {
    	global $primaryInstallation;
        $font_im = @imagecreatefrompng(((isset($primaryInstallation)) ? $primaryInstallation : ".") . "/classes/kernel/font.png");
        $character_im = imagecreatetruecolor($this->symbol_width, $this->height);
        if ($font_im) {
        	imagecopymerge($character_im, $font_im, 0, 0, ($index * $this->symbol_width), 0, $this->symbol_width, $this->height, 100);
        	imagedestroy($font_im);
        }
        return $character_im;
    }

    function generateImage($code, $im)
    {
        for ($i = 0, $x = 0; $i < strlen($code); $i++, $x += $this->symbol_width) {
            $char_im = $this->getCharByIndexFromFont($this->characters[$code[$i]]);
            imagecopymerge($im, $char_im, $x, 0, 0, 0, $this->symbol_width, $this->height, 100);
        }
        return $im;
    }

    function generateCode($length)
    {
    	$str_num = "";
        $mode = $this->getComplex('config.Captcha.captcha_type');
        if ($mode == "numbers"){
            $fisrt = 48;
            $last  = 57;
        } elseif($mode == "letters") {
            $fisrt = 65;
            $last  = 90;
        } else {
            $fisrt = 48;
            $last  = 90;
        }

        for ($i = 0; $i < $length; $i++) {
            $number = rand($fisrt, $last);
    		if (($number > 57) && ($number < 65)) {
        		$i--;
            } else {
    			$str_num .= chr($number);
        	}
    	}
                
        return $str_num;
    }

    function generate($code)
    {
        $width = strlen($code) * $this->symbol_width + 20;
        $bg_color = array (
            			'red' => 255,
                		'green' => 255,
                    	'blue' => 255,
                        0 => 255,
    					1 => 255,
        				2 => 255
                    );

        $text_color = array (
            			'red' => 0,
                		'green' => 0,
                    	'blue' => 0,
                        0 => 0,
    					1 => 0,
        				2 => 0
                    );
        
        $im = imagecreatetruecolor($width, $this->height);
        $bgcolor = imagecolorallocate($im, $bg_color['red'], $bg_color['green'], $bg_color['blue']);

        imagefilledrectangle($im, 0, 0, imagesx($im), imagesy($im), $bgcolor);

        $im = $this->generateImage($code, $im);
        $im = $this->wave($text_color, $bg_color, $im, $width, $this->height);
        $im = $this->drawLines($im, $width - 10, $this->height + 20);

        return $im;
    }
}
