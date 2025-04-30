<?php

namespace App\Controller;

use App\Form\RecetteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RecetteRepository;
use App\Entity\Recette;

class RecetteController extends AbstractController {
    private string $nom = 'Cake au citron';
    private int $type = 1;
    // 1 ( sucré ), 2 ( salé )
    private string $description = 'Facile à préparer avec des ingrédients du quotidien';
    private array $ingredients = [ 'Farine', 'Sucre', 'Oeufs', 'Citron', 'Beurre' ];
    private array $quantites = [ '200g', '100g', '3', '1', '100g' ];

    #[ Route( '/affichage', name: 'affichage' ) ]

    public function afficher(): Response {
        return $this->render( 'recette/affiche.html.twig', [
            'nom' => $this->nom,
            'type' => $this->type,
            'description' => $this->description,
        ] );
    }

    #[ Route( '/details', name: 'details' ) ]

    public function details(): Response {
        return $this->render( 'recette/details.html.twig', [
            'nom' => $this->nom,
            'ingredients' => $this->ingredients,
            'quantites' => $this->quantites,
        ] );
    }
    #[ Route( '/ajout', name: 'recette_ajout' ) ]

    public function ajouter( Request $request, EntityManagerInterface $em ): Response {
        $recette = new Recette();
        $form = $this->createForm( RecetteType::class, $recette );

        $form->handleRequest( $request );
        if ( $form->isSubmitted() && $form->isValid() ) {
            $em->persist( $recette );
            $em->flush();
            return $this->redirectToRoute( 'recette_liste' );
        }

        return $this->render( 'ajout.html.twig', [
            'form' => $form->createView(),
        ] );
    }

    #[ Route( '/liste', name: 'recette_liste' ) ]

    public function lister( RecetteRepository $recetteRepo ): Response {
        $recettes = $recetteRepo->findAll();
        return $this->render( 'liste.html.twig', [
            'recettes' => $recettes,
        ] );
    }
}
