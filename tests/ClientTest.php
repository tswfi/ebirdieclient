<?php declare(strict_types=1);
use PHPUnit\Framework\TestCase;

use tswfi\Ebirdie\Client;

final class ClientTest extends TestCase
{
    /**
     * basic initialization of the client
     *
     * just that it was created
     */
    public function testInit(): void
    {
        $client = new Client(getenv('EBIRDIE_WSDL'), getenv('EBIRDIE_LOGIN'), getenv('EBIRDIE_PASS'));
        $this->assertObjectHasAttribute('trace', $client, 'Client initialized');

        // var_dump($client->__getFunctions());
        // var_dump($client->__getTypes());
    }

    /**
     * fetch list of clubs, we assume the test server always has some clubs defined
     */
    public function test_fetchAllClubs(): void
    {
        $client = new Client(getenv('EBIRDIE_WSDL'), getenv('EBIRDIE_LOGIN'), getenv('EBIRDIE_PASS'));
        $clubs = $client->__soapCall('fetchAllClubs', []);
        $this->assertNotEmpty($clubs);
    }

    /**
     * find club by club id
     *
     * we assume club id 1 exists
     * and club number -1 does not
     */
    public function test_findFullClubByClubId(): void
    {
        $client = new Client(getenv('EBIRDIE_WSDL'), getenv('EBIRDIE_LOGIN'), getenv('EBIRDIE_PASS'));
        $club = $client->__soapCall('findFullClubByClubId', [['clubId' => 1]]);
        $this->assertNotEmpty($club);

        $club = $client->__soapCall('findFullClubByClubId', [['clubId' => -1]]);
        $this->assertNotEmpty($club);
        $this->assertEmpty($club->return->company);
    }

    /**
     * test finding person with id
     *
     * we assume person with id -1 does not exists
     */
    public function test_findPersonByPersonId(): void
    {
        $client = new Client(getenv('EBIRDIE_WSDL'), getenv('EBIRDIE_LOGIN'), getenv('EBIRDIE_PASS'));
        $personRequest = new stdClass();
        $personRequest->personId = -1;
        $this->expectException('SoapFault');
        $personResponse = $client->findPersonByPersonId($personRequest);
    }
}
