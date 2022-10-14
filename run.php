<?php 
require 'three.php';
echo @color("nevy","───────────────────────────────────────────\n");
echo @color("green","                TEMBAK THREE              \n");
echo @color("nevy","───────────────────────────────────────────\n");
echo @color('green', "Check Config.... \n");
sleep(3);
STEP1:
$config = load_config();

if (!$config) {
    echo @color('red', "Need Login First!\n");

    OTP:
    echo @color('purple', "NOMOR\t\t: ");
    $nomor = trim(fgets(STDIN));

    $login = login($nomor);
    if (!$login['status']) {
        echo @color('red', "Wrong Number!\n");
        goto OTP;
    }

    echo @color('green', $login['message']."\n");

    REOTP:
    echo @color('purple', "OTP\t\t: ");
    $otp = trim(fgets(STDIN));

    $otpLogin = otplogin($nomor, $otp);

    if (!$otpLogin['status']) {
        echo @color('red', "Wrong Otp!\n");
        goto REOTP;
    }

    save_config(json_encode($otpLogin));
    goto STEP1;
}

echo @color('green', "Success Load ....\n");
echo @color('purple', "Want Change Number? y/N\t\t: ");
$zz = trim(fgets(STDIN));
$zz = strtolower($zz);
if ($zz == "y") {
    @unlink("config.json");
    goto STEP1;
}

$secret = $config['secretKey'];
$plan = $config['callPlan'];
$nomor = $config['msisdn'];

echo @color('navy', "Check Profile....\n");
$profil = profile($nomor, $plan, $secret);

if (!$profil['status']) {
   goto OTP;
}

$balance = $profil['creditBalance'];
$aktif = $profil['activeUntil'];
$sisakuota = $profil['sumOfInternet'];
$poin = $profil['stotalPoin'];

echo @color('green', "Success ....\n");

echo @color('yellow', "PULSA\t\t: ");
echo @color('nevy', "$balance\n");
echo @color('yellow', "MASA AKTIF\t: ");
echo @color('nevy', "$aktif\n");
echo @color('yellow', "SISA KUOTA\t: ");
echo @color('nevy', "$sisakuota\n");
echo @color('yellow', "BONSTRI\t\t: ");
echo @color('nevy', "$poin Poin\n");

CEK:
echo @color('green', "PILIH PAKET:\n");
echo @color('yellow', "[1] Welcome Reward 5GB ==> Rp 1\n[2] (NEW) 10GB 30 Hari ==> Rp 15000\n[3] (NEW) 15GB 30 Hari ==> Rp 20000\n[4] 25GB 25rb (Diskon) ==> Rp 25000\n[5] 25GB 24 Jam 20 Hari ==> Rp 25000\n[6] (NEW) 25GB 20 Hari ==> Rp 25000\n[7] (NEW) 25GB 20 Hari ==> Rp 25000\n[8] 25GB 24 Jam 30 Hari ==> Rp 29000\n[9] (NEW) 25GB 30 Hari ==> Rp 29000\n[10] (NEW) 55GB 30 Hari ==> Rp 50000\n[11] (NEW) 65GB 30 Hari ==> Rp 60000\n[12] (NEW) 75GB 30 Hari ==> Rp 75000\n[13] (NEW) 90GB 30 Hari ==> Rp 90000\n[14] (NEW) 100GB 30 Hari ==> Rp 90000\n");
echo @color('green', "PILIH : ");
$pilih = trim(fgets(STDIN));

switch ($pilih) {
    case '1':
    $prodid = '25669';
    break;
    case '2':
    $prodid = '25245';
    break;
    case '3':
    $prodid = '25459';
    break;
    case '4':
    $prodid = '22648';
    break;
    case '5':
    $prodid = '23160';
    break;
    case '6':
    $prodid = '25254';
    break;
    case '7':
    $prodid = '25264';
    break;
    case '8':
    $prodid = '23164';
    break;
    case '9':
    $prodid = '25267';
    break;
    case '10':
    $prodid = '25469';
    break;
    case '11':
    $prodid = '25690';
    break;
    case '12':
    $prodid = '25247';
    break;
    case '13':
    $prodid = '25476';
    break;
    case '14':
    $prodid = '25693';
    break;

default:
    echo @color('red', "PILIH PAKET TERLEBIH DAHULU\n");
    goto CEK;
    break;
}

echo @color('navy', "Load Detail ....\n");
$cek = check($prodid);

if (!$cek['status']) {
    echo @color('red', "Error fetch detail!\n");
    goto CEK;
}

echo @color('green', "Success ....\n");
$name = $cek['product']['productName'];
$price = $cek['product']['productPrice'];
$deskripsi = $cek['product']['productDescription'];

echo @color('yellow', "NAMA PAKET\t: ");
echo @color('nevy', "$name\n");
echo @color('yellow', "HARGA\t\t: ");
echo @color('nevy', "$price\n");
echo @color('yellow', "DESKRIPSI\t: ");
echo @color('nevy', "$deskripsi\n");
echo @color('green', "LANJUT ? (y/n) :");
$aa = trim(fgets(STDIN));
if(strtolower($aa) !== 'y') {
    goto CEK;
}

echo @color('navy', "Process ....\n");
$beli = buy($nomor, $plan, $secret, $prodid);

if (!$beli['status']) {
    echo @color('red', "Failed!\n");
}

echo @color('green', "Success ....\n");