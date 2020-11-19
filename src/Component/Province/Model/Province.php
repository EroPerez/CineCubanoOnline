<?php

declare(strict_types=1);

namespace App\Component\Province\Model;

use App\Component\Core\Model\TimestampableTrait;
use App\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Description of Province
 *
 * @author Michel
 */
class Province implements ProvinceInterface 
{

    use TimestampableTrait;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ArrayCollection 
     */
    private $companies;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId(): ?string {
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
