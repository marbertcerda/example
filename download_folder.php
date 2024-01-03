<?php

// Replace 'your-folder-path' with the actual path to your folder
$folderPath = 'assets\uploads';

// Replace 'downloaded-folder.zip' with the desired name for the downloaded ZIP file
$zipFileName = 'downloaded-folder.zip';

// Function to create a ZIP file from a folder
function zipFolder($folderPath, $zipFileName)
{
    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($folderPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folderPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();
        return true;
    } else {
        return false;
    }
}

// Create the ZIP file
if (zipFolder($folderPath, $zipFileName)) {
    // Prompt the user to download the ZIP file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipFileName));

    readfile($zipFileName);

    // Delete the temporary ZIP file
    unlink($zipFileName);
} else {
    echo 'Failed to create the ZIP file.';
}
var_dump(file_exists($folderPath));
var_dump(is_readable($folderPath));

?>
