<?php

namespace AppBundle\Util;

class Helper
{
    public const BASE_URL = 'https://api.hubapi.com/contacts/v1/lists/all/contacts/all';
    public const BASE_CONTACT_ENGAGEMENT_URL = 'https://api.hubapi.com/engagements/v1/engagements/associated/contact/';

    public const CLOSE_LABEL = 'CLOSE';
    public const DEMO_LABEL = 'DEMO';

    public static function getEngagementLabel($html)
    {
        $closeLabel = self::CLOSE_LABEL;
        $demoLabel = self::DEMO_LABEL;

        $stripedHtml = strip_tags($html);
        $closePos = strpos($stripedHtml, $closeLabel);
        $demoPos = strpos($stripedHtml, $demoLabel);

        if (!empty($closePos)) {
          return substr($stripedHtml, $closePos, strlen($closeLabel));
        }

        if (!empty($demoPos)) {
          return substr($stripedHtml, $demoPos, strlen($demoLabel));
        }
    }

    public static function getEngagementDate($timestamp)
    {
        $date = \DateTime::createFromFormat("U.u", $timestamp / 1000);

        return $date->format('Y-m-d');
    }
}