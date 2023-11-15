<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VehiculeRepository; // Assurez-vous d'avoir importé le Repository adéquat
use App\Entity\Vehicule;

class VehiculeController extends AbstractController
{
    private $vehiculeRepository;

    public function __construct(VehiculeRepository $vehiculeRepository)
    {
        $this->vehiculeRepository = $vehiculeRepository;
    }

    /**
     * @Route("/vehicules", name="all_vehicles")
     */
    public function getAllVehicles(): Response
    {
        $vehicules = $this->vehiculeRepository->findAll();

        return $this->render('vehicule/index.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }

/**
     * @Route("/vehicule/{id}", name="show_vehicule")
     */
    public function showVehicule($id): Response
    {
        $vehicule = $this->vehiculeRepository->find($id);

        return $this->render('vehicule/show.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }


    




}
