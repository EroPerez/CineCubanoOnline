<?php

namespace App\Service;

use Doctrine\ORM\EntityManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use App\Repository\UserRepository;

class ContactChatService {

    private $em;
    private $security;
    private $repo;

    public function __construct(EntityManager $manager, UserRepository $repo, Security $security) {
        $this->em = $manager;
        $this->security = $security;
        $this->repo = $repo;
    }

    public function getTeam() {
//        return  $this->repo->getTripleSupportTeam($this->security->getUser());
        return [];
    }

    public function getDescendants() {
        $idUser = $this->security->getUser()->getId();
        $team = [];
//        $tree = $this->repo->buildRelationTree();
//        $ancestorsPlusSelf = $tree->getNodeById($idUser)
//                ->getDescendants();
//        foreach ($ancestorsPlusSelf as $ancestor) {
//            // Get User
//            $user = $this->em->find(User::class, $ancestor->getId());
//            $team[] = $user;
//        }
        return array_chunk($team, 3);
    }

    public function withDescendants() {
//        $idUser = $this->security->getUser()->getId();
//        $tree = $this->repo->buildRelationTree();
//        $ancestorsPlusSelf = $tree->getNodeById($idUser)
//                ->getDescendants();
//
//        return count($ancestorsPlusSelf) > 0;
        return TRUE;
    }

}
