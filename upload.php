<?php
error_reporting(E_ALL);
$debug=false;
if ($debug) {
var_dump($_FILES["fileToUpload"]);
}
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["tmp_name"]);
$uploadOk = 1;
$fileType = pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION);
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if( $fileType != "pdf" ) {
    echo "Sorry, only PDF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
} else {
   move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);

   exec("touch " . $target_file . ".ctl");

   $path = "downloads/".basename($_FILES["fileToUpload"]["tmp_name"]) . ".pdf";

   for ($i = 1; $i <= 60; $i++) {

      if (file_exists($path . ".ctl")){
         break;
      }

      sleep(2);
   }

   // check that file exists and is readable
   if (file_exists($path) && is_readable($path)) {
      // get the file size and send the http headers
      $size = filesize($path);
      header('Content-Type: application/octet-stream');
      header('Content-Length: '.$size);
      header('Content-Disposition: attachment; filename='. $_FILES["fileToUpload"]["name"].'-MDF.pdf');
      header('Content-Transfer-Encoding: binary');
      // open the file in binary read-only mode
      // display the error messages if the file can´t be opened
      $file = @ fopen($path, 'rb');
      if ($file) {
         // stream the file and exit the script when complete
         fpassthru($file);
         exit;
      } 
   } else{
      if ($debug) {
         echo "Upload to " . $path . ". Exist ? " . file_exists($path) . " . Read? " . is_readable($path);
         var_dump($_FILES["fileToUpload"]);
      } else {
          echo "Upload to " . $path;
	}
   }
}
?>
