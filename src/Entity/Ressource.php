<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 *@ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\Entity(repositoryClass=RessourceRepository::class)
 */
class Ressource
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Document;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Contenu;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="ressources")
     */
    private $section;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCreation;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getDocument(): ?string
    {
        return $this->Document;
    }

    public function setDocument(string $Document): self
    {
        $this->Document = $Document;

        return $this;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(?string $Contenu): self
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getSection(): ?section
    {
        return $this->section;
    }

    public function setSection(?section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

 
    public function getClassName()
{
    return (new \ReflectionClass($this))->getShortName();
}
}
