<?php

namespace App\Controller\Webmaster;

use App\Entity\Offer;
use App\Repository\CategoryRepository;
use App\Repository\GeoRepository;
use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class OfferController extends AbstractController
{
    /**
     * @Route({"uk": "/uk/webmaster/offer", "ru": "/ru/webmaster/offer", "en": "/en/webmaster/offer"}, name="app_webmaster_offer")
     */
    public function index(
        GeoRepository $geoRepository,
        CategoryRepository $categoryRepository,
        Request $request,
        PaginatorInterface $paginator,
        OfferRepository $offerRepository,
        ContainerInterface $container
    ): Response {
        $geos = $geoRepository->findAll();
        $categories = $categoryRepository->getTree();

        $pagination = $paginator->paginate(
            $offerRepository->findAllWithFiltersQuery($request->query->get('name'), $request->query->get('geo'),
                $request->query->get('category')),
            $request->query->getInt('page', 1),
            $container->getParameter('count_offers_in_page')
        );

        return $this->render('webmaster/offer/index.html.twig', [
            'categories' => $categories,
            'geos' => $geos,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route({"uk": "/uk/webmaster/offer/view/{id}", "ru": "/ru/webmaster/offer/view/{id}", "en": "/en/webmaster/offer/view/{id}"}, name="app_webmaster_offer_view")
     */
    public function view(Offer $offer): Response
    {
        return $this->render('webmaster/offer/view.html.twig', [
            'offer' => $offer,
        ]);
    }
}
