<?php

namespace App\Entity;

use App\Repository\ApprenantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApprenantRepository::class)
 */
class Apprenant extends User
{

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="apprenants")
     */
    private $groupe;

    /**
     * @ORM\OneToMany(targetEntity=Soumission::class, mappedBy="apprenant")
     */
    private $soumissions;

    /**
     * @ORM\ManyToMany(targetEntity=Cours::class, inversedBy="apprenants")
     */
    private $CoursInscris;

    public function __construct()
    {
        $this->soumissions = new ArrayCollection();
        $this->CoursInscris = new ArrayCollection();
    }

   

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * @return Collection|Soumission[]
     */
    public function getSoumissions(): Collection
    {
        return $this->soumissions;
    }

    public function addSoumission(Soumission $soumission): self
    {
        if (!$this->soumissions->contains($soumission)) {
            $this->soumissions[] = $soumission;
            $soumission->setApprenant($this);
        }

        return $this;
    }

    public function removeSoumission(Soumission $soumission): self
    {
        if ($this->soumissions->removeElement($soumission)) {
            // set the owning side to null (unless already changed)
            if ($soumission->getApprenant() === $this) {
                $soumission->setApprenant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCoursInscris(): Collection
    {
        return $this->CoursInscris;
    }

    public function addCoursInscri(Cours $coursInscri): self
    {
        if (!$this->CoursInscris->contains($coursInscri)) {
            $this->CoursInscris[] = $coursInscri;
        }

        return $this;
    }

    public function removeCoursInscri(Cours $coursInscri): self
    {
        $this->CoursInscris->removeElement($coursInscri);

        return $this;
    }
}
