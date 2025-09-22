# AES-256-CTR File Encryption

A PHP implementation for secure file encryption using AES-256-CTR mode with HMAC-SHA256 authentication.

## Features

- AES-256-CTR encryption for file content
- HMAC-SHA256 for integrity verification
- Chunked processing for large files
- Counter (CTR) mode implementation for streaming encryption
- Benchmark tests for different chunk sizes

## Requirements

- PHP 8.0+
- OpenSSL extension
- hash extension

## Installation

1. Clone the repository
2. Run `composer install`
3. Ensure input directory and data directory exist

## Security Features

- **AES-256-CTR**: Counter mode allows parallel processing and random access
- **HMAC-SHA256**: Ensures file integrity and authenticity
- **Random IV**: Each encryption uses a unique initialization vector
- **Constant-time comparison**: Prevents timing attacks during HMAC verification

## File Format

Encrypted files have the following structure:
1. IV (16 bytes)
2. Encrypted data
3. HMAC (32 bytes)

## Sample File Creation

For testing purposes, you can create a sample input file:

### macOS
```bash
# Create a 4GB test file
mkfile 4G input.csv

# Or create a file with random data
dd if=/dev/urandom of=input.csv bs=1m count=4096
```

### Linux
```bash
# Create a 4GB test file
fallocate -l 4G input.csv

# Or create a file with random data
dd if=/dev/urandom of=input.csv bs=1M count=4096
```

## Usage

### Encryption

```php
$encryption = new \CMDN\PhpEncryption();
$key = random_bytes(32);        // 256-bit encryption key
$hmacKey = random_bytes(32);    // 256-bit HMAC key

$encryption->encrypt(
    'input.csv',               // Input file
    'data/encrypted.bin',      // Output file
    $key,
    $hmacKey,
    1048576                    // Chunk size (1MB default)
);
```

### Decryption

```php
$encryption->decrypt(
    'data/encrypted.bin',      // Encrypted file
    'output.csv',             // Decrypted output file
    $key,
    $hmacKey,
    1048576                   // Chunk size (1MB default)
);
```

## Benchmarking

The project includes benchmark tests for different chunk sizes (1MB, 2MB, 5MB, 10MB). Run benchmarks using:

```bash
./vendor/bin/phpbench run tests/Benchmark --report=default

PHPBench (1.4.1) running benchmarks... #standwithukraine
with configuration file: /path/to/phpbench.json
with PHP version 8.4.8, xdebug ❌, opcache ❌

\CMDN\Tests\Benchmark\PhpEncryptionBench

    benchEncrypt1MBChunk....................I0 - Mo23.311s (±0.00%)
    benchEncrypt2MBChunk....................I0 - Mo22.690s (±0.00%)
    benchEncrypt5MBChunk....................I0 - Mo22.944s (±0.00%)
    benchEncrypt10MBChunk...................I0 - Mo23.942s (±0.00%)

\CMDN\Tests\Benchmark\OpenSSLEncryptionBench

    benchEncryptCTR.........................I0 - Mo14.575s (±0.00%)
    benchEncryptCBC.........................I0 - Mo16.817s (±0.00%)

Subjects: 6, Assertions: 0, Failures: 0, Errors: 0
+------+------------------------+-----------------------+-----+------+-------------+------------------+--------------+----------------+
| iter | benchmark              | subject               | set | revs | mem_peak    | time_avg         | comp_z_value | comp_deviation |
+------+------------------------+-----------------------+-----+------+-------------+------------------+--------------+----------------+
| 0    | PhpEncryptionBench     | benchEncrypt1MBChunk  |     | 1    | 3,713,336b  | 23,310,969.000μs | +0.00σ       | +0.00%         |
| 0    | PhpEncryptionBench     | benchEncrypt2MBChunk  |     | 1    | 6,896,000b  | 22,689,803.000μs | +0.00σ       | +0.00%         |
| 0    | PhpEncryptionBench     | benchEncrypt5MBChunk  |     | 1    | 16,333,184b | 22,944,135.000μs | +0.00σ       | +0.00%         |
| 0    | PhpEncryptionBench     | benchEncrypt10MBChunk |     | 1    | 32,061,824b | 23,942,306.000μs | +0.00σ       | +0.00%         |
| 0    | OpenSSLEncryptionBench | benchEncryptCTR       |     | 1    | 721,992b    | 14,574,884.000μs | +0.00σ       | +0.00%         |
| 0    | OpenSSLEncryptionBench | benchEncryptCBC       |     | 1    | 721,992b    | 16,816,644.000μs | +0.00σ       | +0.00%         |
+------+------------------------+-----------------------+-----+------+-------------+------------------+--------------+----------------+
```

