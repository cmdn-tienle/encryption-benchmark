<?php

namespace CMDN;

use Exception;

class PhpEncryption
{
    private function incrementCounter($iv)
    {
        $ivLen = strlen($iv);

        for ($i = $ivLen - 1; $i >= 0; $i--) {
            $ord = ord($iv[$i]);
            if ($ord === 255) {
                $iv[$i] = chr(0);
            } else {
                $iv[$i] = chr($ord + 1);
                break;
            }
        }

        return $iv;
    }

    public function encrypt($inputFile, $outputFile, $key, $hmacKey, $chunkSize = 1048576)
    {
        $ivLength = openssl_cipher_iv_length('aes-256-ctr');
        $iv = random_bytes($ivLength);

        $in = fopen($inputFile, 'rb');
        $out = fopen($outputFile, 'wb');

        // Write IV at the beginning of file
        fwrite($out, $iv);

        $hmacCtx = hash_init('sha256', HASH_HMAC, $hmacKey);

        while (!feof($in)) {
            $plaintext = fread($in, $chunkSize);
            if ($plaintext === false)
                break;

            $ciphertext = openssl_encrypt($plaintext, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv);

            // update IV for CTR (since openssl doesn't increment automatically across chunks)
            $iv = $this->incrementCounter($iv);

            fwrite($out, $ciphertext);
            hash_update($hmacCtx, $ciphertext);
        }

        // Write HMAC at end of file
        $hmac = hash_final($hmacCtx, true);
        fwrite($out, $hmac);

        fclose($in);
        fclose($out);
    }

    public function decrypt($inputFile, $outputFile, $key, $hmacKey, $chunkSize = 1048576)
    {
        $ivLength = openssl_cipher_iv_length('aes-256-ctr');
        $hmacLength = 32; // SHA256 raw length

        $in = fopen($inputFile, 'rb');
        $out = fopen($outputFile, 'wb');

        // Read IV
        $iv = fread($in, $ivLength);

        // Get filesize
        $fileSize = filesize($inputFile);

        // Ciphertext length = file - iv - hmac
        $cipherLen = $fileSize - $ivLength - $hmacLength;

        $hmacCtx = hash_init('sha256', HASH_HMAC, $hmacKey);

        $totalRead = 0;
        while ($totalRead < $cipherLen) {
            $bytesToRead = min($chunkSize, $cipherLen - $totalRead);
            $ciphertext = fread($in, $bytesToRead);
            $totalRead += strlen($ciphertext);

            $plaintext = openssl_decrypt($ciphertext, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv);

            $iv = $this->incrementCounter($iv);

            fwrite($out, $plaintext);
            hash_update($hmacCtx, $ciphertext);
        }

        // Read HMAC at end
        $hmacStored = fread($in, $hmacLength);
        $hmacCalc = hash_final($hmacCtx, true);

        fclose($in);
        fclose($out);

        if (!hash_equals($hmacStored, $hmacCalc)) {
            throw new Exception("Integrity check failed! HMAC mismatch.");
        }
    }
}
