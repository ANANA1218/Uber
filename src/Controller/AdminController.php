<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VehiculeRepository; // Assurez-vous d'avoir importé le Repository adéquat
use App\Entity\Vehicule;

use App\Repository\CommandeRepository; // Assurez-vous d'avoir importé le Repository adéquat
use App\Entity\Commande;


use App\Repository\MembreRepository; // Assurez-vous d'avoir importé le Repository adéquat
use App\Entity\Membre;

class AdminController extends AbstractController
{

    private $vehiculeRepository;

    private $membreRepository;
    private $commandeRepository;



    public function __construct(VehiculeRepository $vehiculeRepository, MembreRepository $membreRepository, CommandeRepository $commandeRepository)
    {
        $this->vehiculeRepository = $vehiculeRepository;
        $this->membreRepository = $membreRepository;
        $this->commandeRepository = $commandeRepository;
    }


    /**
     * @Route("/admin", name="admin_home")
     */
    public function adminHome(): Response
    {
        return $this->render('admin/home.html.twig');
    }

 /**
     * @Route("/admin/vehicules", name="vehicules_list")
     */
    public function vehiculesList(): Response
    {
        $vehicules = $this->vehiculeRepository->findAll();

        return $this->render('admin/vehicules_list.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }





    
 /**
     * @Route("/admin/orders", name="orders_list")
     */
    public function ordersList(): Response
    {
        $commandes = $this->commandeRepository->findAll();

        return $this->render('admin/orders_list.html.twig', [
            'commandes' => $commandes,
        ]);
    }


    
 /**
     * @Route("/admin/users", name="users_list")
     */
    public function usersList(): Response
    {
        $membres = $this->membreRepository->findAll();

        return $this->render('admin/users_list.html.twig', [
            'membres' => $membres,
        ]);
    }


}


