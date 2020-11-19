<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use App\Component\Core\Model\TimestampableTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Description of User
 *
 * @author Michel
 * @Vich\Uploadable
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(
 *      name="fos_user__user"
 * )
 *
 */
class User extends BaseUser {

    use TimestampableTrait;

    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    protected $id;

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
     * @ORM\OneToMany(targetEntity=Company::class, mappedBy="owner")
     */
    private $companies;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $avatar = null;

    /**
     * @Assert\Image(
     *      maxSize="2M",
     *      mimeTypes={"image/svg", "image/png","image/jpg", "image/jpeg", "image/pjpeg", "image/gif"})
     * @Vich\UploadableField(mapping="user_avatar", fileNameProperty="avatar")
     * @var File $avatarFile
     */
    private $avatarFile;

    /**
     * @ORM\Column(type="string")
     */
    protected $firtName;

    /**
     * @ORM\Column(type="string")
     */
    protected $lastName;

    public function __construct() {
        parent::__construct();
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
            $company->setOwner($this);
        }

        return $this;

    }

    public function removeCompany(Company $company): self {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getOwner() === $this) {
                $company->setOwner(null);
            }
        }

        return $this;

    }

    public function setAvatarFile(File $image = null): self {
        $this->avatarFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;

    }

    public function getAvatarFile() {
        return $this->avatarFile;

    }

    public function hasFile(): bool {
        return null !== $this->avatarFile;

    }

    /**
     * @return string
     */
    public function getAvatarPath(): string {
        if ($this->hasFile()) {
            return (string) ("/uploads/user/avatar/" . $this->getAvatar());
        }
        return "/uploads/user/avatar/avatar0.png";

    }

    public function setAvatar($avatar): self {
        $this->avatar = $avatar;

        return $this;

    }

    public function getAvatar() {
        return $this->avatar;

    }

    public function setFirstName($first_name): self {
        $this->firtName = $first_name;

        return $this;

    }

    public function getFirstName() {
        return $this->firtName;

    }

    public function setLastName($last_name): self {
        $this->lastName = $last_name;

        return $this;

    }

    public function getLastName() {
        return $this->lastName;

    }

    public function getFullName() {
        return $this->getFirstName() . ' ' . $this->getLastName();

    }
    
    
    /**
     * Represents a string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->getFullName() ?: $this->getUsername();

    }

}
