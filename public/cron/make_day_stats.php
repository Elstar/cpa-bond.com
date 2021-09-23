<?php
//запускается раз в день в 00:00 по Киеву

ini_set("display_errors", "1");
error_reporting(E_ALL);
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../classes/class.pdoHelper.php';
include_once __DIR__ . '/../classes/class.pdoHelperSlave.php';
include_once __DIR__ . '/../classes/class.settings.php';

$base = [
    'pre_landing_unique_visits' => 0,
    'landing_unique_visits' => 0,
    'pre_landing_page_unique_visits' => 0,
    'pre_landing_visits' => 0,
    'landing_visits' => 0,
    'pre_landing_page_visits' => 0,
    'new_lead_count' => 0,
    'rejected_lead_count' => 0,
    'accepted_lead_count' => 0,
    'fake_lead_count' => 0
];

function setVisits(array $stats, array $visitor): array
{
    if ($visitor['pre_landing_visits']) {
        $stats['pre_landing_unique_visits']++;
        $stats['pre_landing_visits'] += $visitor['pre_landing_visits'];
    }
    if ($visitor['landing_visits']) {
        $stats['landing_unique_visits']++;
        $stats['landing_visits'] += $visitor['landing_visits'];
    }
    if ($visitor['pre_landing_page_visits']) {
        $stats['pre_landing_page_unique_visits']++;
        $stats['pre_landing_page_visits'] += $visitor['pre_landing_page_visits'];
    }

    return $stats;
}

function setLeads(array $stats, array $lead): array
{

    switch ($lead['status']) {
        case 0:
            $stats['new_lead_count']++;
            break;
        case 1:
            $stats['rejected_lead_count']++;
            break;
        case 2:
            $stats['accepted_lead_count']++;
            break;
        case 3:
            $stats['fake_lead_count']++;
            break;
        default:
            break;
    }
    $stats['leads']++;

    return $stats;
}

function setStatInsert(int $user_id, string $yesterday, array $stat, int $stream_id = 0, int $offer_id = 0): array
{
    $insert = [
        'user_id' => $user_id,
        'stream_id' => $stream_id,
        'offer_id' => $offer_id,
        'day' => $yesterday
    ];
    return array_merge($insert, $stat);
}

$today = date("Y-m-d H:i:s", mktime(0, 0, 0, date('m'), date('d'), date('Y')));
$yesterday = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));

$year = date("Y", $yesterday);
$month = date("n", $yesterday);
$yesterday = date("Y-m-d H:i:s", $yesterday);

$dayStats = pdoHelper::getInstance()->getStmt("SELECT * FROM day_stats WHERE created_at < ?", [$today], 1);
$streams = [];
$stats = [];
$copyAllDayStats = [];
$AllDayStatsIterator = 0;

while ($visitor = $dayStats->fetch()) {

    $user_id = $visitor['user_id'];
    $stream_id = (int)$visitor['stream_id'];
    $copyAllDayStats[] = $visitor;
    if (!isset($streams[$stream_id])) {
        $streams[$stream_id] = pdoHelperSlave::getInstance()->selectRow("SELECT offer_id FROM stream WHERE id=?",
            [$stream_id]);
    }
    $offer_id = $streams[$stream_id]['offer_id'];

    if (!isset($stats[$user_id])) {
        $stats[$user_id]['all'] = $base;
    }
    $stats[$user_id]['all'] = setVisits($stats[$user_id]['all'], $visitor);

    if ($stream_id) {
        if (!isset($stats[$user_id]['streams'][$stream_id])) {
            $stats[$user_id]['streams'][$stream_id] = $base;
        }
        $stats[$user_id]['streams'][$stream_id] = setVisits($stats[$user_id]['streams'][$stream_id], $visitor);
    }
    if ($offer_id) {
        if (!isset($stats[$user_id]['offers'][$offer_id])) {
            $stats[$user_id]['offers'][$offer_id] = $base;
        }
        $stats[$user_id]['offers'][$offer_id] = setVisits($stats[$user_id]['offers'][$offer_id], $visitor);
    }

    //copy 100 rows of day stats
    if ($AllDayStatsIterator > 100) {
        pdoHelperSlave::getInstance()->insert(
            "day_stats_{$year}_{$month}",
            array_keys($copyAllDayStats[0]),
            $copyAllDayStats
        );
        $copyAllDayStats = [];
        $AllDayStatsIterator = 0;
    }
    $AllDayStatsIterator++;
}

if (!empty($stats)) {
    foreach ($stats as $user_id => $stat) {
        $leads = pdoHelper::getInstance()->selectRows("SELECT * FROM `lead` WHERE (user_id=?) AND (created_at BETWEEN ? AND ?)",
            [$user_id, $yesterday, $today]);
        if (!empty($leads)) {
            foreach ($leads as $lead) {
                $stats[$user_id]['all'] = setLeads($stats[$user_id]['all'], $lead);
                if ($lead['stream_id']) {
                    if (!isset($stats[$user_id]['streams'][$lead['stream_id']])) {
                        $stats[$user_id]['streams'][$lead['stream_id']] = $base;
                    }
                    $stats[$user_id]['streams'][$lead['stream_id']] = setLeads($stats[$user_id]['streams'][$lead['stream_id']], $lead);
                }
                if ($lead['offer_id']) {
                    if (!isset($stats[$user_id]['offers'][$lead['offer_id']])) {
                        $stats[$user_id]['offers'][$lead['offer_id']] = $base;
                    }
                    $stats[$user_id]['offers'][$lead['offer_id']] = setLeads($stats[$user_id]['offers'][$lead['offer_id']], $lead);
                }
            }
        }
        $insertStats = [];
        $insertStats[] = setStatInsert($user_id, $yesterday, $stats[$user_id]['all']);
        if (!empty($stats[$user_id]['streams'])) {
            foreach ($stats[$user_id]['streams'] as $stream_id => $stream) {
                $insertStats[] = setStatInsert($user_id, $yesterday, $stream, $stream_id);
            }
        }
        if (!empty($stats[$user_id]['offers'])) {
            foreach ($stats[$user_id]['offers'] as $offer_id => $offer) {
                $insertStats[] = setStatInsert($user_id, $yesterday, $offer, 0, $offer_id);
            }
        }
        if (!empty($insertStats)) {
            pdoHelper::getInstance()->insert('stats', array_keys($insertStats[0]), $insertStats);
        }
    }
}

if (!empty($copyAllDayStats)) {
    pdoHelperSlave::getInstance()->insert(
        "day_stats_{$year}_{$month}",
        array_keys($copyAllDayStats[0]),
        $copyAllDayStats
    );
}

//Clear yesterday visits stats
pdoHelper::getInstance()->delete("DELETE FROM day_stats WHERE created_at < ?", [$today]);