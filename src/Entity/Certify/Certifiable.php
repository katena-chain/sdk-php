<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Certify;

/**
 * Certifiable contains common methods for tx data instances.
 */
trait Certifiable
{
    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return self::getNamespaceCertify();
    }

    /**
     * @return string
     */
    public function getSubNamespace(): string
    {
        return self::getCertificateSubNamespace();
    }

    /**
     * @return string
     */
    public static function getNamespaceCertify() :string
    {
        return Certify::NAMESPACE_CERTIFY;
    }

    /**
     * @return string
     */
    public static function getCertificateSubNamespace() :string
    {
        return vsprintf("%s.%s", [static::getNamespaceCertify(), Certify::TYPE_CERTIFICATE]);
    }

    /**
     * @return string
     */
    public static function getSecretSubNamespace() :string
    {
        return vsprintf("%s.%s", [static::getNamespaceCertify(), Certify::TYPE_SECRET]);
    }

    /**
     * @return string
     */
    public static function getTypeCertificateRawV1() :string
    {
        return vsprintf("%s.%s.%s", [static::getCertificateSubNamespace(), Certify::TYPE_RAW, "v1"]);
    }

    /**
     * @return string
     */
    public static function getTypeCertificateEd25519V1() :string
    {
        return vsprintf("%s.%s.%s", [static::getCertificateSubNamespace(), Certify::TYPE_ED25519, "v1"]);
    }

    /**
     * @return string
     */
    public static function getTypeSecretNaclBoxV1() :string
    {
        return vsprintf("%s.%s.%s", [static::getSecretSubNamespace(), Certify::TYPE_NACL_BOX, "v1"]);
    }
}
