<?php
/**
 * ONE-TIME UNZIP HELPER FOR SHARED HOSTING
 *
 * InfinityFree has no SSH, and uploading ~6,000 files over FTP takes hours.
 * Instead you upload one ZIP and let this script unpack it on the server.
 *
 *  1. Change SECRET below to something only you know.
 *  2. Upload this file and ojtms_app.zip into htdocs/
 *  3. Visit  https://<your-site>/extract.php?key=YOUR_SECRET
 *  4. DELETE BOTH extract.php AND ojtms_app.zip the moment it finishes.
 *
 * Leaving this script on a live server lets anyone who guesses the key
 * overwrite your files. It is a deployment tool, not part of the app.
 */

const SECRET  = 'change-this-to-something-random';
const ARCHIVE = 'ojtms_app.zip';

header('Content-Type: text/plain; charset=utf-8');

if (!isset($_GET['key']) || !hash_equals(SECRET, (string) $_GET['key'])) {
    http_response_code(403);
    exit("Forbidden.\n");
}

if (SECRET === 'change-this-to-something-random') {
    http_response_code(403);
    exit("Set your own SECRET inside extract.php before running it.\n");
}

$zipPath = __DIR__ . '/' . ARCHIVE;

if (!is_file($zipPath)) {
    exit("Archive not found: " . ARCHIVE . "\nUpload it next to this script first.\n");
}

if (!class_exists('ZipArchive')) {
    exit("This server has no ZipArchive extension. Ask support, or upload the files over FTP.\n");
}

echo "Extracting " . ARCHIVE . " (" . round(filesize($zipPath) / 1048576, 1) . " MB)...\n";
@set_time_limit(0);

$zip = new ZipArchive();
$opened = $zip->open($zipPath);

if ($opened !== true) {
    exit("Could not open the archive (error code {$opened}).\n");
}

if (!$zip->extractTo(__DIR__)) {
    $zip->close();
    exit("Extraction failed. Check that htdocs is writable and you have space left.\n");
}

$count = $zip->numFiles;
$zip->close();

echo "Done — {$count} files extracted.\n\n";
echo "NOW DELETE extract.php AND " . ARCHIVE . " FROM THE SERVER.\n";
