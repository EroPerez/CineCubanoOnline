<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Component\Core\Model\TimestampableTrait;
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
//use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ServiceRepository::class)
 * @ORM\Table(
 *      name="core_service"
 * )
 */
class Service implements TranslatableInterface {

    use TranslatableTrait;

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
        return (string)$this->id;
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
     * @ORM\OneToMany(targetEntity=CompanyServices::class, mappedBy="category", orphanRemoval=true)
     */
    private $companies;

    /**
     * @Assert\Valid
     */
    protected $translations;

    public function __construct() {
        $this->companies = new ArrayCollection();
    }

    public function __call($method, $arguments) {
//        $method = ('get' === substr($method, 0, 3) || 'set' === substr($method, 0, 3)) ? $method : 'get' . ucfirst($method);

        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }
//
//    public function __get($name) {
//        $method = 'get' . ucfirst($name);
//        $arguments = [];
//        return $this->proxyCurrentLocaleTranslation($method, $arguments);
//    }    
    

    /**
     * @return Collection|CompanyServices[]
     */
    public function getCompanies(): Collection {
        return $this->companies;
    }

    public function addCompany(CompanyServices $companyService): self {
        if (!$this->companies->contains($companyService)) {
            $this->companies[] = $companyService;
            $companyService->setService($this);
        }

        return $this;
    }

    public function removeCompany(CompanyServices $companyService): self {
        if ($this->companies->removeElement($companyService)) {
            // set the owning side to null (unless already changed)
            if ($companyService->getService() === $this) {
                $companyService->setService(null);
            }
        }

        return $this;
    }

    /**
     * Represents a string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->getName() ?: (string)$this->getId();
    }

}
