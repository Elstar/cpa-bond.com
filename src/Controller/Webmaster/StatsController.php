<?php

namespace App\Controller\Webmaster;

use App\Repository\DayStatsRepository;
use App\Repository\LeadRepository;
use App\Repository\StatsRepository;
use App\Service\StatsBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    /**
     * @Route({"uk": "/uk/webmaster/stats", "ru": "/ru/webmaster/stats", "en": "/en/webmaster/stats"}, name="app_webmaster_stats")
     */
    public function index(
        StatsRepository $statsRepository,
        DayStatsRepository $dayStatsRepository,
        Request $request,
        StatsBuilder $statsBuilder
    ): Response {
        $today = $dateFrom = $dateTo = new \DateTime('today');
        if ($dateRange = $request->query->get('dateRange')) {
            $dateRange = explode(' - ', $dateRange);
            $dateFrom = new \DateTime($dateRange[0]);
            $dateTo = new \DateTime($dateRange[1]);
        }

        $groupBy = $request->query->get('group_by') ?? '';
        $byTime = $request->query->get('by_time') ?? 'by_days';

        if ($today <= $dateTo) {
            $dayStats = $dayStatsRepository->getStatsByUser($this->getUser());
            $statsToArray = $statsBuilder->buildTodayStats($dayStats, $groupBy, $byTime);
        }

        $stats = $statsRepository->getStats($this->getUser(), $dateFrom, $dateTo, $groupBy);

        if (!empty($statsToArray)) {
            $statsToArray = array_merge($statsToArray, $statsBuilder->buildStats($stats, $groupBy, $byTime));
        } else {
            $statsToArray = $statsBuilder->buildStats($stats, $groupBy, $byTime);
        }

        $sum = [];
        if (!empty($statsToArray)) {
            $sum = $statsBuilder->calcSum($statsToArray);
        }
        //dd($statsToArray);

        return $this->render('webmaster/stats/index.html.twig', [
            'stats' => $statsToArray,
            'sum' => $sum,
            'byTime' => $byTime,
            'groupBy' => $groupBy
        ]);
    }
}
