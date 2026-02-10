<?php

use PHPUnit\Framework\TestCase;
use App\Models\Entity\Message;
use App\Models\Entity\MessageStatus;

class MessageEntityTest extends TestCase
{
    public function testStatutLabelDefaultForInvalidStatut()
    {
        $message = new Message(
            id: 1,
            relationId:1,
            senderId:2,
            statut: 98, // valeur invalide
            content: 'Test message'
        );

        $this->assertEquals('Unknown', $message->getStatutLabel());
    
    }

    public function testStatutLabelAvailable()
    {
         $message = new Message(
            id: 1,
            relationId:1,
            senderId:2,
            statut: 1,
            content: 'Test message'
        );

        $this->assertEquals('Unread', $message->getStatutLabel());
    }

    public function testStatutLabelPending()
    {
         $message = new Message(
            id: 1,
            relationId:1,
            senderId:2,
            statut: 2,
            content: 'Test message'
        );

        $this->assertEquals('Read', $message->getStatutLabel());
    }

    public function testDateTimeImmutableInitialization()
    {
        $message = new Message(
            id: 1,
            relationId:1,
            senderId:2,
            statut: 1,
            content: 'Test message'
        );

        $this->assertInstanceOf(\DateTimeImmutable::class, $message->getSentAt());
    }

    public function getMessageStatutIsAEnumInstance()
    {
        $message = new Message(
            id: 1,
            relationId:1,
            senderId:2,
            statut: 1,
            content: 'Test message'
        );

        $this->assertInstanceOf(MessageStatus::class, $message->getStatut());
        $this->assertEquals(MessageStatus::AVAILABLE, $message->getStatut());
    }
}