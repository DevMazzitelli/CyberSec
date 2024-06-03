<?php

// src/Controller/Admin/MessageCrudController.php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Repository\MessageRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class MessageCrudController extends AbstractCrudController
{
    private $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextareaField::new('annonce', 'Annonce'),
        ];
    }

    public function createEntity(string $entityFqcn)
    {
        // Récupérer la dernière annonce
        $latestMessage = $this->messageRepository->findLatest();

        // Créer un nouvel objet Message
        $message = new Message();
        $message->setAnnonce($latestMessage ? $latestMessage->getAnnonce() : ''); // Utilisez la dernière annonce ou une chaîne vide par défaut

        return $message;
    }
}

