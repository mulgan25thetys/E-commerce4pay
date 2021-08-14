<?php

namespace App\Core\Blog\Event;

use App\Core\Content\Event\ContentCreatedEvent;
use App\Entity\Post;

class PostCreatedEvent extends ContentCreatedEvent
{
    public function __construct(Post $content)
    {
        parent::__construct($content);
    }
}
