<?php

namespace CMDN\Tests\Benchmark;

class OpenSSLEncryptionBench
{
    private string $key;
    private string $hmacKey;
    private string $projectRoot;
    
    public function __construct()
    {
        $this->key = random_bytes(32);
        $this->hmacKey = random_bytes(32);

        // Calculate absolute path to project root
        $this->projectRoot = dirname(__DIR__, 2);
    }
    
    public function benchEncryptCTR(): void
    {
        $encryption = new \CMDN\OpenSSLEncryption();
        $encryption->encryptCTR(
            $this->projectRoot . '/input.csv',
            $this->projectRoot . '/data/encrypted_openssl_ctr.bin',
            $this->projectRoot . '/data/encrypted_openssl_ctr.hmac',
            $this->key,
            $this->hmacKey
        );
    }
    
    public function benchEncryptCBC(): void
    {
        $encryption = new \CMDN\OpenSSLEncryption();
        $encryption->encryptCBC(
            $this->projectRoot . '/input.csv',
            $this->projectRoot . '/data/encrypted_openssl_cbc.bin',
            $this->projectRoot . '/data/encrypted_openssl_cbc.hmac',
            $this->key,
            $this->hmacKey
        );
    }
}