<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Certify;

class Certify
{
    const NAMESPACE        = "certify";
    const TYPE_CERTIFICATE = "certificate";
    const TYPE_SECRET      = "secret";
    const TYPE_RAW         = "raw";
    const TYPE_ED25519     = "ed25519";
    const TYPE_NACL_BOX    = "nacl_box";

    /**
     * @return string
     */
    public static function getCategoryCertificate(): string
    {
        return vsprintf("%s.%s", [self::NAMESPACE, self::TYPE_CERTIFICATE]);
    }

    /**
     * @return string
     */
    public static function getCategorySecret(): string
    {
        return vsprintf("%s.%s", [self::NAMESPACE, self::TYPE_SECRET]);
    }

    /**
     * @return string
     */
    public static function getTypeCertificateRawV1(): string
    {
        return vsprintf("%s.%s.%s", [self::getCategoryCertificate(), self::TYPE_RAW, "v1"]);
    }

    /**
     * @return string
     */
    public static function getTypeCertificateEd25519V1(): string
    {
        return vsprintf("%s.%s.%s", [self::getCategoryCertificate(), self::TYPE_ED25519, "v1"]);
    }

    /**
     * @return string
     */
    public static function getTypeSecretNaclBoxV1(): string
    {
        return vsprintf("%s.%s.%s", [self::getCategorySecret(), self::TYPE_NACL_BOX, "v1"]);
    }
}
