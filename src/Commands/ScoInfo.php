<?php

namespace AdobeConnectClient\Commands;

use AdobeConnectClient\Command;
use AdobeConnectClient\Client;
use AdobeConnectClient\SCO;
use AdobeConnectClient\Converter\Converter;
use AdobeConnectClient\Helpers\StatusValidate;
use AdobeConnectClient\Helpers\SetEntityAttributes as FillObject;

/**
 * Gets the Sco info
 *
 * @link https://helpx.adobe.com/adobe-connect/webservices/sco-info.html
 */
class ScoInfo extends Command
{
    /** @var int */
    protected $scoId;

    /**
     * @param Client $client
     * @param int $scoId
     */
    public function __construct(Client $client, $scoId)
    {
        parent::__construct($client);
        $this->scoId = intval($scoId);
    }

    /**
     * @return SCO
     */
    public function execute()
    {
        $response = Converter::convert(
            $this->client->getConnection()->get([
                'action' => 'sco-info',
                'sco-id' => $this->scoId,
                'session' => $this->client->getSession()
            ])
        );
        StatusValidate::validate($response['status']);
        $sco = new SCO();
        FillObject::setAttributes($sco, $response['sco']);
        return $sco;
    }
}
