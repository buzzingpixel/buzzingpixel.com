<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait UserNotes
{
    /**
     * @Mapping\Column(
     *     name="user_notes",
     *     type="string",
     * )
     */
    protected string $userNotes = '';

    public function getUserNotes(): string
    {
        return $this->userNotes;
    }

    public function setUserNotes(string $userNotes): void
    {
        $this->userNotes = $userNotes;
    }
}
