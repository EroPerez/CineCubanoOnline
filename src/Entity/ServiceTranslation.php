<?php
declare(strict_types=1);  

namespace App\Entity;

use App\Repository\ServiceTranslationRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TranslationInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslationTrait;

/**
 * @ORM\Entity(repositoryClass=ServiceTranslationRepository::class)
 * @ORM\Table(
 *      name="core_service_translation"
 * )
 */
class ServiceTranslation implements TranslationInterface
{
    use TranslationTrait;
    
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
     * @ORM\Column(type="string", length=128)
     * @var string|null 
     */
    protected $locale;

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
}
