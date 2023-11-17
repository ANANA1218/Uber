<?php


namespace App\Controller;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{


    private VehiculeRepository $vehiculeRepository;

    public function __construct(VehiculeRepository $vehiculeRepository)
    {
        $this->vehiculeRepository = $vehiculeRepository;
    }


public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

   
    /**
     * @Route("/vehicles", name="search_vehicles")
     */
    public function searchVehicles(Request $request): Response
    {
        $disponibilite = $request->query->get('disponibilite');
        

        // Assurez-vous que la disponibilité est égale à 1 pour rechercher
        if ($disponibilite === 1) {
            // Faites la logique de recherche des véhicules avec les dates ici...
            // Exemple: Recherchez les véhicules disponibles dans votre repository
            $vehicles = $this->vehiculeRepository->findByDisponibilite(1);

            // Puis retournez la réponse avec les résultats de la recherche
            return $this->render('vehicule/index.html.twig', [
                'vehicles' => $vehicles,
            ]);
        } else {
            // Si la disponibilité n'est pas égale à 1, renvoyez une réponse vide ou une redirection, selon votre logique
            return new Response('Aucune disponibilité spécifiée.');
        }
    }
    



}