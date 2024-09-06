<?php
  function convert_bandcamp_link($link) {
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
    } elseif ($host === "bandcamp.com" && $path === "discover") {
      $file = "discover";
    } elseif ($host === "bandcamp.com" && str_starts_with($path, "discover/")) {
      $file = "discover";
      $data = [
        "tags" => explode("/", $path)[1]
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

    $link = $base . $file . ".php";
    if (isset($data))
      $link .= "?" . http_build_query($data);

    return $link;
  };

  function convert_tent_link($link) {
    $path = pathinfo(parse_url($link, PHP_URL_PATH), PATHINFO_FILENAME);
    parse_str(parse_url($link, PHP_URL_QUERY), $query);

    $link = "https://";

    switch ($path) {
      case "artist":
        if (!isset($query["name"]))
          return false;
        $link .= urlencode($query["name"]) . ".bandcamp.com/";
        break;
      case "discover":
        $link .= "bandcamp.com/discover";
        if (isset($query["tags"]))
          $link .= "/" . urlencode($query["tags"]);
        break;
      case "release":
        if (!isset($query["artist"]) || !isset($query["type"]) || !isset($query["name"]))
          return false;
        $link .= urlencode($query["artist"]) . ".bandcamp.com/" . urlencode($query["type"]) . "/" . urlencode($query["name"]);
        break;
      case "search":
        if (!isset($query["query"]))
          return false;
        $link .= "bandcamp.com/search?q=" . urlencode($query["query"]);
        break;
      default:
        return false;
        break;
    };

    return $link;
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
