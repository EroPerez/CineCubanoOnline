<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Component\Core\Model\TimestampableTrait;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\Table(
 *      name="core_service"
 * )
 */
class Service {

    use TimestampableTrait;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    public function getId(): ?string {
        return $this->id;

    }

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    public function getName(): ?string {
        return $this->name;

    }

    public function setName(string $name): self {
        $this->name = $name;

        return $this;

    }

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTimeInterface|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Company::class, mappedBy="services")
     */
    private $companies;

    public function __construct() {
        $this->companies = new ArrayCollection();

    }

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection {
        return $this->companies;

    }

    public function addCompany(Company $company): self {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->addService($this);
        }

        return $this;

    }

    public function removeCompany(Company $company): self {
        if ($this->companies->removeElement($company)) {
            $company->removeService($this);
        }

        return $this;

    }

}
