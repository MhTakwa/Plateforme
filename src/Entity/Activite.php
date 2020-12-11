<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiviteRepository::class)
 */
class Activite extends Ressource
{
   
    /**
     * @ORM\Column(type="datetime")
     */
    private $debutSoumission;

    /**
     * @ORM\Column(type="datetime")
     */
    private $finSoumission;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodeGrace;
    public function getDebutSoumission(): ?\DateTimeInterface
    {
        return $this->debutSoumission;
    }

    public function setDebutSoumission(\DateTimeInterface $debutSoumission): self
    {
        $this->debutSoumission = $debutSoumission;

        return $this;
    }

    public function getFinSoumission(): ?\DateTimeInterface
    {
        return $this->finSoumission;
    }

    public function setFinSoumission(\DateTimeInterface $finSoumission): self
    {
        $this->finSoumission = $finSoumission;

        return $this;
    }

    public function getPeriodeGrace(): ?int
    {
        return $this->periodeGrace;
    }

    public function setPeriodeGrace(?int $periodeGrace): self
    {
        $this->periodeGrace = $periodeGrace;

        return $this;
    }

  
    public function getClassName()
{
    return (new \ReflectionClass($this))->getShortName();
}
}
