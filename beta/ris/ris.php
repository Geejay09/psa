<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Requisition and Issue Slip</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

    <!-- BODY WRAPPER -->
    <div class="d-flex" style="min-height: 90vh;">
        <!-- SIDEBAR -->
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

            <!-- Logout Button -->
            <form id="logoutForm" method="post" class="d-flex justify-content-center mt-5">
                <input type="hidden" name="logout" value="1">
                <button type="button" class="btn logout-btn rounded-pill px-4" onclick="confirmLogout()">LOGOUT</button>
            </form>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2 class="text-center mb-4">REQUISITION AND ISSUE SLIP</h2>
            <form id="risForm">
                <div class="form-section">
                    <div>
                        <label>Division:</label>
                        <input type="text" name="division" class="form-control" required>

                        <label>Office:</label>
                        <input type="text" name="office" value="PSA-Quirino" class="form-control" required>

                        <label>Responsibility Center Code:</label>
                        <input type="text" name="rcc" class="form-control" required>

                        <label for="fc">Fund Cluster:</label>
                            <select name="fc" id="fc" class="form-select form-control" required>
                                <option value="" disabled selected>Fund Cluster</option>
                                <option value="Locally Funded">Locally Funded</option>
                                <option value="Regular">Regular</option>
                            </select>
                    </div>
                    <div>
                        <label>RIS No.:</label>
                        <input type="text" name="ris_no" class="form-control" required>

                        <label>Purpose:</label>
                        <textarea name="purpose" class="form-control" rows="3" required></textarea>

                        <label for="receiver">Received by:</label>
                            <select name="receiver" id="receiver" class="form-select form-control" required>
                                <option value="" disabled selected>Select receiver</option>
                                <option value="Cherry Grace D. Agustin">Cherry Grace D. Agustin</option>
                                <option value="Karen T. Fernandez">Karen T. Fernandez</option>
                                <option value="Marison S. Lomboy">Marison S. Lomboy</option>
                                <option value="Lace Christelle D. Ladia">Lace Christelle D. Ladia</option>
                                <option value="Liz T. Duque">Liz T. Duque</option>
                                <option value="Alexander G. Austria">Alexander G. Austria</option>
                                <option value="Jennifer B. Gamet">Jennifer B. Gamet</option>
                                <option value="Archie C. Ferrer">Archie C. Ferrer</option>
                                <option value="Santa Beverly P. Dorol">Santa Beverly P. Dorol</option>
                                <option value="Elgin Adrian R. Ugot">Elgin Adrian R. Ugot</option>
                                <option value="Joel N. Pilar">Joel N. Pilar</option>
                                <option value="Bless M. Urbano">Bless M. Urbano</option>
                                <option value="Sunshine D. Bumakel">Sunshine D. Bumakel</option>
                                <option value="Psyche R. Villanueva">Psyche R. Villanueva</option>
                            </select>
                    </div>
                </div>
                <br/>
                <table class="table table-bordered styled-table" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Stock No</th>
                            <th>Item</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Requested Quantity</th>
                            <th>Issued Quantity</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="stock_no[]" class="form-control stock_no"></td>
                            <td><input type="text" name="item[]" class="form-control items" readonly></td>
                            <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
                            <td>
                                <select name="unit[]" class="form-select form-control" required>
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
                            <td><input type="number" name="qty[]" class="form-control" required></td>
                            <td><input type="number" name="i_qty[]" class="form-control" required></td>
                            <td><input type="text" name="remarks[]" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>

                <div class="mb-3">
                    <button type="button" class="btn btn-secondary" onclick="addRow()">Add Row</button>
                </div>

                <div>
                    <button type="button" class="btn btn-primary submit-btn" onclick="confirmSubmit()">Submit</button>
                </div>
            </form>
            <button id="helpBtn" class="btn btn-primary rounded-circle" title="Need help?">
                <i class="bi bi-question-lg"></i>
            </button>
        </div>
    </div>
</div>

<script>
    function addRow() {
        const table = document.getElementById("itemsTable").getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        newRow.innerHTML = `
            <td><input type="text" name="stock_no[]" class="form-control stock_no"></td>
            <td><input type="text" name="item[]" class="form-control items" readonly></td>
            <td><input type="text" name="dscrtn[]" class="form-control descd" readonly></td>
            <td>
                <select name="unit[]" class="form-select form-control" required>
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
            <td><input type="number" name="qty[]" class="form-control" required></td>
            <td><input type="number" name="i_qty[]" class="form-control" required></td>
            <td><input type="text" name="remarks[]" class="form-control"></td>
        `;
    }

    function confirmSubmit() {
        Swal.fire({
            title: 'Submit RIS?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, submit it!'
        }).then(result => {
            if (result.isConfirmed) {
                submitFormAJAX();
            }
        });
    }

    function submitFormAJAX() {
        const form = document.getElementById('risForm');
        const formData = new FormData(form);

        fetch('submit_ris.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success!', data.message, 'success');
                form.reset();
            } else {
                Swal.fire('Error!', data.message, 'error');
            }
        })
        .catch(() => {
            Swal.fire('Oops!', 'Something went wrong.', 'error');
        });
    }

    $(document).on('input', '.stock_no', function () {
    var row = $(this).closest('tr');
    var stockCode = $(this).val();

    $.get("../get_description.php", { stock_code: stockCode }, function (response) {
        row.find(".items").val(response.item);
        row.find(".descd").val(response.description);
    });
});


    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure you want to logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, logout',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (result.isConfirmed) {
                window.location.href = '../index.php';
            }
        });
    }

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
        font-family: 'Times New Roman', Times, serif;
    }

    .psa-header {
    background-color: rgb(21, 83, 150);
        color: white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: 'Times New Roman', Times, serif;
  }

  psa-small, .psa-main {
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
        margin-left: 25px;
        padding: 30px;
    }

    .form-section {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .form-section div {
        flex: 1;
        min-width: 300px;
    }

    table th, table td {
        text-align: center;
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
