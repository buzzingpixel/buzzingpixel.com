<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait LastCommentUserType
{
    /**
     * @Mapping\Column(
     *     name="last_comment_user_type",
     *     type="string",
     *     options={"default" : "user"},
     * )
     */
    protected string $lastCommentUserType = '';

    public function getLastCommentUserType(): string
    {
        return $this->lastCommentUserType;
    }

    public function setLastCommentUserType(string $lastCommentUserType): void
    {
        $this->lastCommentUserType = $lastCommentUserType;
    }
}
