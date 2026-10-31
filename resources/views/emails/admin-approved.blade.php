<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Akun Administrator</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f7fb; margin: 0; padding: 24px; color: #1e293b;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e2e8f0;">
        <!-- Header -->
        <tr>
            <td style="background-color: #0d47a1; padding: 28px 24px; text-align: center;">
                <div style="display: inline-block; width: 44px; height: 44px; background-color: rgba(255, 255, 255, 0.15); border-radius: 12px; line-height: 44px; font-size: 24px; margin-bottom: 8px;">
                    🛡️
                </div>
                <h1 style="color: #ffffff; margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -0.5px;">Sistem Inventaris Mess Tanjung Lesung</h1>
                <p style="color: #93c5fd; margin: 6px 0 0 0; font-size: 13px;">Pemberitahuan Persetujuan Hak Akses</p>
            </td>
        </tr>
        <!-- Content -->
        <tr>
            <td style="padding: 32px 28px;">
                <p style="margin: 0 0 16px 0; font-size: 15px; line-height: 1.6; color: #334155;">
                    Halo <b>{{ $user->name }}</b>,
                </p>
                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
                    <p style="margin: 0; font-size: 14px; font-weight: 600; color: #166534;">
                        🎉 Selamat! Permohonan hak akses Administrator Anda telah <b>DISETUJUI</b> oleh Administrator Pertama.
                    </p>
                    <p style="margin: 6px 0 0 0; font-size: 13px; color: #15803d; line-height: 1.5;">
                        Akun Anda sekarang memiliki wewenang penuh untuk mengelola, menambah, mengubah data aset, dan mengunggah dokumentasi fisik inventaris mess.
                    </p>
                </div>

                <!-- Account Info Box -->
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin-bottom: 28px;">
                    <h3 style="margin: 0 0 12px 0; font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                        Detail Akun Pengelola Anda:
                    </h3>
                    <table border="0" cellpadding="4" cellspacing="0" width="100%" style="font-size: 13px; color: #334155;">
                        <tr>
                            <td width="35%" style="color: #64748b;">Nama Lengkap:</td>
                            <td style="font-weight: 600;">{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Username:</td>
                            <td style="font-weight: 600;">{{ $user->username ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Alamat Email:</td>
                            <td style="font-weight: 600;">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Peran (Role):</td>
                            <td><span style="display: inline-block; background-color: #0d47a1; color: #ffffff; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 6px;">ADMINISTRATOR PENGELOLA</span></td>
                        </tr>
                    </table>
                </div>

                <!-- Action Button -->
                <div style="text-align: center; margin: 28px 0;">
                    <a href="{{ url('/login') }}" style="display: inline-block; background-color: #0d47a1; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 10px; font-weight: 600; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(13, 71, 161, 0.2);">
                        Masuk ke Sistem Inventaris &rarr;
                    </a>
                </div>

                <p style="margin: 20px 0 0 0; font-size: 12px; line-height: 1.6; color: #94a3b8; text-align: center;">
                    Gunakan username atau email di atas beserta kata sandi yang telah Anda buat saat mendaftar.
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
