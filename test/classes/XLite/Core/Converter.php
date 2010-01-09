<?php

class XLite_Core_Converter extends XLite_Base implements XLite_Base_ISingleton
{
	public static function getInstance()
	{
		return self::_getInstance(__CLASS__);
	}

	public static function convertToCamelCase($string)
    {
        return strval(preg_replace('/((?:\A|_)([a-zA-Z]))/ie', 'strtoupper(\'\\2\')', $string));
    }
}
