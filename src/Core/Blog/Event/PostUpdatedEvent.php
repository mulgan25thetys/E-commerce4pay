<?php

namespace App\Core\Blog\Event;



use App\Core\Content\Event\ContentUpdatedEvent;
use App\Entity\Post;

class PostUpdatedEvent extends ContentUpdatedEvent
{
    public function __construct(Post $content, Post $previous)
    {
        parent::__construct($content, $previous);
    }
}
