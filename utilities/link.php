<?php
  function convert_link($link) {
    if (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]))
      $scheme = $_SERVER["HTTP_X_FORWARDED_PROTO"];
    elseif (isset($_SERVER["REQUEST_SCHEME"]))
      $scheme = $_SERVER["REQUEST_SCHEME"];
    elseif (isset($_SERVER["HTTPS"]))
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
      $data = [
        "query" => $query["q"]
      ];
    } elseif (str_ends_with($host, ".bandcamp.com") && !$path) {
      $file = "artist";
      $data = [
        "name" => explode(".", $host)[0]
      ];
    } elseif (str_ends_with($host, ".bandcamp.com")) {
      $file = "release";
      $data = [
        "artist" => explode(".", $host)[0],
        "type" => explode("/", $path)[0],
        "name" => explode("/", $path)[1]
      ];
    } elseif ($host === "f4.bcbits.com") {
      $file = "image";
      $data = [
        "file" => basename($link)
      ];
    } elseif ($host === "t4.bcbits.com") {
      $file = "audio";
      $data = [
        "directory" => explode("/", $path)[1],
        "format" => explode("/", $path)[2],
        "file" => explode("/", $path)[3],
        "token" => $query["token"]
      ];
    } else
      return htmlspecialchars($link);

    return $base . $file . ".php?" . http_build_query($data);
  };

  function prefix_link($link, $parameter) {
    if (!filter_var($link, FILTER_VALIDATE_URL))
      return $link = "https://" . urlencode($_GET[$parameter]) . ".bandcamp.com" . $link;
    else
      return $link;
  };

  function resize_link($link, $size) {
    $host = parse_url($link, PHP_URL_HOST);
    if ($host !== "f4.bcbits.com") return $link;

    $file = pathinfo($link)["filename"];
    $ext = pathinfo($link)["extension"];

    return "https://" . $host . "/img/" . explode("_", $file)[0] . "_" . $size . "." . $ext;
  };
?>
