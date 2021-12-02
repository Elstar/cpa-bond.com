<?php

namespace App\Service;

use App\Entity\Lead;

class PostbackBuilder
{
    public function getPostbackLink(string $link_postback, Lead $lead): string
    {
        if ($link_postback) {
            $link_postback = str_replace(
                [
                    '{lead_id}',
                    '{status_lead}',
                    '{utm_source}',
                    '{utm_medium}',
                    '{utm_campaign}',
                    '{utm_content}',
                    '{utm_term}',
                    '{money}',
                    '{currency}'
                ],
                [
                    $lead->getUniqueId(),
                    $lead->getStatus(),
                    $lead->getStream()->getUniqueId(),
                    $lead->getUtmMedium(),
                    $lead->getUtmCampaign(),
                    $lead->getUtmContent(),
                    $lead->getUtmTerm(),
                    $lead->getStream()->getSum(),
                    $lead->getOffer()->getCurrency()->getName()
                ],
                $link_postback
            );
        }
        return $link_postback;
    }
}