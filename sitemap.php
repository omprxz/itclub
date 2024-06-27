<?php
include('action/conn.php');

$blogSql = mysqli_query($mysqli, "SELECT id, lastUpdate, title FROM blogs WHERE visibility = 'public' AND approved > 0");
$blogs = [];
while ($row = mysqli_fetch_assoc($blogSql)) {
    $blogs[] = $row;
}

$noticeSql = mysqli_query($mysqli, "SELECT notice_id,notice_title, lastUpdated FROM notices");
$notices = [];
while ($row = mysqli_fetch_assoc($noticeSql)) {
    $notices[] = $row;
}

$eventSql = mysqli_query($mysqli, "SELECT event_id,event_title, lastUpdated FROM events");
$events = [];
while ($row = mysqli_fetch_assoc($eventSql)) {
    $events[] = $row;
}

$memberSql = mysqli_query($mysqli, "SELECT admin_username,admin_name, admin_joinedOn FROM adminCreds");
$members = [];
while ($row = mysqli_fetch_assoc($memberSql)) {
    $members[] = $row;
}

// Generating the sitemap XML
$currentWebsiteAddress = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];

$xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

//Default Pages
$defaultPages = [
  ['loc' => 'https://itclub.000.pe', 'lastmod' => '2023-10-21T19:31:00+05:30', 'title' => 'Homepage'],
  ['loc' => 'https://itclub.000.pe/members.php', 'lastmod' => '2023-10-21T19:31:00+05:30', 'title' => 'IT Club Members'],
  ['loc' => 'https://itclub.000.pe/blogs/index.php', 'lastmod' => '2023-10-21T19:31:00+05:30', 'title' => 'Blogs Homepage'],
  ['loc' => 'https://itclub.000.pe/faqs.php', 'lastmod' => '2023-10-21T19:31:00+05:30' , 'title' => 'FAQs'],
  ['loc' => 'https://itclub.000.pe/allnotice.php', 'lastmod' => '2023-10-21T19:31:00+05:30', 'title' => 'All Notices'],
  ['loc' => 'https://itclub.000.pe/ourevents.php', 'lastmod' => '2023-10-21T19:31:00+05:30', 'title' => 'Our Events'],
  ['loc' => 'https://itclub.000.pe/result.php', 'lastmod' => '2023-10-21T19:31:00+05:30', 'title' => 'IT Club Result'],
  ['loc' => 'https://itclub.000.pe/admin/', 'lastmod' => '2023-10-21T19:31:00+05:30', 'title' => 'Admin Panel']
  ];
  
foreach ($defaultPages as $page){
  $url = $page['loc'];
    $lastmod = $page['lastmod'];
    $title = $page['title'];
    
    $xml .= "\t<url>" . PHP_EOL;
    $xml .= "\t\t<loc>{$url}</loc>" . PHP_EOL;
    $xml .= "\t\t<title>{$title}</title>" . PHP_EOL;
    $xml .= "\t\t<lastmod>{$lastmod}</lastmod>" . PHP_EOL;
    $xml .= "\t</url>" . PHP_EOL;
}


foreach ($blogs as $blog) {
    $url = "$currentWebsiteAddress/blogs/blog.php?blogid={$blog['id']}";
    $title = $blog['title'];
    $lastmod = date('c',strtotime($blog['lastUpdate']));

    $xml .= "\t<url>" . PHP_EOL;
    $xml .= "\t\t<loc>{$url}</loc>" . PHP_EOL;
    $xml .= "\t\t<title>{$title}</title>" . PHP_EOL;
    $xml .= "\t\t<lastmod>{$lastmod}</lastmod>" . PHP_EOL;
    $xml .= "\t</url>" . PHP_EOL;
}

foreach ($notices as $notice) {
    if (isset($notice['notice_id']) && isset($notice['lastUpdated'])) {
        $url = "$currentWebsiteAddress/notice.php?notice_id={$notice['notice_id']}";
        $title = $notice['notice_title'];
        $lastmod = date('c',strtotime($notice['lastUpdated']));

        $xml .= "\t<url>" . PHP_EOL;
        $xml .= "\t\t<loc>{$url}</loc>" . PHP_EOL;
        $xml .= "\t\t<title>{$title}</title>" . PHP_EOL;
        $xml .= "\t\t<lastmod>{$lastmod}</lastmod>" . PHP_EOL;
        $xml .= "\t</url>" . PHP_EOL;
    }
}

foreach ($events as $event) {
    if (isset($event['event_id']) && isset($event['lastUpdated'])) {
        $url = "$currentWebsiteAddress/event/event.php?event_id={$event['event_id']}";
        $title = $event['event_title'];
        $lastmod = date('c',strtotime($event['lastUpdated']));

        $xml .= "\t<url>" . PHP_EOL;
        $xml .= "\t\t<loc>{$url}</loc>" . PHP_EOL;
        $xml .= "\t\t<title>{$title}</title>" . PHP_EOL;
        $xml .= "\t\t<lastmod>{$lastmod}</lastmod>" . PHP_EOL;
        $xml .= "\t</url>" . PHP_EOL;
    }
}

foreach ($members as $member) {
    if (isset($member['admin_username']) && isset($member['admin_joinedOn'])) {
        $url = "$currentWebsiteAddress/blogs/author.php?author={$member['admin_username']}";
        $title = "Admin - ".$member['admin_name'];
        $lastmod = date('c',strtotime($member['admin_joinedOn']));

        $xml .= "\t<url>" . PHP_EOL;
        $xml .= "\t\t<loc>{$url}</loc>" . PHP_EOL;
        $xml .= "\t\t<title>{$title}</title>" . PHP_EOL;
        $xml .= "\t\t<lastmod>{$lastmod}</lastmod>" . PHP_EOL;
        $xml .= "\t</url>" . PHP_EOL;
    }
}

$xml .= '</urlset>';

// Writing the XML content to a file (sitemap.xml)
$file = fopen("sitemap.xml", "w");
fwrite($file, $xml);
fclose($file);
header('Location: sitemap.xml');
?>