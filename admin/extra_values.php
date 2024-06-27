<?php
include_once('../action/checklogin.php');
?>

<meta name="viewport" content="width=device-width">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<title>Extra Values</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
 <?php include 'header.php'; ?>
  <br/>
<div class="text-center m-3">
  <form method="get">

<?php
require('../action/conn.php');
$admin_id=$_SESSION["admin_id"];
$query = "SELECT admin_level FROM adminCreds WHERE admin_id = $admin_id";
$result = $mysqli->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    $admin_level = $row['admin_level'];
    $result->free_result();
}

if(isset($_GET['update'])){
  if($admin_level>=6){
  $keys=$_GET['keys'];
  $valueInput=$mysqli -> real_escape_string($_GET['valueInput']);
  $value=$_GET['value'];
  
  $sql="update extra set $value = '$valueInput' where key1 = '$keys'";
  $qsql=mysqli_query($mysqli,$sql);
  }else{
    echo("Not permitted on your account");
  }
}

$fetchKeys="select * from extra";
$QfetchKeys=mysqli_query($mysqli,$fetchKeys);
if(mysqli_num_rows($QfetchKeys)>0){?>

<select class="keys form-select" name="keys">
<?php
  while($key2=mysqli_fetch_assoc($QfetchKeys)){
    $value1=$key2['value1'];
    ?>
  <option value="<?php echo $key2['key1']; ?>"><?php echo $key2['key1']; ?></option>
    <?php
  }
  ?>
  </select>
  <?php
}else {
  echo( 'No key');
}
?>
 <?php
if($admin_level < 6){
  echo "<p style='color:red;font-size:16px;'>You are currently not allowed to manipulate extra.</p>";
}
?>
<br />
<label>Value 1 <input class="form-check-input value" type="radio" name="value" id="value1" value="value1" checked /></label> <br />
<label>Value 2 <input class="form-check-input value" type="radio" name="value" id="value2" value="value2" /> </label> <br />
<label>Value 3 <input class="form-check-input value" type="radio" name="value" id="value3" value="value3" /> </label> <br />
<input type="text" class="form-control my-2" name="valueInput" id="valueInput" placeholder="YYYY-MM-DD HH:MM:SS" value="<?php echo $value1; ?>" />
<button class="btn btn-dark" type="submit" name="update" class="update">Update</button>
</form>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>