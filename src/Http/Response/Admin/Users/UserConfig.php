<?php

declare(strict_types=1);

namespace App\Http\Response\Admin\Users;

use App\Context\Users\Entities\User;
use App\Utilities\Countries;
use App\Utilities\States;
use App\Utilities\TimeZoneList;
use Exception;

use function array_merge;
use function count;

class UserConfig
{
    /**
     * @param mixed[] $postData
     *
     * @return mixed[]
     *
     * @throws Exception
     */
    public static function getProfileFormConfigInputs(
        array $postData,
        ?User $user = null,
    ): array {
        $hasPostData = count($postData) > 0;

        if ($user === null) {
            $user = new User(emailAddress: 'tk@tk.tk');
        }

        $requiredForCheckout = 'required for check out';

        return [
            [
                'template' => 'Select',
                'label' => 'Time Zone',
                'name' => 'time_zone',
                'options' => TimeZoneList::getTimezoneListAsOptionsArray(),
                'value' => $hasPostData ?
                    (string) ($postData['time_zone'] ?? '') :
                    $user->timezone()->getName(),
            ],
            [
                'type' => 'heading',
                'heading' => 'Support Profile',
            ],
            [
                'label' => 'Display Name',
                'name' => 'display_name',
                'value' => $hasPostData ?
                    (string) ($postData['display_name'] ?? '') :
                    $user->supportProfile()->displayName(),
            ],
            [
                'type' => 'heading',
                'heading' => 'Billing Contact Details',
            ],
            [
                'label' => 'Billing Name',
                'subHeading' => $requiredForCheckout,
                'name' => 'billing_name',
                'value' => $hasPostData ?
                    (string) ($postData['billing_name'] ?? '') :
                    $user->billingProfile()->billingName(),
            ],
            [
                'label' => 'Billing Company',
                'labelSmall' => '(if applicable)',
                'name' => 'billing_company',
                'value' => $hasPostData ?
                    (string) ($postData['billing_company'] ?? '') :
                    $user->billingProfile()->billingCompany(),
            ],
            [
                'label' => 'Billing Phone',
                'subHeading' => $requiredForCheckout,
                'name' => 'billing_phone',
                'value' => $hasPostData ?
                    (string) ($postData['billing_phone'] ?? '') :
                    $user->billingProfile()->billingPhone(),
            ],
            [
                'type' => 'heading',
                'heading' => 'Billing Address',
            ],
            [
                'template' => 'Select',
                'attrs' => ['@change' => 'data.countryRegion = $event.currentTarget.value'],
                'label' => 'Billing Country / Region',
                'subHeading' => $requiredForCheckout,
                'name' => 'billing_country_region',
                'options' => Countries::countrySelectList(),
                'value' => $hasPostData ?
                    (string) ($postData['billing_country_region'] ?? '') :
                    $user->billingProfile()->billingCountryRegion(),
            ],
            [
                'label' => 'Billing Address',
                'subHeading' => $requiredForCheckout,
                'name' => 'billing_address',
                'value' => $hasPostData ?
                    (string) ($postData['billing_address'] ?? '') :
                    $user->billingProfile()->billingAddress(),
            ],
            [
                'label' => 'Billing Address Continued',
                'name' => 'billing_address_continued',
                'value' => $hasPostData ?
                    (string) ($postData['billing_address_continued'] ?? '') :
                    $user->billingProfile()->billingAddressContinued(),
            ],
            [
                'label' => 'Billing City',
                'subHeading' => $requiredForCheckout,
                'name' => 'billing_city',
                'value' => $hasPostData ?
                    (string) ($postData['billing_city'] ?? '') :
                    $user->billingProfile()->billingCity(),
            ],
            [
                'template' => 'Combine',
                'label' => 'Billing State/Province',
                'subHeading' => $requiredForCheckout,
                'id' => 'billing_state_province',
                'inputs' => [
                    [
                        'wrapperAttrs' => ['x-show' => "data.countryRegion !== 'USA'"],
                        'attrs' => [
                            'x-bind:name' => "data.countryRegion === 'USA' ? '' : 'billing_state_province'",
                            'x-bind:id' => "data.countryRegion === 'USA' ? '' : 'billing_state_province'",
                        ],
                        'name' => '',
                        'value' => $hasPostData ?
                            (string) ($postData['billing_state_province'] ?? '') :
                            $user->billingProfile()->billingStateProvince(),
                    ],
                    [
                        'template' => 'Select',
                        'wrapperAttrs' => ['x-show' => "data.countryRegion === 'USA'"],
                        'attrs' => [
                            'x-bind:name' => "data.countryRegion === 'USA' ? 'billing_state_province' : ''",
                            'x-bind:id' => "data.countryRegion === 'USA' ? 'billing_state_province' : ''",
                        ],
                        'name' => 'billing_state_province',
                        'options' => States::statesSelectList(),
                        'value' => $hasPostData ?
                            (string) ($postData['billing_state_province'] ?? '') :
                            $user->billingProfile()->billingStateProvince(),
                    ],
                ],
            ],
            [
                'label' => 'Billing Postal Code',
                'subHeading' => $requiredForCheckout,
                'name' => 'billing_postal_code',
                'value' => $hasPostData ?
                    (string) ($postData['billing_postal_code'] ?? '') :
                    $user->billingProfile()->billingPostalCode(),
            ],
        ];
    }

    /**
     * @param mixed[] $postData
     *
     * @return mixed[]
     *
     * @throws Exception
     */
    public static function getCreateEditUserFormConfigInputs(
        array $postData,
        ?User $user = null,
    ): array {
        $hasPostData = count($postData) > 0;

        if ($user === null) {
            $user = new User(
                emailAddress: 'tk@tk.tk',
                isActive: true,
            );
        }

        return array_merge(
            [
                [
                    'type' => 'heading',
                    'heading' => 'Account Details',
                    // 'subHeading' => 'Set stuff about your account.',
                ],
                [
                    'template' => 'Toggle',
                    'label' => 'Is Admin?',
                    'name' => 'is_admin',
                    'value' => $hasPostData ?
                        (bool) ($postData['is_admin'] ?? '0') :
                        $user->isAdmin(),
                ],
                [
                    'label' => 'Email Address',
                    'name' => 'email_address',
                    'value' => $hasPostData ?
                        (string) ($postData['email_address'] ?? '') :
                        ($user->emailAddress() !== 'tk@tk.tk' ?
                            $user->emailAddress() :
                            ''),
                ],
                [
                    'type' => 'password',
                    'label' => 'Password',
                    'name' => 'password',
                ],
                [
                    'type' => 'password',
                    'label' => 'Confirm Password',
                    'name' => 'password_verify',
                ],
                [
                    'template' => 'Toggle',
                    'label' => 'Is Active?',
                    'name' => 'is_active',
                    'value' => $hasPostData ?
                        (bool) ($postData['is_active'] ?? '0') :
                        $user->isActive(),
                ],
            ],
            self::getProfileFormConfigInputs(
                $postData,
                $user,
            ),
        );
    }
}
