<?php

namespace App\Controller;

use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\RedirectResponse;



class VehiculeController extends AbstractController
{



    #[Route('/vehicule/create', name: 'vehicule_create')]
    public function create(Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Création d'une nouvelle instance de Vehicule
        $vehicule = new Vehicule();

        // Récupération des données du formulaire
        $titre = $request->request->get('titre');
        $marque = $request->request->get('marque');
        $modele = $request->request->get('modele');
        $description = $request->request->get('description');
        $photo = $request->request->get('photo');
        $prixJournalier = $request->request->get('prix_journalier');
        $dateEnregistrement = new \DateTime($request->request->get('date_enregistrement'));

        // Définition des valeurs pour le véhicule
        $vehicule->setTitre($titre);
        $vehicule->setMarque($marque);
        $vehicule->setModele($modele);
        $vehicule->setDescription($description);
        $vehicule->setPhoto($photo);
        $vehicule->setPrixJournalier($prixJournalier);
        $vehicule->setDateEnregistrement($dateEnregistrement);

        // Enregistrement du véhicule dans la base de données
        $entityManager->persist($vehicule);
        $entityManager->flush();


        $this->addFlash('success', 'Le véhicule a été créé avec succès.');


        // Redirection vers une autre page par exemple après l'ajout
        return $this->redirectToRoute('admin_home');
    }



    #[Route('/vehicule/{id}/edit', name: 'vehicule_edit')]
    public function update(Vehicule $vehicule, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Récupération des données du formulaire et mise à jour du véhicule
        $vehicule->setTitre($request->request->get('titre'));
        $vehicule->setMarque($request->request->get('marque'));
        // ... autres champs à mettre à jour

        // Enregistrement des modifications dans la base de données
        $entityManager->flush();

        // Ajout d'une notification flash pour confirmer la mise à jour du véhicule
        $this->addFlash('success', 'Le véhicule a été mis à jour avec succès.');;

        // Redirection vers la page d'accueil de l'administration ou une autre page après la mise à jour
        return $this->redirectToRoute('admin_home'); // Remplacez 'admin_home' par le nom de votre route vers la page d'accueil de l'administration
    }

    #[Route('/vehicule/{id}', name: 'vehicule_delete', methods: ['DELETE'])]
    public function delete(Vehicule $vehicule, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Suppression du véhicule de la base de données
        $entityManager->remove($vehicule);
        $entityManager->flush();

        // Ajout d'une notification flash pour confirmer la suppression du véhicule
        $this->addFlash('success', 'Le véhicule a été supprimé avec succès.');

        // Redirection vers la page d'accueil de l'administration ou une autre page après la suppression
        return $this->redirectToRoute('admin_home'); // Remplacez 'admin_home' par le nom de votre route vers la page d'accueil de l'administration

    }
}
