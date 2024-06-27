<?php
$ipFilename = 'ip_addresses.txt';
$statsFilename = 'site_stats.json';

if (file_exists($ipFilename)) {
    $prevIPs = file($ipFilename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (file_exists($statsFilename)) {
        $data = json_decode(file_get_contents($statsFilename), true);
        
        // Update IP addresses
      //  $existingIPs = $data['ip_addresses'];
      $existingIPs=[];
        foreach ($prevIPs as $ip) {
                $existingIPs[] = $ip;
        }
        // Update total IP count in page_views
        $data['page_views'] = count($existingIPs);

        $data['ip_addresses'] = $existingIPs;
        
        file_put_contents($statsFilename, json_encode($data));
    }
}
?>