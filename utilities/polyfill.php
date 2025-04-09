<?php
  if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
      return substr($haystack, 0, strlen($needle)) === $needle;
    };
  };

  if (!function_exists('str_ends_with')) {
    function str_ends_with($haystack, $needle) {
      return substr($haystack, -strlen($needle)) === $needle;
    };
  };
?>
