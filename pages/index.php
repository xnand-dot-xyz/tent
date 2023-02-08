<?php include "../elements/header.php" ?>

<?php
  $scheme = $_SERVER["REQUEST_SCHEME"];
  $host = $_SERVER["HTTP_HOST"];
  $uri = $_SERVER["REQUEST_URI"];

  $url = $scheme . "://" . $host . str_replace("/index.php", "/", $uri);
?>

<h1><?= $config["title"] ?></h1>
<p>
  Tent is a simple alternative front-end for <a href="https://bandcamp.com/">Bandcamp</a>.
  <br>
  It was inspired by <a href="https://nitter.net/">Nitter</a> and the like.
</p>

<h2>How do I get started?</h2>
<p>Use the search bar at the top to find what you're looking for.</p>

<h2>Why would I use this?</h2>
<p>You might prefer Tent over the official Bandcamp website if you want to</p>
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

<h2>How do I set up Redirector?</h2>
<p>To set up a redirection extension, create the following rules:</p>
<ul>
  <li><code>https://bandcamp.com/search?q=$1</code> → <code><?= $url ?>search.php?query=$1</code></li>
  <li><code>https://$1.bandcamp.com/</code> → <code><?= $url ?>artist.php?name=$1</code></li>
  <li><code>https://f4.bcbits.com/img/$1</code> → <code><?= $url ?>image.php?file=$1</code></li>
</ul>

<h2>Is Tent open-source?</h2>
<p>
  Tent's source code can be found at <a href="https://codeberg.org/sun/Tent">Codeberg</a>.
  <br>
  To run it, download the repository, install <a href="https://www.php.net/">PHP</a> and its cURL and XML extensions and point your server to <code>pages</code>.
</p>

<?php include "../elements/footer.php" ?>
