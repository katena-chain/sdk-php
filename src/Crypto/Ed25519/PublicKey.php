<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Crypto\Ed25519;

use KatenaChain\Client\Crypto\AbstractKey;
use ParagonIE_Sodium_Compat;
use SodiumException;

/**
 * PublicKey is an Ed25519 public key wrapper (32 bytes).
 */
class PublicKey extends AbstractKey
{
    /**
     * indicates if a message and a signature match.
     * @param string $message
     * @param string $signature
     * @return bool
     * @throws SodiumException
     */
    public function verify(string $message, string $signature): bool
    {
        return ParagonIE_Sodium_Compat::crypto_sign_verify_detached($signature, $message, $this->key);
    }
}
