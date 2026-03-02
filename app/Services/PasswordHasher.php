<?php

namespace App\Services;

/**
 * PasswordHasher implements a simple user-defined hashing algorithm.
 *
 * It uses:
 * - A random salt (hex string)
 * - A fixed number of iterations over SHA-256
 *
 * This avoids Laravel's built-in password hashing so that the logic
 * is completely manual and easy to explain for a data structures/project
 * write-up.
 */
class PasswordHasher
{
    /**
     * Number of hashing iterations (Band B: simple user-defined algorithm).
     */
    private int $iterations = 1000;

    /**
     * Generate a salted hash for the given plain-text password.
     *
     * @return array{hash: string, salt: string}
     */
    public function hash(string $password): array
    {
        $salt = bin2hex(random_bytes(16)); // 32 hex characters

        $hash = $this->runHashLoop($password, $salt);

        return [
            'hash' => $hash,
            'salt' => $salt,
        ];
    }

    /**
     * Verify that a plain-text password matches a stored salted hash.
     */
    public function verify(string $password, string $storedHash, string $salt): bool
    {
        $computed = $this->runHashLoop($password, $salt);

        // Manual constant-time comparison to avoid timing attacks.
        if (strlen($computed) !== strlen($storedHash)) {
            return false;
        }

        $status = 0;
        $length = strlen($computed);

        for ($i = 0; $i < $length; $i++) {
            $status |= ord($computed[$i]) ^ ord($storedHash[$i]);
        }

        return $status === 0;
    }

    /**
     * Core hashing loop: combines salt + password and iterates SHA-256.
     *
     * This is where the "algorithm" element lives – a simple loop that can be
     * described in your report as a user-defined algorithm rather than relying
     * on Laravel helpers.
     */
    private function runHashLoop(string $password, string $salt): string
    {
        $value = $salt . '|' . $password;

        for ($i = 0; $i < $this->iterations; $i++) {
            $value = hash('sha256', $value);
        }

        return $value;
    }
}

