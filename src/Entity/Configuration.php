<?php

namespace App\Entity;

use App\Repository\ConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConfigurationRepository::class)
 * @ORM\Table(
 *      name="core_configuration"
 * )
 */
class Configuration {

    //Variables predefinidas del sistema
    const SESSION_MAX_IDLE_TIME = 'SESSION_MAX_IDLE_TIME';
    const MAX_LOGIN_FAILURE_ATTEMPTS = 'MAX_LOGIN_FAILURE_ATTEMPTS';
    const FAIL_TTL_HOUR = 'FAIL_TTL_HOUR';
    const SOCIAL_ACCOUNT_FACEBOOK = 'SOCIAL_ACCOUNT_FACEBOOK';
    const SOCIAL_ACCOUNT_TWITTER = 'SOCIAL_ACCOUNT_TWITTER';
    const SOCIAL_ACCOUNT_INSTAGRAM = 'SOCIAL_ACCOUNT_INSTAGRAM';
    const SOCIAL_ACCOUNT_YOUTUBE = 'SOCIAL_ACCOUNT_YOUTUBE';
    const SOCIAL_ACCOUNT_LINKEDIN = 'SOCIAL_ACCOUNT_LINKEDIN';

    /**
     * @var string $id
     * @ORM\Id()     
     * @ORM\Column(type="string", length=128)  
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): string {
        return $this->id;
    }

    public function setId(string $newId): self {
        $this->id = $newId;
        return $this;
    }

    public function getValue(): string {
        return $this->value;
    }

    public function setValue(string $value): self {
        $this->value = $value;

        return $this;
    }

   /**
     * Represents a string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->getValue();
    }

}
