<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?int $nbr_personnes = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    

    #[ORM\Column(length: 255)]
    private ?string $theme = null;

    #[ORM\Column(length: 255)]
    private ?string $regime = null;

    // galerie : la liste des noms de toutes les images du menu
    #[ORM\Column(nullable: true)]
    private ?array $images = null;

    // les plats du menu : [0] = entrée, [1] = plat, [2] = dessert (avec les allergènes)
    #[ORM\Column(nullable: true)]
    private ?array $plats = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getNbrPersonnes(): ?int
    {
        return $this->nbr_personnes;
    }

    public function setNbrPersonnes(int $nbr_personnes): static
    {
        $this->nbr_personnes = $nbr_personnes;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getRegime(): ?string
    {
        return $this->regime;
    }

    public function setRegime(string $regime): static
    {
        $this->regime = $regime;

        return $this;
    }

    public function getImages(): array
    {
        // si le menu n'a pas de galerie, on renvoie juste l'image principale
        if (empty($this->images)) {
            return [$this->image];
        }

        return $this->images;
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;

        return $this;
    }

    public function getPlats(): ?array
    {
        return $this->plats;
    }

    public function setPlats(?array $plats): static
    {
        $this->plats = $plats;

        return $this;
    }

}
