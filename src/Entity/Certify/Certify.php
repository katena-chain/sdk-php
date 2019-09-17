<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Client\Entity\Certify;

interface Certify
{
    const NAMESPACE_CERTIFY = "certify";
    const TYPE_CERTIFICATE  = "certificate";
    const TYPE_SECRET       = "secret";
    const TYPE_RAW          = "raw";
    const TYPE_ED25519      = "ed25519";
    const TYPE_NACL_BOX     = "nacl_box";
}
