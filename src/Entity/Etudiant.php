<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateNaissence = null;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'etudiant')]
    private Collection $matiere;

    /**
     * @var Collection<int, Note>
     */
    #[ORM\OneToMany(targetEntity: Note::class, mappedBy: 'etudiant')]
    private Collection $notes;

    public function __construct()
{
    $this->notes = new ArrayCollection();
}

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateNaissence(): ?\DateTime
    {
        return $this->dateNaissence;
    }

    public function setDateNaissence(\DateTime $dateNaissence): static
    {
        $this->dateNaissence = $dateNaissence;

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getMatiere(): Collection
    {
        return $this->matiere;
    }

    public function addMatiere(Note $matiere): static
    {
        if (!$this->matiere->contains($matiere)) {
            $this->matiere->add($matiere);
            $matiere->setEtudiant($this);
        }

        return $this;
    }

    public function removeMatiere(Note $matiere): static
    {
        if ($this->matiere->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getEtudiant() === $this) {
                $matiere->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setEtudiant($this);
        }

        return $this;
    }

    public function removeNote(Note $note): static
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEtudiant() === $this) {
                $note->setEtudiant(null);
            }
        }

        return $this;
    }
    
}
