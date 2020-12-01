<?php

namespace App\Entity;

use App\Repository\CompanyServicesRepository;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Component\Core\Model\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CompanyServicesRepository::class)
 * @Vich\Uploadable 
 * @ORM\Table(
 *      name="manage_company_services"
 * )
 * @UniqueEntity(fields={"company", "category"}, message="app.company.services.unique")  
 */
class CompanyServices
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
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $company;

    /**
     * @ORM\ManyToOne(targetEntity=Service::class, inversedBy="companies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;
    
    /**
     * @Assert\Image(
     *      maxSize="2M",
     *      mimeTypes={"image/svg", "image/png","image/jpg", "image/jpeg", "image/pjpeg", "image/gif"})
     * @Vich\UploadableField(mapping="company_services_logo", fileNameProperty="logo")
     * @var File $logoFile
     */
    private $logoFile;
    
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

    public function getId(): ?string
    {
        return $this->id;
    }
    
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getCategory(): ?Service
    {
        return $this->category;
    }

    public function setCategory(?Service $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }
    
    public function setLogoFile(File $image = null) {
        $this->logoFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getLogoFile() {
        return $this->logoFile;
    }
    
    /**
     * @return string
     */
    public function getLogoUrl(): string {
        if ($this->hasFile()) {
            return (string) ("/uploads/company/services/logo/" . $this->getLogo());
        }
        return $this->getCompany()->getLogoUrl();

    }
    
     private function hasFile(): bool {
        return null !== $this->logoFile;

    }
    
}
