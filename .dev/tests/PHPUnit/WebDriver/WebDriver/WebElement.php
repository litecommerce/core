<?php

class WebDriver_WebElement {
    /**
     * @var WebDriver_Driver
     */
  private $driver;
  private $element_id;
  private $locator;
  
  public function __construct($driver, $element_id, $locator) {
    $this->driver = $driver;
    $this->element_id = $element_id;
    $this->locator = $locator;
  }
  
  private function execute($http_type, $relative_url, $payload = null) {
    return $this->driver->execute($http_type, "/session/:sessionId/element/" . $this->element_id . $relative_url, $payload);
  }
  
  /********************************************************************
   * Getters
   */
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id
  public function describe() {
    $response = $this->execute("GET", "");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/text
  public function get_text() {
    $response = $this->execute("GET", "/text");
    return trim(WebDriver::GetJSONValue($response));
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/value
  public function get_value() {
    $response = $this->execute("GET", "/value");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/displayed
  public function is_visible() {
    $response = $this->execute("GET", "/displayed");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/enabled
  public function is_enabled() {
    $response = $this->execute("GET", "/enabled");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/selected
  public function is_selected() {
    $response = $this->execute("GET", "/selected");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/element
  public function get_next_element($locator) {
    $payload = WebDriver::ParseLocator($locator);
    $response = $this->execute("POST", "/element", $payload);
    $next_element_id = WebDriver::GetJSONValue($response, "ELEMENT");
    return new WebDriver_WebElement($this->driver, $next_element_id, $locator);
  }

    public function get_parent()
    {
        return $this->get_next_element('xpath=..');
    }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/elements
  public function get_all_next_elements($locator) {
    $payload = WebDriver::ParseLocator($locator);
    $response = $this->execute("POST", "/elements", $payload);
    $all_element_ids = WebDriver::GetJSONValue($response, "ELEMENT");
    $all_elements = array();
    foreach ($all_element_ids as $element_id) {
      $all_elements[] = new WebDriver_WebElement($this->driver, $element_id, $locator);
    }
    return $all_elements;
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/name
  public function get_tag_name() {
    $response = $this->execute("GET", "/name");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/attribute/:name
  public function get_attribute_value($attribute_name) {
    $response = $this->execute("GET", "/attribute/" . $attribute_name);
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/equals/:other
  public function is_same_element_as($other_element_id) {
    $response = $this->execute("GET", "/equals/" . $other_element_id);
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/location
  public function get_location() {
    $response = $this->execute("GET", "/location");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/size
  public function get_size() {
    $response = $this->execute("GET", "/size");
    return WebDriver::GetJSONValue($response);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/css/:propertyName
  public function get_css_value($property_name) {
    $response = $this->execute("GET", "/css/" . $property_name);
    return WebDriver::GetJSONValue($response);
  }
  
  public function contains_element($locator) {
    try {
      $this->get_next_element($locator);
      $is_element_present = true;
    } catch (Exception $e) {
      $is_element_present = false;
    }
    return $is_element_present;
  }

  /********************************************************************
   * Getters for <select> elements
   */
  
  public function get_selected() {
    foreach ($this->get_options() as $option) {
      if ($option->is_selected()) {
        return $option;
      }
    }
  }
  
  // 1-based index
  public function get_option_index($index) {
    return $this->get_next_element("//option[$index]");
  }
  
  public function get_option_value($value) {
    return $this->get_next_element("//option[@value=" . WebDriver::QuoteXPath($value) . "]");
  }
  
  public function get_option_label($label) {
    return $this->get_next_element("//option[text()=" . WebDriver::QuoteXPath($label) . "]");
  }
  
  public function get_options() {
    return $this->get_all_next_elements("tag name=option");
  }
  
  /********************************************************************
   * Setters
   */
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/click
  public function click() {
    $this->execute("POST", "/click");
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/submit
  public function submit() {
    $this->execute("POST", "/submit");
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/clear
  public function clear() {
    $this->execute("POST", "/clear");
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/hover
  public function hover() {
    // $this->execute("POST", "/hover"); // Not supported as of Selenium 2.0rc3
    $this->move_cursor_to_center(); // Workaround until /hover is implemented
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/selected
  public function select() {
    $this->click(); // POST /session/:sessionId/element/:id/selected is deprecated as of Selenium 2.0.0
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/toggle
  public function toggle() {
    $response = $this->execute("POST", "/toggle");
    return WebDriver::GetJSONValue($response);
  }

  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/value
  public function send_keys($keys) {
    $payload = array("value" => preg_split('//u', $keys, -1, PREG_SPLIT_NO_EMPTY));
    $this->execute("POST", "/value", $payload);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/element/:id/drag
  public function drag_and_drop($pixels_right, $pixels_down) {
    $payload = array(
      "x" => $pixels_right,
      "y" => $pixels_down
    );
    $this->execute("POST", "/drag", $payload);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/moveto
  public function move_cursor_to_center() {
    $payload = array("element" => $this->element_id);
    $this->driver->execute("POST", "/session/:sessionId/moveto", $payload);
  }
  
  // See http://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/moveto
  public function move_cursor_relative($right, $down) {
    $payload = array(
      "element" => $this->element_id,
      "xoffset" => $right,
      "yoffset" => $down,
    );
    $this->driver->execute("POST", "/session/:sessionId/moveto", $payload);
  }
  
  /********************************************************************
   * Setters for <select> elements
   */

  public function select_label($label) {
    $this->get_next_element("//option[text()=" . WebDriver::QuoteXPath($label) . "]")->select();
  }
  
  public function select_value($value) {
    $this->get_next_element("//option[@value=" . WebDriver::QuoteXPath($value) . "]")->select();
  }
  
  // 1-based index
  public function select_index($index) {
    $this->get_next_element("//option[" . $index . "]")->select();
  }
  
  public function select_random() {
    $all_elements = $this->get_options();
    $new_index = rand(1, count($all_elements));
    $this->select_index($new_index);
  }
  
  /********************************************************************
   * Asserters
   */

  public function assert_visible($message=null) {
    PHPUnit_Framework_Assert::assertTrue($this->is_visible(), $message ?: "Failed asserting that <{$this->locator}> is visible.");
  }
  
  public function assert_hidden($message=null) {
      $wait = $this->driver->set_implicit_wait(0);
      $visible = true;
      for ($i = 0;$i<$wait; $i+=1000){
          if (!$this->is_visible()){
              $visible = false;
              break;
          }
          sleep(1);
      }
      $this->driver->set_implicit_wait($wait);
      PHPUnit_Framework_Assert::assertFalse($visible, $message ?: "Failed asserting that <{$this->locator}> is hidden.");
  }

  public function assert_enabled($message = null) {
    PHPUnit_Framework_Assert::assertTrue($this->is_enabled(), $message ?:"Failed asserting that <{$this->locator}> is enabled.");
  }
  
  public function assert_disabled($message = null) {
    PHPUnit_Framework_Assert::assertFalse($this->is_enabled(),$message ?: "Failed asserting that <{$this->locator}> is disabled.");
  }
  
  public function assert_selected($message = null) {
    PHPUnit_Framework_Assert::assertTrue($this->is_selected(),$message ?: "Failed asserting that <{$this->locator}> is selected.");
  }
  
  public function assert_not_selected($message = null) {
    PHPUnit_Framework_Assert::assertFalse($this->is_selected(), $message ?: "Failed asserting that <{$this->locator}> is not selected.");
  }
  
  public function assert_contains_element($child_locator, $message = null) {
    PHPUnit_Framework_Assert::assertTrue($this->contains_element($child_locator), $message ?: "Failed asserting that <{$this->locator}> contains <$child_locator>.");
  }
  
  public function assert_does_not_contain_element($child_locator, $message = null) {
      $wait = $this->driver->set_implicit_wait(0);

        $present = true;
        for ($i=0;$i<=$wait;$i+=1000)
        {
            if (!$this->contains_element($child_locator)){
                $present = false;
                break;
            }
            sleep(1);
        }
        $this->driver->set_implicit_wait($wait);
        PHPUnit_Framework_Assert::assertFalse($present,$message ?: "Failed asserting that <{$this->locator}> does not contain <$child_locator>.");

  }
  
  public function assert_text($expected_text,$message = null) {
    $end_time = time() + WebDriver::$ImplicitWaitMS/1000;
    do {
      $actual_text = $this->get_text();
    } while (time() < $end_time && $actual_text != $expected_text);
    PHPUnit_Framework_Assert::assertEquals($expected_text, $actual_text, $message ?:"Failed asserting that <{$this->locator}>'s text is <$expected_text>.");
  }
  
  public function assert_text_contains($expected_needle,$message = null) {
    $actual_haystack = $this->get_text();
    PHPUnit_Framework_Assert::assertContains($expected_needle, $actual_haystack, $message ?:"Failed asserting that <{$this->locator}>'s text contains <$expected_needle>.\n$actual_haystack");
  }

  public function assert_text_does_not_contain($expected_missing_needle,$message = null) {
    $actual_haystack = $this->get_text();
    PHPUnit_Framework_Assert::assertNotContains($expected_missing_needle, $actual_haystack, $message ?:"Failed asserting that <{$this->locator}>'s text does not contain <$expected_missing_needle>.");
  }

  public function assert_value($expected_value,$message = null) {

      $wait = $this->driver->set_implicit_wait(0);
      $actual_value = $this->get_value();
      for($i = 0; $i <= $wait; $i += 1000){
          if ($actual_value == $expected_value)
          {
              break;
          }
          sleep(1);
          $actual_value = $this->get_value();
      }
      $this->driver->set_implicit_wait($wait);
      PHPUnit_Framework_Assert::assertEquals($actual_value,$expected_value, $message ?:"Failed asserting that <{$this->locator}>'s value is <$expected_value>.");
  }

  // Will pass for "equivalent" CSS colors such as "#FFFFFF" and "white". Pass $canonicalize_colors = false to disable.
  public function assert_css_value($property_name, $expected_value, $canonicalize_colors = true,$message = null) {
    $actual_value = $this->get_css_value($property_name);
    if (strpos($property_name, 'color') !== false && $canonicalize_colors) {
      $canonical_expected = WebDriver::CanonicalizeCSSColor($expected_value);
      $canonical_actual = WebDriver::CanonicalizeCSSColor($actual_value);
      PHPUnit_Framework_Assert::assertEquals($canonical_expected, $canonical_actual,$message ?: "Failed asserting that <{$this->locator}>'s <{$property_name}> is <$canonical_expected> after canonicalization.\nExpected: $expected_value -> $canonical_expected\nActual: $actual_value -> $canonical_actual");
    } else {
      PHPUnit_Framework_Assert::assertEquals($expected_value, $actual_value,$message ?: "Failed asserting that <{$this->locator}>'s <{$property_name}> is <$expected_value>.");
    }
  }
  
  /********************************************************************
   * Asserters for <select> elements
   */
  
  public function assert_option_count($expected_count,$message = null) {
    $options = $this->get_options();
    PHPUnit_Framework_Assert::assertEquals($expected_count, count($options), $message ?: "Failed asserting that <{$this->locator}> contains $expected_count options.");
  }
  
  public function assert_contains_label($expected_label,$message = null) {
    $contains_label = false;
    $options = $this->get_options();
    $labels = array();
    foreach ($options as $option) {
      $actual_label = $option->get_text();
      if ($actual_label == $expected_label) {
        $contains_label = true;
        break;
      }
      $labels[] = $actual_label;
    }
    PHPUnit_Framework_Assert::assertTrue($contains_label,$message ?: "Failed asserting that <{$this->locator}> contains label <$expected_label>.\n" . print_r($labels, true));
  }
}
