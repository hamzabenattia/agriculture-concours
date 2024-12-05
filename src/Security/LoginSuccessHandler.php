<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): ?RedirectResponse
    {
        $roles = $token->getRoleNames();
        $user = $token->getUser()->getFirstName();

        flash()->success('Connexion réussie. Bienvenue '. $user .'!');

        if (in_array(User::ROLE_ADMIN, $roles, true)) {
            $response = new RedirectResponse($this->router->generate('app_admin'));
        } else{
            $response = new RedirectResponse($this->router->generate('app_home'));

        }

        return $response;
    }
}
