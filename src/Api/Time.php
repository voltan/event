<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Event\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('time', 'event')->makeTime();
 */

class Time extends AbstractApi
{
    public function makeTime()
    {
        switch (Pi::config('date_calendar')) {
            // Set for Iran time
            case 'persian':
                require_once Pi::path('module') . '/event/src/Api/pdate.php';

                $thisMonth = pdate('m');
                $nextMonth = pdate('m') + 1;
                $nextTwoMonth = pdate('m') + 2;
                $nextThreeMonth = pdate('m') + 3;
                $nextFourMonth = pdate('m') + 4;
                $year = pdate('Y');
                if ($nextMonth > 12) {
                    $nextMonth = $nextMonth - 12;
                    $year = $year + 1;
                }
                if ($nextTwoMonth > 12) {
                    $nextTwoMonth = $nextTwoMonth - 12;
                    $year = $year + 1;
                }
                if ($nextThreeMonth > 12) {
                    $nextThreeMonth = $nextThreeMonth - 12;
                    $year = $year + 1;
                }
                if ($nextFourMonth > 12) {
                    $nextFourMonth = $nextFourMonth - 12;
                    $year = $year + 1;
                }

                $time = array(
                    'expired' => time(),
                    'thisWeek' => pmktime(0, 0, 0, pdate('m', strtotime("-1 Saturday")), pdate('d', strtotime("-1 Saturday")), pdate('Y', strtotime("-1 Saturday"))),
                    'nextWeek' => pmktime(0, 0, 0, pdate('m', strtotime("+1 Saturday")), pdate('d', strtotime("+1 Saturday")), pdate('Y', strtotime("+1 Saturday"))),
                    'nextTwoWeek' => pmktime(0, 0, 0, pdate('m', strtotime("+2 Saturday")), pdate('d', strtotime("+2 Saturday")), pdate('Y', strtotime("+2 Saturday"))),
                    'thisMonth' => pmktime(0, 0, 0, $thisMonth, 1, $year),
                    'nextMonth' => pmktime(0, 0, 0, $nextMonth, 1, $year),
                    'nextTwoMonth' => pmktime(0, 0, 0, $nextTwoMonth, 1, $year),
                    'nextThreeMonth' => pmktime(0, 0, 0, $nextThreeMonth, 1, $year),
                    'nextFourMonth' => pmktime(0, 0, 0, $nextFourMonth, 1, $year),
                );
                break;

            default:
                $time = array(
                    'expired' => time(),
                    'thisWeek' => strtotime("monday this week midnight") -1,
                    'nextWeek' => strtotime("next Monday"),
                    'nextTwoWeek' => strtotime("Monday this week +2 weeks"),
                    'thisMonth' => strtotime('first day of this month'),
                    'nextMonth' => strtotime('first day of +1 month'),
                    'nextTwoMonth' => strtotime('first day of +2 month'),
                    'nextThreeMonth' => strtotime('first day of +3 month'),
                    'nextFourMonth' => strtotime('first day of +4 month'),
                );
                break;
        }
        return $time;
    }
}