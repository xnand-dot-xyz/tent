<?php
  function convert_bandcamp_link($link) {
    $base = get_base_url();

    $host = parse_url($link, PHP_URL_HOST);

    $path = parse_url($link, PHP_URL_PATH);
    $path = $path ? ltrim($path, "/") : null;

    $query = parse_url($link, PHP_URL_QUERY);
    if ($query)
      parse_str($query, $query);
    else
      $query = null;

    if ($host === "bandcamp.com" && $path === "search" && !array_key_exists("item_type", $query)) {
      $file = "search";
      $data = [
        "query" => $query["q"]
      ];
    } elseif ($host === "bandcamp.com" && $path === "search") {
      $file = "search";
      $data = [
        "query" => $query["q"],
        "type" => $query["item_type"]
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
    } elseif (is_bandcamp_host($host) && !$path) {
      $file = "artist";
      $data = [
        "name" => $host,
        "host" => true
      ];
    } elseif (is_bandcamp_host($host)) {
      $file = "release";
      $data = [
        "artist" => $host,
        "type" => explode("/", $path)[0],
        "name" => explode("/", $path)[1],
        "host" => true
      ];
    } elseif ($host === "bandcamp.23video.com" && str_starts_with(explode("/", $path)[3], "video_")) {
      $file = "video";
      $data = [
        "parent" => explode("/", $path)[0],
        "child" => explode("/", $path)[1],
        "hash" => explode("/", $path)[2],
        "file" => explode("/", $path)[4]
      ];
    } elseif ($host === "bandcamp.23video.com" && explode("/", $path)[4] === "thumbnail.png") {
      $file = "poster";
      $data = [
        "parent" => explode("/", $path)[0],
        "child" => explode("/", $path)[1],
        "hash" => explode("/", $path)[2]
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

    $query = parse_url($link, PHP_URL_QUERY);
    if ($query)
      parse_str($query, $query);
    else
      $query = null;

    $link = "https://";

    switch ($path) {
      case "artist":
        if (!isset($query["name"]))
          return false;
        $link .= urlencode($query["name"]);
        if (!isset($query["host"]))
          $link .= ".bandcamp.com";
        $link .= "/music";
        break;
      case "discover":
        $link .= "bandcamp.com/discover";
        if (isset($query["tags"]))
          $link .= "/" . urlencode($query["tags"]);
        break;
      case "poster":
        if (!isset($query["parent"]) || !isset($query["child"]) || !isset($query["hash"]))
          return false;
        $link .= "bandcamp.23video.com/" . $query["parent"] . "/" . $query["child"] . "/" . $query["hash"] . "/standard/thumbnail.png";
      case "redirect":
        if (!isset($query["url"]))
          return false;
        $link = $query["url"];
      case "release":
        if (!isset($query["artist"]) || !isset($query["type"]) || !isset($query["name"]))
          return false;
        if (isset($query["host"]) && $query["host"])
          $link .= urlencode($query["artist"]) . "/";
        else
          $link .= urlencode($query["artist"]) . ".bandcamp.com/";
        $link .= urlencode($query["type"]) . "/" . urlencode($query["name"]);
        break;
      case "search":
        if (!isset($query["query"]))
          return false;
        $link .= "bandcamp.com/search?q=" . urlencode($query["query"]);
        if (isset($query["type"]))
          $link .= "&item_type=" . urlencode($query["type"]);
        break;
      case "video":
        if (!isset($query["parent"]) || !isset($query["child"]) || !isset($query["hash"]) || !isset($query["file"]))
          return false;
        $link .= "bandcamp.23video.com/" . $query["parent"] . "/" . $query["child"] . "/" . $query["hash"] . "/video_hd/" . $query["file"];
      default:
        return false;
        break;
    };

    return $link;
  };

  function get_base_url() {
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

    return $scheme . "://" . $host . preg_replace("/\/.*.php/", "/", strtok($uri, "?"));
  };

  function is_bandcamp_host($host) {
    $records = dns_get_record($host . ".", DNS_A | DNS_CNAME);

    if (!$records)
      return false;

    $records = array_filter($records, function($record) {
      $a = $record["type"] === "A" && $record["ip"] === "35.241.62.186";
      $cname = $record["type"] === "CNAME" && $record["target"] === "dom.bandcamp.com";

      return $a || $cname;
    });

    return boolval($records);
  };

  function prefix_link($link, $parameter) {
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
      if (isset($_GET["host"]) && $_GET["host"])
        return $link = "https://" . urlencode($_GET[$parameter]) . $link;
      else
        return $link = "https://" . urlencode($_GET[$parameter]) . ".bandcamp.com" . $link;
    } else {
      return $link;
    };
  };

  function resize_link($link, $size) {
    $host = parse_url($link, PHP_URL_HOST);
    if ($host !== "f4.bcbits.com") return $link;

    $file = pathinfo($link)["filename"];
    $ext = pathinfo($link)["extension"];

    return "https://" . $host . "/img/" . explode("_", $file)[0] . "_" . $size . "." . $ext;
  };
?>
