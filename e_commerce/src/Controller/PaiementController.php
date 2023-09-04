<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Entity\Livre;
use App\Entity\Commande;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    private EntityManagerInterface $em;                                                                             // Initialiser la variable $em
    
    public function __construct(EntityManagerInterface $em){
        $this-> em = $em;
    }

    #[Route('/commande/create-session-stripe/{numero_commande}', name: 'payment_stripe')]                           // Route pour accéder à la page de paiement
    public function stripeCheckout($numero_commande): RedirectResponse
    {
        $produitStripe = [];                                                                                        // Initialiser la variable $produitStripe

        $commande = $this->em->getRepository(Commande::class)->findOneBy(['numero_commande' => $numero_commande]);  // Pour récupérer la commande suivant son numéro de commande
        
        // dd($commande);                                                                                           // Vérifier si on récupère bien la commande

        foreach ($commande->getCommandeLivres()->getValues() as $livre){
            
            // dd($livre);
            
            $livreData = $this->em->getRepository(Livre::class)->findOneBy(['titre' => $livre->getLivre()]);
            dd($livreData);

            $produitStripe[] =[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $produitStripe
                ]
            ];
        }

        Stripe::setApiKey(sk_test_51NaRzqLHfEAyziyoqwIGsIW3m3ZmEZfH6YTCqder78KaGkloVP2YUyR3mgY1or2fQewoEZoSbV9qPR8HAPMKfM8800WdKOnu4v);
        
        $checkout_session = Session::create([
            'line_items' => [[
            # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
            'price' => '{{PRICE_ID}}',
            'quantity' => 1,
        ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

    }

}
