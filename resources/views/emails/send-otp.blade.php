<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7fb; margin: 0; padding: 24px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
        <!-- Header -->
        <tr>
            <td style="background-color: #0d47a1; padding: 28px 24px; text-align: center;">
                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.5px;">Sistem Inventaris Mess Tanjung Lesung</h1>
                <p style="color: #93c5fd; margin: 6px 0 0 0; font-size: 13px;">Verifikasi Email Pendaftaran Pengelola</p>
            </td>
        </tr>
        <!-- Content -->
        <tr>
            <td style="padding: 32px 28px;">
                <p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #334155;">
                    Halo <b>{{ $name }}</b>,
                </p>
                <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                    Terima kasih telah mendaftar sebagai pengelola pada Sistem Inventaris Mess Tanjung Lesung. Silakan gunakan kode OTP di bawah ini untuk memverifikasi alamat email Anda:
                </p>

                <!-- OTP Box -->
                <div style="background-color: #f8fafc; border: 2px dashed #0d47a1; border-radius: 12px; padding: 20px; text-align: center; margin: 24px 0;">
                    <span style="font-size: 34px; font-weight: 800; letter-spacing: 8px; color: #0d47a1; font-family: monospace;">{{ $otp }}</span>
                    <p style="margin: 8px 0 0 0; font-size: 12px; color: #64748b;">Kode berlaku selama <b>15 menit</b></p>
                </div>

                <p style="margin: 0 0 16px 0; font-size: 13px; line-height: 1.6; color: #64748b;">
                    ⚠️ Jangan bagikan kode OTP ini kepada siapa pun demi keamanan akun Anda. Jika Anda tidak merasa melakukan pendaftaran ini, abaikan email ini.
                </p>
            </td>
        </tr>
        <!-- Footer -->
        <tr>
            <td style="background-color: #f8fafc; padding: 18px 24px; text-align: center; border-top: 1px solid #e2e8f0;">
                <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                    &copy; {{ date('Y') }} Sistem Inventaris Mess Kawasan Tanjung Lesung. Seluruh hak cipta dilindungi.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>
