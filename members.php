<?php
require('action/conn.php');

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number.'th';
    else
        return $number.$ends[$number % 10];
}

$inductionList = implode(", ", $currentInduction);

$sql = "SELECT * FROM members WHERE member_induction IN ($inductionList)";

$qsql = mysqli_query($mysqli, $sql);

$mem_categoryArray = [];
$mainData = [];


while ($data = mysqli_fetch_assoc($qsql)) {
    $mainData[] = $data;

    $mem_categoryArray[] = $data['member_category'];

}
$mem_categoryArray = array_unique($mem_categoryArray);

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members - IT Club</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="common.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
    .middle{
      overflow: hidden;
    }
        .coordinators {
            text-align: center;
            color: var(--fif-color);
            border-bottom: 1px solid grey;
            border-radius: 25px;
        }

        .coordinatorsLabel {
            margin-top: 10px;
            margin-bottom: 0px;
            font-size: 32px;
            font-weight: 700;
            color: var(--six-color);
        }

        .coords {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        .coords::-webkit-scrollbar{
          display: none;
        }

        @media screen and (max-width: 767px) {
            .coords {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                overflow-x: scroll;
                gap: 20px;
                padding: 0 20px;
            }
        }

        .our-team {
            padding: 20px 0 35px;
            margin: 0 auto 35px auto;
            background-color: var(--sev-color);
            text-align: center;
            overflow: hidden;
            position: relative;
            min-width: 250px;
            border-radius: 10px;
        }

        .our-team .picture {
            display: inline-block;
            height: 180px;
            width: 180px;
            margin-bottom: 20px;
            z-index: 1;
            position: relative;
        }

        .our-team .picture::before {
            content: "";
            width: 100%;
            height: 0;
            border-radius: 50%;
            background-color: #1369ce;
            position: absolute;
            bottom: 135%;
            right: 0;
            left: 0;
            opacity: 0.9;
            transform: scale(3);
            transition: all 0.3s linear 0s;
        }

        .our-team:hover .picture::before {
            height: 100%;
        }

        .our-team .picture::after {
            content: "";
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: #1369ce;
            position: absolute;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .our-team .picture img {
            width: 100%;
            height: auto;
            border-radius: 50%;
            transform: scale(1);
            transition: all 0.9s ease 0s;
        }

        .our-team:hover .picture img {
            box-shadow: 0 0 0 14px #f7f5ec;
            transform: scale(0.7);
        }

        .our-team .name {
            color: var(--six-color);
        }

        .our-team .title {
            display: block;
            font-size: 15px;
            color: var(--fou-color);
            text-transform: capitalize;
        }

        .our-team .social {
            width: 100%;
            padding: 0;
            margin: 0;
            background-color: #1369ce;
            position: absolute;
            left: 0;
            transition: all 0.5s ease 0s;
        }

        .our-team .social {
            bottom: 0;
        }

        .our-team .social li {
            display: inline-block;
        }

        .our-team .social li a {
            display: block;
            padding: 10px;
            font-size: 17px;
            color: white;
            transition: all 0.3s ease 0s;
            text-decoration: none;
        }

        .membersContainer {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            overflow-x: scroll;
            align-items: center;
            position: relative;
            gap: 20px;
            margin-top: 20px;
            padding: 0 20px;
            margin-bottom: 30px;
        }
        .membersContainer::-webkit-scrollbar {
            display: none;
        }

        .teamLabel {
            text-align: center;
            font-size: 30px;
            font-weight: bold;
            margin: 20px auto 0 auto;
            color: var(--six-color);
        }

        .card {
            background-color: #222831;
            height: 27rem;
            min-width: 17rem;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: rgba(0, 0, 0, 0.7);
            color: white;
        }

        .cardLeader {
            background-color: #222831;
            height: 27rem;
            border-radius: 5px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: rgba(0, 0, 0, 0.7);
            color: white;
            width: 300px;
            margin: auto;
        }

        @media (min-width: 768px) {
            .membersContainer {
                grid-template-columns: repeat(3, 310px);
            }

            .card {
                margin: auto 30px 5px 5px !important;
            }
        }

        @media (max-width: 767px) {
            .membersContainer {
                grid-template-columns: 1fr;
            }

            .card {
                width: 280px;
                margin: 10px auto;
            }

            .cardLeader {
                width: 280px;
            }
        }

        .card:hover .card__image {
            box-shadow: 0 8px 40px rgba(235, 25, 110, 1);
        }

        .card__name {
            margin-top: 15px;
            margin-bottom: 5px;
            font-size: 1.5em;
        }

        .card__desc1 {
            margin-bottom: 0px;
            color: var(--six-color);
        }

        .card__desc2 {
            margin-bottom: 20px;
            color: var(--fou-color);
        }

        .card__image {
            height: 160px;
            width: 160px;
            border-radius: 50%;
            border: 5px solid #272133;
            margin-top: 20px;
            transition: box-shadow 0.4s;
            aspect-ratio: 1/1;
            object-fit: cover;
        }

        .social-icons {
            padding: 0;
            list-style: none;
            margin: 1em;
        }

        .social-icons li {
            display: inline-block;
            margin: 0.15em;
            position: relative;
            font-size: 1em;
        }

        .social-icons i {
            color: #fff;
            position: absolute;
            top: 0.95em;
            left: 0.96em;
            transition: all 265ms ease-out;
        }

        .social-icons a {
            display: inline-block;
        }

        .social-icons a:before {
            transform: scale(1);
            -ms-transform: scale(1);
            -webkit-transform: scale(1);
            content: " ";
            width: 45px;
            height: 45px;
            border-radius: 100%;
            display: block;
            background: linear-gradient(45deg, #ff003c, #c648c8);
            transition: all 265ms ease-out;
        }

        .social-icons a:hover:before {
            transform: scale(0);
            transition: all 265ms ease-in;
        }

        .social-icons a:hover i {
            transform: scale(2.2);
            -ms-transform: scale(2.2);
            -webkit-transform: scale(2.2);
            color: #ff003c;
            background: -webkit-linear-gradient(45deg, #ff003c, #c648c8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            transition: all 265ms ease-in;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-gap: 20px;
            font-size: 1.2em;
        }

        .categoriesLabel,
        .categoriesLabelLeader {
            text-align: center;
            color: var(--six-color);
            margin-bottom: 0;
            margin-top: 15px;
        }


        .member-list {
            display: none;
        }

        .toggle-button-h {
            color: var(--six-color);
        }

        .toggle-button {
            cursor: pointer;
        }

        .member-list li {
            margin-top: 10px;
        }

        .member-list>li {
            color: var(--six-color);
        }

        .member-list>li>ul>li {
            color: var(--fif-color);
        }

        .member-list>li>ul>li>ul>li {
            color: var(--fou-color);
        }

        .member-list li ul li ul li {
            list-style: disc;
        }

        .toggle-button i {
            transition: 0.4s transform;
        }

        .rotate-90 {
            transform: rotateZ(90deg);
        }

        .past-inductions>div {
            border: 1px solid var(--six-color);
            border-radius: 10px;
            margin: 15px;
            padding: 10px;
        }

        .pastInducLabel {
            text-align: center;
            color: white;
            margin: 15px auto;
        }

        .curInducLabel {
            text-align: center;
            color: white;
            margin: 15px auto;
            text-decoration: underline;
        }
        .post2PastInductions div h2{
      color: white;
      text-align: center;
    }
    .inductionMembers{
      display: flex;
      flex-direction: row;
      flex-wrap: nowrap;
      align-items: center;
      overflow-x: scroll;
      padding: 0 10px;
      gap: 10px;
    }
    .inductionMembers::-webkit-scrollbar {
      display: none;
    }
    .inductionMember{
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 8px;
      min-width: 260px;
      min-height: 400px;
      background: #181818;
      color: white;
      border-radius: 8px;
    }
    .inductionMember img{
            height: 160px;
            width: 160px;
            border-radius: 50%;
            border: 3px solid #272133;
            margin-top: 20px;
            transition: box-shadow 0.4s;
            aspect-ratio: 1/1;
            object-fit: cover;
        }
    .past_social-icons{
      display: flex;
      flex-direction: row;
      justify-content: center;
      align-items: center;
      gap: 15px;
      padding: 0;
    }
    .past_social-icons li{
      list-style: none;
      background: hsl(224, 76.3%, 48%);
      width: 45px;
      height: 45px;
      border-radius: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.2rem;
      transition: all 0.5s;
    }
    .past_social-icons li:hover{
      transform: scale(1.1);
      box-shadow: 0px 2px 2px white;
    }
    .past_social-icons li a{
      color: white;
    }
    
    </style>

</head>

<body>

    <?php include 'header.html'; ?>
    
    <section class="middle">
      
    <div class="teamLabel">Our Members</div>
    <h3 class="curInducLabel"><?php echo ordinal(max($currentInduction)); ?> Induction</h3>
    <?php
    for ($i = 0; $i < count($mainData); $i++) {
        if ($mainData[$i]['member_isClubLeader'] == 1) { ?>
            <h3 class='categoriesLabelLeader' style="margin: 15px auto;">IT Club Leader</h3>
        <div style="display:flex;justify-content:center;">

            <div class="cardLeader card" data-aos="fade-up" data-aos-duration="1200">
                <img src="img/lazy.png" data-real-src="img/members/<?php echo $mainData[$i]['member_pfpurl']; ?>" alt="Person"
                    class="card__image">
                <p class="card__name">
                    <?php echo $mainData[$i]['member_name']; ?>
                </p>
                <p class="card__desc2">
                    Club Leader
                </p>
                <div class="grid-container">

                    <div class="grid-child-branch">
                        <?php echo $mainData[$i]['member_branch']; ?>
                    </div>

                    <div class="grid-child-session">
                        <?php echo $mainData[$i]['member_session']; ?>
                    </div>

                </div>
                <ul class="social-icons">
                    <?php
                    if ($mainData[$i]['member_instagram'] != '') { ?>
                        <li><a target="_blank" href="<?php echo $mainData[$i]['member_instagram']; ?>"><i
                                    class="fab fa-instagram"></i></a></li>
                    <?php }
                    ?>
                    <?php
                    if ($mainData[$i]['member_linkedin'] != '') { ?>
                        <li><a target="_blank" href="<?php echo $mainData[$i]['member_linkedin']; ?>"><i
                                    class="fab fa-linkedin"></i></a></li>
                    <?php }
                    ?>
                    <?php
                    if ($mainData[$i]['member_email'] != '') { ?>
                        <li><a target="_blank" href="mailto:<?php echo $mainData[$i]['member_email']; ?>"><i
                                    class="fas fa-envelope"></i></a></li>
                    <?php }

                    ?>

                </ul>
            </div>
            </div>


        <?php }
    }
    $memberIndices = [];
    foreach ($mem_categoryArray as $cat) {
$categ=$cat;
        echo ("
  <h3 class='categoriesLabel'>$cat</h3>
  ");
        echo ('<div class="membersContainer">');
        $a = 0;
        for ($i = 0; $i < count($mainData); $i++) {
            if ($mainData[$i]['member_category'] == $cat) {
                if ($a % 2 == 0) {
                    $animate = 'fade-right';
                } else {
                    $animate = 'fade-left';
                }
                $a++;
                $mainData[$i]['mainCat']=$cat;
                if(strpos($mainData[$i]['member_designation'],$mainData[$i]['member_category']) !== false){
$mainData[$i]['temp_member_designation']='Category Head';     
}else{
    $mainData[$i]['temp_member_designation']=$mainData[$i]['member_designation'];
}
                $mainData[$i]['temp_member_isTeamLeader']=$mainData[$i]['member_isTeamLeader'];
                $memberIndices[] = $mainData[$i];
                
                ?>
                <?php
            }
            elseif ($mainData[$i]['member_category2'] == $cat) {
                if ($a % 2 == 0) {
                    $animate = 'fade-right';
                } else {
                    $animate = 'fade-left';
                }
                $a++;
                $mainData[$i]['mainCat']=$cat;
                
                 if(strpos($mainData[$i]['member_designation'],$cat) == false){
                   
           $mainData[$i]['temp_member_designation']='Club Member';
           $mainData[$i]['temp_member_isTeamLeader']=0;
               }
                 
                
                $memberIndices[] = $mainData[$i];
                
                ?>


                <?php
            }
            elseif ($mainData[$i]['member_category3'] == $cat) {
                if ($a % 2 == 0) {
                    $animate = 'fade-right';
                } else {
                    $animate = 'fade-left';
                }
                $a++;
                $mainData[$i]['mainCat']=$cat;
                  if(strpos($mainData[$i]['member_designation'],$cat) == false){
                   
           $mainData[$i]['temp_member_designation']='Club Member';
           $mainData[$i]['temp_member_isTeamLeader']=0;
               }
                 
                 
                $memberIndices[] = $mainData[$i];
                ?>


                <?php
            }
        }

        usort($memberIndices, function ($a, $b) {
            return $b['temp_member_isTeamLeader'] - $a['temp_member_isTeamLeader'];
        });

        for ($z = 0; $z < count($memberIndices); $z++) { ?>

            <div class="card" data-aos="<?php echo $animate; ?>" data-aos-duration="1200">
                <img src="img/lazy.png" data-real-src="img/members/<?php echo $memberIndices[$z]['member_pfpurl']; ?>"
                    alt="Person" class="card__image">
                <p class="card__name">
                    <?php echo $memberIndices[$z]['member_name']; ?>
                </p>
                <p class="card__desc1">
                    <?php echo $memberIndices[$z]['mainCat']; ?>
                </p>
                <p class="card__desc2">
                    <?php echo $memberIndices[$z]['temp_member_designation']; ?>
                </p>
                <div class="grid-container">

                    <div class="grid-child-branch">
                        <?php echo $memberIndices[$z]['member_branch']; ?>
                    </div>

                    <div class="grid-child-session">
                        <?php echo $memberIndices[$z]['member_session']; ?>
                    </div>

                </div>
                <ul class="social-icons">
                    <?php
                    if ($memberIndices[$z]['member_instagram'] != '') { ?>
                        <li><a target="_blank" href="<?php echo $memberIndices[$z]['member_instagram']; ?>"><i
                                    class="fab fa-instagram"></i></a></li>
                    <?php }
                    ?>
                    <?php
                    if ($memberIndices[$z]['member_linkedin'] != '') { ?>
                        <li><a target="_blank" href="<?php echo $memberIndices[$z]['member_linkedin']; ?>"><i
                                    class="fab fa-linkedin"></i></a></li>
                    <?php }
                    ?>
                    <?php
                    if ($memberIndices[$z]['member_email'] != '') { ?>
                        <li><a target="_blank" href="mailto:<?php echo $memberIndices[$z]['member_email']; ?>"><i
                                    class="fas fa-envelope"></i></a></li>
                    <?php }

                    ?>

                </ul>
            </div>


            <?php
        }

        echo '</div>';

        $memberIndices = [];
    }
    ?>

    <div class="coordinators" data-aos-duration="1000" data-aos="fade-right">
        <p class="coordinatorsLabel">Our Co-ordinators</p>
        <div class="coords">
            <div class="our-team" data-aos-duration="1200" data-aos="fade-right">
                <div class="picture">
                    <img class="img-fluid" src="img/lazy.png" data-real-src="img/members/chandrashekhar.webp">
                </div>
                <div class="team-content">
                    <h3 class="name">Dr. Chandrashekhar Singh</h3>
                    <h4 class="title">Club President</h4>
                </div>
                <ul class="social">
                    <li><a href="mailto:" class="fas fa-envelope" aria-hidden="true"></a></li>
                </ul>
            </div>

            <div class="our-team" data-aos-duration="1200" data-aos="fade-right">
                <div class="picture">
                    <img class="img-fluid" src="img/lazy.png" data-real-src="img/members/manju.webp">
                </div>
                <div class="team-content">
                    <h3 class="name">Dr. Manju Kumari</h3>
                    <h4 class="title">Faculty Co-ordinator</h4>
                </div>
                <ul class="social">
                    <li><a href="mailto:" class="fas fa-envelope" aria-hidden="true"></a></li>
                </ul>
            </div>
            <div class="our-team" data-aos-duration="1200" data-aos="fade-left">
                <div class="picture">
                    <img class="img-fluid" src="img/lazy.png" data-real-src="img/members/ranjeeta.webp">
                </div>
                <div class="team-content">
                    <h3 class="name">Dr. Ranjeeta Tiwari</h3>
                    <h4 class="title">Faculty Co-ordinator</h4>
                </div>
                <ul class="social">
                    <li><a href="mailto:" class="fas fa-envelope" aria-hidden="true"></a></li>
                </ul>
            </div>
            <div class="our-team" data-aos-duration="1200" data-aos="fade-right">
                <div class="picture">
                    <img class="img-fluid" src="img/lazy.png" data-real-src="img/members/shweta.webp">
                </div>
                <div class="team-content">
                    <h3 class="name">Prof. Shweta Chaubey</h3>
                    <h4 class="title">Faculty Co-ordinator</h4>
                </div>
                <ul class="social">
                    <li><a href="mailto:" class="fas fa-envelope" aria-hidden="true"></a></li>
                </ul>
            </div>
            <div class="our-team" data-aos-duration="1200" data-aos="fade-left">
                <div class="picture">
                    <img class="img-fluid" src="img/lazy.png" data-real-src="img/members/divya.webp">
                </div>
                <div class="team-content">
                    <h3 class="name">Dr. Divya Prabha</h3>
                    <h4 class="title">Faculty Co-ordinator</h4>
                </div>
                <ul class="social">
                    <li><a href="mailto:" class="fas fa-envelope" aria-hidden="true"></a></li>
                </ul>
            </div>

            <div class="our-team" data-aos-duration="1200" data-aos="fade-left">
                <div class="picture">
                    <img class="img-fluid" src="img/lazy.png" data-real-src="img/members/ritesh.webp">
                </div>
                <div class="team-content">
                    <h3 class="name">Ritesh Raj (2019-22)</h3>
                    <h4 class="title">IT Club Founder</h4>
                </div>
                <ul class="social">
                    <li><a href="mailto:" class="fas fa-envelope" aria-hidden="true"></a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- PAST MEMBERS -->
    <div class="past-inductions">
        <h3 class="pastInducLabel">Past Inductions</h3>
      
  <div class="post2PastInductions">
        <?php
  $query_unique_inductions = "SELECT DISTINCT member_induction FROM members WHERE member_induction NOT IN ($inductionList)";
  $result_unique_inductions = mysqli_query($mysqli, $query_unique_inductions);

  while($row_unique_inductions = mysqli_fetch_assoc($result_unique_inductions)) {
    $induction = $row_unique_inductions['member_induction'];

    $query_members = "SELECT * FROM members WHERE member_induction='$induction'";
    $result_members = mysqli_query($mysqli, $query_members);

    echo '<div class="induction">';
    echo '<h2>' . ordinal($induction+1) . ' Induction Members</h2>';
    echo '<div class="inductionMembers" data-aos-duration="1200" data-aos="fade-left">';

    while($row_members = mysqli_fetch_assoc($result_members)) {
      echo '<div class="inductionMember">';
      echo '<img src="img/members/' . $row_members['member_pfpurl'] . '" alt="" />';
      echo '<p>' . $row_members['member_name'] . '</p>';
      echo '<p>' . $row_members['member_category'] . '</p>';
      echo '<p>' . $row_members['member_session'] . ' (' . $row_members['member_branch'] . ')</p>';
      ?>
      <ul class="past_social-icons">
                    <?php
                    if ($row_members['member_instagram'] != '') { ?>
                        <li><a target="_blank" href="<?php echo $row_members['member_instagram']; ?>"><i
                                    class="fab fa-instagram"></i></a></li>
                    <?php }
                    ?>
                    <?php
                    if ($row_members['member_linkedin'] != '') { ?>
                        <li><a target="_blank" href="<?php echo $row_members['member_linkedin']; ?>"><i
                                    class="fab fa-linkedin"></i></a></li>
                    <?php }
                    ?>
                    <?php
                    if ($row_members['member_email'] != '') { ?>
                        <li><a target="_blank" href="mailto:<?php echo $row_members['member_email']; ?>"><i
                                    class="fas fa-envelope"></i></a></li>
                    <?php }

                    ?>

                </ul>
      <?php
      echo '</div>';
    }

    echo '</div>';
    echo '</div>';
  }
?>
    </div>
    
        <div class="2nd toggle-button">
            <h4 class="toggle-button-h"><i class="fa-solid fa-circle-chevron-right"></i>&nbsp; 2nd Induction
            </h4>
            <ul class="member-list">
                <li>
                    <strong>TEAM LEADER</strong>
                    <ul>
                        <li>Satyam Kumar Singh_EE (2020-23)</li>
                    </ul>
                </li>
                <li>
                    <strong>TEAM MEMBERS</strong>
                    <ul>
                        <li>
                            <strong>Content Writing:</strong>
                            <ul>
                                <li>Deep Shree_EC (2020-23) - Category Head</li>
                                <li>Hrithik Raj_ME (2020-23) - Category Head</li>
                                <li>Payal Rana_CSE (2021-24)</li>
                                <li>Sawli Bharti_CSE (2021-24)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Video Editing:</strong>
                            <ul>
                                <li>Govind Kumar Singh_CSE (2020-23) - Category Head</li>
                                <li>Satyam Kumar Singh_EE (2020-23)</li>
                                <li>Raushan Kumar_EE (2021-24)</li>
                                <li>Rajveer Kumar_EE (2021-24)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Graphic Designing:</strong>
                            <ul>
                                <li>Mona Kumari_EC (2020-23) - Category Head</li>
                                <li>Md. Modassir Raza_EE (2021-24)</li>
                                <li>Jyoti Kumari_CSE (2021-24)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Photography:</strong>
                            <ul>
                                <li>Mukesh Kumar Choudhary_EC (2020-23) - Category Head</li>
                                <li>Raushan Ragya_CSE (2020-23)</li>
                                <li>Vivek Kumar_CSE (2021-24)</li>
                                <li>Binit Kumar_EC (2021-24)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Technical Team:</strong>
                            <ul>
                                <li>Aniektan Kumar Singh_EC (2020-23) - Category Head</li>
                                <li>Priya Kumari_ME (2021-24)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Website Maintenance:</strong>
                            <ul>
                                <li>Swati Sinha_CSE (2020-23)</li>
                                <li>Satyam Kumar Singh_EE (2020-23)</li>
                                <li>Vivek Kumar_CSE (2021-24)</li>
                                <li>Md. Modassir Raza_EE (2021-24)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Social Media Handling:</strong>
                            <ul>
                                <li>Nikku Kumar Singh_EC (2020-23)</li>
                                <li>Raunit_EE (2021-24)</li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="1st toggle-button">
            <h4 class="toggle-button-h"><i class="fa-solid fa-circle-chevron-right"></i>&nbsp; 1st Induction
            </h4>
            <ul class="member-list">
                <li>
                    <strong>TEAM LEADER</strong>
                    <ul>
                        <li>Ritesh Raj_AU (2019-22)</li>
                    </ul>
                </li>
                <li>
                    <strong>TEAM MEMBERS</strong>
                    <ul>
                        <li>
                            <strong>Content Writing:</strong>
                            <ul>
                                <li>Aisha_EL (2019-22) - Category Head</li>
                                <li>Nitish Prakash_AU (2019-22) - Category Head</li>
                                <li>Sangam Raj_EE (2019-22)</li>
                                <li>Deep Shree_EC (2020-2023)</li>
                                <li>Hrithik Raj_ME (2020-2023)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Video Editing:</strong>
                            <ul>
                                <li>Sushil Kumar Singh_ME (2019-22) - Category Head</li>
                                <li>Mona Kumari_EC (2020-23)</li>
                                <li>Gautam Gambhir_CSE (2019-22)</li>
                                <li>Rajnish Kumar_CSE (2020-23)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Graphic Designing:</strong>
                            <ul>
                                <li>Mohit Kumar_C (2019-22) - Category Head</li>
                                <li>Sushil Kumar Singh_ME (2019-22)</li>
                                <li>Mona Kumari_EC (2020-23)</li>
                                <li>Satyam Kumar Singh_EL (2020-23)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Photography:</strong>
                            <ul>
                                <li>Aman Shankar_CSE (2019-22) - Category Head</li>
                                <li>Mukesh kr. Chaudhary_EC (2020-23)</li>
                                <li>Swati Sinha_CSE (2020-23)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Technical Team:</strong>
                            <ul>
                                <li>Aashish Kumar_EC_15 (2019-22) - Category Head</li>
                                <li>Amar Kumar_ME_35 (2019-22) - Category Head</li>
                                <li>Aman Shankar_CSE_12 (2019-22)</li>
                                <li>Mona Kumari_EC_7 (2020-2023)</li>
                                <li>Payal Kumari_CSE_36 (2019-2022)</li>
                                <li>Aniketan Kumar Singh_EC_32 (2020-2023)</li>
                            </ul>
                        </li>
                        <li>
                            <strong>Web Designing:</strong>
                            <ul>
                                <li>Tushar Kant_A_13 (2019-22) - Category Head</li>
                                <li>Piyush Kumar_M_21 (2019-2022)</li>
                                <li>Deep Shree_EC_08 (2020-23)</li>
                                <li>Aditya Kumar_C_31 (2020-23)</li>
                            </ul>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
  </section>

    <?php  include 'footer.html'; ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script>
        $(document).ready(function () {

            $('img[src="img/lazy.png"]').each(function (index, el) {
                $(el).attr('src', $(el).data('real-src'));
            })
        });

        $(document).ready(function () {
            $(".toggle-button").click(function () {
                $(this).find(".member-list").slideToggle();
                $(this).find("i").toggleClass('rotate-90')
            });
        });
// Get all image elements on the page
const images = document.querySelectorAll('img');
images.forEach((image) => {
  image.setAttribute('loading', 'lazy');
});


(function($) {
    $.fn.scrollContent = function(direction, speed) {
        return this.each(function() {
            var $this = $(this);
            var scrollProps = {};
            var isScrollingForward = true;

            function scroll() {
                if (direction === 'x') {
                    if (isScrollingForward) {
                        scrollProps.scrollLeft = $this.prop('scrollWidth') - $this.width();
                    } else {
                        scrollProps.scrollLeft = 0;
                    }
                } else if (direction === 'y') {
                    if (isScrollingForward) {
                        scrollProps.scrollTop = $this.prop('scrollHeight') - $this.height();
                    } else {
                        scrollProps.scrollTop = 0;
                    }
                } else {
                    console.error('Invalid direction. Use "x" for horizontal or "y" for vertical.');
                    return;
                }

                $this.animate(scrollProps, speed, function() {
                    isScrollingForward = !isScrollingForward;
                    scroll();
                });
            }

            scroll();
        });
    };
})(jQuery);


$('.coords').scrollContent('x', 40000)
$('.inductionMembers').scrollContent('x', 40000)
    </script>

</body>

</html>