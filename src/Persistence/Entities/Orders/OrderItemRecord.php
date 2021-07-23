<?php

declare(strict_types=1);

namespace App\Persistence\Entities\Orders;

use App\Context\Orders\Entities\OrderItem as OrderItemEntity;
use App\Persistence\Entities\Licenses\LicenseRecord;
use App\Persistence\Entities\Software\SoftwareRecord;
use App\Persistence\PropertyTraits\Id;
use App\Persistence\PropertyTraits\OriginalPrice;
use App\Persistence\PropertyTraits\Price;
use App\Persistence\PropertyTraits\Quantity;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Mapping;
use LogicException;
use Ramsey\Uuid\Uuid;

use function assert;

/**
 * @Mapping\Entity
 * @Mapping\HasLifecycleCallbacks
 * @Mapping\Table(name="order_items")
 */
class OrderItemRecord
{
    use Id;
    use Price;
    use OriginalPrice;
    use Quantity;

    /**
     * @Mapping\ManyToOne(
     *     targetEntity="OrderRecord",
     *     inversedBy="orderItems",
     *     cascade={"persist"},
     * )
     * @Mapping\JoinColumn(
     *     name="order_id",
     *     referencedColumnName="id",
     * )
     */
    private ?OrderRecord $order = null;

    public function getOrder(): ?OrderRecord
    {
        if ($this->order === null) {
            return null;
        }

        if (
            $this->newOrderId !== null &&
            $this->order->getId()->toString() !== $this->newOrderId
        ) {
            return null;
        }

        return $this->order;
    }

    public function setOrder(OrderRecord $order): void
    {
        $this->order = $order;
    }

    /**
     * @Mapping\OneToOne(
     *     targetEntity="\App\Persistence\Entities\Licenses\LicenseRecord",
     * )
     * @Mapping\JoinColumn(
     *     name="license_id",
     *     referencedColumnName="id",
     * )
     */
    private ?LicenseRecord $license;

    public function getLicense(): ?LicenseRecord
    {
        return $this->license;
    }

    public function setLicense(?LicenseRecord $license): void
    {
        $this->license = $license;
    }

    private ?string $newOrderId = null;

    public function getNewOrderId(): ?string
    {
        return $this->newOrderId;
    }

    public function setNewOrderId(string $newOrderId): void
    {
        $this->newOrderId = $newOrderId;
    }

    /**
     * @Mapping\OneToOne(
     *     targetEntity="\App\Persistence\Entities\Software\SoftwareRecord"
     * )
     * @Mapping\JoinColumn(
     *     name="software_id",
     *     referencedColumnName="id",
     * )
     */
    private SoftwareRecord $software;

    public function getSoftware(): SoftwareRecord
    {
        return $this->software;
    }

    public function setSoftware(SoftwareRecord $software): void
    {
        $this->software = $software;
    }

    private ?string $newSoftwareId = null;

    public function getNewSoftwareId(): ?string
    {
        return $this->newSoftwareId;
    }

    public function setNewSoftwareId(string $newSoftwareId): void
    {
        $this->newSoftwareId = $newSoftwareId;
    }

    /**
     * @Mapping\PreFlush
     */
    public function preFlushSetOrderFromNewId(PreFlushEventArgs $args): void
    {
        if ($this->getNewOrderId() === null) {
            return;
        }

        /**
         * @psalm-suppress RedundantCondition
         * @psalm-suppress RedundantPropertyInitializationCheck
         */
        if (
            isset($this->order) &&
            $this->getNewOrderId() === $this->order->getId()->toString()
        ) {
            return;
        }

        $order = $args->getEntityManager()
            ->getRepository(OrderRecord::class)
            ->find($this->getNewOrderId());

        if ($order === null) {
            throw new LogicException('No order found');
        }

        $this->setOrder($order);
    }

    /**
     * @Mapping\PreFlush
     */
    public function preFlushSetSoftwareFromNewId(PreFlushEventArgs $args): void
    {
        if ($this->getNewSoftwareId() === null) {
            return;
        }

        /**
         * @psalm-suppress RedundantCondition
         * @psalm-suppress RedundantPropertyInitializationCheck
         */
        if (
            isset($this->software) &&
            $this->getNewSoftwareId() === $this->software->getId()->toString()
        ) {
            return;
        }

        $software = $args->getEntityManager()
            ->getRepository(SoftwareRecord::class)
            ->find($this->getNewSoftwareId());

        if ($software === null) {
            throw new LogicException('No software found');
        }

        $this->setSoftware($software);
    }

    public function hydrateFromEntity(
        OrderItemEntity $entity,
        EntityManager $entityManager,
        ?OrderRecord $orderRecord = null,
    ): self {
        if ($orderRecord !== null) {
            $this->setOrder($orderRecord);
        }

        $this->setId(Uuid::fromString(uuid: $entity->id()));
        $this->setPrice(price: $entity->priceAsInt());
        $this->setPrice(price: $entity->originalPriceAsInt());
        $this->setQuantity(quantity: $entity->quantity());

        $license = $entity->license();

        /** @noinspection PhpUnhandledExceptionInspection */
        $licenseRecord = $entityManager->find(
            LicenseRecord::class,
            $license->id(),
        );

        assert($licenseRecord instanceof LicenseRecord);

        $this->setLicense($licenseRecord);

        $software = $entity->software();

        /** @noinspection PhpUnhandledExceptionInspection */
        $softwareRecord = $entityManager->find(
            SoftwareRecord::class,
            $software->id(),
        );

        assert($softwareRecord instanceof SoftwareRecord);

        $this->setSoftware($softwareRecord);

        return $this;
    }
}
