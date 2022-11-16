<?php
namespace App\Http\Traits;

trait EncryptDecryptTrait{

    /**
     * Get text and action 
     * Return encript data
     *
     * @return string 
    **/
    protected function encryptDecryptText($text, $action){
        $key = 'RV2011@tech%EPIC.18'; //you can set its won method.
        $rv_key = substr(hash('sha256', $key, true), 0, 32); // does not change rv_key value.
        $method = 'aes-256-cbc'; //fix doesnot change method value.

        // IV must be exact 16 chars (128 bit)
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        if($action == 'encrypt')
            return base64_encode(openssl_encrypt($text, $method, $rv_key, OPENSSL_RAW_DATA, $iv));
        elseif($action == 'dencrypt')
            return openssl_decrypt(base64_decode($text), $method, $rv_key, OPENSSL_RAW_DATA, $iv); 
    }

}