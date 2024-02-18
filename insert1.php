<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

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
    
    
    try {
        // Start a transaction
        $yourDBConnection->begin_transaction();
    
        // Your database insertion logic here (make sure it's within the transaction scope)
    
        // Extract data from the form
        $voucherno = $_POST["voucherno"];
        $voucherdate = $_POST["voucherdate"];
        $refno = $_POST["refno"];
        $companyname = $_POST["companyname"];
        $division = $_POST["division"];
        $department = $_POST["department"];
    
        // Insert data into the journal table
        $journalQuery = "INSERT INTO journal (Voucherno, VoucherDate, RefNo, CompanyName, Division, Department) 
                         VALUES ('$voucherno', '$voucherdate', '$refno', '$companyname', '$division', '$department')";
    
        // Execute the query
        if ($yourDBConnection->query($journalQuery) === FALSE) {
            throw new Exception("Error inserting into journal table: " . $yourDBConnection->error);
        }
    
        // Get the inserted journal ID
        $journalId = $yourDBConnection->insert_id;
    
        $tableData = json_decode($_POST["tableData"], true);
    
        foreach ($tableData as $rowData) {
            // Extract data from $rowData
            $transType = $rowData["transType"];
            $transCode = $rowData["transCode"];
            $account = $rowData["account"];
            $debitAmt = $rowData["debitAmt"];
            $creditAmt = $rowData["creditAmt"];
    
            // Insert data into the accountmaster table
            $accountQuery = "INSERT INTO accountmaster (acc_name, transcode)
                             VALUES ('$account', '$transCode')";
    
            // Execute the query
            if ($yourDBConnection->query($accountQuery) === FALSE) {
                throw new Exception("Error inserting into accountmaster table: " . $yourDBConnection->error);
            }
    
            // Get the inserted account ID
            $accountId = $yourDBConnection->insert_id;
    
            // Insert data into the transaction table
            $transactionQuery = "INSERT INTO transactions (journalid, accid, transtype, debit_amt, credit_amt)
                                 VALUES ('$journalId', '$accountId', '$transType', '$debitAmt', '$creditAmt')";
    
            // Execute the query
            if ($yourDBConnection->query($transactionQuery) === FALSE) {
                throw new Exception("Error inserting into transactions table: " . $yourDBConnection->error);
            }

            $transId = $yourDBConnection->insert_id;

    
            
            foreach ($rowData['costCenterData'] as $costCenter) {
                // Extract costCenterData fields...
                $costCode = $costCenter["costCode"];
                $costCenterName = $costCenter["costCenter"]; // Assuming you have the name here; adjust as necessary
                $amount = $costCenter["amount"];
                $remarks = $costCenter["remarks"];

                $costmasterQuery = "INSERT INTO costmaster (costCode, costCenter)
                             VALUES ('$costCode', '$costCenterName')";
    
            // Execute the query
            if ($yourDBConnection->query($costmasterQuery) === FALSE) {
                throw new Exception("Error inserting into costmaster table: " . $yourDBConnection->error);
            }
    
            // Get the inserted account ID
            $costmasterid = $yourDBConnection->insert_id;
    
                // Insert data into the costcenter table
                $costCenterQuery = "INSERT INTO costcenter (costmasterid, transid, remarks, amount)
                                    VALUES ('$costmasterid', '$transId', '$remarks', '$amount')";
    
                // Execute the query
                if ($yourDBConnection->query($costCenterQuery) === FALSE) {
                    throw new Exception("Error inserting into costcenter table: " . $yourDBConnection->error);
                }
            }
        }
    
        // Commit the transaction
        $yourDBConnection->commit();
    
        // You may want to handle success here based on your requirements
    
    } catch (Exception $e) {
        // Rollback the transaction on error
        $yourDBConnection->rollback();
    
        // Handle the exception, log errors, etc.
        echo "Error: " . $e->getMessage();
    }}
// Close your database connection
$yourDBConnection->close();

