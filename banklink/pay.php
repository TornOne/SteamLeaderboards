<?php

// THIS IS AUTO GENERATED SCRIPT
// (c) 2011-2018 Kreata OÜ www.pangalink.net

// File encoding: UTF-8
// Check that your editor is set to use UTF-8 before using any non-ascii characters

// STEP 1. Setup private key
// =========================

$private_key = openssl_pkey_get_private(
"-----BEGIN RSA PRIVATE KEY-----
MIIEoAIBAAKCAQEAsjQT+diaTIjmw699TLPevBOAi1gOIjG+v09oOcc4UJ1+1ZTs
rR8yZhewN3g9QpHGWxpQvWE/vfpitvIPYUQNxJYbDEdjBiAU7bPL0m8yZMbccBsU
q8nYed2bx973ednJv3tc8GNKrPj56cCB1a7KDkI+YKGs/HCZiyLc6IWSzOuqeVpv
1lW5HisRXxXczv1iDw43Lq1zl53CL0LnCBDaKE36ub4J8F5821uPxErlaGdQtKv8
s4e9PxhqtoiyFcErcu/eSjgpz+6TTZF8a5LO+BMAvCz1eoBEMrmu+qFCKBbvZ3s4
0Cv23CAjfL6t2r/z74zFPIZSgJbl2+d+kSGAsQIDAQABAoIBACDMUPjlEtEPSVmu
zOL3IAGBnWEIHeHRQgg39zjbH9RGiHeK51ydp0r/BSHQcYX1Cort9vpEEdVHZi/B
sW73dYk/D09dPC5+bRuqwdLDQIUnzJ6dfVp41ezoCqnosaetm+IwNFTSEYZwdJ6/
HaZUez/QlWZ3mz0DBF+FVR8+plwsnQV66AWz3TryL3WQwrmL3CitkbWqpyw69vwN
8DERzO+cT7pRgTpsj+PkKW5NSzM0SbU+Lh6MbNz2Wl7tX0/dy3GdIoWrzU3/xP7s
aONABt14X+VG5FqBG6kBY+jjUo4WoaavNDGAo9efWDteJLo0jYBV18zCTBa0EmQP
PgLqRpkCgYEA4HP303Et4usqGbBZO5dHVuwbM2462gL0gTS71qiKFBbJcBIVvaYo
OfLMkJ8BCtVmKCxbGc7ijvETYYWv0q71tO39tTYLe0dRbr6xTswQlpInXO9jm7zX
FL0+n1OOiglBVkNSvqiqjJ6D+kRXHDtHqecdivW8VHVC6AbMZ701qdMCgYEAy0AB
q/Xcbi+e0hRZ5Buu9gddQI1173m0Q6xyg6OG/F8ACPcB/7dkGQu0MechU8A4yZZ0
C9bhd/bDuTw6fxrKX7zWOisBHC3sadFGpm97Nsu5sVkZy89Jt8FE9rrpjp6TYsfc
AscBR8SRQVC4DAigk/UXp+e/UfEB4AgXxtqJdOsCfwmk8gEj29TkRerUqQDmfyo8
+u46zkyP0/G5Uljm5Qf38q/eFxEPEqtqw19zdZgBk2ImhcOWICYENdD67ZMf8W7U
yxH+QIALkHQxvWylWBEgNpDHuH/4nUVG3Gn8LSPBQg1y1xtaI/nm1cTrKtMLuQiT
5bES3gTDBRzzLdVHTAcCgYBY/FP/CqyQnU7GoVxdz3UBiGXeflC1IS64NkcItU7D
LYChbCu23n73rDUfaBciSNVUBQgXquOaFzLH5cOipIMX+WA9pVheEwkfpx1sfwCO
FBHfwKfXoJCg4AAmmdzpsSnMJL6BhMldt4T0+LcrpZdQUA0kWQVdhcwRXtmyGTn3
MwKBgGANfJgsFweyRM9dqP2QAyBK/PEAPG7DJUBoJifRgXQBbC/1ohFDI2BSF+TQ
7DPs4vDybz0ADrjazRqAfO9omP14OweewO4sfJlLCSVhqI/FrPrRwkCzKp5/GuBF
ITJ5fS7+qYDssoCqJ7zcctLnWdxi2275P2ItlLVj6byi8wx6
-----END RSA PRIVATE KEY-----");

// STEP 2. Define payment information
// ==================================

$fields = array(
        "VK_SERVICE"     => "1011",
        "VK_VERSION"     => "008",
        "VK_SND_ID"      => "uid13",
        "VK_STAMP"       => "12345",
        "VK_AMOUNT"      => "1",
        "VK_CURR"        => "EUR",
        "VK_ACC"         => "EE152200221234567897",
        "VK_NAME"        => "SteamLeaderboards",
        "VK_REF"         => "1234561",
        "VK_LANG"        => "EST",
        "VK_MSG"         => "Torso Tiger",
        "VK_RETURN"      => "https://steamleaderboards.herokuapp.com/extras.php?payment_action=success",
        "VK_CANCEL"      => "https://steamleaderboards.herokuapp.com/extras.php?payment_action=cancel",
        "VK_DATETIME"    => "2018-04-12T18:51:47+0300",
        "VK_ENCODING"    => "utf-8",
);

// STEP 3. Generate data to be signed
// ==================================

// Data to be signed is in the form of XXXYYYYY where XXX is 3 char
// zero padded length of the value and YYY the value itself
// NB! Swedbank expects symbol count, not byte count with UTF-8,
// so use `mb_strlen` instead of `strlen` to detect the length of a string

$data = str_pad (mb_strlen($fields["VK_SERVICE"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $fields["VK_SERVICE"] .    /* 1011 */
        str_pad (mb_strlen($fields["VK_VERSION"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $fields["VK_VERSION"] .    /* 008 */
        str_pad (mb_strlen($fields["VK_SND_ID"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_SND_ID"] .     /* uid13 */
        str_pad (mb_strlen($fields["VK_STAMP"], "UTF-8"),   3, "0", STR_PAD_LEFT) . $fields["VK_STAMP"] .      /* 12345 */
        str_pad (mb_strlen($fields["VK_AMOUNT"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_AMOUNT"] .     /* 150 */
        str_pad (mb_strlen($fields["VK_CURR"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $fields["VK_CURR"] .       /* EUR */
        str_pad (mb_strlen($fields["VK_ACC"], "UTF-8"),     3, "0", STR_PAD_LEFT) . $fields["VK_ACC"] .        /* EE152200221234567897 */
        str_pad (mb_strlen($fields["VK_NAME"], "UTF-8"),    3, "0", STR_PAD_LEFT) . $fields["VK_NAME"] .       /* SteamLeaderboards */
        str_pad (mb_strlen($fields["VK_REF"], "UTF-8"),     3, "0", STR_PAD_LEFT) . $fields["VK_REF"] .        /* 1234561 */
        str_pad (mb_strlen($fields["VK_MSG"], "UTF-8"),     3, "0", STR_PAD_LEFT) . $fields["VK_MSG"] .        /* Torso Tiger */
        str_pad (mb_strlen($fields["VK_RETURN"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_RETURN"] .     /* http://localhost:3480/project/5acf8067cb262a2cf0653d45?payment_action=success */
        str_pad (mb_strlen($fields["VK_CANCEL"], "UTF-8"),  3, "0", STR_PAD_LEFT) . $fields["VK_CANCEL"] .     /* http://localhost:3480/project/5acf8067cb262a2cf0653d45?payment_action=cancel */
        str_pad (mb_strlen($fields["VK_DATETIME"], "UTF-8"), 3, "0", STR_PAD_LEFT) . $fields["VK_DATETIME"];    /* 2018-04-12T18:51:47+0300 */

/* $data = "0041011003008005uid1300512345003150003EUR020EE152200221234567897017SteamLeaderboards0071234561011Torso Tiger077http://localhost:3480/project/5acf8067cb262a2cf0653d45?payment_action=success076http://localhost:3480/project/5acf8067cb262a2cf0653d45?payment_action=cancel0242018-04-12T18:51:47+0300"; */

// STEP 4. Sign the data with RSA-SHA1 to generate MAC code
// ========================================================

openssl_sign ($data, $signature, $private_key, OPENSSL_ALGO_SHA1);

/* fKzVA25sI+9S0jDMHlIuF93gRwcYgmfS3A3Aj3DcN1K6XT4JI1cUBdEbHw9iwdq9wfLRO3MisuqDoDK4TOTiIG/0qA3rG6NfdnyOfugodfsgSzBQ/IGj27uUnbi05M/P+16Tq2NR0s+s5ebPcWJWsqQuKzV16I61d1JDjYumHPDZYFRGT+ot1Mt5SOJxHsNsHxWLxiiKPK/dmKmkXJLsU6C0GCD3Y377F2OxgZYTMp/Qk859Klyu6v0CTn7I5CnRLcLHT52Omk5reSWJ2GbRbSWrLnmjHosrnB7YZHQXYOX2KwoxhnUEG3NPKuUer5qSWa796jh/Pjb/3XRWGx1cfQ== */
$fields["VK_MAC"] = base64_encode($signature);

// STEP 5. Generate POST form with payment data that will be sent to the bank
// ==========================================================================
?>
