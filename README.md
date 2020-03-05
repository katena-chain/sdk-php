# SDK PHP

## Requirements

- PHP >= 7.1
- ext-json

## Install

```bash
composer require katena-chain/sdk-php
```

## Usage

To rapidly interact with our API, you can use our `Transactor` helper. It handles all the steps needed to correctly
format, sign and send a tx.

Feel free to explore and modify its code to meet your expectations.

## Examples

Detailed examples are provided in the `examples` folder to explain how to use our `Transactor` helper methods.

Available examples:
* Send a `Certificate` transaction
* Retrieve a `Certificate` transaction
* Retrieve a list of `Certificate` transactions
* Encrypt and send a `Secret` transaction
* Retrieve a list of `Secret` transactions and try to decrypt them
* Send a `KeyCreate` transaction
* Send a `KeyRevoke` transaction
* Retrieve a list of `Keys` for a company

For instance, to send a certificate:
```bash
php examples/sendCertificateRaw.php
```

## Katena documentation

For more information, check the [katena documentation](https://doc.katena.transchain.io).
