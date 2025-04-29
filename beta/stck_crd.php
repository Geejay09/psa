<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "dbpsa";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle stock number from form or URL
$stock_no = $_GET['stock_no'] ?? '';
$stock_no = trim($stock_no);

// Fetch entries
$data = [];
if (!empty($stock_no)) {
    $stmt = $conn->prepare("SELECT * FROM tbl_sc WHERE stock_no = ? ORDER BY date ASC");
    $stmt->bind_param("s", $stock_no);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Card - <?= htmlspecialchars($stock_no) ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>


    
</head>
<body>
    <div class="container-fluid p-0">
        <div class="psa-header d-flex align-items-center border-bottom px-4 py-3">
          <img src="assets/psa.png" alt="PSA Logo" class="psa-logo mr-3">
        
          <div>
              <div class="psa-small">REPUBLIC OF THE PHILIPPINES</div>
              <div class="psa-main">PHILIPPINE STATISTICS AUTHORITY - QUIRINO PROVINCIAL OFFICE</div>
          </div>
        </div>


    <!-- BODY WRAPPER -->
    <div class="d-flex" style="min-height: 90vh;">
        <!-- SIDEBAR -->
        <div class="sidebar border-right p-3">
            <button class="btn btn-block mb-3" onclick="location.href='home.php'">Dashboard</button>
            <h5 class="text-center border mb-3 p-1">Data Entry</h5>
            <button class="btn btn-block mb-3" onclick="location.href='ris/ris.php'">Requisition and Issuance Slip</button>
            <button class="btn btn-block mb-4" onclick="location.href='iar/iar.php'">Inspection and Acceptance Report</button>

            <h5 class="text-center border mb-3 p-1">Generate Report</h5>
            <button class="btn btn-block mb-4" onclick="location.href='stck_crd.php'">Stock Card</button>
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
    
        <div class="main-content">
            <h2 class="text-center mb-4">Stock Card </h2>
            <form method="GET" class="search-bar">
        <div class="input-group">
            <input type="text" name="stock_no" class="form-control" placeholder="Enter stock number..." value="<?= htmlspecialchars($stock_no) ?>" required>
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <button id="helpBtn" class="btn btn-primary rounded-circle" title="Need help?">
      <i class="bi bi-question-lg"></i>
  </button> 

    <?php if (!empty($stock_no)): ?>
        <h4>Stock Number: <strong><?= htmlspecialchars($stock_no) ?></strong></h4>
        <?php if (!empty($data)): ?>
            <table id="stockCardTable" class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                    <th>Stock No.</th>
                    <th>item</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Issue Qty</th>
                    <th>Balance Qty</th>
                    <th>Office</th>
                </thead>
                <tbody>
                    <?php foreach ($data as $row): ?>
                        <tr>
                        <td><?= htmlspecialchars($row['stock_no']) ?></td>
                        <td><?= htmlspecialchars($row['item']) ?></td>
                        <td><?= htmlspecialchars($row['dscrtn']) ?></td>
                        <td><?= htmlspecialchars($row['unit']) ?></td>
                        <td><?= htmlspecialchars($row['date']) ?></td>
                        <td><?= htmlspecialchars($row['ref']) ?></td>
                        <td><?= (int)$row['issue_qty'] ?></td>
                        <td><?= (int)$row['balance_qty'] ?></td>
                        <td><?= htmlspecialchars($row['office']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button onclick="exportStockCardToExcel('<?php echo $stock_no; ?>')">Export to Excel</button>



        <?php else: ?>
            <div class="alert alert-warning">No entries found for stock number <strong><?= htmlspecialchars($stock_no) ?></strong>.</div>
        <?php endif; ?>
    <?php endif; ?>
        </div>
</body>

<script>
    async function exportStockCardToExcel(stock_no) {
    const response = await fetch('fetch_stock_card.php?stock_no=' + stock_no);
    const stockData = await response.json();

    const rows = stockData.rows;
    const itemInfo = stockData.item_info;

    const wb = XLSX.utils.book_new();
    const ws = {};

    // Helper style objects
    const borderAll = {
        top: { style: "thin" },
        bottom: { style: "thin" },
        left: { style: "thin" },
        right: { style: "thin" }
    };

    const boldCenter = {
        font: { bold: true },
        alignment: { horizontal: "center", vertical: "center" },
        border: borderAll
    };

    const normalCenter = {
        alignment: { horizontal: "center", vertical: "center" },
        border: borderAll
    };

    const normalLeft = {
        alignment: { horizontal: "left", vertical: "center" },
        border: borderAll
    };

    // Title
    ws['A1'] = { v: 'STOCK CARD', t: 's', s: { font: { bold: true, sz: 14 }, alignment: { horizontal: "center" } } };

    // Header Info
    ws['A2'] = { v: 'Entity Name: Philippine Statistics Authority', t: 's', s: normalLeft };
    ws['F2'] = { v: 'Fund Cluster:', t: 's', s: normalLeft };
    ws['A3'] = { v: 'Item: ' + (itemInfo?.item || ''), t: 's', s: normalLeft };
    ws['F3'] = { v: 'Stock No.: ' + stock_no, t: 's', s: normalLeft };
    ws['A4'] = { v: 'Description: ' + (itemInfo?.descode || ''), t: 's', s: normalLeft };
    ws['F4'] = { v: 'Re-order Point:', t: 's', s: normalLeft };
    ws['A5'] = { v: 'Unit of Measurement: ' + (itemInfo?.unit || ''), t: 's', s: normalLeft };

    // Table Headers
    ws['A7'] = { v: 'Date', t: 's', s: boldCenter };
    ws['B7'] = { v: 'Reference', t: 's', s: boldCenter };
    ws['C7'] = { v: 'Receipt', t: 's', s: boldCenter };
    ws['E7'] = { v: 'Issue', t: 's', s: boldCenter };
    ws['G7'] = { v: 'Balance', t: 's', s: boldCenter };
    ws['I7'] = { v: 'No. of Days to Consume', t: 's', s: boldCenter };

    // Sub-headers
    ws['C8'] = { v: 'Qty.', t: 's', s: boldCenter };
    ws['E8'] = { v: 'Qty.', t: 's', s: boldCenter };
    ws['F8'] = { v: 'Office', t: 's', s: boldCenter };
    ws['G8'] = { v: 'Qty.', t: 's', s: boldCenter };

    // Dynamic data rows
    // Insert static row for Balance Forwarded at row 9
    ws['A9'] = { v: '01/01/2025', t: "s", s: normalCenter };
    ws['B9'] = { v: 'Balance Forwarded', t: "s", s: normalLeft };
    ws['G9'] = { v: itemInfo?.initial_qty || 0, t: "n", s: normalCenter };  // Optional: or hardcode a value like 21
    ws['I9'] = { v: '', t: "s", s: normalCenter };  // Optional

// Now insert dynamic data starting from row 10
for (let i = 0; i < rows.length; i++) {
    const entry = rows[i];
    const row = i + 10;


        ws[`A${row}`] = { v: entry.date || '', t: "s", s: normalCenter };
        ws[`B${row}`] = { v: entry.ref || '', t: "s", s: normalCenter };
        ws[`C${row}`] = { v: entry.receipt_qty || '', t: "n", s: normalCenter };
        ws[`E${row}`] = { v: entry.issue_qty || '', t: "n", s: normalCenter };
        ws[`F${row}`] = { v: entry.office || '', t: "s", s: normalCenter };
        ws[`G${row}`] = { v: entry.balance_qty || '', t: "n", s: normalCenter };
        ws[`I${row}`] = { v: entry.no_days || '', t: "n", s: normalCenter };
    }

    const totalRows = rows.length + 9;  // One extra row for the static entry
    ws['!ref'] = `A1:I${totalRows}`;

    // Merge cells
    ws['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } }, // A1:I1
        { s: { r: 1, c: 0 }, e: { r: 1, c: 3 } }, // A2:D2
        { s: { r: 1, c: 5 }, e: { r: 1, c: 8 } }, // F2:I2
        { s: { r: 2, c: 0 }, e: { r: 2, c: 3 } }, // A3:D3
        { s: { r: 2, c: 5 }, e: { r: 2, c: 8 } }, // F3:I3
        { s: { r: 3, c: 0 }, e: { r: 3, c: 3 } }, // A4:D4
        { s: { r: 3, c: 5 }, e: { r: 3, c: 8 } }, // F4:I4
        { s: { r: 4, c: 0 }, e: { r: 4, c: 3 } }, // A5:D5

        { s: { r: 6, c: 2 }, e: { r: 6, c: 3 } }, // C7:D7 Receipt
        { s: { r: 6, c: 4 }, e: { r: 6, c: 5 } }, // E7:F7 Issue
        { s: { r: 6, c: 6 }, e: { r: 6, c: 7 } }, // G7:H7 Balance
        { s: { r: 6, c: 8 }, e: { r: 7, c: 8 } }, // I7:I8 No of Days to Consume

        { s: { r: 6, c: 0 }, e: { r: 7, c: 0 } }, // A7:A8 Date
        { s: { r: 6, c: 1 }, e: { r: 7, c: 1 } }, // B7:B8 Reference
    ];

    ws['!cols'] = [
        { wch: 15 },
        { wch: 20 },
        { wch: 10 },
        { wch: 5 },
        { wch: 10 },
        { wch: 20 },
        { wch: 10 },
        { wch: 5 },
        { wch: 20 },
    ];

    XLSX.utils.book_append_sheet(wb, ws, "Stock Card");
    XLSX.writeFile(wb, `Stock_Card_${stock_no}.xlsx`);
}

document.getElementById('helpBtn').addEventListener('click', () => {
    Swal.fire({
        title: 'Need Help?',
        html: `
            <p>View the full code guide <a href="codes.html" target="_blank" style="color: blue; text-decoration: underline;">here</a>.</p>
        `,
        icon: 'info',
        confirmButtonText: 'Got it!'
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
                window.location.href = 'index.php';
            }
        });
    }

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
        
        h2 {
            font-weight: bold;
        }
        .back-btn {
            margin-bottom: 20px;
        }
        .search-bar {
            max-width: 400px;
            margin-bottom: 20px;
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
</html>
