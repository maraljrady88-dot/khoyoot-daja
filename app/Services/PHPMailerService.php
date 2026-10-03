<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class PHPMailerService
{
    /**
     * Send an OTP verification email using PHPMailer.
     *
     * @param string $recipientEmail
     * @param string $recipientName
     * @param string $otp 6-digit verification code
     * @param int $expiresMinutes
     * @return array ['success' => bool, 'message' => string, 'error' => string|null]
     */
    public function sendOtpEmail(string $recipientEmail, string $recipientName, string $otp, int $expiresSeconds = 30): array
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP / Mail Configuration from config() and .env
            $host = config('mail.mailers.smtp.host') ?: env('MAIL_HOST', '127.0.0.1');
            $port = (int) (config('mail.mailers.smtp.port') ?: env('MAIL_PORT', 587));
            $username = config('mail.mailers.smtp.username') ?: env('MAIL_USERNAME');
            $password = config('mail.mailers.smtp.password') ?: env('MAIL_PASSWORD');
            $encryption = strtolower((string) (config('mail.mailers.smtp.encryption') ?: env('MAIL_ENCRYPTION', 'tls')));
            $fromAddress = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS', 'noreply@khoyootdaja.alwaysdata.net');
            $fromName = config('mail.from.name') ?: env('MAIL_FROM_NAME', 'خيوط دعجاء');

            $mail->CharSet = 'UTF-8';
            $mail->Timeout = 15;

            // Determine delivery transport:
            // 1) If external SMTP credentials are provided, use SMTP
            $isLocalHost = in_array(strtolower(trim($host)), ['127.0.0.1', 'localhost', '']);
            $hasAuth = !empty($username) && !empty($password);

            if (!$isLocalHost && $hasAuth) {
                $mail->isSMTP();
                $mail->Host       = $host;
                $mail->Port       = $port;
                $mail->SMTPAuth   = true;
                $mail->Username   = $username;
                $mail->Password   = $password;

                if ($encryption === 'tls') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                } elseif ($encryption === 'ssl') {
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                } else {
                    $mail->SMTPSecure = false;
                    $mail->SMTPAutoTLS = false;
                }
            } elseif (function_exists('mail') || file_exists('/usr/sbin/sendmail')) {
                // Use server native sendmail MTA (Alwaysdata default)
                $mail->isSendmail();
            } else {
                $mail->isMail();
            }

            // Recipients & Sender Configuration
            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($recipientEmail, $recipientName ?: 'عميل خيوط دعجاء');
            $mail->addReplyTo($fromAddress, $fromName);

            // Anti-Spam & Delivery Optimization
            $mail->Hostname = 'khoyootdaja.alwaysdata.net';
            $mail->Sender = $fromAddress; // Sets Return-Path for SPF alignment
            $mail->XMailer = ''; // Suppress X-Mailer header so spam filters don't penalize
            $mail->Priority = 1;
            $mail->addCustomHeader('Auto-Submitted', 'auto-generated');
            $mail->addCustomHeader('X-Auto-Response-Suppress', 'OOF, AutoReply');

            // Format expiry display text (e.g. "30 ثانية" or "10 دقائق")
            $expiryText = $expiresSeconds >= 60 
                ? (round($expiresSeconds / 60) . ' دقائق') 
                : ($expiresSeconds . ' ثانية');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'رمز التحقق الخاص بحسابك في متجر خيوط دعجاء';
            $mail->Body    = $this->buildOtpEmailHtml($recipientName, $otp, $expiryText);
            $mail->AltBody = "مرحباً بك في متجر خيوط دعجاء\n\nرمز التحقق الخاص بحسابك هو: {$otp}\nهذا الرمز صالح لمدة {$expiryText}.\n\nتنبيه: لا تشارك هذا الرمز مع أي شخص.\nإذا لم تقم بإنشاء هذا الحساب، يمكنك تجاهل هذه الرسالة.";

            $mail->send();

            Log::info("Verification OTP email sent successfully via PHPMailer to: {$recipientEmail}");

            return [
                'success' => true,
                'message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني بنجاح.',
                'error'   => null,
            ];
        } catch (Exception $e) {
            $errorInfo = $mail->ErrorInfo ?: $e->getMessage();
            Log::error("PHPMailer failed to send OTP to {$recipientEmail}: " . $errorInfo);

            return [
                'success' => false,
                'message' => 'تعذر إرسال رسالة التحقق إلى البريد حالياً. يرجى التأكد من صحة البريد أو المحاولة لاحقاً.',
                'error'   => $errorInfo,
            ];
        } catch (\Throwable $e) {
            Log::error("General exception during email sending: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'حدث خطأ غير متوقع أثناء إرسال البريد. يرجى المحاولة بعد قليل.',
                'error'   => $e->getMessage(),
            ];
        }
    }

    /**
     * Generate luxury branded HTML email body.
     */
    protected function buildOtpEmailHtml(string $recipientName, string $otp, string $expiryText): string
    {
        $safeName = htmlspecialchars($recipientName ?: 'عميلنا العزيز', ENT_QUOTES, 'UTF-8');
        $siteUrl = rtrim(env('APP_URL', 'https://khoyootdaja.alwaysdata.net'), '/');

        return <<<HTML
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>رمز التحقق - خيوط دعجاء</title>
<style>
    body { margin: 0; padding: 0; background-color: #F6F3ED; font-family: 'Segoe UI', Tahoma, Arial, sans-serif; direction: rtl; text-align: right; }
    .email-container { max-width: 580px; margin: 30px auto; background-color: #FFFFFF; border-radius: 12px; overflow: hidden; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    .email-header { background-color: #141312; padding: 32px 20px; text-align: center; }
    .email-title { color: #DFC8A8; font-size: 22px; font-weight: 700; margin: 10px 0 0 0; letter-spacing: 0.5px; }
    .email-subtitle { color: #A8A199; font-size: 13px; margin-top: 4px; }
    .email-body { padding: 36px 30px; color: #1E293B; line-height: 1.7; font-size: 15px; }
    .greeting { font-size: 18px; font-weight: 700; color: #141312; margin-bottom: 12px; }
    .otp-box { background: #FAF8F5; border: 2px dashed #BFA175; border-radius: 10px; padding: 22px; text-align: center; margin: 26px 0; }
    .otp-code { font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: 800; color: #141312; letter-spacing: 8px; margin: 6px 0; display: inline-block; }
    .otp-expiry { font-size: 13px; color: #8C7355; margin-top: 6px; font-weight: 600; }
    .warning-box { background-color: #FFFBEB; border-right: 4px solid #D97706; padding: 12px 16px; border-radius: 4px; font-size: 13px; color: #92400E; margin: 20px 0; }
    .email-footer { background-color: #FAF8F5; padding: 22px; text-align: center; font-size: 12px; color: #64748B; border-top: 1px solid #E2E8F0; }
</style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <div style="font-size: 26px; color: #BFA175; font-weight: 800;">خيوط دعجاء</div>
        <div class="email-subtitle">أصالة وفخامة تليق بك ✦ عبايات خليجية فاخرة</div>
    </div>
    <div class="email-body">
        <div class="greeting">أهلاً بك في خيوط دعجاء، {$safeName} ✨</div>
        <p>
            سعداء جداً بانضمامك إلينا! لتأكيد بريدك الإلكتروني وتفعيل حسابك بنجاح، يُرجى استخدام رمز التحقق التالي:
        </p>

        <div class="otp-box">
            <div style="font-size: 13px; color: #64748B; margin-bottom: 4px;">رمز التحقق الخاص بحسابك (OTP):</div>
            <div class="otp-code">{$otp}</div>
            <div class="otp-expiry">⏱️ هذا الرمز صالح لمدة {$expiryText} فقط</div>
        </div>

        <div class="warning-box">
            <strong>⚠️ تنبيه أمني:</strong> لا تشارك هذا الرمز مع أي شخص. لن يطلب منك فريق خيوط دعجاء هذا الرمز عبر الهاتف أو الواتساب أبداً.
        </div>

        <p style="font-size: 13px; color: #64748B; margin-top: 24px;">
            إذا لم تقم بطلب إنشاء حساب في متجر خيوط دعجاء، يمكنك تجاهل هذه الرسالة ولن يتم إنشاء الحساب.
        </p>
    </div>
    <div class="email-footer">
        جميع الحقوق محفوظة &copy; 2026 <strong>متجر خيوط دعجاء للعبايات الخليجية</strong><br>
        <a href="{$siteUrl}" style="color: #BFA175; text-decoration: none; margin-top: 6px; display: inline-block;">زيارة المتجر: khoyootdaja.alwaysdata.net</a>
    </div>
</div>
</body>
</html>
HTML;
    }
}
