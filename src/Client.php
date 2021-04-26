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

        // for forcing ipv4
        //$streamContextOptions['socket']['bindto'] = '0:0';

        $context = stream_context_create($streamContextOptions);

        // initialize with parent
        parent::__construct(
            $wsdl,
            [
                   'trace' => true,
                   // TODO: remove after auth tests are ok
                   'cache_wsdl' => WSDL_CACHE_NONE,
                   'exceptions' => true,
                   'stream_context' => $context,
                ]
        );

        // add auth headers
        $wsse_header = new WsseAuthHeader($username, $password);
        $this->__setSoapHeaders([$wsse_header]);

        return $this;
    }
}
