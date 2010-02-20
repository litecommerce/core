<?php

class XLite_Module_DrupalConnector_XLite extends XLite implements XLite_Base_IDecorator
{
    /**
     * Return full URL for the resource
     *
     * @param string $url    resource relative URL
     * @param bool   $secure HTTP/HTTPS flag
     *
     * @return string
     * @access public
     * @since  3.0.0 EE
     */
    public function shopUrl($url, $secure = false)
    {
		$result = null;

		if (XLite_Core_CMSConnector::isCMSStarted()) {

            $result = url(implode('/', arg()), array('absolute' => true));

			if ($secure) {
	            $result = preg_replace('/^http:\/\//Ss', 'https://', $result);
			}

		} else {
			$result = parent::shopUrl($url, $secure);
		}
		

		return $result;
	}

}
