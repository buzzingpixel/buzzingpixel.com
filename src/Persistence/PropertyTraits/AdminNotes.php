<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait AdminNotes
{
    /**
     * @Mapping\Column(
     *     name="admin_notes",
     *     type="string",
     * )
     */
    protected string $adminNotes = '';

    public function getAdminNotes(): string
    {
        return $this->adminNotes;
    }

    public function setAdminNotes(string $adminNotes): void
    {
        $this->adminNotes = $adminNotes;
    }
}
