<?php

declare(strict_types=1);

namespace App\Entity;

use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;
use App\Component\Core\Model\TimestampableTrait;
use App\Repository\GroupRepository;
/**
 * Description of Group
 *
 * @author Michel
 * @ORM\Entity(repositoryClass=GroupRepository::class)
 * @ORM\Table(
 *      name="fos_user__group"
 * )
 */
class Group extends BaseGroup {

    use TimestampableTrait;

   
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=Ramsey\Uuid\Doctrine\UuidGenerator::class)
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
     * Represents a string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->getName() ?: '';

    }

}
