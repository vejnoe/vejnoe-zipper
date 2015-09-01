<?php
if (!isset($_GET['path']) || $_GET['path'] == '') {
  print '<h3>Click a folder to ZIP</h3>';

  $rootPath = realpath('.');
  $directory = array_diff(scandir($rootPath), array('..', '.'));
  $directory = array_filter($directory, 'is_dir');
  print '<code>' . $rootPath . '/</code>';
  print '<ul>';
  foreach ($directory as $folder) {
    print '<li><a href="?path='.$folder.'">'.$folder.'</a></li>';
  }
  print '</ul>';

} else if (isset($_GET['delete']) && $_GET['path'] != '') {
  unlink($_GET['delete'] . '.zip');
  print '<h3>' . $_GET['delete'] . '.zip has been deleted</h3>';
  print '<br><br><a href="' . basename(__FILE__, '.php') . '.php">Zip one more</a>';
} else {
  // Set date:
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
  print '<h3>Folder zipped</h3>';
  print 'Download file <code><a href="' . $_GET['path'] . $the_date . '.zip">' . $_GET['path'] . $the_date . '.zip</a></code>  <a href="?delete=' . $_GET['path'] . $the_date . '">[delete]</a>';
  print '<br><br><a href="' . basename(__FILE__, '.php') . '.php">Zip one more</a>';
}
?>
