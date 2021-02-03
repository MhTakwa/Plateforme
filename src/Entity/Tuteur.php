<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TuteurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=TuteurRepository::class)
 *  @UniqueEntity("email")
 *
 */
class Tuteur extends User 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Cours::class, mappedBy="tuteur", orphanRemoval=true,)
     */
    private $cours;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $grade;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $specialite;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return parent::getId(); 
    }

    /**
     * @return Collection|Cours[]
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours[] = $cour;
            $cour->setTuteur($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getTuteur() === $this) {
                $cour->setTuteur(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->getNom()." ".$this->getPrenom();
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(?string $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getTel(): ?int
    {
        return $this->tel;
    }

    public function setTel(int $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }
    public function getEmail(): ?string
    {
        return parent::getEmail();
    }
    public function setEmail(string $email): self
    {
       parent::setEmail($email);
       return $this;
    }
  

}
