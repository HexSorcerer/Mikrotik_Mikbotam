

<?php
   // =====================================================START====================//

   /*

   Core_default.php

   Diperbaharui pada 25 Juli 2024 by HexSorcerer

    */

   // =====================================================START SCRIPT====================//

   date_default_timezone_set('Asia/Jakarta');
include 'src/FrameBot.php';
require_once '../config/system.conn.php';
$mkbot = new FrameBot($token, $usernamebot);
require_once '../config/system.byte.php';
require_once '../Api/routeros_api.class.php';

// Any commands akan di cegah dengan ini jika  perlu silahakan dihapus /* dan  */

/*
$mkbot->cmd('*', 'Maaf commands tidak tersedia');
 */

// Start commands
$mkbot->cmd('/start|/Start', function () {
    include '../config/system.conn.php';
    $info = bot::message();
    $ids = $info['chat']['id'];
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');

    if (has($idtelegram) == false) {
        $text = "👋 <b>Selamat datang di Layanan Kami!</b>\n\n";
        $text .= "Anda belum terdaftar sebagai pengguna. Untuk mulai menggunakan layanan kami, silakan daftar terlebih dahulu.\n\n";
        $text .= 'Gunakan perintah /daftar untuk mendaftar atau tekan tombol di bawah ini.';

        $options = [
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => '📝 Daftar Sekarang', 'callback_data' => 'daftar']],
                    [['text' => '❓ Informasi Layanan', 'callback_data' => 'info_layanan']],
                ],
            ]),
        ];
    } else {
        $text = "👋 <b>Hai @$nametelegram!</b>\n\n";
        $text .= "Selamat datang kembali di layanan kami. Ada yang bisa kami bantu?\n\n";
        $text .= 'Gunakan perintah /help untuk melihat daftar bantuan atau pilih menu di bawah ini:';

        $options = [
            'parse_mode' => 'html',
            'reply_markup' => json_encode([
                'inline_keyboard' => [
                    [['text' => '💰 Cek Saldo', 'callback_data' => 'cek_saldo']],
                    [['text' => '📦 Beli Paket', 'callback_data' => 'beli_paket']],
                    [['text' => '📞 Hubungi Admin', 'url' => 'https://t.me/ahmadcircleid']],
                    [['text' => '❓ Bantuan', 'callback_data' => 'help']],
                ],
            ]),
        ];
    }

    return Bot::sendMessage($text, $options);
});
// deposit commands
$mkbot->cmd('/deposit|/request', function ($jumlah) {
    include '../config/system.conn.php';
    $info = bot::message();
    $ids = $info['chat']['id'];
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];

    if (!empty($jumlah)) {
        if (has($idtelegram) == false) {
            $text = "❌ <b>Anda belum terdaftar</b>\n\n";
            $text .= 'Silakan daftar terlebih dahulu dengan /daftar sebelum melakukan request top up saldo.';
        } else {
            if (preg_match('/^[0-9]+$/', $jumlah)) {
                if (strlen($jumlah) < 7) {
                    $text = "✅ <b>Permintaan Deposit Diterima</b>\n\n";
                    $text .= "👤 User: @$nametelegram\n";
                    $text .= '💰 Jumlah: '.rupiah($jumlah)."\n\n";
                    $text .= "📸 Silakan kirimkan foto bukti pembayaran dengan caption:\n";
                    $text .= "<code>#konfirmasi deposit $jumlah</code>\n\n";
                    $text .= '⏳ Konfirmasi maksimal 2 jam setelah permintaan deposit.';

                    $textsend = "🔔 <b>Permintaan Deposit Baru</b>\n\n";
                    $textsend .= "👤 User: @$nametelegram\n";
                    $textsend .= "🆔 ID: <code>$idtelegram</code>\n";
                    $textsend .= '💰 Nominal: '.rupiah($jumlah)."\n\n";
                    $textsend .= "Silakan tindak lanjuti atau hubungi user @$nametelegram\n\n";
                    $textsend .= 'Tekan tombol di bawah untuk top up otomatis:';

                    $kirimpelangan = [
                       'chat_id' => $id_own,
                       'text' => $textsend,
                       'reply_markup' => json_encode([
                          'inline_keyboard' => [
                             [
                                ['text' => 'QUICK TOP UP', 'callback_data' => '12'],
                             ],
                             [
                                ['text' => rupiah($jumlah), 'callback_data' => "tp|$jumlah|$idtelegram|$nametelegram"],
                             ],
                             [
                                ['text' => 'OR CUSTOM', 'callback_data' => '12'],
                             ],
                             [
                                ['text' => '10000', 'callback_data' => "tp|10000|$idtelegram|$nametelegram"],
                                ['text' => '15000', 'callback_data' => "tp|15000|$idtelegram|$nametelegram"],
                                ['text' => '20000', 'callback_data' => "tp|20000|$idtelegram|$nametelegram"],
                             ],
                             [
                                ['text' => '25000', 'callback_data' => "tp|25000|$idtelegram|$nametelegram"],
                                ['text' => '30000', 'callback_data' => "tp|30000|$idtelegram|$nametelegram"],
                                ['text' => '50000', 'callback_data' => "tp|50000|$idtelegram|$nametelegram"],
                             ],
                             [
                                ['text' => '100000', 'callback_data' => "tp|100000|$idtelegram|$nametelegram"],
                                ['text' => '150000', 'callback_data' => "tp|150000|$idtelegram|$nametelegram"],
                                ['text' => '200000', 'callback_data' => "tp|200000|$idtelegram|$nametelegram"],
                             ],
                          ]]),
                       'parse_mode' => 'html',
                    ];

                    Bot::sendMessage($kirimpelangan['text'], $kirimpelangan);
                } else {
                    $text = "⚠️ <b>Peringatan</b>\n\nMaaf, maksimal deposit top up adalah Rp 1.000.000,00";
                }
            } else {
                $text = "❌ <b>Input Tidak Valid</b>\n\nMaaf, input nominal saldo hanya boleh berupa angka.";
            }
        }
    } else {
        $text = "💰 <b>Request Deposit Saldo</b>\n\n";
        $text .= "Untuk melakukan request deposit, gunakan format:\n";
        $text .= "<code>/deposit [nominal]</code>\n\n";
        $text .= "Contoh:\n";
        $text .= "• <code>/deposit 10000</code>\n";
        $text .= "• <code>/deposit 50000</code>\n\n";
        $text .= 'Atau pilih nominal dari tombol di bawah ini:';

        $options = [
          'reply_markup' => json_encode([
             'inline_keyboard' => [
                [
                   ['text' => '⬇ REQUEST ⬇', 'callback_data' => '12'],
                ],
                [
                   ['text' => '10000', 'callback_data' => 'tps|10000'],
                   ['text' => '15000', 'callback_data' => 'tps|15000'],
                   ['text' => '20000', 'callback_data' => 'tps|20000'],
                ],
                [
                   ['text' => '25000', 'callback_data' => 'tps|25000'],
                   ['text' => '30000', 'callback_data' => 'tps|30000'],
                   ['text' => '50000', 'callback_data' => 'tps|50000'],
                ],
                [
                   ['text' => '100000', 'callback_data' => 'tps|100000'],
                   ['text' => '150000', 'callback_data' => 'tps|150000'],
                   ['text' => '200000', 'callback_data' => 'tps|200000'],
                ],
             ]]),
          'parse_mode' => 'html',
        ];
    }

    return Bot::sendMessage($text, $options ?? ['parse_mode' => 'html']);
});
// cekid commands
$mkbot->cmd('/cekid|/Cekid', function ($jumlah) {
    include '../config/system.conn.php';
    $info = bot::message();
    $iduser = $info['from']['id'];
    $msgid = $info['message_id'];
    $name = $info['from']['username'];
    $id = $info['from']['id'];
    $statusId = has($id);
    $statusStr = $statusId ? '✅ Terdaftar' : '❌ Belum Terdaftar';

    $text = "🔍 <b>Informasi ID Anda</b>\n\n";
    $text .= "┏━━━━━━━━ 👤 Detail Pengguna ━━━━━━━━┓\n";
    $text .= "┃ 🆔 ID User  : <code>$id</code>\n";
    $text .= "┃ 👤 Username : @$name\n";
    $text .= "┃ 📊 Status   : $statusStr\n";
    $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n";

    if (!$statusId) {
        $text .= "\n⚠️ Anda belum terdaftar. Gunakan /daftar untuk mendaftar.";
    }

    $options = [
        'parse_mode' => 'html',
    ];

    return Bot::sendMessage($text, $options);
});
// daftar commands
$mkbot->cmd('/daftar', function () {
    include '../config/system.conn.php';
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];

    Bot::sendChatAction('typing');
    $ids = $info['chat']['id'];

    if (empty($nametelegram)) {
        $text = "⚠️ <b>Peringatan</b>\n\n";
        $text .= "Akun Telegram Anda belum memiliki username.\n";
        $text .= 'Silakan atur username Anda terlebih dahulu di pengaturan Telegram.';
    } else {
        if (has($idtelegram) == false) {
            $cek = daftar($idtelegram, $nametelegram);

            if (empty($cek)) {
                $text = "❌ <b>Pendaftaran Gagal</b>\n\n";
                $text .= "Mohon maaf, sistem kami sedang mengalami gangguan.\n";
                $text .= 'Silakan hubungi Administrator untuk bantuan lebih lanjut.';
            } else {
                $text = "✅ <b>Pendaftaran Berhasil</b>\n\n";
                $text .= "┏━━━━━━━━ 📋 Informasi Akun ━━━━━━━━┓\n";
                $text .= "┃ 🆔 ID User  : <code>$idtelegram</code>\n";
                $text .= "┃ 👤 Username : @$nametelegram\n";
                $text .= "┃ ✅ Status   : Terdaftar\n";
                $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                $text .= "💰 Silakan isi saldo Anda di outlet kami.\n\n";
                $text .= '🙏 Terima kasih atas kepercayaan Anda menggunakan layanan kami.';
            }
        } else {
            $text = "ℹ️ <b>Informasi</b>\n\n";
            $text .= "Anda sudah terdaftar dalam layanan ini.\n\n";
            $text .= "┏━━━━━━━━ 📋 Informasi Akun ━━━━━━━━┓\n";
            $text .= "┃ 🆔 ID User  : <code>$idtelegram</code>\n";
            $text .= "┃ 👤 Username : @$nametelegram\n";
            $text .= "┃ ✅ Status   : Terdaftar\n";
            $text .= '┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛';
        }
    }

    $options = [
        'parse_mode' => 'html',
    ];

    return Bot::sendMessage($text, $options);
});
// help commands
$mkbot->cmd('/help|!Help', function ($id, $name, $notlp, $saldo) {
    include '../config/system.conn.php';
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');

    $text = "🔰 <b>Daftar Perintah</b>\n\n";
    $text .= "┏━━━━━━━━ 🚀 Perintah Umum ━━━━━━━━┓\n";
    $text .= "┃ 📋 /menu     - Menu Voucher\n";
    $text .= "┃ 📝 /daftar   - Daftar sebagai Member\n";
    $text .= "┃ 💰 /ceksaldo - Cek Saldo Layanan\n";
    $text .= "┃ 🔍 /cekid    - Status User\n";
    $text .= "┃ 📷 /qrcode   - Terjemahkan QR Code\n";
    $text .= "┃ 💳 /deposit  - Permintaan Deposit\n";
    $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n";

    if ($idtelegram == $id_own) {
        $text .= "\n🛡️ <b>Perintah Administrator</b>\n\n";
        $text .= "┏━━━━━━━━ 🔧 Admin Tools ━━━━━━━━┓\n";
        $text .= "┃ 🛠️ dbg       - Pesan Debug\n";
        $text .= "┃ 📇 /daftarid - Daftar User Manual\n";
        $text .= "┃ 📉 /topdown  - Kurangi Saldo User\n";
        $text .= "┃ 💸 /topup    - Tambah Saldo User\n";
        $text .= "┃ 🌐 /hotspot  - Monitor Hotspot\n";
        $text .= "┃ 📡 /resource - Resource Router\n";
        $text .= "┃ 👁️ /netwatch - Netwatch Router\n";
        $text .= "┃ 📊 /report   - Laporan Mikhbotam\n";
        $text .= "┃ ❓ /user     - Cari User Hotspot\n";
        $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n";
    }

    $text .= "\n📌 Ketik perintah tanpa tanda '/' untuk informasi lebih lanjut.";

    $options = [
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '📚 Panduan Lengkap', 'url' => 'https://example.com/guide']],
                [['text' => '🔙 Kembali ke Menu', 'callback_data' => 'back_to_menu']],
            ],
        ]),
    ];

    Bot::sendMessage($text, $options);
});
// daftar manual khusus Administator
$mkbot->cmd('/daftarid', function ($id, $name, $notlp, $saldo) {
    include '../config/system.conn.php';
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');

    if ($idtelegram == $id_own) {
        if (empty($id) || empty($name) || empty($notlp) || empty($saldo)) {
            $text = "❌ <b>Format Salah</b>\n\n";
            $text .= "📝 Mohon masukkan format dengan benar:\n";
            $text .= "<code>/daftarid [id] [nama] [no_telp] [saldo]</code>\n\n";
            $text .= "Contoh:\n<code>/daftarid 123456 John_Doe 081234567890 50000</code>";
        } else {
            $lihat = lihatuser($id);

            if (empty($lihat)) {
                $hasil = daftarid($id, $name, $notlp, $saldo);
                $text = "✅ <b>Pendaftaran Berhasil</b>\n\n";
                $text .= "┏━━━━━━━━ 📋 Detail User ━━━━━━━━┓\n";
                $text .= "┃ 🆔 ID User     : <code>$id</code>\n";
                $text .= "┃ 👤 Nama        : $name\n";
                $text .= "┃ 📞 No. Telepon : $notlp\n";
                $text .= '┃ 💰 Saldo Awal  : '.rupiah($saldo)."\n";
                $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                $text .= $hasil; // Tambahkan pesan hasil dari fungsi daftarid() jika ada
            } else {
                $text = "⚠️ <b>Peringatan:</b> User Sudah Terdaftar\n\n";
                $text .= "User dengan ID <code>$id</code> sudah terdaftar dalam sistem.\n";
                $text .= 'Silakan periksa kembali atau gunakan ID lain.';
            }
        }
    } else {
        $text = "🚫 <b>Akses Ditolak</b>\n\n";
        $text .= 'Maaf, akses hanya untuk Administrator.';
    }

    $options = [
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '📊 Lihat Daftar User', 'callback_data' => 'list_users']],
                [['text' => '🔙 Kembali ke Menu', 'callback_data' => 'back_to_menu']],
            ],
        ]),
    ];

    return Bot::sendMessage($text, $options);
});
// topdown khusus Administator
$mkbot->cmd('/topdown', function ($id, $jumlahan) {
    $info = bot::message();
    $msgid = $info['message_id'];
    $name = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        if (!empty($id) && !empty($jumlahan)) {
            if (has($id) == false) {
                $text = "❌ <b>Error:</b> ID tidak terdaftar\n\n";
                $text .= 'Silakan periksa kembali ID yang Anda masukkan.';
            } else {
                if (preg_match('/^[0-9]+$/', $jumlahan)) {
                    if (strlen($jumlahan) < 7) {
                        $topdown = topdown($id, $jumlahan);
                        $text = "💸 <b>Informasi Refund</b>\n\n";
                        $text .= "┏━━━━━━━━ 📊 Detail Refund ━━━━━━━━┓\n";
                        $text .= "┃ 🆔 ID User     : <code>$id</code>\n";
                        $text .= '┃ 💰 Jumlah      : '.rupiah($jumlahan)."\n";
                        $text .= '┃ 💼 Saldo Akhir : '.rupiah(lihatsaldo($id))."\n";
                        $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                        $text .= '✅ Penarikan saldo berhasil dilakukan.';

                        // Kirim notifikasi ke pengguna
                        $userNotif = "ℹ️ <b>Pemberitahuan Refund</b>\n\n";
                        $userNotif .= 'Saldo Anda telah dikurangi sebesar '.rupiah($jumlahan)."\n";
                        $userNotif .= 'Saldo Anda saat ini: '.rupiah(lihatsaldo($id));

                        $options = [
                            'chat_id' => $id,
                            'parse_mode' => 'html',
                        ];
                        Bot::sendMessage($userNotif, $options);
                    } else {
                        $text = "⚠️ <b>Peringatan:</b> Maksimal Refund\n\n";
                        $text .= 'Maaf, maksimal refund adalah '.rupiah(1000000).'.';
                    }
                } else {
                    $text = "❌ <b>Error:</b> Input Tidak Valid\n\n";
                    $text .= 'Maaf, input jumlah refund hanya boleh berupa angka.';
                }
            }
        } else {
            $text = "❗ <b>Format Salah</b>\n\n";
            $text .= "Gunakan format: <code>/topdown (id) (jumlah)</code>\n";
            $text .= 'Contoh: <code>/topdown 123456 50000</code>';
        }
    } else {
        $text = "🚫 <b>Akses Ditolak</b>\n\n";
        $text .= 'Maaf, akses hanya untuk Administrator.';
    }

    $options = [
        'parse_mode' => 'html',
        'reply_markup' => json_encode([
            'inline_keyboard' => [
                [['text' => '🔙 Kembali ke Menu', 'callback_data' => 'back_to_menu']],
            ],
        ]),
    ];
    Bot::sendMessage($text, $options);
});
// topup khusus Administator
$mkbot->cmd('/topup', function ($id, $jumlah) {
    $info = bot::message();
    $msgid = $info['message_id'];
    $name = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');
    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        if (!empty($id) && !empty($jumlah)) {
            if (has($id) == false) {
                $text = "❌ <b>Error:</b> ID tidak terdaftar\n\n";
                $text .= 'Silakan periksa kembali ID yang Anda masukkan.';
            } else {
                if (preg_match('/^[0-9]+$/', $jumlah)) {
                    if (strlen($jumlah) < 7) {
                        $text = topupresseller($id, $name, $jumlah, $id_own);

                        $kirimpelangan = [
                           'chat_id' => $id,
                           'reply_markup' => json_encode([
                              'inline_keyboard' => [
                                 [
                                    ['text' => '🛒 Beli Voucher', 'callback_data' => 'Menu'],
                                    ['text' => '🔥 Promo Hot', 'callback_data' => 'informasi'],
                                 ],
                                 [
                                    ['text' => '💰 Cek Saldo', 'callback_data' => 'ceksaldo'],
                                 ],
                              ]]),
                           'parse_mode' => 'html',
                        ];
                        Bot::sendMessage($text, $kirimpelangan);
                    } else {
                        $text = "⚠️ <b>Peringatan:</b> Maksimal Top Up\n\n";
                        $text .= 'Maaf, maksimal top up adalah '.rupiah(1000000).'.';
                    }
                } else {
                    $text = "❌ <b>Error:</b> Input Tidak Valid\n\n";
                    $text .= 'Maaf, input saldo hanya boleh berupa angka.';
                }
            }
        } else {
            $text = "❗ <b>Format Salah</b>\n\n";
            $text .= "Gunakan format: <code>/topup (id) (jumlah)</code>\n";
            $text .= 'Contoh: <code>/topup 123456 50000</code>';
        }
    } else {
        $text = "🚫 <b>Akses Ditolak</b>\n\n";
        $text .= 'Maaf, akses hanya untuk Administrator.';
    }

    $options = [
       'parse_mode' => 'html',
    ];

    return Bot::sendMessage($text, $options);
});
// lihatsaldo commands
$mkbot->cmd('/lihatsaldo|/ceksaldo', function ($jumlah) {
    include '../config/system.conn.php';
    $info = bot::message();
    $iduser = $info['from']['id'];
    $msgid = $info['message_id'];
    $name = $info['from']['username'];
    $id = $info['from']['id'];
    $lihat = lihatuser($id);
    $ids = $info['chat']['id'];

    if (empty($lihat)) {
        $text = "❌ <b>Anda belum terdaftar</b>\n\n";
        $text .= 'Silakan daftar terlebih dahulu ke admin atau klik /daftar';
    } else {
        $angka = lihatsaldo($id);
        $text = "💰 <b>Informasi Saldo</b>\n\n";
        $text .= "┏━━━━━━━━ 👤 Detail Pengguna ━━━━━━━━┓\n";
        $text .= "┃ 🆔 ID Pengguna : <code>$id</code>\n";
        $text .= "┃ 👤 Nama        : @$name\n";
        $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
        $text .= "┏━━━━━━━━ 💵 Informasi Saldo ━━━━━━━━┓\n";
        $text .= '┃ 💰 Saldo       : <b>'.rupiah($angka)."</b>\n";
        $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";

        if ($angka < 10000) {
            $text .= '⚠️ <i>Saldo Anda sudah menipis. Segera lakukan pengisian ulang!</i>';
        } else {
            $text .= '✅ <i>Saldo Anda masih mencukupi.</i>';
        }
    }

    $options = [
       'parse_mode' => 'html',
    ];

    return Bot::sendMessage($text, $options);
});
// resource commands
$mkbot->cmd('/resource|/Resource', function () {
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');

    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        $API = new routeros_api();

        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            $jambu = $API->comm('/system/health/print');
            $dhealth = $jambu['0'];
            $ARRAY = $API->comm('/system/resource/print');
            $jeruk = $ARRAY['0'];
            $memperc = ($jeruk['free-memory'] / $jeruk['total-memory']);
            $hddperc = ($jeruk['free-hdd-space'] / $jeruk['total-hdd-space']);
            $mem = ($memperc * 100);
            $hdd = ($hddperc * 100);
            $sehat = $dhealth['temperature'];
            $platform = $jeruk['platform'];
            $board = $jeruk['board-name'];
            $version = $jeruk['version'];
            $architecture = $jeruk['architecture-name'];
            $cpu = $jeruk['cpu'];
            $cpuload = $jeruk['cpu-load'];
            $uptime = $jeruk['uptime'];
            $cpufreq = $jeruk['cpu-frequency'];
            $cpucount = $jeruk['cpu-count'];
            $memory = formatBytes($jeruk['total-memory']);
            $fremem = formatBytes($jeruk['free-memory']);
            $mempersen = number_format($mem, 3);
            $hdd = formatBytes($jeruk['total-hdd-space']);
            $frehdd = formatBytes($jeruk['free-hdd-space']);
            $hddpersen = number_format($hdd, 3);
            $sector = $jeruk['write-sect-total'];
            $setelahreboot = $jeruk['write-sect-since-reboot'];
            $kerusakan = $jeruk['bad-blocks'];

            $text = "📡 <b>Resource Information</b>  🌡️ $sehat°C\n\n";
            $text .= "┏━━━━━━━━ 🖥️ System Info ━━━━━━━━┓\n";
            $text .= "┃ 🏷️ Boardname : $board\n";
            $text .= "┃ 🏗️ Platform  : $platform\n";
            $text .= '┃ ⏱️ Uptime    : '.formatDTM($uptime)."\n";
            $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";

            $text .= "┏━━━━━━━━ 💻 CPU Info ━━━━━━━━━━┓\n";
            $text .= "┃ 🔄 CPU Load  : $cpuload%\n";
            $text .= "┃ 💻 CPU Type  : $cpu\n";
            $text .= "┃ ⚡ CPU Freq  : $cpufreq MHz / $cpucount core(s)\n";
            $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";

            $text .= "┏━━━━━━━━ 🧠 Memory Usage ━━━━━━━┓\n";
            $text .= "┃ 💾 Total     : $memory\n";
            $text .= "┃ 🆓 Free      : $fremem\n";
            $text .= "┃ 📊 Used      : $mempersen%\n";
            $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";

            $text .= "┏━━━━━━━━ 💽 Disk Usage ━━━━━━━━┓\n";
            $text .= "┃ 💾 Total     : $hdd\n";
            $text .= "┃ 🆓 Free      : $frehdd\n";
            $text .= "┃ 📊 Used      : $hddpersen%\n";
            $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";

            $text .= "┏━━━━━━ 🔧 Disk Health ━━━━━━━━┓\n";
            $text .= "┃ 📝 Write Sectors : $sector\n";
            $text .= "┃ 🔄 Since Reboot  : $setelahreboot\n";
            $text .= "┃ ⚠️ Bad Blocks    : $kerusakan%\n";
            $text .= '┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛';
        } else {
            $text = '❌ Gagal terhubung ke Router. Silakan periksa koneksi Anda.';
        }
    } else {
        $text = '🚫 Maaf! Akses hanya untuk Administrator';
    }

    $options = ['parse_mode' => 'html'];
    Bot::sendMessage($text, $options);
});
// Hotspot commands khusus Adminstator
$mkbot->cmd('!Hotspot|?hotspot|/hotspot|/Hotspot|!Hotspot', function ($user, $telo) {
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');

    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        $API = new routeros_api();

        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            if ($user == 'aktif') {
                if ($telo != '') {
                    $pepaya = $API->comm('/ip/hotspot/active/print', ['?server' => ''.$telo.'']);
                    $anggur = count($pepaya);
                    $apel = $API->comm('/ip/hotspot/active/print', ['count-only' => '', '?server' => ''.$telo.'']);
                } else {
                    $pepaya = $API->comm('/ip/hotspot/active/print');
                    $anggur = count($pepaya);
                    $apel = $API->comm('/ip/hotspot/active/print', ['count-only' => '']);
                }

                $text .= "User Aktif $apel item\n\n";

                for ($i = 0; $i < $anggur; ++$i) {
                    $mangga = $pepaya[$i];
                    $id = $mangga['.id'];
                    $server = $mangga['server'];
                    $user = $mangga['user'];
                    $address = $mangga['address'];
                    $mac = $mangga['mac-address'];
                    $uptime = $mangga['uptime'];
                    $usesstime = $mangga['session-time-left'];
                    $bytesi = formatBytes($mangga['bytes-in'], 2);
                    $byteso = formatBytes($mangga['bytes-out'], 2);
                    $loginby = $mangga['login-by'];
                    $comment = $mangga['comment'];
                    $text .= "┏━━━━━━━━ 👤 User Aktif ━━━━━━━━┓\n";
                    $text .= "┃ 🆔 ID        : $id\n";
                    $text .= "┃ 👤 User      : $user\n";
                    $text .= "┃ 🌐 IP        : $address\n";
                    $text .= "┃ ⏱️ Uptime    : $uptime\n";
                    $text .= "┣━━━━━━━━ Penggunaan Data ━━━━━━━┫\n";
                    $text .= "┃ ⬇️ Byte IN   : $bytesi\n";
                    $text .= "┃ ⬆️ Byte OUT  : $byteso\n";
                    $text .= "┣━━━━━━━━━━ Info Sesi ━━━━━━━━━━┫\n";
                    $text .= "┃ 🕒 Session   : $usesstime\n";
                    $text .= "┃ 🔐 Login     : $loginby\n";
                    $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                    $text .= "🔍 Lihat detail: /see_$server\n\n";
                }

                $arr2 = str_split($text, 4000);
                $amount_gen = count($arr2);

                for ($i = 0; $i < $amount_gen; ++$i) {
                    $texta = $arr2[$i];
                    Bot::sendMessage($texta);
                }
            } elseif ($user == 'user') {
                $ARRAY = $API->comm('/ip/hotspot/user/print');
                $num = count($ARRAY);
                $text = "Total $num User\n\n";

                for ($i = 0; $i < $num; ++$i) {
                    $no = $i;
                    $data = $ARRAY[$i]['.id'];
                    $dataid = str_replace('*', 'id', $data);
                    $server = $ARRAY[$i]['server'];
                    $name = $ARRAY[$i]['name'];
                    $data3 = $ARRAY[$i]['password'];
                    $data4 = $ARRAY[$i]['mac-address'];
                    $data5 = $ARRAY[$i]['profile'];
                    $data6 = $ARRAY[$i]['limit-uptime'];
                    $text .= "┏━━━━━━━━ 👥 User Info ━━━━━━━━┓\n";
                    $text .= "┃ 🆔 ID       : $dataid\n";
                    $text .= "┃ 👤 Nama     : $name\n";
                    $text .= "┃ 🔑 Password : $data3\n";
                    $text .= "┃ 📱 MAC      : $data4\n";
                    $text .= "┃ 👥 Profil   : $data5\n";
                    $text .= "┣━━━━━━━━━━ Aksi ━━━━━━━━━━━━━┫\n";
                    $text .= "┃ 🗑️ Hapus User: /rEm0v$dataid\n";
                    $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                }

                $arr2 = str_split($text, 4000);
                $amount_gen = count($arr2);

                for ($i = 0; $i < $amount_gen; ++$i) {
                    $texta = $arr2[$i];

                    Bot::sendMessage($texta);
                }
            } else {
                $text .= '';
                $text = "User list or aktif\n";
                $text .= "Filter by server\n";
                $serverhot = $API->comm('/ip/hotspot/print');

                foreach ($serverhot as $index => $jambu) {
                    $sapubasah = str_replace('-', '0', $jambu['name']);
                    $sapubasahbasah = str_replace(' ', '11', $sapubasah);

                    $text .= '/see_'.$sapubasahbasah."\n";
                }

                $keyboard = [['!Hotspot user', '!Hotspot aktif'], ['!Menu', '!Help'], ['!Hide']];
                $replyMarkup = ['keyboard' => $keyboard, 'resize_keyboard' => true, 'one_time_keyboard' => true, 'selective' => true];
                $options = [
                   'reply' => true,
                   'reply_markup' => json_encode($replyMarkup),
                ];
                Bot::sendMessage($text, $options);
            }
        } else {
            $text = '❌ Tidak dapat terhubung dengan Mikrotik. Silakan coba kembali.';
            $options = [
               'reply' => true,
               'parse_mode' => 'html',
            ];
            Bot::sendMessage($text, $options);
        }
    } else {
        $denid = '🚫 Maaf! Akses hanya untuk Administrator';
        $options = [
            'parse_mode' => 'html',
        ];
        Bot::sendMessage($denid, $options);
    }
});
// User commands khusus Administator
$mkbot->cmd('?hs|!User|?user|!user|', function ($name) {
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');

    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        $API = new routeros_api();

        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            $ARRAY = $API->comm('/ip/hotspot/user/print', ['?name' => $name]);
            $get = $API->comm('/system/scheduler/print', ['?name' => $name]);

            if (empty($ARRAY)) {
                $texta = '❌ User tidak ditemukan...';
            } else {
                foreach ($ARRAY as $index => $baris) {
                    $text = '';
                    $text .= "┏━━━━━━━━ 🌟 Hotspot Client ━━━━━━━━┓\n";
                    $text .= '┃ 👤 Nama     : '.$baris['name']."\n";
                    $text .= '┃ 🔑 Password : '.$baris['password']."\n";
                    $text .= '┃ ⏳ Limit    : '.$baris['limit-uptime']."\n";
                    $text .= '┃ ⏱️ Uptime   : '.formatDTM($baris['uptime'])."\n";
                    $text .= '┃ ⬆️ Upload   : '.formatBytes($baris['bytes-in'])."\n";
                    $text .= '┃ ⬇️ Download : '.formatBytes($baris['bytes-out'])."\n";
                    $text .= '┃ 👥 Profil   : '.$baris['profile']."\n";
                    $data = $baris['.id'];
                    $dataid = str_replace('*', 'id', $data);
                    $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n";
                }

                foreach ($get as $index => $baris) {
                    $experid = "┏━━━━━━━━ ⏰ Informasi Waktu ━━━━━━━━┓\n";
                    $experid .= '┃ 🕐 Start-time : '.$baris['start-date'].' '.$baris['start-time']."\n";
                    $experid .= '┃ 🔄 Interval   : '.$baris['interval']."\n";
                    $experid .= '┃ 📅 Expired    : '.$baris['next-run']."\n";
                    $experid .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n";
                }

                $texta = $text.$experid."\n🗑️ Hapus User: /rEm0v$dataid\n\n";
            }
        } else {
            $texta = '❌ Tidak dapat terhubung dengan Mikrotik. Silakan coba kembali.';
        }

        $options = ['parse_mode' => 'html'];
        Bot::sendMessage($texta, $options);
    } else {
        $denid = '🚫 Maaf! Akses hanya untuk Administrator';
        Bot::sendMessage($denid, ['parse_mode' => 'html']);
    }
});
// report commands khusus Administator
$mkbot->cmd('/report', function ($name) {
    $info = bot::message();
    $id = $info['chat']['id'];
    $iduser = $info['from']['id'];
    $msgid = $info['message_id'];
    Bot::sendChatAction('typing');

    if ($iduser == $id_own) {
        $text = '📊 <b>Laporan Bulanan</b> - '.date('d-m-Y')."\n\n";
        $text .= "┏━━━━━━━━ 📈 Statistik ━━━━━━━━┓\n";
        $text .= '┃ 🎟️ Total Voucher : '.countvoucher()." Voucher\n";
        $text .= '┃ 💰 Top up Debit  : '.rupiah(getcounttopup())."\n";
        $text .= '┃ 📊 Mutasi Voucher: '.rupiah(estimasidata())."\n";
        $text .= '┃ 👥 User Baru     : + '.countuser()." User\n";
        $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n";
    } else {
        $text = '🚫 Maaf! Akses hanya untuk Administrator';
    }

    $options = [
       'parse_mode' => 'html',
    ];
    Bot::sendMessage($text, $options);
});
// netwatch commands khusus Administator
$mkbot->cmd('/netwatch|/Netwatch', function () {
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    Bot::sendChatAction('typing');

    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        $API = new routeros_api();

        if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
            $ARRAY = $API->comm('/tool/netwatch/print');
            $num = count($ARRAY);
            $text = "📡 <b>Daftar Host Netwatch</b> ($num)\n\n";

            for ($i = 0; $i < $num; ++$i) {
                $no = $i + 1;
                $host = $ARRAY[$i]['host'];
                $interval = $ARRAY[$i]['interval'];
                $timeout = $ARRAY[$i]['timeout'];
                $status = $ARRAY[$i]['status'];
                $since = $ARRAY[$i]['since'];

                $text .= "┏━━━━━━━ 🖥️ Netwatch $no ━━━━━━━┓\n";
                $text .= "┃ 🌐 Host   : $host\n";
                $text .= '┃ 🕒 Status : ';

                if ($status == 'up') {
                    $text .= "✅ UP\n";
                } else {
                    $text .= "⚠️ Down\n";
                }

                $text .= "┃ 🕰️ Since  : $since\n";
                $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
            }
        } else {
            $text = '❌ Tidak dapat terhubung dengan Mikrotik. Silakan coba kembali.';
        }

        $arr2 = str_split($text, 4000);
        $amount_gen = count($arr2);

        for ($i = 0; $i < $amount_gen; ++$i) {
            $options = ['parse_mode' => 'html'];
            Bot::sendMessage($arr2[$i], $options);
        }
    } else {
        $text = '🚫 Maaf! Akses hanya untuk Administrator';
        Bot::sendMessage($text, ['parse_mode' => 'html']);
    }
});
// debug message semua
$mkbot->cmd('dbg', function ($pesan) {
    $info = bot::message();
    $id = $info['chat']['id'];
    $text = '<code>'.json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).'</code>';
    $options = ['parse_mode' => 'html'];

    return Bot::sendMessage($text, $options);
});
// qrcode terjemah qrcode
$mkbot->cmd('/qrcode', function () {
    include '../config/system.conn.php';
    $info = bot::message();
    $ambilgambar = $info['reply_to_message']['photo'][0]['file_id'];

    if (empty($ambilgambar)) {
        $text = '📸 Silakan balas pesan ini dengan gambar/foto QR code yang ingin Anda scan.';
        Bot::sendMessage($text);
    } else {
        $cek = Bot::getFile($ambilgambar);
        $hasilkirimaaa = json_decode($cek, true);
        $hasilurl = $hasilkirimaaa['result']['file_path'];
        $urlkirim = 'http://api.qrserver.com/v1/read-qr-code/?fileurl=https://api.telegram.org/file/bot'.$token.'/'.$hasilurl;
        $hasilurla = file_get_contents($urlkirim);
        $hasilkirim = json_decode($hasilurla, true);

        $terjemah = "🔍 <b>Hasil Scan QR Code</b>\n\n";
        $terjemah .= "┏━━━━━━━━ 📊 Informasi ━━━━━━━━┓\n";
        $terjemah .= '┃ 📝 Isi : '.$hasilkirim[0]['symbol'][0]['data']."\n";
        $terjemah .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n";

        $options = [
            'parse_mode' => 'html',
        ];

        return Bot::sendMessage($terjemah, $options);
    }
});
// see_ melihat user aktif
$mkbot->regex('/^\/see_/', function ($matches) {
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    $isipesan = $info['text'];
    Bot::sendChatAction('typing');
    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        if ($isipesan == '/see_') {
            $text = "⚠️ <b>Periksa</b>\n\n<b>KETERANGAN:</b> Tidak Ditemukan";
        } else {
            $sapubasah = str_replace('/see_', '', $isipesan);
            $sapulantai = str_replace('0', '-', $sapubasah);
            $sapuujuk = str_replace('11', ' ', $sapulantai);
            $sapulidi = str_replace('@'.$usernamebot.'', '', $sapuujuk);
            $API = new routeros_api();

            if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
                $pepaya = $API->comm('/ip/hotspot/active/print', ['?server' => $sapulidi]);

                if (empty($pepaya)) {
                    $text = "⚠️ Tidak ada user aktif di server $sapulidi";
                } else {
                    $text = "👥 <b>User Aktif di $sapulidi</b>\n\n";

                    for ($i = 0; $i < count($pepaya); ++$i) {
                        $mangga = $pepaya[$i];
                        $id = $mangga['.id'];
                        $server = $mangga['server'];
                        $user = $mangga['user'];
                        $address = $mangga['address'];
                        $mac = $mangga['mac-address'];
                        $uptime = $mangga['uptime'];
                        $usesstime = $mangga['session-time-left'];
                        $bytesi = formatBytes($mangga['bytes-in'], 2);
                        $byteso = formatBytes($mangga['bytes-out'], 2);
                        $loginby = $mangga['login-by'];
                        $comment = $mangga['comment'];

                        $text .= "┏━━━━━━━━ 👤 User Aktif ━━━━━━━━┓\n";
                        $text .= "┃ 🆔 ID        : $id\n";
                        $text .= "┃ 👤 User      : $user\n";
                        $text .= "┃ 🌐 IP        : $address\n";
                        $text .= "┃ ⏱️ Uptime    : $uptime\n";
                        $text .= "┣━━━━━━━━ Penggunaan Data ━━━━━━━┫\n";
                        $text .= "┃ ⬇️ Byte IN   : $bytesi\n";
                        $text .= "┃ ⬆️ Byte OUT  : $byteso\n";
                        $text .= "┣━━━━━━━━━━ Info Sesi ━━━━━━━━━━┫\n";
                        $text .= "┃ 🕒 Session   : $usesstime\n";
                        $text .= "┃ 🔐 Login     : $loginby\n";
                        $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                    }

                    $text .= "📊 Total login di $server: ".count($pepaya)." user\n";
                }
            } else {
                $text = '❌ Tidak dapat terhubung dengan Mikrotik. Silakan coba kembali.';
            }
        }

        $options = [
            'parse_mode' => 'html',
        ];
        Bot::sendMessage($text, $options);
    } else {
        $denid = '🚫 Maaf! Akses hanya untuk Administrator';
        Bot::sendMessage($denid, ['parse_mode' => 'html']);
    }
});
$mkbot->regex('/^\/rEm0vid/', function ($matches) {
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    $isipesan = $info['text'];
    Bot::sendChatAction('typing');
    $text = '';
    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        if ($isipesan == '/rEm0vid') {
            $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN:</b> Tidak Ditemukan ID User";
        } else {
            $id = str_replace('/rEm0vid', '*', $isipesan);
            $ids = str_replace('@'.$usernamebot, '', $id);
            $API = new routeros_api();

            if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
                $ARRAY = $API->comm('/ip/hotspot/user/print', ['?.id' => $ids]);
                $data1 = $ARRAY[0]['.id'];
                $data2 = $ARRAY[0]['name'];
                $data3 = $ARRAY[0]['password'];
                $data5 = $ARRAY[0]['profile'];
                $ARRAY2 = $API->comm('/ip/hotspot/user/remove', ['numbers' => $ids]);
                $texta = json_encode($ARRAY2);

                if (strpos(strtolower($texta), 'no such item') !== false) {
                    $gagal = $ARRAY2['!trap'][0]['message'];
                    $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN:</b> $gagal";
                } elseif (strpos(strtolower($texta), 'invalid internal item number') !== false) {
                    $gagal = $ARRAY2['!trap'][0]['message'];
                    $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN:</b> $gagal";
                } elseif (strpos(strtolower($texta), 'default trial user can not be removed') !== false) {
                    $gagal = $ARRAY2['!trap'][0]['message'];
                    $text .= "⛔ Gagal dihapus \n\n<b>KETERANGAN:</b> $gagal";
                } else {
                    $text .= "✅ Berhasil Dihapus\n\n";
                    $text .= "┏━━━━━━━━ 👤 User Info ━━━━━━━━┓\n";
                    $text .= "┃ 🆔 ID       : $ids\n";
                    $text .= "┃ 🖥️ Server   : $data1\n";
                    $text .= "┃ 👤 Nama     : $data2\n";
                    $text .= "┃ 🔑 Password : $data3\n";
                    $text .= "┃ 👥 Profil   : $data5\n";
                    $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                    sleep(2);
                    $ARRAY3 = $API->comm('/ip/hotspot/user/print');
                    $jumlah = count($ARRAY3);
                    $text .= "📊 Jumlah user saat ini: $jumlah user";
                }
            } else {
                $text = '❌ Gagal terhubung ke Router. Silakan periksa koneksi Anda.';
            }
        }

        $options = ['parse_mode' => 'html'];

        return Bot::sendMessage($text, $options);
    } else {
        $denid = '🚫 Maaf! Akses hanya untuk Administrator';
        Bot::sendMessage($denid, ['parse_mode' => 'html']);
    }
});
$mkbot->cmd('!Menu|/Menu|/menu', function () {
    $info = bot::message();
    $ids = $info['chat']['id'];
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];

    if (has($idtelegram)) {
        include '../config/system.conn.php';
        $data = json_decode($voucher_1, true);
        if (!empty($data)) {
            $text = "🎫 <b>Menu Voucher</b>\n\n";
            $text .= "<i>Silakan pilih voucher di bawah ini:</i>\n\n";
            $text .= "📋 <b>Daftar Voucher:</b>\n";

            foreach ($data as $hargas) {
                $textlist = $hargas['Text_List'];
                $text .= "• $textlist\n";
            }

            for ($i = 0; $i < count($data); ++$i) {
                ${'database'.$i} = [
                    'text' => $data[$i]['Voucher'],
                    'callback_data' => 'Vcr'.$data[$i]['id'],
                ];
            }

            $vouchernamea0 = array_filter([$database0, $database1]);
            $vouchernameb1 = array_filter([$database2, $database3]);
            $vouchernamec2 = array_filter([$database4, $database5]);

            $menu_idakhir = [
               ['text' => '💰 Cek Saldo', 'callback_data' => 'ceksaldo'],
               ['text' => '🔖 Informasi', 'callback_data' => 'informasi'],
            ];

            $send = [
                $vouchernamea0,
                $vouchernameb1,
                $vouchernamec2,
                $menu_idakhir,
            ];

            $options = [
               'reply_markup' => json_encode(['inline_keyboard' => $send]),
               'parse_mode' => 'html',
            ];

            Bot::sendMessage($text, $options);
            unset($data, $voucher_1);
        } else {
            Bot::sendMessage('❌ Maaf, sistem tidak memiliki voucher saat ini.');
        }
    } else {
        $text = "⚠️ Anda belum terdaftar.\n\n";
        $text .= 'Silakan daftar terlebih dahulu ke admin atau klik /daftar';
        Bot::sendMessage($text);
    }
});
$mkbot->regex('/^\/see_/', function ($matches) {
    $info = bot::message();
    $msgid = $info['message_id'];
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    $isipesan = $info['text'];
    Bot::sendChatAction('typing');
    include '../config/system.conn.php';

    if ($idtelegram == $id_own) {
        if ($isipesan == '/see_') {
            $text = "⚠️ <b>Periksa</b>\n\n<b>KETERANGAN:</b> Tidak Ditemukan";
        } else {
            $sapubasah = str_replace('/see_', '', $isipesan);
            $sapulantai = str_replace('0', '-', $sapubasah);
            $sapuujuk = str_replace('11', ' ', $sapulantai);
            $sapulidi = str_replace('@'.$usernamebot.'', '', $sapuujuk);
            $API = new routeros_api();

            if ($API->connect($mikrotik_ip, $mikrotik_username, $mikrotik_password, $mikrotik_port)) {
                $pepaya = $API->comm('/ip/hotspot/active/print', ['?server' => $sapulidi]);

                if (empty($pepaya)) {
                    $text = "⚠️ Tidak ada user aktif di server $sapulidi";
                } else {
                    $text = "👥 <b>User Aktif di $sapulidi</b>\n\n";

                    for ($i = 0; $i < count($pepaya); ++$i) {
                        $mangga = $pepaya[$i];
                        $id = $mangga['.id'];
                        $server = $mangga['server'];
                        $user = $mangga['user'];
                        $address = $mangga['address'];
                        $mac = $mangga['mac-address'];
                        $uptime = $mangga['uptime'];
                        $usesstime = $mangga['session-time-left'];
                        $bytesi = formatBytes($mangga['bytes-in'], 2);
                        $byteso = formatBytes($mangga['bytes-out'], 2);
                        $loginby = $mangga['login-by'];
                        $comment = $mangga['comment'];

                        $text .= "┏━━━━━━━━ 👤 User Aktif ━━━━━━━━┓\n";
                        $text .= "┃ 🆔 ID        : $id\n";
                        $text .= "┃ 👤 User      : $user\n";
                        $text .= "┃ 🌐 IP        : $address\n";
                        $text .= "┃ ⏱️ Uptime    : $uptime\n";
                        $text .= "┣━━━━━━━━ Penggunaan Data ━━━━━━━┫\n";
                        $text .= "┃ ⬇️ Byte IN   : $bytesi\n";
                        $text .= "┃ ⬆️ Byte OUT  : $byteso\n";
                        $text .= "┣━━━━━━━━━━ Info Sesi ━━━━━━━━━━┫\n";
                        $text .= "┃ 🕒 Session   : $usesstime\n";
                        $text .= "┃ 🔐 Login     : $loginby\n";
                        $text .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                    }

                    $text .= "📊 Total login di $server: ".count($pepaya)." user\n";
                }
            } else {
                $text = '❌ Tidak dapat terhubung dengan Mikrotik. Silakan coba kembali.';
            }
        }

        $options = [
            'parse_mode' => 'html',
        ];
        Bot::sendMessage($text, $options);
    } else {
        $denid = '🚫 Maaf! Akses hanya untuk Administrator';
        Bot::sendMessage($denid, ['parse_mode' => 'html']);
    }
});
$mkbot->on('photo', function () {
    $info = bot::message();
    $nametelegram = $info['from']['username'];
    $idtelegram = $info['from']['id'];
    $caption = strtolower($info['caption']);
    $explode = explode(' ', $caption);
    $konfirmasitext = $explode[0];
    $deposittext = $explode[1];
    $jumlahtext = $explode[2];

    if (!empty($caption)) {
        include '../config/system.conn.php';
        if (has($idtelegram)) {
            if (preg_match('/^#konfirmasi/', $konfirmasitext)) {
                if ($konfirmasitext == '#konfirmasi' && $deposittext == 'deposit' && !empty($jumlahtext)) {
                    if (preg_match('/^[0-9]+$/', $jumlahtext)) {
                        $fototerbaik = $info['photo'][3]['file_id'] ?? null;
                        $fotomedium = $info['photo'][2]['file_id'] ?? null;
                        $fotorendah = $info['photo'][1]['file_id'] ?? null;
                        $fotojelek = $info['photo'][0]['file_id'] ?? null;

                        $caption = "💰 <b>Konfirmasi Deposit</b>\n\n";
                        $caption .= "┏━━━━━━━━ 📌 Detail ━━━━━━━━┓\n";
                        $caption .= "┃ 👤 Pengirim : @$nametelegram\n";
                        $caption .= '┃ 💵 Jumlah   : '.rupiah($jumlahtext)."\n";
                        $caption .= "┗━━━━━━━━━━━━━━━━━━━━━━━━━┛\n\n";
                        $caption .= '🔍 Silakan periksa dan tindak lanjuti.';

                        $options = [
                            'chat_id' => $id_own,
                            'caption' => $caption,
                            'parse_mode' => 'html',
                        ];

                        if (!empty($fototerbaik)) {
                            Bot::sendPhoto($fototerbaik, $options);
                            $response = "✅ Konfirmasi deposit telah kami terima dan akan segera kami proses.\n\n⏳ Mohon tunggu.\n\nTerima kasih! 🙏";
                        } elseif (!empty($fotomedium)) {
                            Bot::sendPhoto($fotomedium, $options);
                            $response = "✅ Konfirmasi deposit telah kami terima dan akan segera kami proses.\n\n⏳ Mohon tunggu.\n\nTerima kasih! 🙏";
                        } elseif (!empty($fotorendah) || !empty($fotojelek)) {
                            $response = "⚠️ Maaf, foto Anda kurang jelas. Sistem kami tidak dapat membaca foto tersebut.\n\n📸 Mohon kirim ulang dengan foto yang lebih jelas.";
                        } else {
                            $response = '❌ Terjadi kesalahan. Mohon coba lagi nanti.';
                        }

                        Bot::sendMessage($response, ['parse_mode' => 'html']);
                    } else {
                        Bot::sendMessage("⚠️ Maaf, jumlah deposit hanya boleh berupa angka.\n\n📝 Contoh yang benar: <code>#konfirmasi deposit 50000</code>", ['parse_mode' => 'html']);
                    }
                } else {
                    $response = "ℹ️ <b>Panduan Konfirmasi Deposit</b>\n\n";
                    $response .= "Untuk melakukan konfirmasi deposit, silakan kirim foto bukti transfer dengan keterangan sebagai berikut:\n\n";
                    $response .= "<code>#konfirmasi deposit [jumlah]</code>\n\n";
                    $response .= 'Contoh: <code>#konfirmasi deposit 50000</code>';
                    Bot::sendMessage($response, ['parse_mode' => 'html']);
                }
            }
        } else {
            $response = "⚠️ Maaf, Anda belum terdaftar.\n\n";
            $response .= 'Silakan daftar terlebih dahulu ke admin atau klik /daftar';
            Bot::sendMessage($response, ['parse_mode' => 'html']);
        }
    }
});
$mkbot->run();

