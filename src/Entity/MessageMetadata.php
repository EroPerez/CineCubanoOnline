<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\MessageMetadata as BaseMessageMetadata;

/**
 * Description of MessageMetadata
 *
 * @author Michel
 */

/**
 * @ORM\Entity
 */
class MessageMetadata extends BaseMessageMetadata {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="App\Entity\Message",
     *   inversedBy="metadata"
     * )
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var \FOS\MessageBundle\Model\MessageInterface
     */
    protected $message;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @var \FOS\MessageBundle\Model\ParticipantInterface
     */
    protected $participant;

}
