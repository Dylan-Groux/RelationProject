<?php

namespace App\Models\Entity\DTO;

class ConversationDTO
{
    public int $relationId;
    public string $nickname;
    public string $picture;
    public string $lastMessage;
    public string $lastDate;
    public int $unreadCount;

    public function __construct(
        int $relationId,
        string $nickname,
        string $picture,
        string $lastMessage,
        string $lastDate,
        int $unreadCount = 0
    ) {
        $this->relationId = $relationId;
        $this->nickname = $nickname;
        $this->picture = $picture;
        $this->lastMessage = $lastMessage;
        $this->lastDate = $lastDate;
        $this->unreadCount = $unreadCount;
    }
}
