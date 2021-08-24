<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Contracts\Translation\TranslatorInterface;

class ApiTokenAuthenticator extends AbstractAuthenticator
{
    private UserRepository $userRepository;
    private TranslatorInterface $translator;


    /**
     * ApiTokenAuthenticator constructor.
     */
    public function __construct(UserRepository $userRepository, TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->translator = $translator;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') && 0 === strpos($request->headers->get('Authorization'),
                'Bearer ');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $apiToken = substr($request->headers->get('Authorization'), 7);
        if (null === $apiToken) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        return new SelfValidatingPassport(new UserBadge($apiToken, function ($userIdentifier) {
            return $this->userRepository->findOneBy(['apiToken' => $userIdentifier]);
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => $this->translator->trans('Authentication failed')
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
