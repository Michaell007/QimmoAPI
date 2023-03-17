<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AnnonceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: AnnonceRepository::class)]
class Annonce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_annonce'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_annonce'])]
    private ?string $title = null;

    #[ORM\Column(length: 155)]
    #[Groups(['show_annonce'])]
    private ?string $Etiquette = null;

    #[ORM\Column]
    #[Groups(['show_annonce'])]
    private ?int $montant = null;

    #[ORM\Column(length: 100)]
    #[Groups(['show_annonce'])]
    private ?string $lieu = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_annonce'])]
    private ?int $nbLit = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_annonce'])]
    private ?int $nbDouche = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_annonce'])]
    private ?int $dimension = null;

    #[ORM\Column]
    #[Groups(['show_annonce'])]
    private ?bool $isForOwnerSite = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_annonce'])]
    private ?string $linkUrl = null;

    #[ORM\OneToMany(mappedBy: 'annonce', targetEntity: Image::class)]
    #[Groups(['show_annonce'])]
    private Collection $images;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Groups(['show_annonce'])]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'annonces')]
    #[Groups(['show_annonce'])]
    private ?TypeAnnonce $typeAnnonce = null;

    #[Groups(['show_annonce'])]
    #[ORM\ManyToOne(inversedBy: 'annonces')]
    private ?Utilisateur $annonceur = null;

    #[Groups(['show_annonce'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * Function type HasLifecycleCallbacks - PrePersist
     * Operations effectuees avant le save bd
     */
    #[ORM\PrePersist]
    public function prePersistDate() {
        
        if ($this->createdAt == null) {
            $this->createdAt = new \DateTime();
        }
        $this->updatedAt = new \DateTime();
    }

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setAnnonce($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getAnnonce() === $this) {
                $image->setAnnonce(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getTypeAnnonce(): ?TypeAnnonce
    {
        return $this->typeAnnonce;
    }

    public function setTypeAnnonce(?TypeAnnonce $typeAnnonce): self
    {
        $this->typeAnnonce = $typeAnnonce;

        return $this;
    }

    public function getAnnonceur(): ?Utilisateur
    {
        return $this->annonceur;
    }

    public function setAnnonceur(?Utilisateur $annonceur): self
    {
        $this->annonceur = $annonceur;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
