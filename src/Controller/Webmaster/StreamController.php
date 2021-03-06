<?php

namespace App\Controller\Webmaster;

use App\Entity\Offer;
use App\Entity\Stream;
use App\Form\Webmaster\StreamFormType;
use App\Repository\LandingRepository;
use App\Repository\PreLandingPageRepository;
use App\Repository\PreLandingRepository;
use App\Repository\StreamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_USER")
 */
class StreamController extends AbstractController
{
    /**
     * @Route({"uk": "/uk/webmaster/stream", "ru": "/ru/webmaster/stream", "en": "/en/webmaster/stream"}, name="app_webmaster_stream")
     */
    public function index(
        StreamRepository $streamRepository,
        PaginatorInterface $paginator,
        Request $request,
        ContainerInterface $container
    ): Response {

        $pagination = $paginator->paginate(
            $streamRepository->findStreamsByUserQuery($this->getUser()),
            $request->query->getInt('page', 1),
            $container->getParameter('count_streams_in_page')
        );

        return $this->render('webmaster/stream/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route({"uk": "/uk/webmaster/stream/create/{id}", "ru": "/ru/webmaster/stream/create/{id}", "en": "/en/webmaster/stream/create/{id}"}, name="app_webmaster_stream_create")
     */
    public function create(Offer $offer, EntityManagerInterface $em, Request $request): Response
    {
        $stream = new Stream();
        $stream
            ->setOffer($offer)
            ->setSum($offer->getPaySum())
            ->setPayPercent($offer->getPayPercent())
            ->setUser($this->getUser());
        $streamForm = $this->createForm(StreamFormType::class, $stream);

        if ($this->handleFormRequest($streamForm, $em, $request)) {
            $this->addFlash('flash_message', 'Stream added successfully');
        }

        return $this->render('webmaster/stream/create.html.twig', [
            'offer' => $offer,
            'streamForm' => $streamForm->createView()
        ]);
    }

    /**
     * @Route({"uk": "/uk/webmaster/stream/edit/{id}", "ru": "/ru/webmaster/stream/edit/{id}", "en": "/en/webmaster/stream/edit/{id}"}, name="app_webmaster_stream_edit")
     */
    public function edit(Stream $stream, EntityManagerInterface $em, Request $request): Response
    {
        $streamForm = $this->createForm(StreamFormType::class, $stream);

        if ($this->handleFormRequest($streamForm, $em, $request)) {
            $this->addFlash('flash_message', 'Stream edited successfully');
        }

        return $this->render('webmaster/stream/create.html.twig', [
            'streamForm' => $streamForm->createView()
        ]);
    }

    /**
     * @Route({"uk": "/uk/webmaster/stream/delete/{id}", "ru": "/ru/webmaster/stream/delete/{id}", "en": "/en/webmaster/stream/delete/{id}"}, name="app_webmaster_stream_delete")
     */
    public function delete(Stream $stream, StreamRepository $streamRepository): Response
    {
        if ($stream->getUser() == $this->getUser()) {
            if ($streamRepository->deleteStream($stream)) {
                return $this->redirectToRoute('app_webmaster_stream');
            }
        }
    }

    /**
     * @param FormInterface $form
     * @param EntityManagerInterface $em
     * @param Request $request
     * @return Stream|null
     */
    private function handleFormRequest(FormInterface $form, EntityManagerInterface $em, Request $request): ?Stream
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var Stream $stream
             */
            $stream = $form->getData();

            $stream->setUrl();

            $em->persist($stream);
            $em->flush();

            return $stream;
        }
        return null;
    }
}
