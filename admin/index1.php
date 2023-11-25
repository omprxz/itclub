<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}
?>
<title><?php echo $_SESSION['admin_name']." - Admin"; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
.btnContainer{
  text-align: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
  flex-wrap: wrap;
  gap: 10px;
  padding: 20px;
}
  .btnLinks{
    background:black;
    color:white;
    font-size:18px;
    padding:12px 20px;
    border-radius:5px;
    text-decoration:none;
    margin:10px;
  }
</style>
<div class="btnContainer">
  <h3><?php echo "Hello ".$_SESSION['admin_name']."."; ?></h3>
  
<a href="../" class="btnLinks">HOME</a>
  <a href="create_notice.php" class="btnLinks">Create Notice</a>
  <a href="create_event.php" class="btnLinks">Create Event</a>
  <a href="add_result.php" class="btnLinks">Publish Results</a>
  <a href="extra_values.php" class="btnLinks">Extra</a>
  <a href="blog" class="btnLinks">Blog</a>
<a href="sqlquery.php" class="btnLinks">SQL Query</a>
</div>


<p style="text-align:center;padding:15px;">
<a style="background:grey;color:white;font-size:18px;padding:12px 20px;border-radius:5px;text-decoration:none;margin:10px;" href="logout.php">Logout</a>
</p>