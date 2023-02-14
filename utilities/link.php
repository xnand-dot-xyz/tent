<?php
  function convert_link($link) {
    if ($_SERVER["REQUEST_SCHEME"])
      $scheme = $_SERVER["REQUEST_SCHEME"];
    elseif ($_SERVER["HTTPS"])
      $scheme = "https";
    else
      $scheme = "http";

    $host = $_SERVER["HTTP_HOST"];
    $uri = $_SERVER["REQUEST_URI"];

    $base = $scheme . "://" . $host . preg_replace("/\/.*.php/", "/", strtok($uri, "?"));

    unset($scheme);
    unset($host);
    unset($uri);

    $host = parse_url($link, PHP_URL_HOST);
    $path = ltrim(parse_url($link, PHP_URL_PATH), "/");
    parse_str(parse_url($link, PHP_URL_QUERY), $query);

    if ($host === "bandcamp.com" && $path === "search") {
      $file = "search";
      $data = http_build_query([
        "query" => $query["q"]
      ]);
    } elseif (str_ends_with($host, ".bandcamp.com") && !$path) {
      $file = "artist";
      $data = http_build_query([
        "name" => explode(".", $host)[0]
      ]);
    } elseif (str_ends_with($host, ".bandcamp.com")) {
      $file = "release";
      $data = http_build_query([
        "artist" => explode(".", $host)[0],
        "type" => explode("/", $path)[0],
        "name" => explode("/", $path)[1]
      ]);
    } elseif ($host === "f4.bcbits.com") {
      $file = "image";
      $data = http_build_query([
        "file" => basename($link)
      ]);
    } elseif ($host === "t4.bcbits.com") {
      $file = "audio";
      $data = http_build_query([
        "directory" => explode("/", $path)[1],
        "format" => explode("/", $path)[2],
        "file" => explode("/", $path)[3],
        "token" => $query["token"]
      ]);
    } else
      return $link;

    return $base . $file . ".php?" . $data;
  };

  function prefix_link($link, $parameter) {
    if (!filter_var($link, FILTER_VALIDATE_URL))
      return $link = "https://" . urlencode($_GET[$parameter]) . ".bandcamp.com" . $link;
    else
      return $link;
  };
?>
