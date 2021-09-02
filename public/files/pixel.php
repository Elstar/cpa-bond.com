<?php
header('Content-type: image/png');
ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../classes/class.pdoHelper.php';

function getIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arIp = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $ip = $arIp[0];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$pixel = gzinflate(base64_decode('6wzwc+flkuJiYGDg9fRwCQLSjCDMwQQkJ5QH3wNSbCVBfsEMYJC3jH0ikOLxdHEMqZiTnJCQAOSxMDB+E7cIBcl7uvq5rHNKaAIA'));

$streamId = 0;

if (isset($_GET['stream_id'])) {
    $streamId = trim(strip_tags($_GET['stream_id']));
    $stream = pdoHelper::getInstance()->selectRow('SELECT * FROM stream WHERE unique_id=?', [$streamId]);
}
$preLandingId = 0;
if (isset($_GET['pre_landing_id'])) {
    $preLandingId = (int)$_GET['pre_landing_id'];
}
$landingId = 0;
if (isset($_GET['landing_id'])) {
    $landingId = (int)$_GET['landing_id'];
}
$preLandingPageId = 0;
if (isset($_GET['pre_landing_page_id'])) {
    $preLandingPageId = (int)$_GET['pre_landing_page_id'];
}

if ($streamId && !empty($stream)) {
    $ip = getIp();
    $ipBinary = inet_pton($ip);
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $uaHash = md5($ua);

    $visit = pdoHelper::getInstance()->selectRow('SELECT * FROM day_stats WHERE ip=? AND ua_hash=?',
        [$ipBinary, $uaHash]);
    $timeStr = date('Y-m-d H:i:s');

    if (empty($visit)) {
        $insert = [
            'stream_id' => $stream['id'],
            'user_id' => $stream['user_id'],
            'ip' => $ipBinary,
            'ua' => $ua,
            'ua_hash' => $uaHash,
            'created_at' => $timeStr,
            'updated_at' => $timeStr,
            'ref' => $_SERVER['HTTP_REFERER']
        ];

        if ($preLandingId && $preLandingId == $stream['pre_landing_id']) {
            $insert['pre_landing_visits'] = 1;
        } elseif ($landingId && $landingId == $stream['landing_id']) {
            $insert['landing_visits'] = 1;
        } elseif ($preLandingPageId && $preLandingPageId == $stream['pre_landing_page_id']) {
            $insert['pre_landing_page_visits'] = 1;
        }

        pdoHelper::getInstance()->insert('day_stats', array_keys($insert), $insert);
    } else {
        $updateField = '';
        if ($preLandingId && $preLandingId == $stream['pre_landing_id']) {
            $updateField = ', pre_landing_visits=pre_landing_visits+1';
        } elseif ($landingId && $landingId == $stream['landing_id']) {
            $updateField = ', landing_visits=landing_visits+1';
        } elseif ($preLandingPageId && $preLandingPageId == $stream['pre_landing_page_id']) {
            $updateField = ', pre_landing_page_visits=pre_landing_page_visits+1';
        }
        pdoHelper::getInstance()->update("UPDATE day_stats SET visits=visits+1{$updateField}, updated_at=? WHERE id=?",
            [$timeStr, $visit['id']]);
    }
}

print $pixel;
exit;



