<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity;

use KatenaChain\Client\Crypto\Ed25519\PrivateKey;

/**
 * TxSigner contains all information about a Tx signer.
 */
class TxSigner
{
    /**
     * @var string
     */
    protected $fqId;

    /**
     * @var PrivateKey
     */
    protected $privateKey;

    /**
     * @return string
     */
    public function getFqId(): string
    {
        return $this->fqId;
    }

    /**
     * @return PrivateKey
     */
    public function getPrivateKey(): PrivateKey
    {
        return $this->privateKey;
    }

    /**
     * TxSigner constructor.
     * @param string $fqId
     * @param PrivateKey|null $privateKey
     */
    public function __construct(
        string $fqId,
        ?PrivateKey $privateKey = null
    )
    {
        $this->fqId = $fqId;
        $this->privateKey = $privateKey;
    }
}
