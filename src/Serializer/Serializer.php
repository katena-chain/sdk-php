<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer;

use KatenaChain\Client\Serializer\Normalizer\CryptoNormalizer;
use KatenaChain\Client\Serializer\Normalizer\DateTimeNormalizer;
use KatenaChain\Client\Serializer\Normalizer\BytesNormalizer;
use KatenaChain\Client\Serializer\Normalizer\TxNormalizer;
use KatenaChain\Client\Serializer\Normalizer\TxWrappersNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class Serializer
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];

        $dateNormalizer = new DateTimeNormalizer();
        $cryptoNormalizer = new CryptoNormalizer();
        $signatureNormalizer = new BytesNormalizer();
        $arrayNormalizer = new ArrayDenormalizer();
        $txWrappersNormalizer = new TxWrappersNormalizer();
        $objNormalizer = new ObjectNormalizer(
            null,
            new CamelCaseToSnakeCaseNameConverter(),
            null,
            new ReflectionExtractor()
        );

        $txNormalizer = new TxNormalizer(
            null,
            new CamelCaseToSnakeCaseNameConverter(),
            null,
            new ReflectionExtractor()
        );
        $normalizers = [
            $dateNormalizer,
            $arrayNormalizer,
            $txWrappersNormalizer,
            $signatureNormalizer,
            $txNormalizer,
            $cryptoNormalizer,
            $objNormalizer
        ];

        $ignoredAttributes = ['namespace', 'subNamespace', 'type'];
        $txNormalizer->setIgnoredAttributes($ignoredAttributes);
        $objNormalizer->setIgnoredAttributes($ignoredAttributes);


        $this->serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
    }


    /**
     * serializes data in the appropriate format.
     *
     * @param mixed $data Any data
     * @param array $context Options normalizers/encoders have access to
     *
     * @return string
     */
    public function serialize($data, array $context = [])
    {
        $context = array_merge(
            [
                'json_encode_options' => JSON_UNESCAPED_SLASHES,
            ],
            $context
        );
        return $this->serializer->serialize($data, JsonEncoder::FORMAT, $context);
    }

    /**
     * deserializes data into the given type.
     *
     * @param mixed $data
     * @param string $type
     *
     * @param array $context
     * @return mixed
     */
    public function deserialize($data, $type, array $context = [])
    {
        return $this->serializer->deserialize($data, $type, JsonEncoder::FORMAT, $context);
    }
}
