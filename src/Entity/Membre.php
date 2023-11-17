<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;



#[ORM\Entity(repositoryClass: MembreRepository::class)]
class Membre implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column]
    private ?bool $civilite = null;

    #[ORM\Column]
    private ?int $status = null;

    #[ORM\Column(type: 'boolean')]
    private bool $role = false; 

    

    #[ORM\Column(name: "date_enregistrement", type: "datetime")]
    private \DateTimeInterface $dateEnregistrement;


    public function __construct()
    {
        $this->dateEnregistrement = new \DateTime(); // Date d'enregistrement définie lors de la création de la commande
    }

    
    public function getDateEnregistrement(): \DateTimeInterface
    {
        return $this->dateEnregistrement;
    }

    public function setDateEnregistrement(\DateTimeInterface $dateEnregistrement): self
    {
        $this->dateEnregistrement = $dateEnregistrement;
        return $this;
    }





    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function isCivilite(): ?bool
    {
        return $this->civilite;
    }

    public function setCivilite(bool $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getRole(): bool
    {
        return $this->role;
    }

    public function setRole(bool $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER']; // Vous pouvez modifier les rôles selon vos besoins
    }

    public function eraseCredentials()
    {
        // Si vous avez des données sensibles à effacer, vous pouvez le faire ici
        // Par exemple, réinitialiser temporairement le mot de passe en mémoire
        $this->mdp = null;
    }

    public function getUserIdentifier(): string
    {
        return (string)$this->email; // Retournez l'identifiant unique de l'utilisateur
    }



}
