<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VehiculeRepository; // Assurez-vous d'avoir importé le Repository adéquat
use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommandeRepository; // Assurez-vous d'avoir importé le Repository adéquat
use App\Entity\Commande;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use App\Repository\MembreRepository; // Assurez-vous d'avoir importé le Repository adéquat
use App\Entity\Membre;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

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


    // vehicules 
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
     * @Route("/vehicule/{id}", name="show_vehicule")
     */
    public function showVehiculeByID($id): Response
    {
        $vehicule = $this->vehiculeRepository->find($id);

        return $this->render('admin/vehicule_by_id.html.twig', [
            'vehicule' => $vehicule,
        ]);
    }



 /**
     * @Route("/admin/vehicule/create", name="create_vehicule")
     */
    public function createVehicule(Request $request, EntityManagerInterface $entityManager): Response
{
    // Création d'une nouvelle instance de Vehicule
    $vehicule = new Vehicule();

    // Création du formulaire
    $form = $this->createFormBuilder($vehicule)
        ->add('titre', TextType::class)
        ->add('marque', TextType::class)
        ->add('modele', TextType::class)
        ->add('description', TextareaType::class)
        ->add('photo', TextType::class)
        ->add('prixJournalier', NumberType::class)
        ->add('save', SubmitType::class, ['label' => 'Ajouter'])
        ->getForm();

    // Gérer la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupération des données du formulaire
        $vehicule = $form->getData();

        // Enregistrement du véhicule dans la base de données
        $entityManager->persist($vehicule);
        $entityManager->flush();

        $this->addFlash('success', 'Véhicule ajouté avec succès !');

        // Redirection vers une autre page après l'ajout
        return $this->redirectToRoute('vehicules_list');
    }

    // Rendre la vue du formulaire
    return $this->render('admin/Form/vehicule/create.html.twig', [
        'form' => $form->createView(),
    ]);
}


// Modifier la route pour l'action update
/**
 * @Route("/admin/vehicule/{id}/update", name="update_vehicule")
 */
public function updateVehicule(Request $request, EntityManagerInterface $entityManager, Vehicule $vehicule): Response
{
    
    // Création du formulaire pré-rempli avec les données du véhicule à mettre à jour
    $form = $this->createFormBuilder($vehicule)
        ->add('titre', TextType::class)
        ->add('marque', TextType::class)
        ->add('modele', TextType::class)
        ->add('description', TextareaType::class)
        ->add('photo', TextType::class)
        ->add('prixJournalier', NumberType::class)
        ->add('save', SubmitType::class, ['label' => 'Modifier'])
        ->getForm();
    // Gérer la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement automatique des modifications dans la base de données via Doctrine
        $entityManager->flush();

        $this->addFlash('success', 'Véhicule mis à jour avec succès !');

        // Redirection vers une autre page après la mise à jour
        return $this->redirectToRoute('vehicules_list');
    }

    // Rendre la vue du formulaire
    return $this->render('admin/Form/vehicule/update.html.twig', [
        'form' => $form->createView(),
    ]);
}

/**
 * @Route("/admin/vehicule/{id}/delete", name="delete_vehicule")
 */
public function deleteVehicule(Request $request, EntityManagerInterface $entityManager, Vehicule $vehicule): Response
{
    $entityManager->remove($vehicule);
    $entityManager->flush();

    $this->addFlash('success', 'Véhicule supprimé avec succès !');

    return $this->redirectToRoute('vehicules_list');
}




// commande




