<?php

namespace AdobeConnectClient\Commands;

use AdobeConnectClient\Command;
use AdobeConnectClient\Client;
use AdobeConnectClient\SCORecord;
use AdobeConnectClient\Converter\Converter;
use AdobeConnectClient\Helpers\StatusValidate;
use AdobeConnectClient\Helpers\SetEntityAttributes as FillObject;

/**
 * Provides a list of recordings (FLV and MP4) for a specified folder or SCO
 *
 * @link https://helpx.adobe.com/adobe-connect/webservices/list-recordings.html
 */
class ListRecordings extends Command
{
    /** @var int */
    protected $folderId;

    /**
     * @param Client $client
     * @param int $folderId
     */
    public function __construct(Client $client, $folderId)
    {
        parent::__construct($client);
        $this->folderId = (int) $folderId;
    }

    /**
     * @return SCORecord[]
     */
    public function execute()
    {
        $response = Converter::convert(
            $this->client->getConnection()->get([
                'folder-id' => $this->folderId,
                'session' => $this->client->getSession()
            ])
        );

        StatusValidate::validate($response['status']);

        $recordings = [];

        foreach ($response['recordings'] as $recordingAttributes) {
            $scoRecording = new SCORecord();
            FillObject::setAttributes($scoRecording, $recordingAttributes);
            $recordings[] = $scoRecording;
        }

        return $recordings;
    }
}
