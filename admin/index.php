<?php
include_once('../action/checklogin.php');
require '../action/conn.php';
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_name,admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $admin_name = $row['admin_name'];
    
    $result->free_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $admin_name; ?> - Admin Page</title>
  <!-- Some resources are in header.php -->
  <style>
  .main{
      display:flex;
      justify-content:center;
  }
    .links{
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      justify-content: center;
      width: clamp(300px,100%,500px);
      gap: 15px;
      margin-bottom: 15px;
    }
    .btnLinks{
      width: calc(100% / 2 - 20px);
      height: 160px;
      font-size: 17px;
      font-weight: 700;
      display: flex;
      flex-direction: column;
      gap: 15px;
      justify-content: center;
      align-items: center;
      border: 1px solid #03F7EB;
      background: #03f7eb30;
      border-radius: 5px;
      color: black;
      text-decoration: none;
      text-align: center;
      font-family: 'Mukta';
      line-height: 1.1;
    }
    .btnLinks i{
      font-size: 40px;
    }
  </style>
</head>
<body>
  <?php include 'header.php'; ?>
  <br/>
  
  <section class="main">
    <div class="links">
      <a href="notice/" class="btnLinks">
        <i class="fas fa-bullhorn"></i>
        <span>
          Create & Manage Notices
        </span>
      </a>
      <a href="event/" class="btnLinks">
        <i class="fas fa-calendar"></i>
        <span>
          Create & Manage Events
        </span>
      </a>
      <a href="blog/" class="btnLinks">
        <i class="fas fa-feather"></i>
        <span>
          Create & Manage Blogs
        </span>
      </a>
      <a href="add_result.php" class="btnLinks">
        <i class="fas fa-trophy"></i>
        <span>
          Publish Results
        </span>
      </a>
      <a href="changeAdminLevel.php" class="btnLinks">
        <i class="fas fa-cog"></i>
        <span>
          Admins Level
        </span>
      </a>
      <a href="extra_values.php" class="btnLinks">
        <i class="fas fa-plus"></i>
        <span>
          Extras
        </span>
      </a>
      <a href="sqlquery.php" class="btnLinks">
        <i class="fas fa-database"></i>
        <span>
          SQL Query
        </span>
      </a>
    </div>
  </section>
  
  <script>
    const colors=['#FAC60A','#03F7EB','#340068','#EA3546','#53DD6C','#F1D302','#235789'];
    const btnLinks = document.querySelectorAll('.btnLinks')
    let colorIndex=0;
    for(let i=0;i<btnLinks.length;i++){
      btnLinks[i].style.background = colors[colorIndex]+"20";
      btnLinks[i].style.border = "1px solid "+colors[colorIndex];
      colorIndex++
      if(colorIndex>=colors.length){
        colorIndex=0
      }
    }
  </script>
</body>
</html>