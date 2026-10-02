<?php
/**
 * Khoyoot Daja - Automatic Git Deployment Webhook
 * Supports GitHub Webhook (POST) and manual browser trigger (GET)
 */

header('Content-Type: application/json; charset=utf-8');

$secretKey = 'khoyoot_daja_deploy_secret_2026';
$providedSecret = $_GET['secret'] ?? $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// Simple token authentication
if (empty($_GET['secret']) || $_GET['secret'] !== $secretKey) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized: Invalid or missing secret token.']);
    exit;
}

$laravelDir = '/home/khoyootdaja/daja/laravel';
$htdocsDir  = '/home/khoyootdaja/daja/htdocs';
$repoUrl    = 'https://github.com/maraljrady88-dot/khoyoot-daja.git';

$output = [];
$status = 0;

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

// 1. Initialize git if not already a git repository
if (!is_dir($laravelDir . '/.git')) {
    runCommand("cd " . escapeshellarg($laravelDir) . " && git init", $output);
    runCommand("cd " . escapeshellarg($laravelDir) . " && git remote add origin " . escapeshellarg($repoUrl), $output);
}

// 2. Pull latest code from GitHub main branch
$pullRet = runCommand("cd " . escapeshellarg($laravelDir) . " && git pull origin main", $output);

// 3. Sync public folder to htdocs
if (is_dir($laravelDir . '/public')) {
    runCommand("cp -ru " . escapeshellarg($laravelDir . '/public/') . "* " . escapeshellarg($htdocsDir . '/'), $output);
}

// 4. Run Laravel cache clear
$artisan = $laravelDir . '/artisan';
if (file_exists($artisan)) {
    runCommand("php " . escapeshellarg($artisan) . " optimize:clear", $output);
}

echo json_encode([
    'success' => ($pullRet === 0),
    'timestamp' => date('Y-m-d H:i:s'),
    'log' => $output
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
