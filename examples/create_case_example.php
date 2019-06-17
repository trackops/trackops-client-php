<?php

/**
 * This example demonstrates (in a very rudimentary way) how to create a case
 * using a JSON encoded request body.
 *
 * Please note, this example is for educational purposes only, and should not be
 * considered quality production code.
 */

require __DIR__.'/../vendor/autoload.php';

use Trackops\Api\Client;

date_default_timezone_set('UTC');

// Create a new API Client using the customer subdomain, username, and api token
$api = new Client(getenv('TRACKOPS_API_SUBDOMAIN'), getenv('TRACKOPS_API_USERNAME'), getenv('TRACKOPS_API_TOKEN'));

// define the parameters we will use in our request
$request = ['casefile' => [
    'case_type_id' => 1,
    'client_id' => 4,
    'primary_contact_id' => 2,
    'case_region_id' => 1,
    'company_location_id' => 2,
    'created_on' => '2019-06-14',
    'due_on' => '2019-06-21',
    'reference_value_1' => 'ClaimNumber123',
    'reference_value_2' => 'AltClaimNumber456',
    'reference_value_3' => 'SomeOtherReference',
    'case_location' => 'Denver, CO',
    'budget_money' => 1500.00,
    'budget_hours' => 8,
    'notes' => 'Determine if the claimant is injured.',
    'scheduling_notes' => 'Schedule surveillance for 1 weekday and 1 weekend day.',
    'admin_notes' => 'Created by the API',
    'case_services_list' => [1, 2],
    'case_flags_list' => [1],
    'custom_fields' => [
        [
            'id' => 1,
            'value' => 'field 1 value here',
        ],
        [
            'id' => 2,
            'value' => 'field 2 value here',
        ],
    ],
    'subjects' => [
        [
            'subject_type_id' => 1,
            'is_primary' => true,
            'is_pinned' => true,
            'custom_fields' => [
                [
                    'id' => 1,
                    'value' => [
                        'first_name' => 'John',
                        'middle_name' => 'B',
                        'last_name' => 'Doe',
                    ],
                ],
                [
                    'id'  => 2,
                    'value' => 'Big John',
                ],
                [
                    'id'  => 3,
                    'value' => [
                        'address_1' => '123 Main Street',
                        'address_2' => '#456',
                        'city'  => 'Denver',
                        'state' => 'CO',
                        'zip' => '80202',
                        'country' => 'US',
                    ]
                ],
            ]
        ]
    ],
]];

// Call out to the API using a json encoded request body as defined
$response = $api->createRequest()->post('cases', $request);

echo $response."\n\n";
exit;
