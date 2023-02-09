<?php
  function convert_link($link) {
    $scheme = $_SERVER["REQUEST_SCHEME"];
    $host = $_SERVER["HTTP_HOST"];
    $uri = $_SERVER["REQUEST_URI"];

    $base = $scheme . "://" . $host . preg_replace("/\/.*.php/", "/", strtok($uri, "?"));

    $host = parse_url($link, PHP_URL_HOST);
    $path = ltrim(parse_url($link, PHP_URL_PATH), "/");
    parse_str(parse_url($link, PHP_URL_QUERY), $query);

    if ($host === "bandcamp.com" && $path === "search")
      return $base . "search.php?query=" . $query["q"];
    elseif (str_ends_with($host, ".bandcamp.com") && !$path)
      return $base . "artist.php?name=" . explode(".", $host)[0];
    elseif (str_ends_with($host, ".bandcamp.com") && explode("/", $path)[0] === "album")
      return $base . "album.php?artist=" . explode(".", $host)[0] . "&name=" . explode("/", $path)[1];
    elseif ($host === "f4.bcbits.com")
      return $base . "image.php?file=" . basename($link);
    elseif ($host === "t4.bcbits.com")
      return $base . "audio.php?directory=" . explode("/", $path)[1] . "&file=" . explode("/", $path)[3] . "&token=" . $query["token"];
    else
      return $link;
  };

  function prefix_link($link, $parameter) {
    if (!filter_var($link, FILTER_VALIDATE_URL))
      return $link = "https://" . urlencode($_GET[$parameter]) . ".bandcamp.com" . $link;
    else
      return $link;
  };
?>
