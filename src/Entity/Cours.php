<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=CoursRepository::class)
 */
class Cours
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="cours")
     * @Assert\Unique(message="The {{ value }} existe déja.")
     */
    private $sections;

    /**
     * @ORM\ManyToOne(targetEntity=Tuteur::class, inversedBy="cours",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $tuteur;

    /**
     * @ORM\OneToMany(targetEntity=VideoConference::class, mappedBy="Cours")
     */
    private $videoConferences;

    /**
     * @ORM\ManyToMany(targetEntity=Groupe::class, inversedBy="cours")
     */
    private $groupe;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cle;

    /**
     * @ORM\ManyToMany(targetEntity=Apprenant::class, mappedBy="CoursInscris")
     */
    private $apprenants;

    public function __construct()
    {
        $this->sections = new ArrayCollection();
        $this->videoConferences = new ArrayCollection();
        $this->groupe = new ArrayCollection();
        $this->apprenants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setCours($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->removeElement($section)) {
            // set the owning side to null (unless already changed)
            if ($section->getCours() === $this) {
                $section->setCours(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getLibelle();
    }

    public function getTuteur(): ?Tuteur
    {
        return $this->tuteur;
    }

    public function setTuteur(?Tuteur $tuteur): self
    {
        $this->tuteur = $tuteur;

        return $this;
    }

    /**
     * @return Collection|VideoConference[]
     */
    public function getVideoConferences(): Collection
    {
        return $this->videoConferences;
    }

    public function addVideoConference(VideoConference $videoConference): self
    {
        if (!$this->videoConferences->contains($videoConference)) {
            $this->videoConferences[] = $videoConference;
            $videoConference->setCours($this);
        }

        return $this;
    }

    public function removeVideoConference(VideoConference $videoConference): self
    {
        if ($this->videoConferences->removeElement($videoConference)) {
            // set the owning side to null (unless already changed)
            if ($videoConference->getCours() === $this) {
                $videoConference->setCours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupe(): Collection
    {
        return $this->groupe;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupe->contains($groupe)) {
            $this->groupe[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        $this->groupe->removeElement($groupe);

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(?string $cle): self
    {
        $this->cle = $cle;

        return $this;
    }

    /**
     * @return Collection|Apprenant[]
     */
    public function getApprenants(): Collection
    {
        return $this->apprenants;
    }

    public function addApprenant(Apprenant $apprenant): self
    {
        if (!$this->apprenants->contains($apprenant)) {
            $this->apprenants[] = $apprenant;
            $apprenant->addCoursInscri($this);
        }

        return $this;
    }

    public function removeApprenant(Apprenant $apprenant): self
    {
        if ($this->apprenants->removeElement($apprenant)) {
            $apprenant->removeCoursInscri($this);
        }

        return $this;
    }
}
