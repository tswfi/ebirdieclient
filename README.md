# eBirdie client

Tests read wsdl, user and key from environment variables

EBIRDIE_WSDL=https://USER:PASS@stage.uhs.golf.fi/docs/eBirdie_API-2_1_1/ns0.wsdl
EBIRDIE_LOGIN=username
EBIRDIE_PASS=apikey

running tests

```bash
EBIRDIE_WSDL=https://USER:PASS@stage.uhs.golf.fi/docs/eBirdie_API-2_1_1/ns0.wsdl EBIRDIE_LOGIN=username EBIRDIE_PASS=apikey ./vendor/bin/phpunit tests
```

# Usage

* Get wsdl url with basic auth information if required
* Get ebirdie username and password

See more [examples](https://github.com/tswfi/ebirdieclientexample)

## Initialize client

In this example the values are read from environment variables but of course you can set them however you like

```php
  $client = new Client(getenv('EBIRDIE_WSDL'), getenv('EBIRDIE_LOGIN'), getenv('EBIRDIE_PASS'));
```

