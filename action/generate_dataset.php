<?php
include_once("conn.php");
$filePath = 'components/dataset.txt';

$baseUrl = "https://itclub.000.pe";
// Introductory text
$intro = "IT Club Raw Data Starts Here >>>\n\n";

$file = fopen($filePath, 'w');
fwrite($file, $intro);

// Homepage

function getWebpageContent($url, $stripTags = true) {
    $htmlContent = file_get_contents($url);

    if ($htmlContent === FALSE) {
        return "Error fetching the URL content.";
    }

    if ($stripTags) {
        $htmlContent = strip_tags($htmlContent);
    }

    $htmlContent = preg_replace('/^\h*\v+/m', '', $htmlContent);

    return $htmlContent;
}


$visibleTextContent = getWebpageContent('./index.php', true);
fwrite($file, $visibleTextContent);

// FAQs
$FaqsFilePath = 'components/faqs.json';
$faqsData = file_get_contents($FaqsFilePath);
$faqsData = json_decode($faqsData, true);
fwrite($file, "\n\nFAQs:- \n\n");
foreach ($faqsData['faqs'] as $faq) {
    $question = $faq['question'];
    $answer = $faq['answer'];
    fwrite($file, "Question: $question\nAnswer: $answer\n\n");
}

//Site stats
$siteStats = json_decode(file_get_contents('admin/site_stats.json'), true);

fwrite($file, "These are the total till now views or traffic on this website - ". $siteStats['page_views']." and this is of IT Club AI page total views - ". $siteStats['itclub_ai']."\n\n");

//Notices
$noticeSql = mysqli_query($mysqli, "SELECT notice_id, notice_imgurl, notice_title,notice_timestamp from notices order by notice_timestamp desc");
$noticeContent = "These are the notices and notice details: \n Format: {Notice Title, Notice Url, Notice Image Url, Notice Created at Timestamp}\n";
while($notice = mysqli_fetch_assoc($noticeSql)){
  $title = $notice['notice_title'];
  $id = $notice['notice_id'];
  $imgUrl = 'https://itclub.000.pe/img/notices/'.$notice['notice_imgurl'];
  $timestamp = $notice['notice_timestamp'];
  $timestamp = date("d M Y H:i:s", strtotime($timestamp));
  
  $noticeContent .= "{".$title.", ".$baseUrl."/notice.php?notice_id=".$id.", ". $imgUrl .", ".$timestamp."}\n";
}
  fwrite($file, $noticeContent);
  
//Events
$eventSql = mysqli_query($mysqli, "SELECT event_id, event_imgurl, event_title, event_date, event_gphotoslink, event_ytlink from events order by event_queryTimestamp desc");
$eventContent = "\nThese are the events and events details:\n Format: {Event Title, Event Url, Event Image Url, Event Date, Event Google Photos Links: {Photos links (commas seperated if multiple)}, Event YouTube Video Links: {Video Links(commas seperated if multiple)}}\n";
while($event = mysqli_fetch_assoc($eventSql)){
  $title = $event['event_title'];
  $id = $event['event_id'];
  $imgUrl = 'https://itclub.000.pe/img/events/'.$event['event_imgurl'];
  $date = $event['event_date'];
  $gphotolinks = $event['event_gphotoslink'];
  $ytlinks = $event['event_ytlink'];
  
  $eventContent .= "{".$title.", ".$baseUrl."/event.php?event_id=".$id.", ".$imgUrl.", " . $date . "Event Google Photos Links: {".$gphotolinks."}, Event YouTube Video Links: {".$ytlinks."}}\n";
}
fwrite($file, $eventContent);

// IT Club Members
$membSql = mysqli_query($mysqli, "SELECT * FROM members order by member_induction desc");
$membContent = "\nThese are the IT Club Members and their details: \n";
$membContent .= "Format: { Name, [Photo url], Category, Branch, Session, Email, LinkedIn, Instagram, Designation, Induction, Team Leader, Club Leader, Second Category (If Applicable)}\n";

while ($memb = mysqli_fetch_assoc($membSql)) {
    /*
    Name
    Category
    Branch
    Session
    Email
    LinkedIn
    Instagram
    Designation
    Induction
    Team Leader
    Club Leader
    Second Category (If Applicable)
    */

    $name = $memb['member_name'];
    $pfpurl = 'https://itclub.000.pe/img/members/'.$memb['member_pfpurl'];
    $category = $memb['member_category'];
    $branch = $memb['member_branch'];
    $session = $memb['member_session'];
    $email = !empty($memb['member_email']) ? $memb['member_email'] : 'Not available';
    $linkedin = !empty($memb['member_linkedin']) ? $memb['member_linkedin'] : 'Not available';
    $instagram = !empty($memb['member_instagram']) ? $memb['member_instagram'] : 'Not available';
    $designation = $memb['member_designation'];
    $induction = $memb['member_induction'];
    $teamLeader = $memb['member_isTeamLeader'];
    $clubLeader = $memb['member_isClubLeader'];
    $secondCategory = !empty($memb['member_category2']) ? $memb['member_category2'] : 'No any 2nd Category';

    // Handle Team Leader and Club Leader
    $teamLeaderStatus = ($teamLeader == 1) ? "$category Leader of ". $induction+1 ." Induction (Generation)" : 'Not Team Leader';
    $clubLeaderStatus = ($clubLeader == 1) ? "Club Leader of ".$induction+1 ." Induction or Generation" : 'Not Club Leader';
    $membContent .= "{ $name, $pfpurl, $category, $branch, $session, $email, $linkedin, $instagram, $designation, $induction, $teamLeaderStatus, $clubLeaderStatus, $secondCategory}\n";
}
$membContent .= "
Past induction members of 1st and 2nd induction in unstructured way :-
Past Inductions
2nd Induction >>>
TEAM LEADER
Satyam Kumar Singh_EE (2020-23)

