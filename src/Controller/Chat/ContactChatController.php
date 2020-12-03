<?php

namespace App\Controller\Chat;

use App\Entity\ContactConversation;
use App\Entity\ContactMessage;
use App\Repository\ContactConversationRepository;
use App\Repository\ContactMessageRepository;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/chat")
 */
class ContactChatController extends Controller {
     public function __construct() {
        \date_default_timezone_set('UTC');
    }

    /**
     * @Route("/conversation/create")
     * @Method("POST")
     */
    public function createConversation(Request $request, UserRepository $users): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $body = json_decode($request->getContent());
        $member = $users->find($body->user);
        $conversation = new ContactConversation();
        $conversation->addMember($user);
        $conversation->addMember($member);
        $em->persist($conversation);
        $em->flush();
        return new JsonResponse([
            'id' => $conversation->getId(),
            "last_message" => null,
            "last_time" => null,
            "unseen" => 0,
            "member" => ['id' => $member->getId(), 'name' => $member->getFullname(), 'avatar' => $member->getAvatar()]
                ], 200);
    }

  /**
   * @Route("/conversation")
   * @Method("GET")
   */
  public function getConversations(Request $request): JsonResponse
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    $user = $this->getUser();
    $conversations = [];
    Carbon::setLocale($request->getLocale());
    foreach ($user->getContactConversations() as $conversation) {
      $member = $conversation->getReciverMember($user->getId());
      $dateTime = $conversation->getLastMessage()->getDate();
      $last_time = (new Carbon($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone()))->diffForHumans();
      if (!$conversation->getLastMessage()->GetMessage()) {
        $last_time = null;
      }
      $conversations[] = [
        'id' => $conversation->getId(),
        "last_message" => $conversation->getLastMessage()->GetMessage(),
        "last_time" => $last_time,
        "unseen" => $conversation->getUnSeen($user),
        "member" => ['id' => $member->getId(), 'name' => $member->getFullname(), 'avatar' => $member->getAvatar()]
      ];
    }
    return new JsonResponse($conversations, 200);
  }

  /**
   * @Route("/seen/{id}")
   * @Method("POST")
   */
  public function setSeen(ContactMessageRepository $message, $id): JsonResponse
  {
    $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    $user_id = $this->getUser()->getId();
    $m = $message->find($id);
    if (!$m->getConversation()->inConversation($user_id)) {
      return new JsonResponse('Deny access for conversation', 400);
    }
    $em = $this->getDoctrine()->getManager();
    $m->setSeen(true);
    $em->persist($m);
    $em->flush();
    return new JsonResponse("Message Read", 200);
  }

    /**
     * @Route("/conversation/{id}")
     * @Method("GET")
     */
    public function getConversationMessages(Request $request,ContactConversationRepository $conversation, $id): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser()->getId();
        Carbon::setLocale($request->getLocale());
        $conversation = $conversation->find($id);
        if (!$conversation || !$conversation->inConversation($user_id)) {
            return new JsonResponse('Deny access for conversation', 400);
        }

        $massages = [];
        $em = $this->getDoctrine()->getManager();
        foreach ($conversation->getContactMessages() as $contactMessage) {
            $dateTime = $contactMessage->getDate();
            $massages[] = [
                'id' => $contactMessage->getId(),
                'msg' => $contactMessage->getMessage(),
                'out' => $contactMessage->isOut($user_id),
                'date' => (new Carbon($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone()))->diffForHumans(),
            ];
            if (!$contactMessage->isOut($user_id)) {
                $contactMessage->setSeen(true);
                $em->persist($contactMessage);
            }
        }
        $em->flush();

        return new JsonResponse($massages, 200);
    }

    /**
     * @Route("/conversation/{id}/refresh")
     * @Method("POST")
     */
    public function refreshConversationMessages(Request $request, ContactConversationRepository $conversation, $id): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser()->getId();
        Carbon::setLocale($request->getLocale());
        $conversation = $conversation->find($id);
        if (!$conversation || !$conversation->inConversation($user_id)) {
            return new JsonResponse('Deny access for conversation', 400);
        }

        $massages = [];
        $em = $this->getDoctrine()->getManager();
        $body = json_decode($request->getContent());
        $number = $body->number;
        foreach ($conversation->refreshMessage($number, $this->getUser()) as $contactMessage) {
            $dateTime = $contactMessage->getDate();
            $massages[] = [
                'id' => $contactMessage->getId(),
                'msg' => $contactMessage->getMessage(),
                'out' => $contactMessage->isOut($user_id),
                'date' => (new Carbon($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone()))->diffForHumans(),
            ];
            if (!$contactMessage->isOut($user_id)) {
                $contactMessage->setSeen(true);
                $em->persist($contactMessage);
            }
        }
        $em->flush();

        return new JsonResponse($massages, 200);
    }

    /**
     * @Route("/conversation/{id}/send")
     * @Method("POST")
     */
    public function sendMessage(Request $request, ContactConversationRepository $conversation, $id): JsonResponse {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user_id = $this->getUser()->getId();
        $conversation = $conversation->find($id);
        if (!$conversation || !$conversation->inConversation($user_id)) {
            return new JsonResponse('Deny access for conversation', 400);
        }
        $em = $this->getDoctrine()->getManager();
        $body = json_decode($request->getContent());
        $number = $body->number;
        $msg = $body->msg;
        $message = new ContactMessage();
        $message->setSeen(false);
        $message->setMessage($msg);
        $message->setUser($this->getUser());
        $message->setConversation($conversation);
        $em->persist($message);
        $em->flush();
        return new JsonResponse(['message' => $message->getId(), 'number' => $number], 200);
    }

}
