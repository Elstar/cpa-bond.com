<?php

namespace App\Controller\Api;

use App\Entity\Stream;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StreamController extends AbstractController
{
    /**
     * @Route("/api/v1/get-stream/{uniqueId}", name="api_stream")
     */
    public function index(?Stream $stream, Request $request): Response
    {
        $status = 200;
        if ($request->getMethod() != 'GET') {
            $dataError = [
                'message' => 'You need GET method for get stream'
            ];
            $status = Response::HTTP_METHOD_NOT_ALLOWED;
        }
        if (empty($stream)) {
            $dataError = [
                'message' => 'Empty data'
            ];
            $status = Response::HTTP_BAD_REQUEST;
        } else {
            $dataSuccess = [
                'id' => $stream->getId(),
                'offer_id' => $stream->getOffer()->getId(),
                'landing' => $stream->getLanding()->getUrl(),
                'google_tag_id' => $stream->getGoogleTagId(),
                'google_tag_conversion_id' => $stream->getGoogleTagConversionId()
            ];
        }
        if (!empty($dataError)) {
            $dataError['status'] = 'error';
            return new JsonResponse($dataError, $status);
        }
        return $this->json($dataSuccess, $status, []);
    }
}
