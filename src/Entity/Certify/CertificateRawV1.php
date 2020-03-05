<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Certify;

use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Entity\TxData;

/**
 * CertificateRawV1 is the first version of a raw certificate.
 */
class CertificateRawV1 implements TxData
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var Bytes
     */
    protected $value;

    /**
     * @param string $id
     * @return CertificateRawV1
     */
    public function setId(string $id): CertificateRawV1
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param Bytes $value
     * @return CertificateRawV1
     */
    public function setValue(Bytes $value): CertificateRawV1
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Bytes
     */
    public function getValue(): Bytes
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Certify::getTypeCertificateRawV1();
    }
}
