<?php require_once "../elements/header.php" ?>
<?php require_once "../utilities/link.php" ?>

<h1><?= $config["title"] ?></h1>
<p>
  <?= $config["title"] ?> is a simple alternative front-end for <a href="https://bandcamp.com/">Bandcamp</a>.
  <br>
  It is free and open source software, with its code available on <a href="https://forgejo.sny.sh/sun/Tent">Forgejo</a>.
  <br>
  It was inspired by <a href="https://invidious.io/">Invidious</a>, <a href="https://nitter.net/">Nitter</a> and the like.
</p>

<h2>How do I get started?</h2>
<p>Use the search bar at the top to find what you're looking for.</p>

<h2>Why would I use this?</h2>
<p>You might prefer it over the official Bandcamp website if you want to</p>
<ul>
  <li>browse Bandcamp without enabling JavaScript,</li>
  <li>escape Bandcamp's analytics or</li>
  <li>use a more lightweight website.</li>
</ul>

<h2>Why would I not use this?</h2>
<p>The official website may be preferable if you want to</p>
<ul>
  <li>support the artists you listen to,</li>
  <li>purchase releases or merchandise or</li>
  <li>manage your account or collection.</li>
</ul>

<h2>How do I set up redirection?</h2>
<p>
  The easiest way to automatically redirect Bandcamp to <?= $config["title"] ?> is to use <a href="https://libredirect.github.io/">LibRedirect</a>.
  <br>
  You can also use a generic redirection extension, in which case you need to create the following rules:
</p>
<ul>
  <?php
    $rules = [
      "https://$1.bandcamp.com/",
      "https://$1.bandcamp.com/$2/$3",
      "https://bandcamp.com/search?q=$1",
      "https://f4.bcbits.com/img/$1",
      "https://t4.bcbits.com/stream/$1/$2/$3?token=$4"
    ];

    foreach ($rules as $rule) {
      echo "<li>";
      echo "<code>" . $rule . "</code>";
      echo " → ";
      echo "<code>" . urldecode(convert_link($rule)) . "</code>";
      echo "</li>";
    };
  ?>
</ul>

<?php require_once "../elements/footer.php" ?>
