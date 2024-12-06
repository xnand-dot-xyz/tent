<?php
  function echo_sidebar ($image, $text, $links = null) {
    echo "<div class=\"sidebar\">";

    if (isset($image)) {
      $image = get_placeholder() ?: $image;

      echo "<a href=\"" . convert_bandcamp_link(resize_link($image, 0)) . "\">";
      echo "<img src=\"" . convert_bandcamp_link($image) . "\">";
      echo "</a>";
    };

    if (!empty($text)) echo "<p>" . nl2br(htmlspecialchars(trim($text))) . "</p>";

    if ($links && $links->count()) {
      echo "<p>";
      foreach ($links as $index => $link) {
        echo "<a href=\"" . convert_bandcamp_link($link->getAttribute("href")) . "\">" . htmlspecialchars($link->textContent) . "</a>";
        if ($index !== count($links) - 1) echo "<br>";
      };
      echo "</p>";
    };

    echo "</div>";
  };
?>
