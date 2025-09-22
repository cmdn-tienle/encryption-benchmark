<?php

namespace CMDN\Tests\Benchmark;

class PhpEncryptionBench
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
    
    public function benchEncrypt1MBChunk(): void
    {
        $encryption = new \CMDN\PhpEncryption();
        $encryption->encrypt(
            $this->projectRoot . '/input.csv',
            $this->projectRoot . '/data/encrypted.bin',
            $this->key,
            $this->hmacKey,
            1 * 1024 * 1024 // 1 MB chunk size
        );
    }
    
    public function benchEncrypt2MBChunk(): void
    {
        $encryption = new \CMDN\PhpEncryption();
        $encryption->encrypt(
            $this->projectRoot . '/input.csv',
            $this->projectRoot . '/data/encrypted.bin',
            $this->key,
            $this->hmacKey,
            2 * 1024 * 1024 // 2 MB chunk size
        );
    }
    
    public function benchEncrypt5MBChunk(): void
    {
        $encryption = new \CMDN\PhpEncryption();
        $encryption->encrypt(
            $this->projectRoot . '/input.csv',
            $this->projectRoot . '/data/encrypted.bin',
            $this->key,
            $this->hmacKey,
            5 * 1024 * 1024 // 5 MB chunk size
        );
    }
    
    public function benchEncrypt10MBChunk(): void
    {
        $encryption = new \CMDN\PhpEncryption();
        $encryption->encrypt(
            $this->projectRoot . '/input.csv',
            $this->projectRoot . '/data/encrypted.bin',
            $this->key,
            $this->hmacKey,
            10 * 1024 * 1024 // 10 MB chunk size
        );
    }
}