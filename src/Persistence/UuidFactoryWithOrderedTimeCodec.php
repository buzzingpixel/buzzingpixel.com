<?php

declare(strict_types=1);

namespace App\Persistence;

use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Ramsey\Uuid\FeatureSet;
use Ramsey\Uuid\UuidFactory;

class UuidFactoryWithOrderedTimeCodec extends UuidFactory
{
    public function __construct(?FeatureSet $features = null)
    {
        parent::__construct($features);

        $this->setCodec(new OrderedTimeCodec(
            $this->getUuidBuilder()
        ));
    }
}
