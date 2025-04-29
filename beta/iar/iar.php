<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inspection and Acceptance Report</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
<div class="container-fluid p-0">
<div class="psa-header d-flex align-items-center border-bottom px-4 py-3">
  <img src="../assets/psa.png" alt="PSA Logo" class="psa-logo mr-3">

  <div>
      <div class="psa-small">REPUBLIC OF THE PHILIPPINES</div>
      <div class="psa-main">PHILIPPINE STATISTICS AUTHORITY - QUIRINO PROVINCIAL OFFICE</div>
  </div>
</div>


<div class="d-flex" style="min-height: 90vh;">
  <!-- Sidebar -->
  <div class="sidebar border-right p-3">
    <button class="btn btn-block mb-3" onclick="location.href='../home.php'">Dashboard</button>
    <h5 class="text-center border mb-3 p-1">Data Entry</h5>
    <button class="btn btn-block mb-3" onclick="location.href='../ris/ris.php'">Requisition and Issuance Slip</button>
    <button class="btn btn-block mb-4" onclick="location.href='../iar/iar.php'">Inspection and Acceptance Report</button>

    <h5 class="text-center border mb-3 p-1">Generate Report</h5>
    <button class="btn btn-block mb-4" onclick="location.href='../stck_crd.php'">Stock Card</button>
    <button class="btn btn-block mb-2">Stock Ledger Card</button>
    <button class="btn btn-block mb-2">Report of Supplies and Materials Issued</button>
    <button class="btn btn-block mb-4">Report on the Physical Count of Inventories</button>

    <h5 class="text-center border mb-3 p-1">Utilities</h5>
    <button class="btn btn-block mb-4">Manage Employee List</button>

    <!-- Logout -->
<form id="logoutForm" method="post" class="d-flex justify-content-center mt-5">
  <input type="hidden" name="logout" value="1">
  <button type="button" class="btn logout-btn rounded-pill px-4" onclick="confirmLogout()">LOGOUT</button>
</form>

  </div>


  <!-- Main Content -->
  <div class="main-content">
    <h2 class="text-center mb-4">INSPECTION AND ACCEPTANCE REPORT</h2>

    <form id="iarForm">
      <table class="noborder">
      
          <td colspan="2"><strong>Entity Name :</strong> Philippine Statistics Authority</td>
          <td colspan="2"><strong>Fund Cluster :</strong> <input type="text" name="fund_cluster"></td>
        </tr>
        <tr>
          <td colspan="2"><strong>Supplier :</strong> <input type="text" name="supplier" required></td>
          <td><strong>IAR No. :</strong> <input type="text" name="iar_no" required></td>
          <td><strong>Date :</strong> <input type="date" name="date" required></td>
        </tr>
        <tr>
          <td colspan="2"><strong>PO No./Date :</strong> <input type="text" name="pr_no" required></td>
          <td><strong>Invoice No. :</strong> <input type="text" name="invoice_no"></td>
          <td><strong>Date :</strong> <input type="date" name="invoice_date"></td>
        </tr>
        <tr>
          <td colspan="4"><strong>Requisitioning Office/Dept. :</strong> <input type="text" name="requisitioning_office"></td>
        </tr>
        <tr>
          <td colspan="4"><strong>Responsibility Center Code :</strong> <input type="text" name="responsibility_center"></td>
        </tr>
      </table>

      <table id="itemsTable">
        <tr class="section-title">
          <th>Stock/Property No.</th>
          <th>Item</th>
          <th>Description</th>
          <th>Unit</th>
          <th>Quantity</th>
        </tr>
        <tr>
          <td><input type="text" class="form-control stock-code" name="stock_code[]"></td>
          <td><input type="text" name="item[]" class="form-control items" readonly></td>
          <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
          <td>
            <select name="unit[]" required>
              <option value="">--Select--</option>
                    <option value="pc">pc</option>
                    <option value="bottle">bottle</option>
                    <option value="ream">ream</option>
                    <option value="kg">kg</option>
                    <option value="liter">liter</option>
                    <option value="pack">pack</option>
                    <option value="box">box</option>
                    <option value="roll">roll</option>
                    <option value="pair">pair</option>
            </select>
          </td>
          <td><input type="number" name="quantity[]" min="0"></td>
        </tr>
      </table>

      <button type="button" onclick="addRow()">Add Row</button>

      <br><strong class="d-block mt-4">INSPECTION</strong>
      <table>
        <tr>
          <td>
            <strong>Date Inspected :</strong>
            <input type="date" name="date_inspected" required><br><br>
            Inspected, verified and found in order as to quantity and specifications.<br><br>
            <strong>Inspection Officer :</strong><br>
            <input type="text" name="i_officer" placeholder="Enter name" required>
          </td>
          <td>
            <strong>Date Received :</strong>
            <input type="date" name="final_date_received" required><br><br>
            <strong>Custodian :</strong><br>
            <input type="text" name="custodian" placeholder="Enter name" required>
          </td>
        </tr>
      </table>

      <button type="button" onclick="confirmSubmit()">Submit</button>
    </form>
    <button id="helpBtn" class="btn btn-primary rounded-circle" title="Need help?">
      <i class="bi bi-question-lg"></i>
  </button>
  </div>
