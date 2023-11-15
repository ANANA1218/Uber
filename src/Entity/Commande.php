<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;

use App\Entity\Membre;
use App\Entity\Vehicule;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: "date_heure_depart", type: "datetime")]
    private \DateTimeInterface $dateHeureDepart;

    #[ORM\Column(name: "date_heure_fin", type: "datetime")]
    private \DateTimeInterface $dateHeureFin;

    #[ORM\Column(name: "prix_total", type: "integer")]
    private int $prixTotal;

   
    #[ORM\ManyToOne(targetEntity: Membre::class)]
    #[ORM\JoinColumn(name: "id_membre", referencedColumnName: "id", nullable: false)]
    private Membre $membre;

    #[ORM\ManyToOne(targetEntity: Vehicule::class)]
    #[ORM\JoinColumn(name: "id_vehicule", referencedColumnName: "id", nullable: false)]
    private Vehicule $vehicule;

    // Les getters et setters pour les nouveaux champs

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeureDepart(): \DateTimeInterface
    {
        return $this->dateHeureDepart;
    }

    public function setDateHeureDepart(\DateTimeInterface $dateHeureDepart): self
    {
        $this->dateHeureDepart = $dateHeureDepart;
        return $this;
    }

    public function getDateHeureFin(): \DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): self
    {
        $this->dateHeureFin = $dateHeureFin;
        return $this;
    }

    public function getPrixTotal(): int
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(int $prixTotal): self
    {
        $this->prixTotal = $prixTotal;
        return $this;
    }



    public function getMembre(): Membre
    {
        return $this->membre;
    }

    public function setMembre(Membre $membre): self
    {
        $this->membre = $membre;
        return $this;
    }

    public function getVehicule(): Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(Vehicule $vehicule): self
    {
        $this->vehicule = $vehicule;
        return $this;
    }


    
}
