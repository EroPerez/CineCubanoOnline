<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use FOS\MessageBundle\Entity\Message as BaseMessage;

/**
 * Description of Message
 *
 * @author Michel
 */

/**
 * @ORM\Entity
 */
class Message extends BaseMessage {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="App\Entity\Thread",
     *   inversedBy="messages"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var \FOS\MessageBundle\Model\ThreadInterface
     */
    protected $thread;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $sender;

    /**
     * @ORM\OneToMany(
     *   targetEntity="App\Entity\MessageMetadata",
     *   mappedBy="message", cascade={"persist", "remove"}
     * )
     * @var MessageMetadata[]|Collection
     */
    protected $metadata;


    public function __construct() {

        parent::__construct();
    
    }

}
