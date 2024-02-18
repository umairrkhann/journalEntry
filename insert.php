<?php
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$dbname = "nfinance";

// Create a connection
$yourDBConnection = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($yourDBConnection->connect_error) {
    die("Connection failed: " . $yourDBConnection->connect_error);
}

// Set UTF-8 character set
$yourDBConnection->set_charset("utf8mb4");
       
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract data from the form
    $voucherno = $_POST["voucherno"];
    $voucherdate = $_POST["voucherdate"];
    $refno = $_POST["refno"];
    $companyname = $_POST["companyname"];
    $division = $_POST["division"];
    $department = $_POST["department"];

    $costcode = ''; // Initialize based on your logic
    $costcenter = ''; // Initialize based on your logic
    $acc_name = ''; // Initialize based on your logic
    $transcode = ''; // Initialize based on your logic
    
    // Insert data into the journal table
    $journalQuery = "INSERT INTO journal (Voucherno, VoucherDate, RefNo, CompanyName, Division, Department) 
                     VALUES ('$voucherno', '$voucherdate', $refno, '$companyname', '$division', '$department')";

    // Execute the query
    if ($yourDBConnection->query($journalQuery) === TRUE) {
        // Get the inserted journal ID
        $journalId = $yourDBConnection->insert_id;

        // Loop through the transactions and insert data into the transaction table
        foreach ($_POST['transtype'] as $key => $transtype) {
            $transcode = $_POST['transcode'][$key];
            $accid = $_POST['accid'][$key];
            $debitamt = $_POST['debitamt'][$key];
            $creditamt = $_POST['creditamt'][$key];

            // Insert data into the transaction table
            $transactionQuery = "INSERT INTO transactions (journalid, accid, transtype, transcode, debit_amt, credit_amt)
                                 VALUES ($journalId, $accid, '$transtype', $transcode, $debitamt, $creditamt)";

            // Execute the query
            if ($yourDBConnection->query($transactionQuery) === TRUE) {
                // Get the inserted transaction ID
                $transactionId = $yourDBConnection->insert_id;

                // Loop through the cost center details and insert data into the costcenter table
                foreach ($_POST['costCode'][$key] as $index => $costCode) {
                    $costmasterid = $_POST['costMasterId'][$key][$index];
                    $remarks = $_POST['remarks'][$key][$index];
                    $amount = $_POST['amount'][$key][$index];

                    // Insert data into the costcenter table
                    $costCenterQuery = "INSERT INTO costcenter (costmasterid, transid, remarks, amount)
                                        VALUES ($costmasterid, $transactionId, '$remarks', $amount)";

                    // Execute the query
                    $yourDBConnection->query($costCenterQuery);
                }
            }
        }

        // Insert data into the costmaster table
        $costmasterQuery = "INSERT INTO costmaster (costcode, costcenter)
                            VALUES ('$costcode', '$costcenter')";

        // Execute the query
        $yourDBConnection->query($costmasterQuery);

        // Get the inserted costmaster ID
        $costmasterId = $yourDBConnection->insert_id;

        // Insert data into the accountmaster table
        $accountQuery = "INSERT INTO accountmaster (acc_name, transcode)
                         VALUES ('$acc_name', $transcode)";

        // Execute the query
        $yourDBConnection->query($accountQuery);

        // Get the inserted account ID
        $accountId = $yourDBConnection->insert_id;

        // You may want to handle success or failure here based on your requirements
    } else {
        // Handle query error
        echo "Error: " . $journalQuery . "<br>" . $yourDBConnection->error;
    }
}

// Close your database connection
$yourDBConnection->close();
?>
