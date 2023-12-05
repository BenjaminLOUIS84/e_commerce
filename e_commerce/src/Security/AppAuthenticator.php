<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AppAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        // On vérifie si le champ "recaptcha-response" contient une valeur/////////CAPTCHA
        // if(empty($_POST['recaptcha-response'])){
        //     header('Location: app_login'); 
        // }else{
        //     // On prépare l'URL
        //     $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LemV_MnAAAAAMVu3oth8lvd3LVLOXoH7FMdKuJt&response={$_POST['recaptcha-response']}";

        //     // On vérifie si CURL est installé
        //     if(function_exists('curl_version')){
        //         $curl = curl_init($url);
        //         curl_setopt($curl, CURLOPT_HEADER, false);
        //         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        //         curl_setopt($curl, CURLOPT_TIMEOUT, 1);
        //         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        //         $response = curl_exec($curl);
        //     }else{
        //         $response = file_get_contents($url);
        //     }

        //     // On vérifie si on a une réponse
        //     if(empty($response) || is_null($response)){
        //         header('Location: app_login'); 
        //     }else{
        //         $data = json_decode($response);
        //         if($data->success){    
                    
                    $email = $request->request->get('email', '');

                    $request->getSession()->set(Security::LAST_USERNAME, $email);
            
                    return new Passport(
                        new UserBadge($email),
                        new PasswordCredentials($request->request->get('password', '')),
                        [
                            new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                            new RememberMeBadge(),
                        ]
                    );
    //             }else{
    //                 header('Location: app_login'); 
    //             }
    //         }


    //     }

        
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_home'));
        throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
