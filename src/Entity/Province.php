<?php


declare(strict_types=1);

namespace App\Entity;

use App\Component\Core\Model\TimestampableTrait;
use App\Repository\ProvinceRepository;
use App\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Doctrine\ORM\Mapping as ORM;


/**
 * Description of Province
 *
 * @author Michel

 * @ORM\Entity(repositoryClass=ProvinceRepository::class)
 * @ORM\Table(
 *      name="core_province"
 * )
 */
class Province implements ProvinceInterface 
{

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

   /**
     * @ORM\Column(type="string", length=25, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Company::class, mappedBy="province", orphanRemoval=true, cascade={"all"})
     */
    private $companies;
    
    
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
    

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?UuidInterface {
        return $this->id;

    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string {
        return $this->name;

    }

    /**
     * {@inheritdoc}
     */
    public function setName(string $name): void {
        $this->name = $name;

    }

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setProvince($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getProvince() === $this) {
                $company->setProvince(null);
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
        return $this->getName();

    }

}