//ajouter  un delete, update , un viewByID

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
     * @Route("/commande/{id}", name="show_commande")
     */
    public function showCommmande($id): Response
    {
        $commande = $this->commandeRepository->find($id);

        return $this->render('admin/orders_by_id.html.twig', [
            'commande' => $commande,
        ]);
    }







    /**
     * @Route("/admin/commande/create", name="create_commande")
     */

    public function createCommande(Request $request, EntityManagerInterface $entityManager): Response
    {

        $membresRepository = $entityManager->getRepository(Membre::class);
        $listeMembres = $membresRepository->findAll(); 


        $vehiculesRepository = $entityManager->getRepository(Vehicule::class);
        $listeIdVehicules = $vehiculesRepository->findAll(); 

        // Création d'une nouvelle instance de Commande
        $commande = new Commande();
    
        // Création du formulaire pour créer une nouvelle commande
        $form = $this->createFormBuilder($commande)
        ->add('membre', ChoiceType::class, [
            'choices' => $listeMembres,
            'choice_label' => function ($membre) {
                return $membre->getId(); 
            },
            'label' => 'ID Membre'
        ])

        ->add('vehicule', ChoiceType::class, [
            'choices' => $listeIdVehicules,
            'choice_label' => function ($vehicule) {
                return $vehicule->getId(); 
            },
            'label' => 'ID Membre'
        ])
            ->add('date_heure_depart', DateTimeType::class)
            ->add('date_heure_fin', DateTimeType::class)
            ->add('prix_total', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Créer'])
            ->getForm();
    
        // Gérer la soumission du formulaire
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrement automatique de la nouvelle commande dans la base de données via Doctrine
            $entityManager->persist($commande);
            $entityManager->flush();
    
            $this->addFlash('success', 'Nouvelle commande créée avec succès !');
    
            // Redirection vers une autre page après la création
            return $this->redirectToRoute('orders_list');
        }
    
        // Rendre la vue du formulaire de création
        return $this->render('admin/Form/order/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    
// Modifier la route pour l'action update
/**
 * @Route("/admin/commande/{id}/update", name="update_commande")
 
*/
public function updateCommande(Request $request, EntityManagerInterface $entityManager, Commande $commande): Response
{


    $membresRepository = $entityManager->getRepository(Membre::class);
        $listeMembres = $membresRepository->findAll(); 


        $vehiculesRepository = $entityManager->getRepository(Vehicule::class);
        $listeIdVehicules = $vehiculesRepository->findAll(); 


    // Création du formulaire pré-rempli avec les données du véhicule à mettre à jour
    $form = $this->createFormBuilder($commande)
    ->add('membre', ChoiceType::class, [
        'choices' => $listeMembres,
        'choice_label' => function ($membre) {
            return $membre->getId(); 
        },
        'label' => 'ID Membre'
    ])

    ->add('vehicule', ChoiceType::class, [
        'choices' => $listeIdVehicules,
        'choice_label' => function ($vehicule) {
            return $vehicule->getId(); 
        },
        'label' => 'ID Membre'
    ])
        ->add('date_heure_depart', DateTimeType::class)
        ->add('date_heure_fin', DateTimeType::class)
        ->add('prix_total', TextType::class)
        ->add('save', SubmitType::class, ['label' => 'Modifier'])
        ->getForm();

    // Gérer la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement automatique des modifications dans la base de données via Doctrine
        $entityManager->flush();

        $this->addFlash('success', 'Commande mis à jour avec succès !');

        // Redirection vers une autre page après la mise à jour
        return $this->redirectToRoute('orders_list');
    }

    // Rendre la vue du formulaire
    return $this->render('admin/Form/order/update.html.twig', [
        'form' => $form->createView(),
    ]);
}




/**
 * @Route("/admin/commande/{id}/delete", name="delete_commande")
 */
public function deleteCommande(Request $request, EntityManagerInterface $entityManager, Commande $commande): Response
{
    $entityManager->remove($commande);
    $entityManager->flush();

    $this->addFlash('success', 'Commande supprimé avec succès !');

    return $this->redirectToRoute('orders_list');
}






// member



//ajouter un create , un delete, update , un viewByID

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


    /**
     * @Route("/user/{id}", name="show_users")
     */
    public function showUser($id): Response
    {
        $membre = $this->membreRepository->find($id);

        return $this->render('admin/users_by_id.html.twig', [
            'user' => $membre,
        ]);
    }


    
 /**
     * @Route("/admin/user/create", name="create_users")
     */
    public function createUser(Request $request, EntityManagerInterface $entityManager): Response
{
    // Création d'une nouvelle instance de Vehicule
    $membre = new Membre();

    // Création du formulaire
    $form = $this->createFormBuilder($membre)
        ->add('pseudo', TextType::class)
        ->add('mdp', TextType::class)
        ->add('nom', TextType::class)
        ->add('prenom', TextareaType::class)
        ->add('email', TextType::class)
        ->add('civilite', ChoiceType::class, [
            'choices' => [
                'M.' => 'Monsieur',
                'Mme' => 'Madame',
            ],
        ])
        ->add('status', IntegerType::class)
        ->add('save', SubmitType::class, ['label' => 'Ajouter'])
        ->getForm();

    // Gérer la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Récupération des données du formulaire
        $membre = $form->getData();

        // Enregistrement du véhicule dans la base de données
        $entityManager->persist($membre);
        $entityManager->flush();

        $this->addFlash('success', 'Membre ajouté avec succès !');

        // Redirection vers une autre page après l'ajout
        return $this->redirectToRoute('users_list');
    }

    // Rendre la vue du formulaire
    return $this->render('admin/Form/user/create.html.twig', [
        'form' => $form->createView(),
    ]);
}


// Modifier la route pour l'action update
/**
 * @Route("/admin/user/{id}/update", name="update_users")
 */
public function updateUser(Request $request, EntityManagerInterface $entityManager, Membre $membre): Response
{
    // Création du formulaire pré-rempli avec les données du véhicule à mettre à jour
        $form = $this->createFormBuilder($membre)
        ->add('pseudo', TextType::class)
        ->add('mdp', TextType::class)
        ->add('nom', TextType::class)
        ->add('prenom', TextareaType::class)
        ->add('email', TextType::class)
        ->add('civilite', ChoiceType::class, [
            'choices' => [
                'M.' => 'Monsieur',
                'Mme' => 'Madame',
            ],
        ])
        ->add('status', IntegerType::class)
        ->add('save', SubmitType::class, ['label' => 'Modifier'])
        ->getForm();

    // Gérer la soumission du formulaire
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Enregistrement automatique des modifications dans la base de données via Doctrine
        $entityManager->flush();

        $this->addFlash('success', 'Membre mis à jour avec succès !');

        // Redirection vers une autre page après la mise à jour
        return $this->redirectToRoute('users_list');
    }

    // Rendre la vue du formulaire
    return $this->render('admin/Form/user/update.html.twig', [
        'form' => $form->createView(),
    ]);
}





/**
 * @Route("/admin/user/{id}/delete", name="delete_users")
 */
public function deleteUser(Request $request, EntityManagerInterface $entityManager, Membre $membre): Response
{
    $entityManager->remove($membre);
    $entityManager->flush();

    $this->addFlash('success', 'Membre supprimé avec succès !');

    return $this->redirectToRoute('users_list');
}





}