</div>

<script>
 document.querySelector('.logout-btn').addEventListener('click', () => {
  Swal.fire({
    title: 'Are you sure you want to logout?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, logout',
    cancelButtonText: 'Cancel'
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        icon: 'success',
        title: 'Logged out!',
        text: 'You have been logged out successfully.',
        showConfirmButton: false,
        timer: 1500
      }).then(() => {
        window.location.href = '../index.php';
      });
    }
  });
});


  function addRow() {
    const table = document.getElementById("itemsTable");
    const row = table.insertRow();

    row.innerHTML = `
      <td><input type="text" class="form-control stock-code" name="stock_code[]"></td>
      <td><input type="text" name="item[]" class="form-control items" readonly></td>
      <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
      <td>
        <select name="unit[]" required>
          <option value="">--Select--</option>
                    <option value="pc">pc</option>
                    <option value="bottle">bottle</option>
                    <option value="ream">ream</option>
                    <option value="kg">kg</option>
                    <option value="liter">liter</option>
                    <option value="pack">pack</option>
                    <option value="box">box</option>
                    <option value="roll">roll</option>
                    <option value="pair">pair</option>
        </select>
      </td>
      <td><input type="number" name="quantity[]" min="0"></td>
    `;
  }

  function confirmSubmit() {
    Swal.fire({
      title: 'Submit IAR?',
      text: "Are you sure you want to submit this report?",
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, submit it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        submitFormAJAX();
      }
    });
  }

  function submitFormAJAX() {
    const form = document.getElementById('iarForm');
    const formData = new FormData(form);

    fetch('submit.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        Swal.fire('Success!', data.message, 'success');
        form.reset();
      } else {
        Swal.fire('Error!', data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      Swal.fire('Oops!', 'Something went wrong.', 'error');
    });
  }

  $(document).on('input', '.stock-code', function () {
  var row = $(this).closest('tr');
  var stockCode = $(this).val();

  $.get("../get_description.php", { stock_code: stockCode }, function (response) {
      row.find(".items").val(response.item);
      row.find(".descd").val(response.description);
  });
});

document.getElementById('helpBtn').addEventListener('click', () => {
    Swal.fire({
        title: 'Need Help?',
        html: `
            <p>View the full code guide <a href="../codes.html" target="_blank" style="color: blue; text-decoration: underline;">here</a>.</p>
        `,
        icon: 'info',
        confirmButtonText: 'Got it!'
    });
});

</script>


<style>
  body {
    font-family: 'Segoe UI', sans-serif;
        background-color: #f0f2f5;
        color: #333;
  }

  .psa-header {
    background-color: rgb(21, 83, 150);
        color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: 'Times New Roman', Times, serif;
  }

  .psa-small, .psa-main {
        font-family: 'Times New Roman', Times, serif;
    }

  .psa-logo {
    height: 60px;
    width: auto;
  }

  .psa-small {
    font-size: 1rem;
        font-weight: 500;
        text-transform: uppercase;
        color: #dbeafe;
  }

  .psa-main {
    font-size: 2.1rem;
        font-weight: 700;
        color: #fff;
  }

  .sidebar {
    width: 280px;
    background-color: #dee2e6;
    min-height: 100vh;
    padding: 20px;
    box-shadow: inset -1px 0 0 rgba(0,0,0,0.1);
  }

  .sidebar h5 {
    font-size: 1rem;
    font-weight: 600;
    color: #343a40;
  }

  .sidebar .btn {
    background-color: transparent;
        color: #343a40;
        border: 1px solid #adb5bd;
        transition: all 0.2s ease-in-out;
  }

  .sidebar .btn:hover {
    background-color: rgb(0, 109, 218);
    color: white;
  }

  .logout-btn {
    background-color: transparent;
        border: 1px solid #ff4d4f;
        color: #ff4d4f;
        transition: all 0.2s ease-in-out;
  }

  .logout-btn:hover {
    background-color: #ff4d4f;
    color: white;
  }

  .main-content {
    flex-grow: 1;
    padding: 30px;
    background-color: #f8f9fa;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  td, th {
    border: 1px solid black;
    padding: 6px;
  }

  .noborder td {
    border: none;
  }

  .section-title {
    background-color: #f0f0f0;
    font-weight: bold;
    text-align: center;
  }

  input[type="text"],
  input[type="date"],
  input[type="number"],
  select {
    width: 100%;
    border: none;
    outline: none;
    font-size: 14px;
    padding: 4px;
  }

  button {
    margin-top: 20px;
  }

  #helpBtn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        z-index: 1000;
    }
</style>



</body>
</html>
