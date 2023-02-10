<?php
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
