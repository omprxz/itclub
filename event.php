<?php
        require 'action/conn.php';
    
        // Get the rollno from the GET parameter
        $event_id = $_GET['event_id'];

$sql0 = "SELECT event_id FROM events WHERE event_id = $event_id";
$result0 = mysqli_query($mysqli, $sql0);

if (mysqli_num_rows($result0) > 0) {


        // Fetch event data from the database based on the rollno
        $sql = "SELECT * FROM events WHERE event_id = '$event_id'";
        $result = mysqli_query($mysqli, $sql);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $event_title = $row['event_title'];
            $event_date = $row['event_date'];
            

            $event_description = $row['event_description'];
            $event_imgurl = $row['event_imgurl'];
            $event_gphotoslink = $row['event_gphotoslink'];
            $gpUrls = explode(',',$event_gphotoslink);
$event_ytlink = $row['event_ytlink'];
$ytUrls=explode(',',$event_ytlink);
    
            // Close the database connection
            mysqli_free_result($result);
        } else {
            echo "Error in executing query: " . mysqli_error($mysqli);
        }
    
        $mysqli->close();
        ?>
<html>
    <head>
        <title>
        <?php echo $event_title; ?> - Event
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="common.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <style>
            .event-div {
                color: #ffffff;
                padding: 20px;
                margin: 5px auto;
                border-radius: 5px;
            }
    
            .event-div-title {
                font-size: 24px;
            }
    
            .event-div-date {
                font-size: 14px;
                margin-top: 5px;
            }
    
            .event-div-image {
                width: clamp(150px,90%,360px);
                height: auto;
                margin: 10px auto;
border-radius:5px;
            }
    
            .event-div-description {
                font-size: 16px;
                margin-top: 10px;
                text-align: justify;
            }
    
            .event-div-google-embed {
                margin-top: 20px;
                text-align: center;
            }
.eventLinks{
display:flex;
flex-direction:column;
justify-content:center;
gap:10px;
}
            .event-div-gallerybutton,.event-div-ytbutton{
                margin: auto;
                border: 0;
                border-radius: 7px;
                padding: 15px;
                cursor: pointer;
                background-color: #00388d;
                font-size: 15px;
                color: #fff;
                font-weight: 500;
                transition: all 0.4s;
                text-decoration: none;
                display: inline-block;
            }
            .event-div-gallerybutton:visited,.event-div-ytbutton:visited{
                color: white;
            }
            .event-div-gallerybutton:hover,.event-div-ytbutton:hover{
                background-color: #fff;
                color: black;
            }
        </style>
    </head>
    <body>

        <?php include 'header.html'; ?>
    
        <div class="event-div" style="margin:10px auto;">
            <h1 style="text-align:center;">EVENT</h1>
            <div class="event-div-title"><?php echo $event_title; ?></div>
            <?php
            if($event_date!=''){
              $event_date = strtotime($event_date);
            $event_date = date('d F Y', $event_date);
              ?>
            <div class="event-div-date">Event Date: <?php echo $event_date; ?></div>
           <?php }
            ?>
            <?php
            if($event_imgurl==''){
              $event_imgurl='defaultEventImage.webp';
            }
              ?>
            <img class="event-div-image" src="img/events/<?php echo $event_imgurl; ?>" alt="Event Image">
            <p class="event-div-description"><?php echo $event_description; ?></p>
           <!-- <div class="event-div-google-embed">
             Add your Google Photos embed code here -->
                <!-- <iframe src="<?php //echo $event_gphotoslink; ?>" frameborder="0" allowfullscreen="true"></iframe> <br> -->
                
<div class="eventLinks">
<?php
            if($event_gphotoslink!=''){
                $c='';
        for($i=0;$i<count($gpUrls);$i++){ ?>
                <a target="_blank" class="event-div-gallerybutton" href="<?php echo $gpUrls[$i]; ?>"><i class="fas fa-image"></i> &nbsp;Event Gallery &nbsp;<?php echo $c; ?></a>
<?php $c=$i+2; }} 
        if($event_ytlink!=''){
            $d='';
        for($i=0;$i<count($ytUrls);$i++){ ?>
 <a target="_blank" class="event-div-ytbutton" href="<?php echo $ytUrls[$i]; ?>"><i class="fas fa-video"></i> &nbsp;Watch Video &nbsp;<?php echo $d; ?></a>
</div>
           <?php $d=$i+2; }}
            ?>
            </div>
        </div>

        <?php include 'footer.html'; ?>
    
        
    </body>
    </html>

    <?php
} else {
  echo "Invalid event id $event_id";
  echo "<a href='index.php'>Redirecting you to homepage</a>";
  sleep(1);
  header("Location: index.php");
}
?>