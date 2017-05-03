<html>
<head>
  <!--

  Vejnø Zipper - Beta v0.1
  https://github.com/vejnoe/vejnoe-zipper

  |||||||||||||||   Vejnø
  |||   |||   |||   Andreas Vejnø Andersen
  |||   |||   |||   www.vejnoe.dk
  |||||||||||||||   © 2015

  Inspired by Dador @ http://stackoverflow.com/questions/4914750/how-to-zip-a-whole-folder-using-php#4914807

  -->
  <title>Vejnø Zipper</title>
  <style>
    @import url(https://fonts.googleapis.com/css?family=Open+Sans:400,700);
    html {
      min-height: 100%;
      background-color: #ECEEF1;
      color: #61666c;
      font-weight: 400;
      font-size: 1rem;
      font-family: 'Open Sans', sans-serif;
      line-height: 1.5rem;
    }
    body { padding: 4rem; }
    h1, h2, h3, h4 { font-weight: 700; }
    code {
      font-family:consolas,monospace;
      background-color: #fff;
      line-height: 4rem;
      padding: .2rem;
    }
    code.alert { color: #9a002a; }
    code.success { color: #339c54; }
    a {
      color: #61666c;
      text-decoration: none;
    }
    a:hover { color: #2281d0; }
    .button {
      color: #fff;
      background-color: #2281d0;
      text-decoration: none;
      display: inline-block;
      line-height: 2rem;
      padding: 0 1rem;
      border-radius: 2px;
    }
    .button:hover, .button:focus {
      color: #fff;
      background-color: #1664a6;
    }
    .button.alert { background-color: #C8073B; }
    .button.alert:hover, .button.alert:focus { background-color: #9a002a; }
    .tiny {
      font-size: .8rem;
      line-height: 1.5rem;
      padding: 0 .5rem;
    }
    ul { padding-left: 1.4rem; margin-bottom: 3rem; }
    li { margin-bottom: .4rem; }
    li.folder { list-style-image: url(//files.vejnoe.dk/theme/icons/folder-osx.png); }
    li.zip { list-style-image: url(//files.vejnoe.dk/theme/icons/zip.png); }
    .vejnoe:hover svg g#logo { fill: #2281d0; }
  </style>
</head>
<body>
<div class="content">
<?php
// The zipper script
if (isset($_GET['delete'])) {

  if (file_exists($_GET['delete'])) {
    // Delete file
    unlink($_GET['delete']);
    // Content
    print '<h2>File deleted</h2>';
    print '<ul><li class="zip" style="text-decoration: line-through">' . $_GET['delete'] . '.zip</li></ul>';
  } else {
    // Content
    print '<h2>File does not exists</h2>';
    print '<p><code>' . $_GET['delete'] . '</code></p>';
  }

  print '<br><br><a href="' . basename(__FILE__, '.php') . '.php" class="button back">Back</a>';

} else if (isset($_GET['path'])) {

  // Get date
  $the_date = date("_Y-m-d_H.i.s");
  // Get real path for our folder
  $rootPath = realpath($_GET['path']);
  // Initialize archive object
  $zip = new ZipArchive();
  $zip->open($_GET['path'] . $the_date . '.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

  // Create recursive directory iterator
  /** @var SplFileInfo[] $files */
  $files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
  );
  foreach ($files as $name => $file)
  {
    // Skip directories (they would be added automatically)
    if (!$file->isDir())
    {
      // Get real and relative path for current file
      $filePath = $file->getRealPath();
      $relativePath = substr($filePath, strlen($rootPath) + 1);

      // Add current file to archive
      $zip->addFile($filePath, $relativePath);
    }
  }
  // Zip archive will be created only after closing object
  $zip->close();

  // Content
  print '<h2>Folder zipped</h2>';
  print '<ul><li class="zip"><a href="' . $_GET['path'] . $the_date . '.zip">' . $_GET['path'] . $the_date . '.zip</a>   <a href="?delete=' . $_GET['path'] . $the_date . '.zip" class="button alert tiny delete">Delete</a></li></ul>';
  print '<br><br><a href="' . basename(__FILE__, '.php') . '.php" class="button back">Back</a>';

} else if (isset($_GET['self-destruct']) && $_GET['self-destruct'] == true) {

  print '<h2>Good bye</h2>';
  print '<p><code>' . __FILE__ . '</code> has been deleted.</p>';
  print '<p><strong>Thanks for using this script</strong><br>If you found it helpfull please Star this project on GitHub :)</p>';
  print '<iframe src="https://ghbtns.com/github-btn.html?user=vejnoe&repo=vejnoe_zipper&type=watch&count=false&size=large&v=2" frameborder="0" scrolling="0" width="100px" height="30px"></iframe>';
  print '<iframe src="https://ghbtns.com/github-btn.html?user=vejnoe&repo=vejnoe_zipper&type=fork&count=false&size=large" frameborder="0" scrolling="0" width="87px" height="30px"></iframe>';
  print '<iframe src="https://ghbtns.com/github-btn.html?user=vejnoe&type=follow&count=false&size=large" frameborder="0" scrolling="0" width="220px" height="30px"></iframe>';
  print '<br><br><a class="vejnoe" href="http://vejnoe.dk?from=vejnoe_zipper" target="_blank" title="Vejnø – Creative Web Development – www.vejnoe.dk – ">Creative Web Development by <svg height="9px" version="1.1" viewbox="0 0 10 6" width="15px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g fill="none" fill-rule="evenodd" id="vejnoe" stroke="none" stroke-width="0"><g fill="#666666" id="logo" transform="translate(-1164.000000, -2473.000000)"><path d="M1164,2473 L1174,2473 L1174,2479 L1164,2479 L1164,2473 Z M1166,2475 L1168,2475 L1168,2477 L1166,2477 L1166,2475 Z M1170,2475 L1172,2475 L1172,2477 L1170,2477 L1170,2475 Z" id="vejnoe-logo"></path></g></g></svg> Vejnø</a>';
  // Deleting the hole script file
  unlink(__FILE__);

} else {

  if (isset($_GET['unzip'])):
    $zip = new ZipArchive;
    $res = $zip->open($_GET['unzip']);
    if ($res === TRUE) {
      $zip->extractTo(realpath('.'));
      $zip->close();
      print '<code class="success">"' . $_GET['unzip'] . '" got Unziped</code>';
    } else {
      print '<code class="alert">Sorry, could&#039;t Unzip "' . $_GET['unzip'] . '"!</code>';
    }
  endif;

  // Get current directory
  $rootPath = realpath('.');
  $directory = array_diff(scandir($rootPath), array('..', '.'));

  // Content
  print '<h2>Click a folder to ZIP</h2>';
  print 'Current directory <code>' . $rootPath . '/</code>';
  print '<ul>';
  foreach ($directory as $file) {
    if (is_dir($file)):
      print '<li class="folder">'.$file.'   <a href="?path='.$file.'" class="button tiny zip">Zip</a></li>';
    elseif (preg_match('/.+\.zip$/', $file)):
      print '<li class="zip">'.$file.'   <a href="?delete=' . $file . '" class="button tiny alert delete">Delete</a> <a href="?unzip=' . $file . '" class="button tiny unzip">Unzip</a></li>';
    endif;
  }
  print '</ul>';
  print '<a href="?self-destruct=true" class="button alert">Self destruct</a>';

}
?>
</div>
</body>
</html>
