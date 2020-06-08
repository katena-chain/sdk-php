<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity;

use DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * Tx wraps a tx data with its signature information and a nonce time to avoid replay attacks.
 */
class Tx
{
    /**
     * @var DateTime
     */
    protected $nonceTime;

    /**
     * @var TxData
     */
    protected $data;

    /**
     * @var string
     */
    protected $signerFqId;

    /**
     * @var Bytes
     */
    protected $signature;

    /**
     * @param DateTime $nonceTime
     * @return Tx
     */
    public function setNonceTime(DateTime $nonceTime): Tx
    {
        $this->nonceTime = $nonceTime;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getNonceTime(): DateTime
    {
        return $this->nonceTime;
    }

    /**
     * @param TxData $data
     * @return Tx
     */
    public function setData(TxData $data): Tx
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return TxData
     */
    public function getData(): TxData
    {
        return $this->data;
    }

    /**
     * @SerializedName("signer_fqid")
     * @param string $signerFqId
     * @return Tx
     */
    public function setSignerFqId(string $signerFqId): Tx
    {
        $this->signerFqId = $signerFqId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignerFqId(): string
    {
        return $this->signerFqId;
    }

    /**
     * @param Bytes $signature
     * @return Tx
     */
    public function setSignature(Bytes $signature): Tx
    {
        $this->signature = $signature;
        return $this;
    }

    /**
     * @return Bytes
     */
    public function getSignature(): Bytes
    {
        return $this->signature;
    }
}
