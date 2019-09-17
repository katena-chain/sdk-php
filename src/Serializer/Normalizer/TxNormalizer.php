<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Serializer\Normalizer;

use KatenaChain\Client\Entity\Certify\Certifiable;
use KatenaChain\Client\Entity\Certify\CertificateEd25519V1;
use KatenaChain\Client\Entity\Certify\CertificateRawV1;
use KatenaChain\Client\Entity\Certify\SecretNaclBoxV1;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class TxNormalizer extends ObjectNormalizer
{

    /**
     * @return array
     */
    public static function getAvailableTypes(): array
    {
        return [
            Certifiable::getTypeCertificateRawV1()     => CertificateRawV1::class,
            Certifiable::getTypeCertificateEd25519V1() => CertificateEd25519V1::class,
            Certifiable::getTypeSecretNaclBoxV1()      => SecretNaclBoxV1::class,
        ];
    }

    /**
     * @param $obj
     * @param null $format
     * @param array $context
     * @return array|bool|float|int|mixed|string
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function normalize($obj, $format = null, array $context = [])
    {
        /**
         * @var ApiTxNormalizable $obj
         */
        $value = parent::normalize($obj, $format, $context);
        if (is_array($value)) {
            ksort($value);
        }

        return [
            "type"  => $obj->getType(),
            "value" => $value
        ];
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @param array $context
     * @return array|object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalize($data, $type, $format = null, array $context = [])
    {
        if (is_subclass_of($type, ApiTxNormalizable::class)
            && $data["type"] && self::getAvailableTypes()[$data["type"]]) {
            return parent::denormalize($data["value"], self::getAvailableTypes()[$data["type"]], $format, $context);
        }

        return parent::denormalize($data, $type, $format, $context);
    }

    /**
     * @param $data
     * @param null $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return \is_object($data) && $data instanceof ApiTxNormalizable;
    }

    /**
     * @param $data
     * @param $type
     * @param null $format
     * @return bool
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return (is_subclass_of($type, ApiTxNormalizable::class));
    }
}
