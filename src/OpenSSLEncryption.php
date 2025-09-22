<?php

namespace CMDN;

class OpenSSLEncryption
{
    public function encryptCTR($inputFile, $encryptedFile, $hmacFile, $key, $hmacKey)
    {
        $key = bin2hex($key);
        $iv = bin2hex(random_bytes(16));
        $hmacKey = bin2hex($hmacKey);

        // Encrypt
        $cmd = "openssl enc -aes-256-ctr -in " . escapeshellarg($inputFile) .
            " -out " . escapeshellarg($encryptedFile) .
            " -K $key -iv $iv";
        exec($cmd, $out, $ret);
        if ($ret !== 0)
            throw new Exception("OpenSSL encrypt failed");

        // HMAC
        $cmd = "openssl dgst -sha256 -mac HMAC -macopt hexkey:$hmacKey " .
            escapeshellarg($encryptedFile) . " > " . escapeshellarg($hmacFile);
        exec($cmd, $out, $ret);
        if ($ret !== 0)
            throw new Exception("OpenSSL HMAC failed");
    }


    public function encryptCBC($inputFile, $encryptedFile, $hmacFile, $key, $hmacKey)
    {
        $key = bin2hex($key);
        $iv = bin2hex(random_bytes(16));
        $hmacKey = bin2hex($hmacKey);

        // Encrypt
        $cmd = "openssl enc -aes-256-cbc -in " . escapeshellarg($inputFile) .
            " -out " . escapeshellarg($encryptedFile) .
            " -K $key -iv $iv";
        exec($cmd, $out, $ret);
        if ($ret !== 0)
            throw new Exception("OpenSSL encrypt failed");

        // HMAC
        $cmd = "openssl dgst -sha256 -mac HMAC -macopt hexkey:$hmacKey " .
            escapeshellarg($encryptedFile) . " > " . escapeshellarg($hmacFile);
        exec($cmd, $out, $ret);
        if ($ret !== 0)
            throw new Exception("OpenSSL HMAC failed");
    }
}