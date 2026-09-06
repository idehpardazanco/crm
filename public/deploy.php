<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$secret = 'AB5B91BBA6884632B19187A8E3AC2'; // Your GitHub Webhook Secret
$logFile = '/home/tabasicrm/public_html/webhook.log';
$repoPath = '/home/tabasicrm/public_html/backend';
$composerPath = '/usr/local/bin/composer';
$phpPath= '/usr/local/bin/php';
$branch = 'main';

$nodePath = '/bin/node';
$npmPath = '/bin/npm';

// Function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Get the raw GitHub payload
$rawPost = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE'] ?? ''; // GitHub signature

// Compute the expected hash
$computedHash = 'sha1=' . hash_hmac('sha1', $rawPost, $secret);

// Log request headers and body for debugging
// logMessage("GitHub Signature: " . $signature);
// logMessage("Computed Signature: " . $computedHash);
// logMessage("Raw Payload: " . $rawPost);

// // Validate signature 
// if (!$signature || !hash_equals($computedHash, $signature)) {
//     http_response_code(403);
//     logMessage("ERROR: Signature verification failed!");
//     exit("Access denied!");
// }

// Signature is valid, proceed with Git pull
// logMessage("Signature verification successful. Running deployment...");

// Start SSH agent (necessary for private repositories)
exec("eval $(ssh-agent -s) && ssh-add .ssh/id_rsa", $output, $returnVar);

logMessage("SSH Agent Output: " . implode("\n", $output));

// Run Git pull
exec("cd $repoPath && git reset --hard origin/$branch && git pull origin $branch 2>&1", $output, $returnVar);
logMessage("Git Pull Output: " . implode("\n", $output));

if ($returnVar !== 0) { 
    logMessage("ERROR: Git pull failed!");
    exit("Git pull failed.");
}

// Run Composer Install
// exec("cd $repoPath && $phpPath $composerPath install --no-dev --ignore-platform-req=ext-sodium --optimize-autoloader 2>&1", $output);
// logMessage("Composer Output: " . implode("\n", $output));

// // Run Composer Update
// exec("cd $repoPath && $phpPath $composerPath update --no-dev --ignore-platform-req=ext-sodium --optimize-autoloader 2>&1", $output);
// logMessage("Composer Update Output: " . implode("\n", $output));

// // Clear Laravel Cache
// exec("cd $repoPath && $phpPath artisan cache:clear 2>&1", $output);
// exec("cd $repoPath && $phpPath artisan config:clear 2>&1", $output);
// exec("cd $repoPath && $phpPath artisan route:clear 2>&1", $output);
// logMessage("Cache Clear Output: " . implode("\n", $output));

$output = [];

// Composer Install
// $output = [];
// exec("cd $repoPath && $phpPath -d register_argc_argv=Off $composerPath install --no-dev --ignore-platform-req=ext-sodium --optimize-autoloader 2>&1", $output);
// logMessage("Composer Output: " . implode("\n", $output));

// NPM Install with dev dependencies
$output = [];
exec("cd $repoPath && $npmPath install --include=dev 2>&1", $output);
logMessage("NPM Install Output: " . implode("\n", $output));

// Vite Build
$output = [];
// exec("cd $repoPath && $npmPath run build 2>&1", $output);
exec("cd $repoPath && PATH=$repoPath/node_modules/.bin:/bin:/usr/bin:/usr/local/bin $npmPath run build 2>&1", $output, $returnVar);
logMessage("NPM Build Output: " . implode("\n", $output));

// Laravel Migrate
$output = [];
exec("cd $repoPath && $phpPath artisan migrate --force 2>&1", $output);
logMessage("Migrate Output: " . implode("\n", $output));

// Clear Laravel Cache
$output = [];
exec("cd $repoPath && $phpPath artisan optimize:clear 2>&1", $output);
logMessage("Optimize Clear Output: " . implode("\n", $output));

// Cache Laravel Config/Routes/Views
$output = [];
exec("cd $repoPath && $phpPath artisan config:cache 2>&1", $output);
exec("cd $repoPath && $phpPath artisan route:cache 2>&1", $output);
exec("cd $repoPath && $phpPath artisan view:cache 2>&1", $output);
logMessage("Laravel Cache Output: " . implode("\n", $output));

logMessage("✅ Webhook deployment completed successfully.");
echo "Webhook processed successfully.";



?>
