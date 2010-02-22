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

		if (XLite_Module_DrupalConnector_Handler::getInstance()->checkCurrentCMS()) {

			$parsed = parse_url($url);

			$args = array();

			if (!isset($parsed['path']) && preg_match('/^q=/Ss', $parsed['query'])) {

				// Build full URL based on Drupal query path
            	$query = array();

                parse_str($parsed['query'], $query);

				$args = explode('/', $query['q']);
				array_shift($args);

			} elseif (preg_match('/cart\.php$/Ss', $parsed['path'])) {

				// Build full URL based on LC URL
				$args = array(
					'main',
					''
				);

				if (isset($parsed['query'])) {
					$query = array();

					parse_str($parsed['query'], $query);
					if ($query) {
						if (isset($query['target'])) {
							$args[0] = $query['target'];
							unset($query['target']);
						}

            	        if (isset($query['action'])) { 
                	        $args[1] = $query['action'];
                    	    unset($query['action']);
	                    }

						foreach ($query as $k => $v) {
							$args[] = $k . '-' . $v;
						}
					}
				}
			}

			if ($args) {

				array_unshift($args, 'store');

    	        $result = url(implode('/', $args), array('absolute' => true));

				if ($secure) {
	        	    $result = preg_replace('/^http:\/\//Ss', 'https://', $result);

				} else {
					$result = preg_replace('/^https:\/\//Ss', 'http://', $result);
				}
			}
		}

		if (is_null($result)) {
			$result = parent::shopUrl($url, $secure);
		}

		return $result;
	}

}
