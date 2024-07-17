<?php
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
