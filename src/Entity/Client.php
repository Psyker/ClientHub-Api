<?php

namespace App\Entity;

use App\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    use Timestampable;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @ORM\Column(type="string")
     */
    private $zipCode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervention", mappedBy="client", orphanRemoval=true)
     */
    private $interventions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Referrer", mappedBy="client", orphanRemoval=true)
     */
    private $referrers;

    public function __construct()
    {
        $this->interventions = new ArrayCollection();
        $this->referrers     = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    /**
     * @return Collection|Intervention[]
     */
    public function getInterventions(): Collection
    {
        return $this->interventions;
    }

    public function addIntervention(Intervention $intervention): self
    {
        if (!$this->interventions->contains($intervention)) {
            $this->interventions[] = $intervention;
            $intervention->setClient($this);
        }

        return $this;
    }

    public function removeIntervention(Intervention $intervention): self
    {
        if ($this->interventions->contains($intervention)) {
            $this->interventions->removeElement($intervention);
            // set the owning side to null (unless already changed)
            if ($intervention->getClient() === $this) {
                $intervention->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Referrer[]
     */
    public function getReferrers(): Collection
    {
        return $this->referrers;
    }

    public function addReferrer(Referrer $referrer): self
    {
        if (!$this->referrers->contains($referrer)) {
            $this->interventions[] = $referrer;
            $referrer->setClient($this);
        }

        return $this;
    }

    public function removeReferrer(Referrer $referrer): self
    {
        if ($this->referrers->contains($referrer)) {
            $this->referrers->removeElement($referrer);
            // set the owning side to null (unless already changed)
            if ($referrer->getClient() === $this) {
                $referrer->setClient(null);
            }
        }

        return $this;
    }
}
