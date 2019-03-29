<?php

namespace App\Entity;

use App\Traits\Timestampable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     * @Assert\Length(min="3", max="150")
     * @ORM\Column(type="string", length=150)
     */
    private $name;

    /**
     * @var string $description
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="10", max="200")
     * @ORM\Column(type="text", length=200)
     */
    private $address;

    /**
     * @var string $city
     * @ORM\Column(type="string", length=200)
     */
    private $city;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $zipCode;

    /**
     * @var BusinessSegment $businessSegment
     * @ORM\OneToOne(targetEntity="App\Entity\BusinessSegment")
     */
    private $businessSegment;

    /**
     * @ORM\Column(type="string", length=150)
     * @Gedmo\Slug(fields={"name"})
     * @var string
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervention", mappedBy="client", orphanRemoval=true)
     */
    private $interventions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Referrer", mappedBy="client", orphanRemoval=true)
     */
    private $referrers;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="clients")
     */
    private $user;

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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Client
    {
        $this->slug = $slug;

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

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return self
     */
    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return self
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return BusinessSegment
     */
    public function getBusinessSegment(): BusinessSegment
    {
        return $this->businessSegment;
    }

    /**
     * @param BusinessSegment $businessSegment
     * @return Client
     */
    public function setBusinessSegment(BusinessSegment $businessSegment): Client
    {
        $this->businessSegment = $businessSegment;

        return $this;
    }
}
