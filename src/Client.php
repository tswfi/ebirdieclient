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
    const USERAGENT = "PHP-SOAP eBirdieClient";
    // TODO: these can be removed if / when ebirdie fixes their live wsdl file
    const STAGINGDOMAIN = "stage.uhs.golf.fi";
    const LIVEDOMAIN = "api.golf.fi";

    public function __construct(string $wsdl, string $username, string $password)
    {
        // this is ugly, we check the wsdl url domain name to know if we are
        // in live or staging environment
        // TODO: this can be removed if / when ebirdie fixes their live wsdl file
        // default to staging
        $this->production = false;
        if (strpos($wsdl, self::LIVEDOMAIN) == true) {
            $this->production = true;
        }

        // send useragent string
        $streamContextOptions['http']['user_agent'] = self::USERAGENT;

        // for forcing ipv4
        //$streamContextOptions['socket']['bindto'] = '0:0';

        $context = stream_context_create($streamContextOptions);

        // initialize with parent
        parent::__construct(
            $wsdl,
            [
                   'trace' => true,
                   // TODO: remove after auth tests are ok
                   // 'cache_wsdl' => WSDL_CACHE_NONE,
                   'exceptions' => true,
                   'stream_context' => $context,
                ]
        );

        // add auth headers
        $wsse_header = new WsseAuthHeader($username, $password);
        $this->__setSoapHeaders([$wsse_header]);

        return $this;
    }

    /**
     * The current live wsdl edpoints point to staging
     * If we are calling the live wsdl mangle the endpoint
     * to live
     *
     * TODO: this can be removed if / when eBirdie changes their live wsdl
     */
    public function __doRequest($request, $location, $action, $version, $one_way = NULL)
    {
        if($this->production) {
            $location = str_replace(self::STAGINGDOMAIN, self::LIVEDOMAIN, $location);
        }
        return parent::__doRequest($request, $location, $action, $version, $one_way);
    }
}
