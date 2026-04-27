<?php

namespace App\Tests\Service;

use App\Entity\Location;
use App\Entity\Material;
use App\Entity\MaterialBatch;
use App\Entity\MaterialMovement;
use App\Entity\MaterialStock;
use App\Entity\MaterialUnit;
use App\Repository\MaterialMovementRepository;
use App\Repository\MaterialRepository;
use App\Repository\MaterialStockRepository;
use App\Repository\MaterialUnitRepository;
use App\Repository\ServiceMaterialRepository;
use App\Service\MaterialManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;

class KitTransferTest extends TestCase
{
    private $materialRepository;
    private $unitRepository;
    private $stockRepository;
    private $movementRepository;
    private $serviceMaterialRepository;
    private $entityManager;
    private $registry;
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
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->security = $this->createMock(Security::class);

        $this->entityManager->method('isOpen')->willReturn(true);
        $this->registry->method('getManager')->willReturn($this->entityManager);

        $this->materialManager = new MaterialManager(
            $this->materialRepository,
            $this->unitRepository,
            $this->stockRepository,
            $this->movementRepository,
            $this->serviceMaterialRepository,
            $this->registry,
            $this->security,
            $this->entityManager
        );
    }

    public function testTransferBetweenKitsConsumable(): void
    {
        // 1. Setup Material
        $material = new Material();
        $material->setName('Consumable M');
        $material->setNature(Material::NATURE_CONSUMABLE);
        $material->setStock(5);

        // 2. Setup Kit A (Origin)
        $kitA = new Location();
        $kitA->setName('Kit A');
        $kitA->setType(Location::TYPE_KIT);

        $stockA = new MaterialStock();
        $stockA->setMaterial($material);
        $stockA->setLocation($kitA);
        $stockA->setQuantity(5);
        $kitA->addStock($stockA);

        // 3. Setup Kit B (Destination)
        $kitB = new Location();
        $kitB->setName('Kit B');
        $kitB->setType(Location::TYPE_KIT);

        // 4. Mocks
        $this->stockRepository->method('findOneBy')->willReturnCallback(function($criteria) use ($kitA, $stockA, $kitB) {
            if ($criteria['location'] === $kitA) return $stockA;
            if ($criteria['location'] === $kitB) return null; // Create new for B
            return null;
        });

        // Mock QueryBuilder for FIFO stock lookup in transfer()
        $query = $this->getMockBuilder(\Doctrine\ORM\Query::class)->disableOriginalConstructor()->getMock();
        $query->method('getResult')->willReturn([$stockA]);
        $qbStock = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $qbStock->method('leftJoin')->willReturnSelf();
        $qbStock->method('where')->willReturnSelf();
        $qbStock->method('andWhere')->willReturnSelf();
        $qbStock->method('setParameter')->willReturnSelf();
        $qbStock->method('orderBy')->willReturnSelf();
        $qbStock->method('addOrderBy')->willReturnSelf();
        $qbStock->method('getQuery')->willReturn($query);
        $this->stockRepository->method('createQueryBuilder')->willReturn($qbStock);

        // Mock movement history check to return empty
        $query = $this->getMockBuilder(\Doctrine\ORM\Query::class)
            ->disableOriginalConstructor()
            ->getMock();
        $query->method('getResult')->willReturn([]);
        $qb = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $qb->method('where')->willReturnSelf();
        $qb->method('andWhere')->willReturnSelf();
        $qb->method('setParameter')->willReturnSelf();
        $qb->method('getQuery')->willReturn($query);
        $this->movementRepository->method('createQueryBuilder')->willReturn($qb);

        // Track movements
        $movements = [];
        $this->entityManager->method('persist')->willReturnCallback(function($entity) use (&$movements) {
            if ($entity instanceof MaterialMovement) {
                $movements[] = $entity;
            }
        });

        // 5. Action: Transfer 3 from Kit A to Kit B
        $this->materialManager->transfer(
            $material,
            $kitA,
            $kitB,
            3,
            'Transfer from A to B',
            null
        );

        // 6. Assertions
        $this->assertEquals(2, $stockA->getQuantity(), "Kit A should have 2 units left");

        $stockB = null;
        foreach ($kitB->getStocks() as $s) {
            if ($s->getMaterial() === $material) {
                $stockB = $s;
                break;
            }
        }
        $this->assertNotNull($stockB, "Kit B should have a stock record now");
        $this->assertEquals(3, $stockB->getQuantity(), "Kit B should have 3 units");
        $this->assertEquals(5, $material->getStock(), "Global stock should remain 5");

        // Check movements
        // After refactoring, it records a SINGLE atomic "Transfer" movement
        $this->assertCount(1, $movements);
        $this->assertEquals(3, $movements[0]->getQuantity());
        $this->assertEquals($kitA, $movements[0]->getOrigin());
        $this->assertEquals($kitB, $movements[0]->getDestination());
        $this->assertStringContainsString('Traspaso', $movements[0]->getReason());
    }

    public function testTransferWithExplicitStock(): void
    {
        // 1. Setup
        $material = new Material();
        $material->setName('M1');
        $material->setNature(Material::NATURE_CONSUMABLE);

        $locA = new Location();
        $locA->setName('Loc A');

        $stock = new MaterialStock();
        $stock->setMaterial($material);
        $stock->setLocation($locA);
        $stock->setQuantity(10);
        $locA->addStock($stock);

        $locB = new Location();
        $locB->setName('Loc B');

        // 2. Mocks
        $this->stockRepository->method('findOneBy')->willReturnCallback(function($criteria) use ($locA, $stock, $locB) {
            if ($criteria['location'] === $locA) return $stock;
            return null;
        });

        $query = $this->getMockBuilder(\Doctrine\ORM\Query::class)->disableOriginalConstructor()->getMock();
        $query->method('getResult')->willReturn([]);
        $qb = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $qb->method('where')->willReturnSelf(); $qb->method('andWhere')->willReturnSelf(); $qb->method('setParameter')->willReturnSelf(); $qb->method('getQuery')->willReturn($query);
        $this->movementRepository->method('createQueryBuilder')->willReturn($qb);

        // 3. Action: Transfer using EXPLICIT stock object
        // We pass NULL for origin to prove it resolves from $stock
        $this->materialManager->transfer(
            $material,
            null,
            $locB,
            4,
            'Reason',
            null,
            null,
            null,
            $stock
        );

        // 4. Assertions
        $this->assertEquals(6, $stock->getQuantity());
        $stockB = null;
        foreach ($locB->getStocks() as $s) { $stockB = $s; break; }
        $this->assertNotNull($stockB);
        $this->assertEquals(4, $stockB->getQuantity());
    }

    public function testTransferBetweenKitsTechnical(): void
    {
        // 1. Setup Material
        $material = new Material();
        $material->setName('Technical T');
        $material->setNature(Material::NATURE_TECHNICAL);
        $material->setStock(1);

        // 2. Setup Kit A (Origin)
        $kitA = new Location();
        $kitA->setName('Kit A');
        $kitA->setType(Location::TYPE_KIT);

        $unit = new MaterialUnit();
        $unit->setMaterial($material);
        $unit->setLocation($kitA);
        $kitA->addUnit($unit);

        // MaterialStock for technical (to track count at location)
        $stockA = new MaterialStock();
        $stockA->setMaterial($material);
        $stockA->setLocation($kitA);
        $stockA->setQuantity(1);
        $kitA->addStock($stockA);

        // 3. Setup Kit B (Destination)
        $kitB = new Location();
        $kitB->setName('Kit B');
        $kitB->setType(Location::TYPE_KIT);

        // 4. Mocks
        $this->stockRepository->method('findOneBy')->willReturnCallback(function($criteria) use ($kitA, $stockA, $kitB) {
            if ($criteria['location'] === $kitA) return $stockA;
            return null;
        });

        // Mock movement history check
        $query = $this->getMockBuilder(\Doctrine\ORM\Query::class)->disableOriginalConstructor()->getMock();
        $query->method('getResult')->willReturn([]);
        $qb = $this->createMock(\Doctrine\ORM\QueryBuilder::class);
        $qb->method('where')->willReturnSelf(); $qb->method('andWhere')->willReturnSelf(); $qb->method('setParameter')->willReturnSelf(); $qb->method('getQuery')->willReturn($query);
        $this->movementRepository->method('createQueryBuilder')->willReturn($qb);

        // 5. Action: Transfer unit from A to B
        $this->materialManager->transfer(
            $material,
            $kitA,
            $kitB,
            1,
            'Transfer Unit from A to B',
            null,
            $unit
        );

        // 6. Assertions
        $this->assertEquals($kitB, $unit->getLocation());
        $this->assertEquals(0, $stockA->getQuantity());

        $stockB = null;
        foreach ($kitB->getStocks() as $s) {
            if ($s->getMaterial() === $material) { $stockB = $s; break; }
        }
        $this->assertNotNull($stockB);
        $this->assertEquals(1, $stockB->getQuantity());
        $this->assertEquals(1, $material->getStock());
    }
}
