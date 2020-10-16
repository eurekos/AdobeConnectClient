<?php

namespace AdobeConnectClient\Commands;

use AdobeConnectClient\Command;
use AdobeConnectClient\ArrayableInterface;
use AdobeConnectClient\Converter\Converter;
use AdobeConnectClient\Helpers\StatusValidate;

/**
 * Returns a list of users who attended a Adobe Connect meeting.
 *
 * More info see {@link https://helpx.adobe.com/adobe-connect/webservices/report-meeting-attendance.html}
 */
class ReportMeetingAttendance extends Command
{
    /**
     * @var int
     */
    protected $scoId;

    /**
     * @param int $scoId
     * @param ArrayableInterface|null $filter
     * @param ArrayableInterface|null $sorter
     */
    public function __construct(
        $scoId,
        ArrayableInterface $filter = null,
        ArrayableInterface $sorter = null
    ) {
        $this->parameters = [
            'action' => 'report-meeting-attendance',
        ];

        $this->scoId = intval($scoId);

        if ($filter) {
            $this->parameters += $filter->toArray();
        }

        if ($sorter) {
            $this->parameters += $sorter->toArray();
        }
    }

    /**
     * @inheritdoc
     *
     * @return array
     */
    protected function process()
    {
        $response = Converter::convert(
            $this->client->doGet(
                $this->parameters + ['sco-id' => $this->scoId, 'session' => $this->client->getSession()]
            )
        );

        StatusValidate::validate($response['status']);

        $attendees = [];

        if (!empty($response['reportMeetingAttendance'])) {
            $attendees = $response['reportMeetingAttendance'];
        }

        return $attendees;
    }
}

