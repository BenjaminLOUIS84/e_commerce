<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // On vérifie si le champ "recaptcha-response" contient une valeur/////////CAPTCHA
            if(empty($_POST['recaptcha-response'])){
                header('Location: app_register'); 

            }else{ // On prépare l'URL
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LemV_MnAAAAAMVu3oth8lvd3LVLOXoH7FMdKuJt&response={$_POST['recaptcha-response']}";

                // On vérifie si CURL est installé
                if(function_exists('curl_version')){
                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    $response = curl_exec($curl);
                }else{
                    $response = file_get_contents($url);
                }

                // On vérifie si on a une réponse
                if(empty($response) || is_null($response)){
                    header('Location: app_register'); 
                }else{
                    $data = json_decode($response);
                    if($data->success){

                        // Sinon on éxécute les instructions encode the plain password
                        $user->setPassword(
                            $userPasswordHasher->hashPassword(
                            $user,
                                $form->get('plainPassword')->getData()
                            )
                        );
                    
                        $entityManager->persist($user);
                        $entityManager->flush();

                        // generate a signed url and email it to the user (utilise le bundle de symfonyCast)
                        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                            (new TemplatedEmail())
                                // ->from(new Address('etrefouetsage@gmail.com', 'Daniel Aaron'))
                                ->from(new Address('contact@danielaaron.eu', 'Daniel Aaron'))
                                ->to($user->getEmail())
                                ->subject('Confirmer votre email')
                                ->htmlTemplate('registration/confirmation_email.html.twig')
                        );
                        // do anything else you need here, like send an email

                        return $userAuthenticator->authenticateUser(
                            $user,
                            $authenticator,
                            $request
                        ); 
                    }else{
                        header('Location: app_register'); 
                    }
                }   
            }
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse mail est bien vérifiée.');

        return $this->redirectToRoute('app_home');
    }
}