/*Please contact @Bangachil for bugs
history
1 Maret 2019
-Make ceksaldo command
-Make cekid
2 Maret 2019
-Make callback data
-Make menu command
-Make array menu
-Make callback answer
3 Maret 2019
-Make database Saldo
-bugs fix daftar
-bugs fix menu
-bugs fix saldo minus
-bugs fix topup
-Make topup send to ID
-Make button menu
10 Maret 2019
-make emoticon button menu
-make cek id calbback
-make voucher defalut disable
-make voucher null
19 Maret 2019
-bugs fix menu command
-bugs fix callback answer
-bugs fix list Voucher array
-bugs fix database
Version update 1.2.3
20 maret 2019
-Make User id callback
-remove emotion calbback
-remove array_filter
-move data callback
-move ceksaldo
-Make ceksaldo cek id
Version update 1.2.11

2 april 2019
-remove start auto join
-make hitspot view
-make remove user hotspot cmd
-make help cmd
-Make hass user
Version update 1.2.13

3 april 2019
-edited vcr callback data

Version update 1.2.14
8 april 2019
-Penambahan type char
-color qrcode
-bugs fix saldo minus
Version update 1.3.00

-10 april 2019
-Perispan nonsaldo
-edit text
-Version update 1.4.00
11 april 2019

-Version update 1.5.00

#25 Juli 2024 : HexSorcerer
- pergantian string (dengan icon)

Thanks to topupGroup an member , SengkuniCode, and to all user support mini project
Thanks to SengkuniCode for web ui,

 */
