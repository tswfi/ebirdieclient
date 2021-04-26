<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use tswfi\Ebirdie\Client;

final class ClientTest extends TestCase
{
    public function testInit(): void
    {
        return;
        $client = new Client(getenv('EBIRDIE_WSDL'), getenv('EBIRDIE_LOGIN'), getenv('EBIRDIE_PASS'));
        $this->assertObjectHasAttribute('trace', $client, 'Client initialized');

        var_dump($client->__getFunctions());
        // var_dump($client->__getTypes());
    }

    public function test_fetchAllClubs(): void
    {
        $client = new Client(getenv('EBIRDIE_WSDL'), getenv('EBIRDIE_LOGIN'), getenv('EBIRDIE_PASS'));

        $clubs = [];
        try {
            $clubs = $client->__soapCall('fetchAllClubs', []);
        } catch (SoapFault $fault) {
            echo 'REQUEST HEADERS: ' . $client->__getLastRequestHeaders() . "\n";
            echo 'REQUEST DATA: ' . $client->__getLastRequest() . "\n";
            echo 'RESPONSE HEADERS: ' . $client->__getLastResponseHeaders() . "\n";
            echo 'RESPONSE DATA: ' . $client->__getLastResponse() . "\n";
            echo 'ERROR: ' . $fault->getMessage() . "\n";
        }
        var_dump($clubs);
    }
}
