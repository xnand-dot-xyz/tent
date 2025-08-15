<?php
  function echo_blacklist_message($artist) {
    if (!in_array(strtolower($artist), [
      "joshuaharrison888"
    ]))
      return false;

    $bandcamp = convert_tent_link("//" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);

    echo "<h1>Not available.</h1>";
    echo "<p>
      This artist has explicitly asked for their content to not be available via Tent." . ($bandcamp ? " <a href=\"" . $bandcamp . "\">Open this page on Bandcamp.</a>" : "") . "
    </p>";

    return true;
  };

  function echo_design_style($document) {
    if (!isset($_COOKIE["design"]) || $_COOKIE["design"] !== "on")
      return;

    $design = $document
      ->evaluate("//style[@id=\"custom-design-rules-style\"]")
      ->item(0);

    if (!$design)
      return;

    $design = $design
      ->getAttribute("data-design");

    if (!$design)
      return;

    $design = json_decode($design);

    if (
      $design->body_color === "FFFFFF" &&
      $design->text_color === "363636" &&
      $design->link_color === "0687F5"
    )
      return;

    $borders = sscanf($design->text_color, "%02x%02x%02x");
    $borders = "rgba(" . implode(", ", $borders) . ", 25%)";

    echo "<style>
      body {
        --background: #" . $design->body_color . " !important;
        --color: #" . $design->text_color . " !important;
        --borders: " . $borders . " !important;
        --links: #" . $design->link_color . " !important;
        --selection-background: var(--color) !important;
        --selection-color: var(--background) !important;
      }
    </style>";
  };

  function echo_error_message() {
    $url = get_base_url();
    $type = pathinfo(parse_url($url, PHP_URL_HOST), PATHINFO_EXTENSION);

    switch ($type) {
      case "onion":
        $type = "tor";
        break;
      case "i2p":
        break;
      default:
        $type = "http";
        break;
    };

    $instances = json_decode(file_get_contents("../instances.json"));
    $instances = array_filter($instances, fn($instance) => $instance->url !== $url && $instance->type === $type);

    if ($instances)
      $instance = $instances[array_rand($instances)]->url . basename($_SERVER["REQUEST_URI"]);

    echo "<h1>No results.</h1>";
    echo "<p>
      If you're certain that something should be here, Bandcamp may be temporarily rate limiting this instance.<br>
      In that case, try refreshing this page a few times or using a different instance." . ($instances ? " <a href=\"" . $instance . "\">Open this page on a random instance.</a>" : "") . "
    </p>";
  };

  function encode_document($text) {
    libxml_use_internal_errors(true);

    $document = new DOMDocument();
    $document->loadHTML("<?xml encoding=\"UTF-8\">" . $text);

    foreach ($document->childNodes as $node) {
      if ($node->nodeType === XML_PI_NODE)
        $document->removeChild($node);
    };

    $document->encoding = "UTF-8";
    return $document;
  };
?>
