<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactMessageRepository")
 */
class ContactMessage {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="contactMessages")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $seen;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ContactConversation", inversedBy="contactMessages")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $conversation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function __construct() {
        $this->date = new \DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getMessage(): ?string {
        return $this->message;
    }

    public function setMessage(string $message): self {
        $this->message = $message;

        return $this;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): self {
        $this->user = $user;

        return $this;
    }

    public function getSeen(): ?bool {
        return $this->seen;
    }

    public function setSeen(bool $seen): self {
        $this->seen = $seen;

        return $this;
    }

    public function getConversation(): ?ContactConversation {
        return $this->conversation;
    }

    public function setConversation(?ContactConversation $conversation): self {
        $this->conversation = $conversation;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self {
        $this->date = $date;

        return $this;
    }

    public function isOut($user): bool {

        return \strcmp((string) $this->getUser()->getId(), $user) === 0;
    }

}
