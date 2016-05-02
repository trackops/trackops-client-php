<?php
/**
 * This example demonstrates (in a very rudimentary way) how to export a list
 * of expense details to a CSV file.
 *
 * Please note, this example is for educational purposes only, and should not be
 * considered quality production code.
 */


require __DIR__.'/../vendor/autoload.php';

Use Trackops\Api\Client;

date_default_timezone_set('UTC');

// Create a new API Client using the customer subdomain, username, and api token
$api = new Client('subdomain', 'username', 'apitoken');

// define the parameters we will use in our search
$params = [
    'from'      => '2015-12-01',
    'to'        => '2015-12-31',
    'dir'       => 'asc',
    'per_page'  => 100,
    'page'      => 1,
];

// Run a count on the records to capture what we should expect
$counts = $api->createRequest()->count('expense/entries', $params)->toArray();
echo ' > Record Count: '.$counts['record_count']."\n";
echo ' > Page Count: '.$counts['page_count']."\n";
echo "\n";

// Sleep to prevent API throttling
sleep(2);

// Set the start time before we begin the export
$startDate = new DateTime();
echo ' > Beginning export at '.$startDate->format('Y-m-d H:i:s')."\n\n";

// Open a new file pointer to capture the exported results
$fp = fopen(__DIR__.'/'.$params['from'].'-'.$params['to'].'-expense-export.csv', 'wb');

// Create an infinte loop until we manually break the cycle.
while (1) {
    echo sprintf(' > Processing page %s', $params['page'])."\n";

    // Call out to the API using the parameters defined
    $records = $api->createRequest()->get('expense/entries', $params)->toArray();
    foreach ($records as $record)
    {
        // Create an array of values, pulled from the expense entry,
        // that will be the same on every row of the detailed line items
        $entry = [
           $record['formatted_entry_number'], // expense entry number
           $record['entry_date'], // date of the expenses
           $record['User']['name'], // the person these expenses are for
        ];

        // Loop through each expense detail and extract the necessary data we
        // want to capture in our export.
        foreach ($record['ExpenseDetails'] as $detail) {
            $details = [
              $detail['FinanceItem']['expense_alias'], // Expense item name
              $detail['notes'], // Line item notes for this expense detail
              $detail['rate'], // The rate of this expense detail
              $detail['quantity'], // The quanity of the expense detail
              $detail['total'], // The total (rate * quantity) for this line
            ];

            // For each detail, merge the entry values with the current
            // line item (expense detail) values, to generate a single line.
            // Then write that line to the file using fputcsv().
            fputcsv($fp, array_merge($entry, $details));
        }
    }

    // If the number of records returned are less than the per_page parameter,
    // we are finished with the export, so break the loop.
    if (count($records) < $params['per_page']) {
        break;
    }

    // Increment the page count so the next time around it gets the next page.
    $params['page']++;

    // Sleep to prevent API throttling
    sleep(2);
}

// Close the file pointer.
fclose($fp);

// Capture some statistics about the time and memory usage for the export
$endDate = new DateTime();
echo "\n";
echo ' > Completed export at '.$endDate->format('Y-m-d H:i:s')."\n\n";
echo ' > Duration: '.$endDate->diff($startDate)->format('%H:%I:%S')."\n";
echo ' > Peak memory used: '.memory_get_peak_usage()." bytes \n";
echo "\n";
exit;