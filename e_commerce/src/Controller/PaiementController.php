<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Livre;
use App\Entity\Commande;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    private EntityManagerInterface $em;                                                                             // Initialiser la variable $em
    private UrlGeneratorInterface $generator;                                                                             // Initialiser la variable $em
    
    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $generator){
        
        $this-> em = $em;
        $this-> generator = $generator;

    }

    #[Route('/commande/create-session-stripe/{numero_commande}', name: 'payment_stripe')]                           // Route pour accéder à la page de paiement
    public function StripeCheckout($numero_commande): RedirectResponse
    {
        $produitStripe = [];                                                                                        // Initialiser la variable $produitStripe

        $commande = $this->em->getRepository(Commande::class)->findOneBy(['numero_commande' => $numero_commande]);  // Pour récupérer la commande suivant son numéro de commande
        
        // dd($commande);                                                                                           // Vérifier si on récupère bien la commande

        foreach ($commande->getCommandeLivres()->getValues() as $livre){

            // dd($livre);
            
            $livreData = $this->em->getRepository(Livre::class)->findOneBy(['id' => $livre->getLivre()]);           // Pour récupérer les livres

            // dd($livreData);

            $produitStripe[] = [                                                                                    // Instancier les caractéristiques de la page de commande
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $livreData->getPrixUnitaire(),
                    'product_data' => [
                        'name' => $livre->getLivre()->getTitre()
                    ]
                ],
                'quantity' => $livre->getQuantite()
            ];
        }

        dd($produitStripe);

        Stripe::setApiKey(sk_test_51NaRzqLHfEAyziyoqwIGsIW3m3ZmEZfH6YTCqder78KaGkloVP2YUyR3mgY1or2fQewoEZoSbV9qPR8HAPMKfM8800WdKOnu4v);
        
        $checkout_session = Session::create([

            'customer_email' => $this->getUser()->getEmail(),                                                       // Afficher automatiquement l'email de l'utilisateur
            'payment_method_types' => ['card'],                                                                     // Préciser le mode de paiement par carte
            
            'line_items' => [[
                $produitStripe
            ]],

            'mode' => 'payment',

            'success_url' => $this->generator->generate(
                'payment_success', 
                ['numero_commande' => $commande->getNumeroCommande()], 
                Type:UrlGeneratorInterface::ABSOLUTE_URL
            ),

            'cancel_url' => $this->generator->generate(
                'payment_error', 
                ['numero_commande' => $commande->getNumeroCommande()], 
                Type:UrlGeneratorInterface::ABSOLUTE_URL
            )
            
        ]);

        return new RedirectResponse($checkout_session->url);

    }

    #[Route('/commande/success/{numero_commande}', name: 'payment_success')]                           
    public function StripeSuccess($numero_commande, CartService $service): Response
    {
        return $this->render(view: 'commande/success.html.twig');
    }

    #[Route('/commande/error/{numero_commande}', name: 'payment_error')]                           
    public function StripeError($numero_commande, CartService $service): Response
    {
        return $this->render(view: 'commande/error.html.twig');
    }

}
