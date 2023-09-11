<?php
namespace Utils;

class FileUploader
{
  private $allowedExtensions = ["jpeg", "jpg", "png"];
  private $maxFileSize = 2097152; // 2MB
  private $uploadPath = "resource/uploads/";

  public function __construct($config = [])
  {
    if (isset($config["allowedExtensions"])) {
      $this->allowedExtensions = $config["allowedExtensions"];
    }
    if (isset($config["maxFileSize"])) {
      $this->maxFileSize = $config["maxFileSize"];
    }
    if (isset($config["uploadPath"])) {
      $this->uploadPath = $config["uploadPath"];
    }
  }

  public function upload($file)
  {
    $errors = [];
    $fileName = $file["name"];
    $fileSize = $file["size"];
    $fileTmpName = $file["tmp_name"];
    $fileType = $file["type"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $this->allowedExtensions)) {
      $errors[] =
        "Extension not allowed, please choose a " .
        implode(", ", $this->allowedExtensions) .
        " file.";
    }

    if ($fileSize > $this->maxFileSize) {
      $errors[] =
        "File size must be less than " .
        $this->formatBytes($this->maxFileSize) .
        ".";
    }

    if (empty($errors)) {
      $newFileName = uniqid() . "." . $fileExt;
      $destination = $this->uploadPath . $newFileName;
      if (move_uploaded_file($fileTmpName, $destination)) {
        return '/' . $destination;
      } else {
        $errors[] = "An error occurred while uploading the file.";
      }
    }
    return $errors;
  }

  private function formatBytes($size, $precision = 2)
  {
    $base = log($size, 1024);
    $suffixes = ["B", "KB", "MB", "GB", "TB"];
    return round(pow(1024, $base - floor($base)), $precision) .
      $suffixes[floor($base)];
  }
}