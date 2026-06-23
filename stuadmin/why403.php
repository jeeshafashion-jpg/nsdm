<?php
http_response_code(200); // show page content even though it was a 403
header('Content-Type: text/plain; charset=UTF-8');

echo "WHY403 DEBUG\n";
echo "Time: ".date('c')."\n\n";

echo "REQUEST_URI: ".($_SERVER['REQUEST_URI'] ?? '')."\n";
echo "SCRIPT_FILENAME: ".($_SERVER['SCRIPT_FILENAME'] ?? '')."\n";
echo "REMOTE_ADDR: ".($_SERVER['REMOTE_ADDR'] ?? '')."\n";
echo "USER_AGENT: ".($_SERVER['HTTP_USER_AGENT'] ?? '')."\n\n";

echo "All request headers:\n";
foreach (getallheaders() as $k=>$v) echo "$k: $v\n";

echo "\nFile/dir perms check:\n";
$paths = [
  __DIR__,
  __FILE__,
  __DIR__.'/add.php',
  __DIR__.'/add_record.php',
  __DIR__.'/test-ok.php',
];
foreach ($paths as $p) {
  if (file_exists($p)) {
    $perms = substr(sprintf('%o', fileperms($p)), -4);
    echo "$p => perms $perms, owner ".get_current_user()."\n";
  } else {
    echo "$p => (missing)\n";
  }
}

echo "\nPHP info snippet:\n";
echo "PHP_SAPI: ".PHP_SAPI."\n";
echo "version: ".PHP_VERSION."\n";
