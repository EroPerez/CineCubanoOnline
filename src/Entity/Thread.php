<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Thread as BaseThread;

/**
 * Description of Thread
 *
 * @author Michel
 */
/**
 * @ORM\Entity
 */
class Thread extends BaseThread
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $createdBy;

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\Entity\Message",
     *   mappedBy="thread", cascade={"persist", "remove"}
     *  )
     * @var Message[]|Collection
     */
    protected $messages;

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\Entity\ThreadMetadata",
     *   mappedBy="thread", cascade={"persist", "remove"}
     * )
     * @var ThreadMetadata[]|Collection
     */
    protected $metadata;
    
     /**
     * Get the collection of ThreadMetadata.
     *
     * @return Collection
     */
    public function getMetadata()
    {
        return $this->metadata;
    }
}
