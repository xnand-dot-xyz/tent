<?php
  function echo_design_style($document) {
    if (!isset($_COOKIE["design"]) || $_COOKIE["design"] !== "on")
      return;

    $design = $document
      ->evaluate("//style[@id=\"custom-design-rules-style\"]")
      ->item(0)
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

    echo "<style>
      body {
        --background: #" . $design->body_color . " !important;
        --color: #" . $design->text_color . " !important;
      }

      a[href] {
        color: #" . $design->link_color . ";
      }
    </style>";
  };

  function echo_error_message() {
    echo "<h1>No results.</h1>";
    echo "<p>
      If you're certain that something should be here, Bandcamp may be rate limiting this instance.<br>
      In that case, try refreshing this page a few times or using a different instance.
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
