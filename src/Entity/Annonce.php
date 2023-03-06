<?php

namespace App\Entity;

use App\Repository\AnnonceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 155)]
    private ?string $Etiquette = null;

    #[ORM\Column]
    private ?int $montant = null;

    #[ORM\Column(length: 100)]
    private ?string $lieu = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbLit = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbDouche = null;

    #[ORM\Column(nullable: true)]
    private ?int $dimension = null;

    #[ORM\Column]
    private ?bool $isForOwnerSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $linkUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEtiquette(): ?string
    {
        return $this->Etiquette;
    }

    public function setEtiquette(string $Etiquette): self
    {
        $this->Etiquette = $Etiquette;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getNbLit(): ?int
    {
        return $this->nbLit;
    }

    public function setNbLit(?int $nbLit): self
    {
        $this->nbLit = $nbLit;

        return $this;
    }

    public function getNbDouche(): ?int
    {
        return $this->nbDouche;
    }

    public function setNbDouche(?int $nbDouche): self
    {
        $this->nbDouche = $nbDouche;

        return $this;
    }

    public function getDimension(): ?int
    {
        return $this->dimension;
    }

    public function setDimension(?int $dimension): self
    {
        $this->dimension = $dimension;

        return $this;
    }

    public function isIsForOwnerSite(): ?bool
    {
        return $this->isForOwnerSite;
    }

    public function setIsForOwnerSite(bool $isForOwnerSite): self
    {
        $this->isForOwnerSite = $isForOwnerSite;

        return $this;
    }

    public function getLinkUrl(): ?string
    {
        return $this->linkUrl;
    }

    public function setLinkUrl(?string $linkUrl): self
    {
        $this->linkUrl = $linkUrl;

        return $this;
    }
}
