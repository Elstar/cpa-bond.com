<?php

namespace App\Service;

use App\Entity\DayStats;
use App\Entity\Lead;
use App\Entity\Stats;
use App\Repository\LeadRepository;

class StatsBuilder
{
    private LeadRepository $leadRepository;
    private string $primaryKey;
    private string $groupKey;

    public function __construct(LeadRepository $leadRepository)
    {
        $this->leadRepository = $leadRepository;
    }

    /**
     * @param $stats Stats[]
     */
    public function buildStats(?array $stats, ?string $groupBy = '', ?string $separateBy = 'by_days'): array
    {
        $result = [];
        foreach ($stats as $stat) {
            /**
             * @var Stats $stat
             */
            $this->setPrimaryStatsKey($stat, $groupBy, $separateBy);

            if (!isset($result[$this->primaryKey][$this->groupKey])) {
                $this->setZero($result[$this->primaryKey][$this->groupKey]);
            }

            if ($stat->getPreLandingUniqueVisits()) {
                $result[$this->primaryKey][$this->groupKey]['uniq'] += $stat->getPreLandingUniqueVisits();
                $result[$this->primaryKey][$this->groupKey]['hits'] += $stat->getPreLandingVisits();
            } else {
                $result[$this->primaryKey][$this->groupKey]['uniq'] += $stat->getLandingUniqueVisits() + $stat->getPreLandingPageUniqueVisits();
                $result[$this->primaryKey][$this->groupKey]['hits'] += $stat->getLandingVisits() + $stat->getPreLandingPageVisits();
            }
            $result[$this->primaryKey][$this->groupKey]['pre_landing'] = $result[$this->primaryKey][$this->groupKey]['uniq'];

            $result[$this->primaryKey][$this->groupKey]['landing'] += $stat->getLandingUniqueVisits() + $stat->getPreLandingPageUniqueVisits();

            $result[$this->primaryKey][$this->groupKey]['leads'] += $stat->getLeads();
            $result[$this->primaryKey][$this->groupKey]['new_lead_count'] += $stat->getNewLeadCount();
            $result[$this->primaryKey][$this->groupKey]['rejected_lead_count'] += $stat->getRejectedLeadCount();
            $result[$this->primaryKey][$this->groupKey]['accepted_lead_count'] += $stat->getAcceptedLeadCount();
            $result[$this->primaryKey][$this->groupKey]['fake_lead_count'] += $stat->getFakeLeadCount();
            $result[$this->primaryKey][$this->groupKey]['payoff'] += $stat->getPayoff();
        }

        return $this->calcCr($result);
    }

    /**
     * @param $stats DayStats[]
     */
    public function buildTodayStats(?array $stats, ?string $groupBy = '', ?string $separateBy = 'by_days'): array
    {
        $result = [];
        foreach ($stats as $stat) {
            /**
             * @var DayStats $stat
             */
            $this->setPrimaryStatsKey($stat, $groupBy, $separateBy);

            if (!isset($result[$this->primaryKey][$this->groupKey])) {
                $this->setZero($result[$this->primaryKey][$this->groupKey]);
            }

            $result[$this->primaryKey][$this->groupKey]['uniq']++;
            $result[$this->primaryKey][$this->groupKey]['hits'] += $stat->getVisits();

            if ($stat->getPreLandingVisits()) {
                $result[$this->primaryKey][$this->groupKey]['pre_landing']++;
            }
            if ($stat->getLandingVisits() || $stat->getPreLandingPageVisits()) {
                $result[$this->primaryKey][$this->groupKey]['landing']++;
            }

        }
        if (!empty($result)) {
            $leads = $this->leadRepository->getUserLeadsForPeriod($stats[0]->getUser(), new \DateTime('today'),
                new \DateTime('now'));
            foreach ($leads as $lead) {
                /**
                 * @var Lead $lead
                 */
                $this->setPrimaryStatsKey($lead, $groupBy, $separateBy);
                $result[$this->primaryKey][$this->groupKey]['leads']++;
                switch ($lead->getStatus()) {
                    case 0:
                        $result[$this->primaryKey][$this->groupKey]['new_lead_count']++;
                        break;
                    case 1:
                        $result[$this->primaryKey][$this->groupKey]['rejected_lead_count']++;
                        break;
                    case 2:
                        $result[$this->primaryKey][$this->groupKey]['accepted_lead_count']++;
                        $result[$this->primaryKey][$this->groupKey]['payoff'] += $lead->getStream()->getSum();
                        break;
                    case 3:
                        $result[$this->primaryKey][$this->groupKey]['fake_lead_count']++;
                        break;
                }
            }
        }


        return $this->calcCr($result);
    }

