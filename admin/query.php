<?php
date_default_timezone_set('Asia/Kolkata');

include_once('../action/checklogin.php');
require('../action/conn.php');
 $admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}
//echo $_SESSION['admin_level'];
if ($admin_level >= 7) {
  require "../action/conn.php";
  $query = $_GET['query'];

  // Execute the SQL query
  $result = $mysqli->query($query);


  if ($result) {
    $output = '<div style="margin:8px 8px;" class="alert alert-success">Query executed successfully.</div>';

    // Display the results in a table
    if ($result->num_rows > 0) {
    $output .= '<style>.queryTable{text-align:center;margin:8px 8px;}</style><table class="table queryTable table-bordered m-2" id="results"><tr>';
      while ($row = $result->fetch_assoc()) {
        if (empty($headers)) {
          $headers = array_keys($row);
          $output .= '<tr>';
          foreach ($headers as $header) {
            $output .= '<th>' . $header . '</th>';
          }
          $output .= '</tr>';
        }
        $output .= '<tr>';
        foreach ($row as $value) {
          $output .= '<td>' . htmlspecialchars($value) . '</td>';
        }
        $output .= '</tr>';
      }
      $output .= '</table>';
    } else {
      $output .= '<div class="alert alert-info">No results found.</div>';
    }
  } else {
    $output = '<div class="alert alert-danger">Error executing query: ' . mysqli_error($mysqli) . '</div>';
  }
} else {
  $output = '<div class="alert alert-danger">This action is not permitted on your account.</div>';
}
echo $output;
?>

<div class="d-flex justify-content-center gap-4 my-4">
  <button class="exportBtns btn btn-primary rounded" onclick="exportToCSV()">Export to CSV</button>
  <button class="exportBtns btn btn-primary rounded" onclick="exportToSQL()">Export to SQL</button>
</div>

<script>
  function exportToCSV() {
    let fileNameCSV = prompt("Enter a filename for the CSV export:");
    if(!fileNameCSV){
      fileNameCSV="exported_data"
    }
    fileNameCSV+='.csv'
    let table = document.getElementById("results");
    let rows = table.rows;
    let csvContent = "data:text/csv;charset=utf-8,";
    for (let i = 0; i < rows.length; i++) {
      let row = [];
      let cols = rows[i].cells;
      for (let j = 0; j < cols.length; j++) {
        row.push(cols[j].textContent);
      }
      csvContent += row.join(",") + "\n";
    }
    let encodedUri = encodeURI(csvContent);
    let link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", fileNameCSV);
    link.click();
  }
  
  function exportToSQL() {
            let tableName = prompt("Enter table name:");
            if (!tableName) {
                tableName = "exported";
            }
            let fileNameSQL = prompt("Enter a filename for the SQL export:");
            if (!fileNameSQL) {
                fileNameSQL = "export_table";
            }
            fileNameSQL += ".sql";
            let table1 = document.getElementById("results");
            let rows1 = table1.rows;
            let sqlContent = "-- SQL Table Creation\n";
            sqlContent += "CREATE TABLE "+tableName+" (\n";
            // Extract column names from the first row
            let headerRow1 = rows1[0].cells;
            for (let i = 0; i < headerRow1.length; i++) {
                sqlContent += `    ${headerRow1[i].textContent} VARCHAR(255)${i < headerRow1.length - 1 ? "," : ""}\n`;
            }
            sqlContent += ");\n";
            // Insert data into the table
            for (let i = 1; i < rows1.length; i++) {
                let cols = rows1[i].cells;
                let values = [];
                for (let j = 0; j < cols.length; j++) {
                    values.push(`'${cols[j].textContent}'`);
                }
                sqlContent += `INSERT INTO your_table VALUES (${values.join(", ")});\n`;
            }
            // Create a Blob with the SQL content and trigger a download
            let blob = new Blob([sqlContent], { type: "text/plain" });
            let a = document.createElement("a");
            a.href = URL.createObjectURL(blob);
            a.download = fileNameSQL;
            a.style.display = "none";
            document.body.appendChild(a);
            a.click();
         //   document.body.removeChild(a);
        }
</script>