<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiviteRepository::class)
 */
class Activite extends Ressource
{
   

    /**
     * @ORM\Column(type="datetime")
     */
    private $finSoumission;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $periodeGrace;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity=Soumission::class, mappedBy="activite")
     */
    private $soumissions;

    /**
     * @ORM\Column(type="integer")
     */
    private $phase;

    public function __construct()
    {
        $this->soumissions = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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
            $soumission->setActivite($this);
        }

        return $this;
    }

    public function removeSoumission(Soumission $soumission): self
    {
        if ($this->soumissions->removeElement($soumission)) {
            // set the owning side to null (unless already changed)
            if ($soumission->getActivite() === $this) {
                $soumission->setActivite(null);
            }
        }

        return $this;
    }

    public function getPhase(): ?int
    {
        return $this->phase;
    }

    public function setPhase(int $phase): self
    {
        $this->phase = $phase;

        return $this;
    }
}
