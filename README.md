# AES-256-CTR File Encryption

A PHP implementation for secure file encryption using AES-256-CTR mode with HMAC-SHA256 authentication.

## Features

- AES-256-CTR encryption for file content
- HMAC-SHA256 for integrity verification
- Chunked processing for large files
- Counter (CTR) mode implementation for streaming encryption
- Benchmark tests for different chunk sizes

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

## Security Features

- **AES-256-CTR**: Counter mode allows parallel processing and random access
- **HMAC-SHA256**: Ensures file integrity and authenticity
- **Random IV**: Each encryption uses a unique initialization vector
- **Constant-time comparison**: Prevents timing attacks during HMAC verification

## Benchmarking

The project includes benchmark tests for different chunk sizes (1MB, 2MB, 5MB, 10MB). Run benchmarks using:

```bash
./vendor/bin/phpbench run tests/Benchmark --report=default
```

## File Format

Encrypted files have the following structure:
1. IV (16 bytes)
2. Encrypted data
3. HMAC (32 bytes)

## Requirements

- PHP 8.0+
- OpenSSL extension
- hash extension

## Installation

1. Clone the repository
2. Run `composer install`
3. Ensure input directory and data directory exist

