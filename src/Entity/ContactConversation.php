<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactConversationRepository")
 */
class ContactConversation {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="contactConversations")
     */
    private $members;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ContactMessage", mappedBy="conversation")
     */
    private $contactMessages;

    public function __construct() {
        $this->members = new ArrayCollection();
        $this->contactMessages = new ArrayCollection();
        $this->date = new \DateTime();
    }

    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getMembers(): Collection {
        return $this->members;
    }

    public function addMember(User $member): self {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
        }

        return $this;
    }

    public function removeMember(User $member): self {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
        }

        return $this;
    }

    /**
     * @return Collection|ContactMessage[]
     */
    public function getContactMessages(): Collection {
        return $this->contactMessages;
    }

    public function addContactMessage(ContactMessage $contactMessage): self {
        if (!$this->contactMessages->contains($contactMessage)) {
            $this->contactMessages[] = $contactMessage;
            $contactMessage->setConversation($this);
        }

        return $this;
    }

    public function refreshMessage($id, $user): Collection {
        $criteria1 = Criteria::create()->where(Criteria::expr()->neq('user', $user));
        $criteria = Criteria::create()->where(Criteria::expr()->gt('id', $id));
        return $this->contactMessages->matching($criteria)->matching($criteria1);
    }

    public function removeContactMessage(ContactMessage $contactMessage): self {
        if ($this->contactMessages->contains($contactMessage)) {
            $this->contactMessages->removeElement($contactMessage);
            // set the owning side to null (unless already changed)
            if ($contactMessage->getConversation() === $this) {
                $contactMessage->setConversation(null);
            }
        }

        return $this;
    }

    public function getLastMessage(): ContactMessage {
        $message = $this->contactMessages->last();
        if (!$message) {
            return new ContactMessage();
        }
        return $message;
    }

    public function getUnSeen($user): int {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('seen', false));
        $criteria1 = Criteria::create()->where(Criteria::expr()->neq('user', $user));
        $unseen = $this->contactMessages->matching($criteria1)->matching($criteria);
        return $unseen->count();
    }

    /**
     * @paraam id \Ramsey\Uuid\UuidInterface
     */
    public function getReciverMember(\Ramsey\Uuid\UuidInterface $id): ?User {
        $criteria = Criteria::create()->where(Criteria::expr()->neq('id', $id));
        $user = $this->members->matching($criteria);

        return !$user->first() ? null : $user->first();
    }

    /**
     * @paraam id \Ramsey\Uuid\UuidInterface
     */
    public function inConversation(\Ramsey\Uuid\UuidInterface $id): bool {
        $criteria = Criteria::create()->where(Criteria::expr()->eq('id', $id));
        $user = $this->members->matching($criteria);
        return $user->count() == 1;
    }

}
