<?php

namespace App\Core\Attachment\Validator;

use App\Entity\Attachment;

/**
 * Représente un attachment qui n'existe pas en base de données.
 */
class NonExistingAttachment extends Attachment
{
    public function __construct(int $expectedId)
    {
        $this->id = $expectedId;
    }
}
