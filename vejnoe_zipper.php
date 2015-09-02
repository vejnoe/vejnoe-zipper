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
      margin-top: 4rem;
    }
    .button:hover, .button:focus {
      color: #fff;
      background-color: #2281d0;
    }
    .alert { background-color: #C8073B; }
    .alert:hover, .alert:focus { background-color: #9a002a; }
    .tiny {
      font-size: .8rem;
      line-height: 1.5rem;
      padding: 0 .5rem;
    }
    .delete { margin-left: 1rem; }
    ul { padding-left: 1.4rem; }
    li.folder { list-style-image: url(//files.vejnoe.dk/theme/icons/folder-osx.png); }
    li.zip { list-style-image: url(//files.vejnoe.dk/theme/icons/zip.png); }
  </style>
</head>
<body>
<div class="content">
<?php
// The zipper script
if (isset($_GET['delete'])) {

  if (file_exists($_GET['delete'] . '.zip')) {
    // Delete file
    unlink($_GET['delete'] . '.zip');
    // Content
    print '<h2>File deleted</h2>';
    print '<ul><li class="zip" style="text-decoration: line-through">' . $_GET['delete'] . '.zip</li></ul>';
  } else {
    // Content
    print '<h2>File does not exists</h2>';
    print '<p><code>' . $_GET['delete'] . '.zip</code></p>';
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
  print '<ul><li class="zip"><a href="' . $_GET['path'] . $the_date . '.zip">' . $_GET['path'] . $the_date . '.zip</a> <a href="?delete=' . $_GET['path'] . $the_date . '" class="button alert tiny delete">Delete</a></li></ul>';
  print '<br><br><a href="' . basename(__FILE__, '.php') . '.php" class="button back">Back</a>';

} else if (isset($_GET['self-destruct']) && $_GET['self-destruct'] == true) {

  print '<h2>Good bye</h2>';
  print '<p><code>' . __FILE__ . '</code> has been deleted.</p>';
  unlink(__FILE__);

} else {

  // Get current directory
  $rootPath = realpath('.');
  $directory = array_diff(scandir($rootPath), array('..', '.'));
  $directory = array_filter($directory, 'is_dir');

  // Content
  print '<h2>Click a folder to ZIP</h2>';
  print 'Current directory <code>' . $rootPath . '/</code>';
  print '<ul>';
  foreach ($directory as $folder) {
    print '<li class="folder"><a href="?path='.$folder.'">'.$folder.'</a></li>';
  }
  print '</ul>';
  print '<a href="?self-destruct=true" class="button alert">Self destruct</a>';

}
?>
</div>
</body>
</html>
