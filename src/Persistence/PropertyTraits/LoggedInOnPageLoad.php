<?php

declare(strict_types=1);

namespace App\Persistence\PropertyTraits;

use Doctrine\ORM\Mapping;

trait LoggedInOnPageLoad
{
    /**
     * @Mapping\Column(
     *     name="logged_in_on_page_load",
     *     type="boolean"
     * )
     */
    protected bool $loggedInOnPageLoad = true;

    public function getLoggedInOnPageLoad(): bool
    {
        return $this->loggedInOnPageLoad;
    }

    public function setLoggedInOnPageLoad(bool $loggedInOnPageLoad): void
    {
        $this->loggedInOnPageLoad = $loggedInOnPageLoad;
    }
}
