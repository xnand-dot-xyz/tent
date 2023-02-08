<?php

function convert_link($link) {
  $scheme = $_SERVER["REQUEST_SCHEME"];
  $host = $_SERVER["HTTP_HOST"];
  $uri = strtok($_SERVER["REQUEST_URI"], "?");

  $base = $scheme . "://" . $host . preg_replace("/\/.*.php/", "/", $uri);

  $host = parse_url($link, PHP_URL_HOST);
  $path = parse_url($link, PHP_URL_PATH);
  parse_str(parse_url($link, PHP_URL_QUERY), $query);

  if ($host === "bandcamp.com" && $path === "/search") {
    return $base . "search.php?query=" . $query["q"];
  } elseif (str_ends_with($host, ".bandcamp.com") && !$path) {
    return $base . "artist.php?name=" . explode(".", $host)[0];
  } elseif ($host === "f4.bcbits.com") {
    return $base . "image.php?file=" . basename($link);
  } else {
    return $link;
  };
};

?>
