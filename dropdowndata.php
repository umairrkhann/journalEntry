<?php
$servername = "127.0.0.1:3307";
$username = "root";
$password = "";
$dbname = "nfinance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data for Company dropdown from the journal table
$sqlCompany = "SELECT DISTINCT CompanyName FROM journal";
$resultCompany = $conn->query($sqlCompany);
$companyData = array();
while ($rowCompany = $resultCompany->fetch_assoc()) {
    $companyData[] = $rowCompany['CompanyName'];
}

// Fetch data for Division dropdown from the journal table
$sqlDivision = "SELECT DISTINCT Division FROM journal";
$resultDivision = $conn->query($sqlDivision);
$divisionData = array();
while ($rowDivision = $resultDivision->fetch_assoc()) {
    $divisionData[] = $rowDivision['Division'];
}

// Fetch data for Department dropdown from the journal table
$sqlDepartment = "SELECT DISTINCT Department FROM journal";
$resultDepartment = $conn->query($sqlDepartment);
$departmentData = array();
while ($rowDepartment = $resultDepartment->fetch_assoc()) {
    $departmentData[] = $rowDepartment['Department'];
}

$sqlAccount = "SELECT DISTINCT acc_name FROM accountmaster";
$resultAccount = $conn->query($sqlAccount);
$accountData = array();
while ($rowAccount = $resultAccount->fetch_assoc()) {
    $accountData[] = $rowAccount['acc_name'];
}

// Fetch data for Cost Center dropdown from the costcenter table
$sqlCostCenter = "SELECT DISTINCT costcenter FROM costmaster";
$resultCostCenter = $conn->query($sqlCostCenter);
$costCenterData = array();
while ($rowCostCenter = $resultCostCenter->fetch_assoc()) {
    $costCenterData[] = $rowCostCenter['costcenter'];
}

// Return the JSON response
$response = array(
    'company' => $companyData,
    'division' => $divisionData,
    'department' => $departmentData,
    'account' => $accountData,
    'costcenter' => $costCenterData
);

echo json_encode($response);
// Close connection
$conn->close();
?>
