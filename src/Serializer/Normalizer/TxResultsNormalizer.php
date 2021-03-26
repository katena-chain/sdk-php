<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer\Normalizer;

use KatenaChain\Client\Entity\Api\TxResult;
use KatenaChain\Client\Entity\Api\TxResults;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

class TxResultsNormalizer extends ArrayDenormalizer
{

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return array|TxResults|mixed|object
     * @throws ExceptionInterface
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        /**
         * @var TxResults $txResults
         */
        $txs = parent::denormalize($data['txs'], TxResult::class . "[]", $format, $context);
        $txResults = new $type();
        $txResults->setTotal($data['total'])
            ->setTxs($txs);
        return $txResults;
    }

    /**
     * @param $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof TxResults);
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return bool
     */
    public function supportsDenormalization($data, string $type, string $format = null, array $context = []) : bool
    {
        return ($type == TxResults::class);
    }
}