TEAM MEMBERS

Content Writing:
Deep Shree_EC (2020-23) - Category Head
Hrithik Raj_ME (2020-23) - Category Head
Payal Rana_CSE (2021-24)
Sawli Bharti_CSE (2021-24)

Video Editing:
Govind Kumar Singh_CSE (2020-23) - Category Head
Satyam Kumar Singh_EE (2020-23)
Raushan Kumar_EE (2021-24)
Rajveer Kumar_EE (2021-24)

Graphic Designing:
Mona Kumari_EC (2020-23) - Category Head
Md. Modassir Raza_EE (2021-24)
Jyoti Kumari_CSE (2021-24)

Photography:
Mukesh Kumar Choudhary_EC (2020-23) - Category Head
Raushan Ragya_CSE (2020-23)
Vivek Kumar_CSE (2021-24)
Binit Kumar_EC (2021-24)

Technical Team:
Aniektan Kumar Singh_EC (2020-23) - Category Head
Priya Kumari_ME (2021-24)

Website Maintenance:
Swati Sinha_CSE (2020-23)
Satyam Kumar Singh_EE (2020-23)
Vivek Kumar_CSE (2021-24)
Md. Modassir Raza_EE (2021-24)

Social Media Handling:
Nikku Kumar Singh_EC (2020-23)
Raunit_EE (2021-24)
<<<
  
1st Induction >>>

TEAM LEADER
Ritesh Raj_AU (2019-22)

TEAM MEMBERS

Content Writing:
Aisha_EL (2019-22) - Category Head
Nitish Prakash_AU (2019-22) - Category Head
Sangam Raj_EE (2019-22)
Deep Shree_EC (2020-2023)
Hrithik Raj_ME (2020-2023)

Video Editing:
Sushil Kumar Singh_ME (2019-22) - Category Head
Mona Kumari_EC (2020-23)
Gautam Gambhir_CSE (2019-22)
Rajnish Kumar_CSE (2020-23)

Graphic Designing:
Mohit Kumar_C (2019-22) - Category Head
Sushil Kumar Singh_ME (2019-22)
Mona Kumari_EC (2020-23)
Satyam Kumar Singh_EL (2020-23)

Photography:
Aman Shankar_CSE (2019-22) - Category Head
Mukesh kr. Chaudhary_EC (2020-23)
Swati Sinha_CSE (2020-23)

Technical Team:
Aashish Kumar_EC_15 (2019-22) - Category Head
Amar Kumar_ME_35 (2019-22) - Category Head
Aman Shankar_CSE_12 (2019-22)
Mona Kumari_EC_7 (2020-2023)
Payal Kumari_CSE_36 (2019-2022)
Aniketan Kumar Singh_EC_32 (2020-2023)

Web Designing:
Tushar Kant_A_13 (2019-22) - Category Head
Piyush Kumar_M_21 (2019-2022)
Deep Shree_EC_08 (2020-23)
Aditya Kumar_C_31 (2020-23)";
fwrite($file, $membContent);

// Blogs
$blogSql = mysqli_query($mysqli, "SELECT id, title, metaDesc, tags, views, likes, publishTime, adminId FROM blogs order by createdTime desc");
$blogContent = "\nThese are the blog details:\n Format: {Title, Blog url, Meta Description, Tags, Views, Likes, Publish Time, Author Name}\n";

while ($blog = mysqli_fetch_assoc($blogSql)) {
    $blogId = $blog['id'];
    $title = !empty($blog['title']) ? $blog['title'] : 'Not Available';
    $metaDesc = !empty($blog['metaDesc']) ? $blog['metaDesc'] : 'Not Available';
    $tags = !empty($blog['tags']) ? $blog['tags'] : 'Not Available';
    $views = !empty($blog['views']) ? $blog['views'] : '0';
    $likes = !empty($blog['likes']) ? $blog['likes'] : '0';
    $publishTime = $blog['publishTime'];
    $adminId = $blog['adminId'];

    // Fetch admin_name from adminCreds
    $adminNameSql = mysqli_query($mysqli, "SELECT admin_name FROM adminCreds WHERE admin_id = $adminId");
    $adminName = mysqli_fetch_assoc($adminNameSql)['admin_name'];

    // Append to blog content
    $blogContent .= "{"."$title, $baseUrl/blogs/blog.php?blogid=$blogId, $metaDesc, $tags, $views, $likes, $publishTime, $adminName"."}\n";
}

fwrite($file, $blogContent);


function getImages($dir, $includeFolders) {
    $images = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        $filePath = $file->getPathname();
        $pathParts = pathinfo($filePath);
        if (isset($pathParts['extension']) && in_array(strtolower($pathParts['extension']), ['wpv', 'jpg', 'jpeg', 'png', 'spg', 'gif'])) {
            $include = false;
            foreach ($includeFolders as $includeFolder) {
                if (strpos($filePath, DIRECTORY_SEPARATOR . $includeFolder . DIRECTORY_SEPARATOR) !== false) {
                    $include = true;
                    break;
                }
            }
            if ($include) {
                $images[] = $filePath;
            }
        }
    }
    return json_encode($images);
}

$rootDir = './';
$includeFolders = ['img/gallery'];
$images = getImages($rootDir, $includeFolders);

fwrite($file, "\nThese are the gallery images - ".$images);
fwrite($file, "IT Club logo: https://itclub.000.pe/img/itclublogo.webp");
fwrite($file, "NGPP (New Government Polytechnic Patna 13) logo: https://itclub.000.pe/img/ngpplogo.jpg");

fwrite($file, "\n<<< Raw Data Ended Here");

fclose($file);

$dataset = file_get_contents($filePath);
?>