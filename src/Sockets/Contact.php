<?php

namespace App\Sockets;

use App\Repository\ContactMessageRepository;
use Carbon\Carbon;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Contact implements MessageComponentInterface {

    protected $clients;
    protected $repo;

    public function __construct(ContactMessageRepository $repo) {
        $this->clients = [];
        $this->repo = $repo;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $msg = json_decode($msg);
        if ($msg->type == "status") {
            $this->clients[$msg->id] = $from;
            echo "comming user {$msg->id}\n";
        } else {
            $message = $this->repo->find($msg->msg_id);
            Carbon::setLocale('es');
            $dateTime = $message->getDate();
            echo "sending message to {$msg->id}\n";
            if (array_key_exists($msg->id, $this->clients)) {
                $this->clients[$msg->id]->send(json_encode([
                    'conversation_id' => $message->getConversation()->getId(),
                    'message' => [
                        'id' => $message->getId(),
                        'msg' => $message->getMessage(),
                        'out' => false,
                        'date' => (new Carbon($dateTime->format('Y-m-d H:i:s.u'), $dateTime->getTimezone()))->diffForHumans(),
                    ]
                ]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $id = '';
        foreach ($this->clients as $key => $client) {
            if ($client->resourceId == $conn->resourceId) {
                $id = $key;
                unset($client);
                break;
            }
        }
        echo "Connection {$conn->resourceId} with id {$id} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}
