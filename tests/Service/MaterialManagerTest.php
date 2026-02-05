<?php

namespace App\Tests\Service;

use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Repository\MaterialRepository;
use App\Repository\MaterialUnitRepository;
use App\Repository\MaterialStockRepository;
use App\Repository\MaterialMovementRepository;
use App\Repository\ServiceMaterialRepository;
use App\Service\MaterialManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\User;
use App\Entity\MaterialStock;
use App\Entity\MaterialMovement;

class MaterialManagerTest extends TestCase
{
    private $materialRepository;
    private $unitRepository;
    private $stockRepository;
    private $movementRepository;
    private $serviceMaterialRepository;
    private $entityManager;
    private $security;
    private $materialManager;

    protected function setUp(): void
    {
        $this->materialRepository = $this->createMock(MaterialRepository::class);
        $this->unitRepository = $this->createMock(MaterialUnitRepository::class);
        $this->stockRepository = $this->createMock(MaterialStockRepository::class);
        $this->movementRepository = $this->createMock(MaterialMovementRepository::class);
        $this->serviceMaterialRepository = $this->createMock(ServiceMaterialRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->security = $this->createMock(Security::class);

        $this->materialManager = new MaterialManager(
            $this->materialRepository,
            $this->unitRepository,
            $this->stockRepository,
            $this->movementRepository,
            $this->serviceMaterialRepository,
            $this->entityManager,
            $this->security
        );
    }

    public function testHasEnoughStock(): void
    {
        $material = new Material();
        $material->setNature(Material::NATURE_CONSUMABLE);
        $material->setStock(10);

        $this->assertTrue($this->materialManager->hasEnoughStock($material, 5));
        $this->assertTrue($this->materialManager->hasEnoughStock($material, 10));
        $this->assertFalse($this->materialManager->hasEnoughStock($material, 11));
    }

    public function testIsUnitAvailableMaintenance(): void
    {
        $unit = new MaterialUnit();
        $unit->setIsInMaintenance(true);

        $start = new \DateTime('2025-01-01 10:00:00');
        $end = new \DateTime('2025-01-01 12:00:00');

        $this->assertFalse($this->materialManager->isUnitAvailable($unit, $start, $end));
    }

    public function testCountAvailableUnits(): void
    {
        $material = new Material();
        $material->setNature(Material::NATURE_TECHNICAL);

        $unit1 = new MaterialUnit();
        $unit1->setMaterial($material);
        $unit1->setIsInMaintenance(false);

        $unit2 = new MaterialUnit();
        $unit2->setMaterial($material);
        $unit2->setIsInMaintenance(true);

        $this->unitRepository->expects($this->any())
            ->method('findBy')
            ->willReturn([$unit1, $unit2]);

        // Mock the query builder for isUnitAvailable
        $query = $this->getMockBuilder(\Doctrine\ORM\Query::class)
            ->disableOriginalConstructor()
            ->getMock();
        $query->method('getResult')->willReturn([]);

        $qb = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $qb->method('join')->willReturnSelf();
        $qb->method('where')->willReturnSelf();
        $qb->method('andWhere')->willReturnSelf();
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('getQuery')->willReturn($query);

        $this->serviceMaterialRepository->method('createQueryBuilder')->willReturn($qb);

        $start = new \DateTime('2025-01-01 10:00:00');
        $end = new \DateTime('2025-01-01 12:00:00');

        $this->assertEquals(1, $this->materialManager->countAvailableUnits($material, $start, $end));
    }

    public function testAdjustStockCreatesMovement(): void
    {
        $material = new Material();
        $material->setNature(Material::NATURE_CONSUMABLE);
        $material->setStock(10);

        $user = new User();
        $this->security->method('getUser')->willReturn($user);

        // Mock Location finding
        $locationRepo = $this->createMock(\App\Repository\LocationRepository::class);
        $this->entityManager->method('getRepository')
            ->with(\App\Entity\Location::class)
            ->willReturn($locationRepo);

        $location = new \App\Entity\Location();
        $locationRepo->method('findOneBy')->willReturn($location);

        $this->entityManager->expects($this->atLeastOnce())
            ->method('persist');

        $this->materialManager->adjustStock($material, 5, 'Test reason');

        $this->assertEquals(15, $material->getStock());
    }

    public function testAdjustStockWithSizes(): void
    {
        $material = new Material();
        $material->setNature(Material::NATURE_CONSUMABLE);
        $material->setStock(10);

        // Mock Location finding
        $locationRepo = $this->createMock(\App\Repository\LocationRepository::class);
        $this->entityManager->method('getRepository')
            ->with(\App\Entity\Location::class)
            ->willReturn($locationRepo);

        $location = new \App\Entity\Location();
        $locationRepo->method('findOneBy')->willReturn($location);

        $this->stockRepository->method('findOneBy')
            ->willReturn(null);

        $this->entityManager->expects($this->atLeast(2))
            ->method('persist'); // Movement and new Stock (and possibly Location if it didn't exist)

        $this->materialManager->adjustStock($material, 3, 'New size', 'XL');

        $this->assertEquals(13, $material->getStock());
    }

    public function testTransferEntry(): void
    {
        $material = new Material();
        $material->setNature(Material::NATURE_CONSUMABLE);
        $material->setStock(10);

        $destination = new \App\Entity\Location();
        $destination->setName('Warehouse');

        $this->stockRepository->method('findOneBy')
            ->willReturn(null);

        $this->materialManager->transfer(
            $material,
            null, // Origin
            $destination, // Destination
            5,
            'Entry',
            null
        );

        $this->assertEquals(15, $material->getStock());
    }

    public function testTransferMove(): void
    {
        $material = new Material();
        $material->setNature(Material::NATURE_CONSUMABLE);
        $material->setStock(10);

        $origin = new \App\Entity\Location();
        $origin->setName('Origin');
        $destination = new \App\Entity\Location();
        $destination->setName('Destination');

        $this->stockRepository->method('findOneBy')
            ->willReturn(null);

        $this->materialManager->transfer(
            $material,
            $origin,
            $destination,
            3,
            'Move',
            null
        );

        // Global stock should remain unchanged
        $this->assertEquals(10, $material->getStock());
    }
}
