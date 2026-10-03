<?php
/**
 * Khoyoot Daja - Automatic Git Deployment Webhook
 * Supports GitHub Webhook (POST) and manual browser trigger (GET)
 */

header('Content-Type: application/json; charset=utf-8');

$secretKey = 'khoyoot_daja_deploy_secret_2026';
$providedSecret = $_GET['secret'] ?? '';

// Verify secret key
if (empty($providedSecret) || $providedSecret !== $secretKey) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized: Invalid or missing secret token.']);
    exit;
}

$laravelDir = '/home/khoyootdaja/daja/laravel';
$htdocsDir  = '/home/khoyootdaja/daja/htdocs';
$repoUrl    = 'https://github.com/maraljrady88-dot/khoyoot-daja.git';

$output = [];

function runCommand($cmd, &$output) {
    $out = [];
    $ret = 0;
    exec($cmd . ' 2>&1', $out, $ret);
    $output[] = [
        'cmd' => $cmd,
        'exit_code' => $ret,
        'output' => $out
    ];
    return $ret;
}

// 1. Ensure git is initialized and remote is set
if (!is_dir($laravelDir . '/.git')) {
    runCommand("cd " . escapeshellarg($laravelDir) . " && git init", $output);
}
runCommand("cd " . escapeshellarg($laravelDir) . " && git remote set-url origin " . escapeshellarg($repoUrl) . " 2>/dev/null || git remote add origin " . escapeshellarg($repoUrl), $output);

// 2. Fetch latest changes from GitHub
runCommand("cd " . escapeshellarg($laravelDir) . " && git fetch origin main", $output);

// 3. Reset to origin/main (preserves ignored files like .env and storage)
$resetRet = runCommand("cd " . escapeshellarg($laravelDir) . " && git reset --hard origin/main", $output);

// 4. Sync public assets to htdocs
if (is_dir($laravelDir . '/public')) {
    runCommand("cp -ru " . escapeshellarg($laravelDir . '/public/') . "* " . escapeshellarg($htdocsDir . '/'), $output);
}

// 4.5 Ensure composer dependencies are up to date
$checkCmd = "cd " . escapeshellarg($laravelDir) . " && php -r \"require 'vendor/autoload.php'; echo class_exists('PHPMailer\\\\PHPMailer\\\\PHPMailer') ? 'EXISTS' : 'NOT_FOUND';\"";
$checkOut = [];
$checkRet = 0;
exec($checkCmd . ' 2>&1', $checkOut, $checkRet);
$output[] = ['cmd' => $checkCmd, 'exit_code' => $checkRet, 'output' => $checkOut];

if (empty($checkOut) || in_array('NOT_FOUND', $checkOut) || !is_dir($laravelDir . '/vendor/phpmailer/phpmailer')) {
    runCommand("cd " . escapeshellarg($laravelDir) . " && composer install --no-dev --optimize-autoloader --no-interaction", $output);
}

// 5. Ensure storage symlinks exist
$laravelStorage = $laravelDir . '/storage/app/public';
if (!file_exists($htdocsDir . '/storage') && is_dir($laravelStorage)) {
    @symlink($laravelStorage, $htdocsDir . '/storage');
}
if (!file_exists($laravelDir . '/public/storage') && is_dir($laravelStorage)) {
    @symlink($laravelStorage, $laravelDir . '/public/storage');
}

// 6. Run migrations if any
$artisan = $laravelDir . '/artisan';
if (file_exists($artisan)) {
    runCommand("php " . escapeshellarg($artisan) . " migrate --force --no-interaction", $output);
    runCommand("php " . escapeshellarg($artisan) . " optimize:clear", $output);
}

// 7. Check mail capabilities and test PHPMailer isSendmail
$sendmailTestCmd = "cd " . escapeshellarg($laravelDir) . " && php -r '
require \"vendor/autoload.php\";
\$m = new \\PHPMailer\\PHPMailer\\PHPMailer(true);
\$m->isSendmail();
\$m->setFrom(\"noreply@khoyootdaja.alwaysdata.net\", \"خيوط دعجاء\");
\$m->addAddress(\"hhtlr5504@gmail.com\");
\$m->Subject = \"اختبار التحقق من خيوط دعجاء\";
\$m->Body = \"هذه رسالة اختبارية لتأكيد وصول بريد التحقق عبر سيرفر المتجر\";
try {
    \$m->send();
    echo \"SENDMAIL_DISPATCHED_SUCCESSFULLY\";
} catch (\\Throwable \$e) {
    echo \"SENDMAIL_ERROR: \" . \$e->getMessage();
}
'";
runCommand($sendmailTestCmd, $output);

// 8. Read latest log entries
runCommand("tail -n 15 " . escapeshellarg($laravelDir . '/storage/logs/laravel.log'), $output);

echo json_encode([
    'success' => ($resetRet === 0),
    'timestamp' => date('Y-m-d H:i:s'),
    'log' => $output
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
