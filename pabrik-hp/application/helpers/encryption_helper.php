
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| ENCRYPT SHA1
|--------------------------------------------------------------------------
*/
function encrypt_sha1($string)
{
    return sha1($string);
}

/*
|--------------------------------------------------------------------------
| ENCRYPT MD5
|--------------------------------------------------------------------------
*/
function encrypt_md5($string)
{
    return md5($string);
}

/*
|--------------------------------------------------------------------------
| ENCRYPT PASSWORD
|--------------------------------------------------------------------------
| kombinasi :
| password -> sha1 -> private_key -> md5
|--------------------------------------------------------------------------
*/
function encrypt_password($password)
{
    // private key rahasia
    $private_key = 'MY_APP_KEY';

    // step 1 : sha1
    $sha1 = encrypt_sha1($password);

    // step 2 : gabungkan dengan private key
    $combine = $sha1 . $private_key;

    // step 3 : md5
    $final = encrypt_md5($combine);

    return $final;
}