    public function calcSum(array $result): array
    {
        $this->setZero($sum['sum'][0]);
        foreach ($result as $primaryKey => $primary) {
            foreach ($primary as $groupKey => $row) {
                $sum['sum'][0]['uniq'] += $row['uniq'];
                $sum['sum'][0]['hits'] += $row['hits'];

                $sum['sum'][0]['pre_landing'] += $row['pre_landing'];
                $sum['sum'][0]['landing'] += $row['landing'];

                $sum['sum'][0]['leads'] += $row['leads'];
                $sum['sum'][0]['new_lead_count'] += $row['new_lead_count'];
                $sum['sum'][0]['rejected_lead_count'] += $row['rejected_lead_count'];
                $sum['sum'][0]['accepted_lead_count'] += $row['accepted_lead_count'];
                $sum['sum'][0]['fake_lead_count'] += $row['fake_lead_count'];
                $sum['sum'][0]['payoff'] += $row['payoff'];
            }
        }
        $sum = $this->calcCr($sum);
        return $sum['sum'][0];
    }

    /**
     * @param Stats|DayStats|Lead $stat
     * @param string|null $groupBy
     * @param string|null $separateBy
     */
    private function setPrimaryStatsKey($stat, ?string $groupBy = '', ?string $separateBy = 'by_days')
    {
        $primaryKey = 0;
        $groupKey = 0;
        if ($separateBy == 'by_days') {
            if ($stat instanceof Stats) {
                $primaryKey = $stat->getDay()->format('d.m.Y');
            } elseif ($stat instanceof Lead) {
                $primaryKey = $stat->getCreatedAt()->format('d.m.Y');
            } else {
                $primaryKey = $stat->getCreatedAt()->format('d.m.Y');
            }
            if ($groupBy == 'by_offer') {
                if ($stat instanceof Stats) {
                    $groupKey = $stat->getOffer()->getName();
                } else {
                    $groupKey = $stat->getStream()->getOffer()->getName();
                }
            } elseif ($groupBy == 'by_stream') {
                $groupKey = $stat->getStream()->getName();
            }
        } else {
            if ($groupBy == 'by_offer') {
                $primaryKey = $stat->getStream()->getOffer()->getName();
            } elseif ($groupBy == 'by_stream') {
                $primaryKey = $stat->getStream()->getName();
            }
        }
        $this->primaryKey = $primaryKey;
        $this->groupKey = $groupKey;
    }

    private function calcCr(array $result): array
    {
        if (!empty($result)) {
            foreach ($result as $primaryKey => $primary) {
                foreach ($primary as $groupKey => $row) {
                    $result[$primaryKey][$groupKey]['cr_pre_land_land'] =
                        $row['landing'] ? round($row['pre_landing'] / $row['landing']) : $row['pre_landing'];
                    $result[$primaryKey][$groupKey]['cr_land_lead'] =
                        $row['leads'] ? round($row['landing'] / $row['leads']) : 0;
                    $result[$primaryKey][$groupKey]['cr_accept_lead'] =
                        ($row['accepted_lead_count'] && $row['leads']) ? round($row['accepted_lead_count'] / $row['leads'],2) * 100 : 0;
                }
            }
        }

        return $result;
    }

    private function setZero(&$result)
    {
        $result['uniq'] = 0;
        $result['hits'] = 0;
        $result['leads'] = 0;
        $result['new_lead_count'] = 0;
        $result['rejected_lead_count'] = 0;
        $result['accepted_lead_count'] = 0;
        $result['fake_lead_count'] = 0;
        $result['landing'] = 0;
        $result['pre_landing'] = 0;
        $result['payoff'] = 0;
    }
}