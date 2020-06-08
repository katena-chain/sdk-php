<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\Examples\Common;

use stdClass;

class Settings
{

    public static function defaultSettings(): stdClass
    {
        $settingsArray = array(
            // API url to dialogue with a Katena network
            "apiUrl"        => "https://nodes.test.katena.transchain.io/api/v1",

            // Katena network id
            "chainId"       => "katena-chain-test",

            // Number of transactions the API should return
            "txPerPage"     => 10,

            // Dummy company committed on chain
            "company"       => array(

                // Unique company identifier on a Katena network
                "bcId"        => "abcdef",

                // Dummy users with their keys to sign transactions
                "ed25519Keys" => array(
                    "alice" => array(
                        "id"            => "36b72ca9-fd58-44aa-b90d-5a855276ff82",
                        "privateKeyStr" => "7C67DeoLnhI6jvsp3eMksU2Z6uzj8sqZbpgwZqfIyuCZbfoPcitCiCsSp2EzCfkY52Mx58xDOyQLb1OhC7cL5A==",
                    ),
                    "bob"   => array(
                        "id"            => "7cf17643-5567-4dfa-9b0c-9cd19c45177a",
                        "privateKeyStr" => "3awdq5HUZ2fgV2fM6sbV1yJKIvuTV2OZ5AMfes4ftHUiOpqsicnv+67vLfKLwWR/Bh/hNbJaq6fziXoh+oqxRQ==",
                    ),
                    "carla" => array(
                        "id"            => "236f8028-bb87-4c19-b6e0-cbcaea35e764",
                        "privateKeyStr" => "p2T1gRu2HHdhcsTVEk6VwpJRkLahvnLsi9miSS1Yg4PSk6jrTRFvtoPzi2z6yn+Ul9+niTHBUvbskbQ2TkDxmQ==",
                    ),
                ),
            ),

            // Sample transaction ids used in examples
            // If one id is already used on the Katena test network, feel free to change these values
            "certificateId" => "ce492f92-a529-40c1-91e9-2af71e74ebea",
            "secretId"      => "3b1cfd5f-d0fe-478c-ba30-17817e29611e",
            "keyId"         => "9941bc28-4033-4d5a-a337-76b640223de2",

            // Off chain samples data to do off chain operations
            "offChain"      => array(

                // Dummy users with their keys to sign off-chain data
                "ed25519Keys" => array(
                    "david" => array(
                        "privateKeyStr" => "aGya1W2C2bfu1bMA+wJ8kbpZePjKprv4t93EhX+durqOksFaT9pC0054jFeKYFyGzi+1gCp1NZAeCsG/yQEJWA==",
                        "publicKeyStr"  => "jpLBWk/aQtNOeIxXimBchs4vtYAqdTWQHgrBv8kBCVg=",
                    ),
                ),

                // Dummy users with their keys to seal/open nacl boxes to share secret information
                "x25519Keys"  => array(
                    "alice" => array(
                        "privateKeyStr" => "nyCzhimWnTQifh6ucXLuJwOz3RgiBpo33LcX1NjMAsP1ZkQcdlDq64lTwxaDx0lq6LCQAUeYywyMUtfsvTUEeQ==",
                        "publicKeyStr"  => "9WZEHHZQ6uuJU8MWg8dJauiwkAFHmMsMjFLX7L01BHk=",
                    ),
                    "bob"   => array(
                        "privateKeyStr" => "quGBP8awD/J3hjSvwGD/sZRcMDks8DPz9Vw0HD4+zecqJP0ojBoc4wQtyq08ywxUksTkdz0/rQNkOsEZBwqWTw==",
                        "publicKeyStr"  => "KiT9KIwaHOMELcqtPMsMVJLE5Hc9P60DZDrBGQcKlk8=",
                    ),
                ),
            ),
        );
        return json_decode(json_encode($settingsArray));
    }

}
