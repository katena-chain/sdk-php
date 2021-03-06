<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Utils;

use KatenaChain\Client\Crypto\Ed25519;
use KatenaChain\Client\Crypto\Nacl;
use ParagonIE_Sodium_Compat;

class Crypto
{
    /**
     * accepts a base64 encoded Ed25519 private key (88 chars) and returns an Ed25519 private key.
     * @param string $privateKeyBase64
     * @return Ed25519\PrivateKey
     */
    public static function createPrivateKeyEd25519FromBase64(string $privateKeyBase64): Ed25519\PrivateKey
    {
        return new Ed25519\PrivateKey(base64_decode($privateKeyBase64));
    }

    /**
     * accepts a base64 encoded Ed25519 public key (44 chars) and returns an Ed25519 public key.
     * @param string $publicKeyBase64
     * @return Ed25519\PublicKey
     */
    public static function createPublicKeyEd25519FromBase64(string $publicKeyBase64): Ed25519\PublicKey
    {
        return new Ed25519\PublicKey(base64_decode($publicKeyBase64));
    }

    /**
     * generates a new ed25519 private key.
     * @return Ed25519\PrivateKey
     * @throws \SodiumException
     */
    public static function generateNewPrivateKeyEd25519(): Ed25519\PrivateKey
    {
        return new Ed25519\PrivateKey(substr(ParagonIE_Sodium_Compat::crypto_sign_keypair(), 0, 64));
    }

    /**
     * accepts a base64 encoded X25519 private key (88 chars) and returns an X25519 private key.
     * @param string $privateKeyBase64
     * @return Nacl\PrivateKey
     */
    public static function createPrivateKeyX25519FromBase64(string $privateKeyBase64): Nacl\PrivateKey
    {
        return new Nacl\PrivateKey(base64_decode($privateKeyBase64));
    }

    /**
     * accepts a base64 encoded X25519 public key (44 chars) and returns an X25519 public key.
     * @param string $publicKeyBase64
     * @return Nacl\PublicKey
     */
    public static function createPublicKeyX25519FromBase64(string $publicKeyBase64): Nacl\PublicKey
    {
        return new Nacl\PublicKey(base64_decode($publicKeyBase64));
    }
}
