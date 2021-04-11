<?php
// DEFINE our cipher
define('AES_256_CBC', 'aes-256-cbc');
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);


// Generate a 256-bit encryption key
// This should be stored somewhere instead of recreating it each time

function encrypt($pass, $data){

	$encryption_key = substr(hash('sha512', $pass),32);
	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
	$encrypted = openssl_encrypt($data, AES_256_CBC, $encryption_key, 0, $iv);
	$encrypted = bin2hex(base64_decode($encrypted)) . ':' . bin2hex($iv);
	return $encrypted;
}

function decrypt($hash, $data){

	$encryption_key = substr($hash,32);
	$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
	$parts = explode(':', $data);
	$decrypted = openssl_decrypt(base64_encode(hex2bin($parts[0])), AES_256_CBC, $encryption_key, 0, hex2bin($parts[1]));
	return $decrypted;
}

function sodiumGenerate(){
	$keypair = sodium_crypto_box_keypair();
	$public_key = sodium_crypto_box_publickey($keypair);
	return array("keypair" => $keypair, "public_key" => $public_key);
}