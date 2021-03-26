<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use KatenaChain\Client\Serializer\Normalizer\CryptoNormalizer;
use KatenaChain\Client\Serializer\Normalizer\DateTimeNormalizer;
use KatenaChain\Client\Serializer\Normalizer\BytesNormalizer;
use KatenaChain\Client\Serializer\Normalizer\TxDataNormalizer;
use KatenaChain\Client\Serializer\Normalizer\TxResultsNormalizer;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class Serializer
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    protected $ignoredAttributes;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];

        $dateNormalizer = new DateTimeNormalizer();
        $cryptoNormalizer = new CryptoNormalizer();
        $bytesNormalizer = new BytesNormalizer();
        $arrayNormalizer = new ArrayDenormalizer();
        $txResultsNormalizer = new TxResultsNormalizer();

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory, new CamelCaseToSnakeCaseNameConverter());

        $txDataNormalizer = new TxDataNormalizer(
            $classMetadataFactory,
            $metadataAwareNameConverter,
            null,
            new ReflectionExtractor()
        );

        $objNormalizer = new ObjectNormalizer(
            $classMetadataFactory,
            $metadataAwareNameConverter,
            null,
            new ReflectionExtractor()
        );

        $normalizers = [
            $dateNormalizer,
            $bytesNormalizer,
            $txDataNormalizer,
            $cryptoNormalizer,
            $arrayNormalizer,
            $txResultsNormalizer,
            $objNormalizer,
        ];

        $this->ignoredAttributes = ['type', 'namespace'];

        $this->serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
    }


    /**
     * serializes data in the appropriate format.
     *
     * @param mixed $data Any data
     * @param array $context Options normalizers/encoders have access to
     * @return string
     */
    public function serialize($data, array $context = [])
    {
        $context = array_merge(
            [
                'json_encode_options' => JSON_UNESCAPED_SLASHES,
                'ignored_attributes' => $this->ignoredAttributes
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
