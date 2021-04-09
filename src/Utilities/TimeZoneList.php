<?php

declare(strict_types=1);

namespace App\Utilities;

use DateTime;
use DateTimeZone;
use Exception;

class TimeZoneList
{
    public const TIMEZONES = [
        'Pacific/Honolulu' => 'Hawaii',
        'US/Alaska' => 'Alaska',
        'America/Los_Angeles' => 'Pacific Time (US & Canada)',
        'US/Arizona' => 'Arizona',
        'US/Mountain' => 'Mountain Time (US & Canada)',
        'US/Central' => 'Central Time (US & Canada)',
        'US/Eastern' => 'Eastern Time (US & Canada)',
        'US/East-Indiana' => 'Indiana (East)',
        'Pacific/Midway' => 'Midway Island',
        'Pacific/Samoa' => 'Samoa',
        'America/Tijuana' => 'Tijuana',
        'America/Chihuahua' => 'La Paz',
        'America/Mazatlan' => 'Mazatlan',
        'America/Managua' => 'Central America',
        'America/Mexico_City' => 'Mexico City',
        'America/Monterrey' => 'Monterrey',
        'Canada/Saskatchewan' => 'Saskatchewan',
        'America/Bogota' => 'Quito',
        'America/Lima' => 'Lima',
        'Canada/Atlantic' => 'Atlantic Time (Canada)',
        'America/Caracas' => 'Caracas',
        'America/La_Paz' => 'La Paz',
        'America/Santiago' => 'Santiago',
        'Canada/Newfoundland' => 'Newfoundland',
        'America/Sao_Paulo' => 'Brasilia',
        'America/Argentina/Buenos_Aires' => 'Georgetown',
        'America/Godthab' => 'Greenland',
        'America/Noronha' => 'Mid-Atlantic',
        'Atlantic/Azores' => 'Azores',
        'Atlantic/Cape_Verde' => 'Cape Verde Is.',
        'Africa/Casablanca' => 'Casablanca',
        'Europe/London' => 'London',
        'Etc/Greenwich' => 'Greenwich Mean Time : Dublin',
        'Europe/Lisbon' => 'Lisbon',
        'Africa/Monrovia' => 'Monrovia',
        'UTC' => 'UTC',
        'Europe/Amsterdam' => 'Amsterdam',
        'Europe/Belgrade' => 'Belgrade',
        'Europe/Berlin' => 'Bern',
        'Europe/Bratislava' => 'Bratislava',
        'Europe/Brussels' => 'Brussels',
        'Europe/Budapest' => 'Budapest',
        'Europe/Copenhagen' => 'Copenhagen',
        'Europe/Ljubljana' => 'Ljubljana',
        'Europe/Madrid' => 'Madrid',
        'Europe/Paris' => 'Paris',
        'Europe/Prague' => 'Prague',
        'Europe/Rome' => 'Rome',
        'Europe/Sarajevo' => 'Sarajevo',
        'Europe/Skopje' => 'Skopje',
        'Europe/Stockholm' => 'Stockholm',
        'Europe/Vienna' => 'Vienna',
        'Europe/Warsaw' => 'Warsaw',
        'Africa/Lagos' => 'West Central Africa',
        'Europe/Zagreb' => 'Zagreb',
        'Europe/Athens' => 'Athens',
        'Europe/Bucharest' => 'Bucharest',
        'Africa/Cairo' => 'Cairo',
        'Africa/Harare' => 'Harare',
        'Europe/Helsinki' => 'Kyiv',
        'Europe/Istanbul' => 'Istanbul',
        'Asia/Jerusalem' => 'Jerusalem',
        'Africa/Johannesburg' => 'Pretoria',
        'Europe/Riga' => 'Riga',
        'Europe/Sofia' => 'Sofia',
        'Europe/Tallinn' => 'Tallinn',
        'Europe/Vilnius' => 'Vilnius',
        'Asia/Baghdad' => 'Baghdad',
        'Asia/Kuwait' => 'Kuwait',
        'Europe/Minsk' => 'Minsk',
        'Africa/Nairobi' => 'Nairobi',
        'Asia/Riyadh' => 'Riyadh',
        'Europe/Volgograd' => 'Volgograd',
        'Asia/Tehran' => 'Tehran',
        'Asia/Muscat' => 'Muscat',
        'Asia/Baku' => 'Baku',
        'Europe/Moscow' => 'St. Petersburg',
        'Asia/Tbilisi' => 'Tbilisi',
        'Asia/Yerevan' => 'Yerevan',
        'Asia/Kabul' => 'Kabul',
        'Asia/Karachi' => 'Karachi',
        'Asia/Tashkent' => 'Tashkent',
        'Asia/Calcutta' => 'Sri Jayawardenepura',
        'Asia/Kolkata' => 'Kolkata',
        'Asia/Katmandu' => 'Kathmandu',
        'Asia/Almaty' => 'Almaty',
        'Asia/Dhaka' => 'Dhaka',
        'Asia/Yekaterinburg' => 'Ekaterinburg',
        'Asia/Rangoon' => 'Rangoon',
        'Asia/Bangkok' => 'Hanoi',
        'Asia/Jakarta' => 'Jakarta',
        'Asia/Novosibirsk' => 'Novosibirsk',
        'Asia/Hong_Kong' => 'Hong Kong',
        'Asia/Chongqing' => 'Chongqing',
        'Asia/Krasnoyarsk' => 'Krasnoyarsk',
        'Asia/Kuala_Lumpur' => 'Kuala Lumpur',
        'Australia/Perth' => 'Perth',
        'Asia/Singapore' => 'Singapore',
        'Asia/Taipei' => 'Taipei',
        'Asia/Ulan_Bator' => 'Ulaan Bataar',
        'Asia/Urumqi' => 'Urumqi',
        'Asia/Irkutsk' => 'Irkutsk',
        'Asia/Tokyo' => 'Tokyo',
        'Asia/Seoul' => 'Seoul',
        'Australia/Adelaide' => 'Adelaide',
        'Australia/Darwin' => 'Darwin',
        'Australia/Brisbane' => 'Brisbane',
        'Australia/Canberra' => 'Canberra',
        'Pacific/Guam' => 'Guam',
        'Australia/Hobart' => 'Hobart',
        'Australia/Melbourne' => 'Melbourne',
        'Pacific/Port_Moresby' => 'Port Moresby',
        'Australia/Sydney' => 'Sydney',
        'Asia/Yakutsk' => 'Yakutsk',
        'Asia/Vladivostok' => 'Vladivostok',
        'Pacific/Auckland' => 'Wellington',
        'Pacific/Fiji' => 'Marshall Is.',
        'Pacific/Kwajalein' => 'International Date Line West',
        'Asia/Kamchatka' => 'Kamchatka',
        'Asia/Magadan' => 'Solomon Is.',
        'Pacific/Tongatapu' => "Nuku'alofa",
    ];

    /**
     * @return string[]
     *
     * @throws Exception
     */
    public static function getList(): array
    {
        $return = [];

        $now = new DateTime('now', new DateTimeZone('UTC'));

        foreach (self::TIMEZONES as $id => $name) {
            $zone = new DateTimeZone($id);

            $offset = $zone->getOffset($now) / 3600;

            if ($offset < 0) {
                $return[$id] = $name . ' (GMT' . $offset . ':00)';
                continue;
            }

            if ($offset === 0) {
                $return[$id] = $name . ' (GMT)';
                continue;
            }

            $return[$id] = $name . ' (GMT+' . $offset . ':00)';
        }

        return $return;
    }

    /**
     * @return mixed[]
     *
     * @throws Exception
     */
    public static function getTimezoneListAsOptionsArray(): array
    {
        $timezoneOptions = [
            [
                'value' => '',
                'label' => 'Choose timezone&hellip;',
            ],
        ];

        foreach (self::getList() as $tz => $label) {
            $timezoneOptions[] = [
                'value' => $tz,
                'label' => $label,
            ];
        }

        return $timezoneOptions;
    }
}
