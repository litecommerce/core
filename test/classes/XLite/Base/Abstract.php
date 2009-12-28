<?php

abstract class XLite_Base_Abstract
{
	protected function __construct()
	{
	}

	public function _die($message)
	{
		// TODO - add logging

		die ($message);
	}
}

