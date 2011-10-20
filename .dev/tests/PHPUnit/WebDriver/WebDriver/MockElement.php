<?php

class WebDriver_MockElement extends WebDriver_WebElement {
  public function assert_visible($message = null) { return true; }
  public function assert_hidden($message = null) { return true; }
  public function assert_enabled($message = null) { return true; }
  public function assert_disabled($message = null) { return true; }
  public function assert_selected($message = null) { return true; }
  public function assert_not_selected($message = null) { return true; }
  public function assert_text($expected_text,$message = null) { return true; }
  public function assert_text_contains($expected_needle,$message = null) { return true; }
  public function assert_text_does_not_contain($expected_missing_needle,$message = null) { return true; }
  public function assert_value($expected_value,$message = null) { return true; }
  public function assert_option_count($expected_count,$message = null) { return true; }
  public function assert_contains_label($expected_label,$message = null) { return true; }
}
