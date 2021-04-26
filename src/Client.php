<?php

namespace tswfi\Ebirdie;

use tswfi\Ebirdie\WsseAuthHeader;
/**
*  Ebirdie client
*
*  @author Tatu Wikman
*/
class Client extends \SoapClient
{
    const USERAGENT = "php eBirdieClient v0.0.1";

    public function __construct(string $wsdl, string $username, string $password)
    {
        // send useragent string
        $streamContextOptions['http']['header'] = self::USERAGENT;
        // force ipv4
        //$streamContextOptions['socket']['bindto'] = '0:0';
        $context = stream_context_create($streamContextOptions);

        // build the client from parent
        $this->_client = parent::__construct(
            $wsdl,
            [
               'trace' => true,
               // TODO: remove after auth tests are ok
               'cache_wsdl' => WSDL_CACHE_NONE,
               'exceptions' => true,
               'stream_context' => $context,
          ]
        );

        $wsse_header = new WsseAuthHeader($username, $password);
        $this->_client->__setSoapHeaders(array($wsse_header));

        return $this->_client;
    }

}
