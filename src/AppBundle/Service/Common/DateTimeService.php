<?php


namespace AppBundle\Service\Common;


use DateTime;
use DateTimeZone;

class DateTimeService implements DateTimeServiceInterface
{
    /**
     * @return DateTime
     * @throws \Exception
     */
    public function setDateTimeNow():DateTime
    {
        $timeZone = new DateTimeZone('Europe/Sofia');
        $datetime = new DateTime('now');
        $datetime->setTimezone($timeZone);
        return $datetime;
    }

    /**
     * @return DateTime
     * @throws \Exception
     */
    public function setDateTimeBlank():DateTime
    {
        $dateFinish = new DateTime('0000-00-00 00:00:00');
        return $dateFinish;
    }
}
