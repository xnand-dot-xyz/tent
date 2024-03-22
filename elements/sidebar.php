<?php
  function echo_sidebar ($image, $text, $links = null) {
    echo "<div class=\"sidebar\">";

    if (isset($image)) echo "<img src=\"" . convert_link($image) . "\">";
    if (!empty($text)) echo "<p>" . nl2br(htmlspecialchars(trim($text))) . "</p>";

    if ($links) {
      echo "<p>";
      foreach ($links as $index => $link) {
        echo "<a href=\"" . convert_link($link->attr("href")) . "\">" . htmlspecialchars($link->text()) . "</a>";
        if ($index !== count($links) - 1) echo "<br>";
      };
      echo "</p>";
    };

    echo "</div>";
  };
?>
