<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_event = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTime $heure = null;

    #[ORM\Column]
    private ?int $nbr_personnes = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $date_creation = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $id_user = null;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?menu $id_menu = null;

    /**
     * @var Collection<int, Avis>
     */
    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'commande', orphanRemoval: true)]
    private Collection $avis;

    #[ORM\Column(length: 255)]
    private ?string $user_nom = null;

    #[ORM\Column(length: 255)]
    private ?string $menu_nom = null;

    #[ORM\Column]
    private ?float $frais = null;

    #[ORM\Column]
    private ?float $remise = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateEnAttente = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateAcceptee = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateEnPreparation = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateEnCoursDeLivraison = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateLivree = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $dateTerminee = null;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate_Event(): ?\DateTime
    {
        return $this->date_event;
    }

    public function setDateEvent(\DateTime $date_event): static
    {
        $this->date_event = $date_event;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getHeure(): ?\DateTime
    {
        return $this->heure;
    }

    public function setHeure(\DateTime $heure): static
    {
        $this->heure = $heure;

        return $this;
    }

    public function getNbr_Personnes(): ?int
    {
        return $this->nbr_personnes;
    }

    public function setNbrPersonnes(int $nbr_personnes): static
    {
        $this->nbr_personnes = $nbr_personnes;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getDate_Creation(): ?\DateTime
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTime $date_creation): static
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->id_user;
    }

    public function setIdUser(?user $id_user): static
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdMenu(): ?menu
    {
        return $this->id_menu;
    }

    public function setIdMenu(?menu $id_menu): static
    {
        $this->id_menu = $id_menu;

        return $this;
    }

    /**
     * @return Collection<int, Avis>
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): static
    {
        if (!$this->avis->contains($avi)) {
            $this->avis->add($avi);
            $avi->setCommande($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): static
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getCommande() === $this) {
                $avi->setCommande(null);
            }
        }

        return $this;
    }

    public function setUserNom(string $user_nom): static
    {
        $this->user_nom = $user_nom;

        return $this;
    }
    public function getUserNom(): ?string
    {
        return $this->user_nom;
    }

    public function getMenuNom(): ?string
    {
        return $this->menu_nom;
    }

    public function setMenuNom(string $menu_nom): static
    {
        $this->menu_nom = $menu_nom;

        return $this;
    }

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(float $frais): static
    {
        $this->frais = $frais;

        return $this;
    }

    public function getRemise(): ?float
    {
        return $this->remise;
    }

    public function setRemise(float $remise): static
    {
        $this->remise = $remise;

        return $this;
    }

    public function getDateEnAttente(): ?\DateTime
    {
        return $this->dateEnAttente;
    }

    public function setDateEnAttente(\DateTime $dateEnAttente): static
    {
        $this->dateEnAttente = $dateEnAttente;

        return $this;
    }

    public function getDateAcceptee(): ?\DateTime
    {
        return $this->dateAcceptee;
    }

    public function setDateAcceptee(\DateTime $dateAcceptee): static
    {
        $this->dateAcceptee = $dateAcceptee;

        return $this;
    }

    public function getDateEnPreparation(): ?\DateTime
    {
        return $this->dateEnPreparation;
    }

    public function setDateEnPreparation(\DateTime $dateEnPreparation): static
    {
        $this->dateEnPreparation = $dateEnPreparation;

        return $this;
    }

    public function getDateEnCoursDeLivraison(): ?\DateTime
    {
        return $this->dateEnCoursDeLivraison;
    }

    public function setDateEnCoursDeLivraison(\DateTime $dateEnCoursDeLivraison): static
    {
        $this->dateEnCoursDeLivraison = $dateEnCoursDeLivraison;

        return $this;
    }

    public function getDateLivree(): ?\DateTime
    {
        return $this->dateLivree;
    }

    public function setDateLivree(\DateTime $dateLivree): static
    {
        $this->dateLivree = $dateLivree;

        return $this;
    }

    public function getDateTerminee(): ?\DateTime
    {
        return $this->dateTerminee;
    }

    public function setDateTerminee(\DateTime $dateTerminee): static
    {
        $this->dateTerminee = $dateTerminee;

        return $this;
    }
}
