<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use App\Component\Core\Model\TimestampableTrait;


/**
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 * @ORM\Table(
 *      name="manage_company"
 * )
 */
class Company
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
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $legalPoeple;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $juristPeople;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $inscriptionNumber;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Province::class, inversedBy="companies")
     * @ORM\JoinColumn(nullable=true)
     */
    private $province;   

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="companies")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $logo = null;

    /**
     * @Assert\Image(
     *      maxSize="2M",
     *      mimeTypes={"image/svg", "image/png","image/jpg", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="company_logo", fileNameProperty="logo")
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

    /**
     * @ORM\OneToMany(targetEntity=CompanyServices::class, mappedBy="company", orphanRemoval=true)
     */
    private $services;
    
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $videoUrl = null;


    public function __construct()
    {
        $this->services = new ArrayCollection();        
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

    public function getLegalPoeple(): ?string
    {
        return $this->legalPoeple;
    }

    public function setLegalPoeple(string $legalPoeple): self
    {
        $this->legalPoeple = $legalPoeple;

        return $this;
    }

    public function getJuristPeople(): ?string
    {
        return $this->juristPeople;
    }

    public function setJuristPeople(string $juristPeople): self
    {
        $this->juristPeople = $juristPeople;

        return $this;
    }

    public function getInscriptionNumber(): ?string
    {
        return $this->inscriptionNumber;
    }

    public function setInscriptionNumber(string $inscriptionNumber): self
    {
        $this->inscriptionNumber = $inscriptionNumber;

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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): self
    {
        $this->province = $province;

        return $this;
    }   

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
    
    public function isOwner($user) {
        return $user->getId() == $this->getOwner()->getId();
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
     public function hasFile(): bool {
        return null !== $this->logoFile;

    }

    /**
     * @return string
     */
    public function getLogoUrl(): string {
        if ($this->hasFile()) {
            return (string) ("/uploads/company/logo/" . $this->getLogo());
        }
        return "/uploads/company/logo/logo0.jpg";

    }

    public function setLogo($aLogo): self {
        $this->logo = $aLogo;
        return $this;
    }

    public function getLogo(): ?string {
        return $this->logo;
    }

    /**
     * @return Collection|CompanyServices[]
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(CompanyServices $companyService): self
    {
        if (!$this->services->contains($companyService)) {
            $this->services[] = $companyService;
            $companyService->setCompany($this);
        }

        return $this;
    }

    public function removeService(CompanyServices $companyService): self
    {
        if ($this->services->removeElement($companyService)) {
            // set the owning side to null (unless already changed)
            if ($companyService->getCompany() === $this) {
                $companyService->setCompany(null);
            }
        }

        return $this;
    }
    
    
   

    public function setVideoUrl($aVideo): self {
        $this->videoUrl = $aVideo;
        return $this;
    }

    public function getVideoUrl(): ?string {
        return $this->videoUrl;
    }
    
    /**
     * Represents a string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->getName() ?: $this->getId();

    }
}
