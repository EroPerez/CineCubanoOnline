<?php

namespace App\Command;

use App\Entity\ContactMessage;
use App\Sockets\Contact;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChatSocketCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'socket:start:chat';

    protected function configure()
    {
        $this
            ->setDescription('Socket para el chat')
            ->addArgument('port', InputArgument::REQUIRED, 'Puerto')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

      $repository = $this->getContainer()->get("doctrine")
        ->getRepository(ContactMessage::class);
      $output->writeln([
        'Chat socket',// A line
        '============',// Another line
        'Starting chat, open your browser.',// Empty line
      ]);

      $server = IoServer::factory(
        new HttpServer(
          new WsServer(
            new Contact($repository)
          )
        ),
        $input->getArgument('port')
      );

      $server->run();
    }
}
