<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\MessageBundle\Entity\ThreadMetadata as BaseThreadMetadata;

/**
 * Description of ThreadMetadata
 *
 * @author Michel
 */

/**
 * @ORM\Entity
 */
class ThreadMetadata extends BaseThreadMetadata {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="App\Entity\Thread",
     *   inversedBy="metadata"
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
    protected $participant;

}
