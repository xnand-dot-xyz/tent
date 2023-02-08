<?php
  echo "<div class=\"sidebar\">";

  $image = $document->find(".bio-pic a")->attr("href");
  $description = $document->find("#bio-text")->text();
  $links = $document->find("#band-links li a");

  if (isset($image)) echo "<img src=\"" . convert_link($image) . "\">";
  if (isset($description)) echo "<p>" . nl2br($description) . "</p>";

  if ($links->length) echo "<p>";
  foreach ($links as $link) {
    echo "<a href=\"" . convert_link($link->attr("href")) . "\">" . htmlspecialchars($link->text()) . "</a>";
    echo "<br>";
  };
  if ($links->length) echo "</p>";

  echo "</div>";
?>
