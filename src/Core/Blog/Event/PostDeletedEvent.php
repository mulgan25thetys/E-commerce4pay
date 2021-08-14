<?php

namespace App\Core\Blog\Event;



use App\Core\Content\Event\ContentDeletedEvent;
use App\Entity\Post;

class PostDeletedEvent extends ContentDeletedEvent
{
    public function __construct(Post $content)
    {
        parent::__construct($content);
    }
}
