# FTP Zipper (PHP) by Vejnø
Make ZIP-file of a folder. Eg. Zipping a folder for downloading it faster than via normal FTP. 
Useful if you do not have SFTP or SSH access to your web server and want to download a large folder.

## Usage
- [Download the `vejnoe-zipper.php` file](https://github.com/vejnoe/vejnoe-zipper/archive/master.zip).
- Uploade it to your server eg. via FTP.
- Browse the file on you server via a internet browser. Eg. `http://www.vejnoe.dk/vejnoe-zipper.php`
- Click the folder you wish to ZIP.
- Done. You can now **download** you file, **delete** it after download and go back to **self destruct** this script.

## Warning
By adding this file to a live server poses a security risk. Make sure to delete the file again after use.  
You can do this by the "Self destruct" button.

## Issues
If the folder is to big the script can fail if servers execution time are limited.
