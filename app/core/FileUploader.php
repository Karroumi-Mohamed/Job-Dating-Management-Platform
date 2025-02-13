<?php

namespace App\Core;

class FileUploader
{
    private static $allowedExtensions = ['jpg', 'jpeg', 'png'];
    private static $uploadDirectory = __DIR__.'/../../public/assets/uploads/';

    public static function upload($file, $directory = '')
    {
        if (!$file || $file['error'] !== 0) {
            error_log('File upload error: ' . print_r($file, true));
            return false;
        }

        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, self::$allowedExtensions)) {
            error_log('Invalid file extension: ' . $fileExtension);
            return false;
        }

        $uploadPath = self::$uploadDirectory . $directory;
        if (!file_exists($uploadPath)) {
            if (!mkdir($uploadPath, 0777, true)) {
                error_log('Failed to create directory: ' . $uploadPath);
                return false;
            }
        }

        $storedFileName = time() . '_' . $fileName;
        $fullPath = $uploadPath . $storedFileName;

        if (!move_uploaded_file($fileTmpName, $fullPath)) {
            error_log('Failed to move uploaded file to: ' . $fullPath);
            return false;
        }

        // Return the relative path for database storage
        return 'assets/uploads/' . $directory . $storedFileName;
    }

    public static function delete($path)
    {
        if (!$path) return;
        
        $fullPath = __DIR__ . '/../../public/' . $path;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
} 