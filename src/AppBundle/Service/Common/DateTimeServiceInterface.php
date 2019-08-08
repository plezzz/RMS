<?php


namespace AppBundle\Service\Common;


use DateTime;

interface DateTimeServiceInterface
{
    public function setDateTimeNow(): DateTime;
    public function setDateTimeBlank():DateTime;
}
