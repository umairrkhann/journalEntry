<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Journal Entry Screen</title>
  <link rel="stylesheet" href="styles.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
    crossorigin="anonymous"></script>
</head>

<body>
  <div class="general">
    <div class="heading">
      Journal Entry
      <br />
      <br />
    </div>
    <form class="row g-3" action="main.php" method="POST" id="myForm">
      <div class="col-md-4">
        <label for="inputvoucherno" class="form-label">Voucher No:</label>
        <input type="text" class="form-control" name="voucherno" id="inputvoucherno" />
      </div>
      <div class="col-md-4">
        <label for="inputvoucherdate" class="form-label">Voucher Date:</label>
        <input type="date" class="form-control" name="voucherdate" id="inputvoucherdate" />
      </div>
      <div class="col-md-4">
        <label for="inputref" class="form-label">Ref No:</label>
        <input type="number" class="form-control" name="refno" id="inputref" />
      </div>
      <!-- // second row -->
      <div class="col-md-4 ">
        <label for="dropdownSelection" class="form-label">Company Name:</label>
        <select class="form-select" id="dropdownSelection" name="companyname" aria-label="Select Company">
          <option selected>Choose Company</option>
          <!-- <option value="Company 1">Company 1</option>
          <option value="Company 2">Company 2</option>
          <option value="Company 3">Company 3</option> -->
        </select>
      </div>
      <div class="col-md-4">
        <label for="inputdivison" class="form-label">Divison:</label>
        <select class="form-select" id="inputdivison" name="division" aria-label="Select Division">
          <option selected>Choose Division</option>
          <!-- <option value="Div 1">Div 1</option>
          <option value="Div 2">Div 2</option>
          <option value="Div 3">Div 3</option> -->
        </select>
      </div>
      <div class="col-md-4">
        <label for="inputdep" class="form-label">Department:</label>
        <select class="form-select" id="inputdep" name="department" aria-label="Select Department">
          <option selected>Choose Department</option>
          <!-- <option value="1">Department 1</option>
          <option value="2">Department 2</option>
          <option value="3">Department 3</option> -->
        </select>
      </div>

      <!-- // row 3 -->
      <div class="col-md-2">
        <label for="dropdownSelection" class="form-label">Trans. type:</label>
        <select class="form-select" id="transactionType" name="transtype" aria-label="Select type"
          onchange="toggleAmountFields()">
          <option value="Trans.type" selected>Choose Trans. type</option>
          <option value="Debit">Debit</option>
          <option value="Credit">Credit</option>
        </select>
      </div>

      <div class="col-md-2">
        <label for="inputcodeno" class="form-label">Trans. Code:</label>
        <input type="number" class="form-control" name="transcode" id="inputcodeno" />
      </div>

      <div class="col-md-2">
        <label for="inputacc" class="form-label">Account:</label>
        <select class="form-select" id="inputacc" name="accountname" aria-label="Select Account">
          <option selected>Choose Account</option>
          <!-- <option value="1">Acc. 1</option>
            <option value="2">Acc. 2</option>
            <option value="3">Acc. 3</option> -->
        </select>
      </div>

      <div class="col-md-2">
        <label for="inputdebitamt" class="form-label">Debit Amt.:</label>
        <input type="number" class="form-control" name="debitamt" id="inputdebitamt" />
      </div>

      <div class="col-md-2">
        <label for="inputcreditamt" class="form-label">Credit Amt.:</label>
        <div class="input-group">
          <input type="number" class="form-control" name="creditamt" id="inputcreditamt" />
        </div>
      </div>

      <div class="col-md-2">
        <button id="transactionbutton" style="margin-top: 31px" class="btn btn-secondary" type="button"
          onclick="displayRow()">
          <span class="bi bi-plus"></span>
        </button>
      </div>
    </form>

    <!-- TRANSACTIONS LIST -->
    <br />
    <div class="row g-3 justify-content-center" id="transtable">
      <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">Trans. type</th>
            <th scope="col">Trans. Code</th>
            <th scope="col">Account</th>
            <th scope="col">Debit Amt.</th>
            <th scope="col">Credit Amt.</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody id="transentriesTableBody"></tbody>
      </table>
    </div>

    <div class="row g-3 justify-content-center">
      <div class="col-md-2">
        <button style="margin-top: 31px" class="btn btn-dark" type="button" onclick="location.reload()">New Journal
          Entry</button>
      </div>

      <div class="col-md-2 ">
        <button id="editbtn" style="margin-top: 31px; " class="btn btn-warning" type="button"
          onclick="saveJournalEntry()">Save Journal Entry</button>
      </div>
    </div>
  </div>
  </div>
  <script>
    function toggleAmountFields() {
      var transactionType = document.getElementById("transactionType").value;
      var debitAmtField = document.getElementById("inputdebitamt");
      var creditAmtField = document.getElementById("inputcreditamt");

      if (transactionType === "Debit") {
        debitAmtField.disabled = false;
        creditAmtField.disabled = true;
      } else {
        debitAmtField.disabled = true;
        creditAmtField.disabled = false;
      }
    }


    var currentRow = 0;
    function displayRow() {

      const tbodyEl = document.querySelector('tbody');
      const transtype = document.getElementById("transactionType").value;
      const transcode = document.getElementById("inputcodeno").value;
      const account = document.getElementById("inputacc").value;
      const debitamt = document.getElementById("inputdebitamt").value;
      const creditamt = document.getElementById("inputcreditamt").value;

      function deleteRow(e) {
        e.preventDefault();
        if (!e.target.classList.contains("deleteBtn")) {
          return;
        }

        const btn = e.target;
        btn.closest("tr").remove();
      }

      tbodyEl.innerHTML += `
        <tr>
            <td>${transtype}</td>
            <td>${transcode}</td>
            <td>${account}</td>
            <td>${debitamt}</td>
            <td>${creditamt}</td>
            <td><button class="btn btn-danger"id="deleteBtn-`+ currentRow + `" onclick=deleteRow(event)>Delete</button></td>
            <td><button class="btn btn-secondary bi bi-plus"id="addCost-` + currentRow + `" onclick="duplicateRow(event)"></button></td>
        </tr>
        
        <div class="row g-3 justify-content-center costCenterDetailsRow" style = "display: none ;"  >    
          <div class="col-md-2 style:"text-align:center;" >
                <label for="costCode" class="form-label">Cost Code:</label>
                <input
                  type="text"
                  class="form-control"
                  id="costCode"
                  name="costCode[]"
                />
              </div> 
             <div class="col-md-2 style:"text-align:center;">
                <label for="costCenter" class="form-label">Cost Center:</label>
                <select class="form-select" id="costCenter" name="costCenter[]">
                  <option selected>Choose Cost Center</option>
                  
                </select>
              </div>
              <div class="col-md-2 style:"text-align:center;">
                <label for="amount" class="form-label">Amount:</label>
                <input type="number" class="form-control" id="amount" name="amount[]" />
              </div>
              <div class="col-md-2 style:"text-align:center;">
                <label for="remarks" class="form-label">Remarks:</label>
                <input type="text" class="form-control" id="remarks" name="remarks[]" />
              </div>
              <div class="col-md-2 style:"text-align:center;">
                <button style="margin-top: 31px" class="btn btn-secondary" type="button" onclick="addCostCenterRow(event)">
                  Add more
                </button>
              </div>
          </div>
          <div id="costDiv" style="display:none;">

          </div>
        `;

      document.getElementById("transactionType").value = "Trans.type";
      document.getElementById("inputcodeno").value = "";
      document.getElementById("inputacc").value = "";
      document.getElementById("inputdebitamt").value = "";
      document.getElementById("inputcreditamt").value = "";
      currentRow++;
    }
    function duplicateRow(e) {
      console.log(e.target);
      console.log(e.target.id);
      if (e.target.classList.contains('bi-plus')) {
        const button = document.querySelector(`#${e.target.id}`);

        if (button) {
          // console.log('Button found:', button);

          const costCenterDetailsRow = button.closest("tr").nextElementSibling;

          if (costCenterDetailsRow) {
            console.log('costCenterDetailsRow found:', costCenterDetailsRow);

            costCenterDetailsRow.style.display = costCenterDetailsRow.style.display === "none" ? "" : "none";
            const rowNumber = e.target.id.split('-')[1];
            costCenterDetailsRow.classList.add(`cc${rowNumber}`);

            if (costCenterDetailsRow.style.display === "none") {
              button.classList.remove('bi-minus');
              button.classList.add('bi-plus');
            } else {
              button.classList.remove('bi-plus');
              button.classList.add('bi-minus');
            }
          } else {
            console.log('costCenterDetailsRow not found.');
          }
        } else {
          console.log('Button not found.');
          console.log(cls);
        }
      }
      else {
        const rowNumber = e.target.id.split('-')[1];
        const classHidecc = `cc${rowNumber}`

        const hidecc = document.querySelectorAll(`.${classHidecc}`);

        for (let i = 0; i < hidecc.length; i++) {
          const element = hidecc[i];
          element.style.display = "none";
          console.log(element)
        }
        e.target.classList.remove("bi-minus");
        e.target.classList.add("bi-plus");
      }
    }

    function saveJournalEntry() {
    // Gather data from the form
    var formData = new FormData(document.getElementById("myForm"));

    // Make an AJAX request to insert.php
    $.ajax({
      type: "POST",
      url: "insert.php",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        // Handle the response from insert.php
        console.log(response);
        // You can add more logic here based on the response if needed
      },
      error: function (error) {
        // Handle any errors during the AJAX request
        console.error(error);
      }
    });
  }

    function addCostCenterRow(e) {
      const button = e.currentTarget; // Use currentTarget instead of target
      const originalRow = button.closest('div').parentNode;

      if (originalRow) {
        const newRow = originalRow.cloneNode(true);

        // Clear values in the new row
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        newRow.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

        // Insert the new row below the original row
        originalRow.parentNode.insertBefore(newRow, originalRow.nextSibling);

        // Show the new row
        newRow.style.display = '';

        // Scroll to the newly added row
        newRow.scrollIntoView({ behavior: 'smooth', block: 'end' });
      } else {
        console.error('Could not find the original row.');
      }
    }

    function deleteRow(e) {
      console.log(e.target);
      const divDelete = e.target.parentNode.parentNode;
      divDelete.remove();
      const rowNumber = e.target.id.split('-')[1];
      const classDeletecc = `cc${rowNumber}`

      const deletecc = document.querySelectorAll(`.${classDeletecc}`);

      for (let i = 0; i < deletecc.length; i++) {
        const element = deletecc[i];
        element.remove();
        console.log(element)
      }
      console.log(deletecc)
    }

    $(document).ready(function () {
    // Fetch and populate dropdown data
    $.ajax({
        type: 'GET',
        url: 'dropdowndata.php',
        dataType: 'json',
        success: function (data) {
            // Populate Company dropdown
            var companyDropdown = $("#dropdownSelection");
            $.each(data.company, function (index, value) {
                companyDropdown.append($("<option>").text(value));
            });

            // Populate Division dropdown
            var divisionDropdown = $("#inputdivison");
            $.each(data.division, function (index, value) {
                divisionDropdown.append($("<option>").text(value));
            });

            // Populate Department dropdown
            var departmentDropdown = $("#inputdep");
            $.each(data.department, function (index, value) {
                departmentDropdown.append($("<option>").text(value));
            });

            // Populate Account dropdown
            var accountDropdown = $("#inputacc");
            $.each(data.account, function (index, value) {
                accountDropdown.append($("<option>").text(value));
            });
            var costCenterDropdown = $("#costCenter"); // Update with the correct ID
            $.each(data.costcenter, function (index, value) {
                costCenterDropdown.append($("<option>").text(value));
            });
        },
        error: function (xhr, status, error) {
            console.error('Failed to fetch dropdown data:', status, error);
        }
    });

    
});
</script>
</body>
</html>