<?php

namespace App\Tests\Service;

use App\Entity\Material;
use App\Entity\MaterialUnit;
use App\Repository\MaterialRepository;
use App\Repository\MaterialUnitRepository;
use App\Repository\ServiceMaterialRepository;
use App\Service\MaterialManager;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class MaterialManagerTest extends TestCase
{
    private $materialRepository;
    private $unitRepository;
    private $serviceMaterialRepository;
    private $entityManager;
    private $materialManager;

    protected function setUp(): void
    {
        $this->materialRepository = $this->createMock(MaterialRepository::class);
        $this->unitRepository = $this->createMock(MaterialUnitRepository::class);
        $this->serviceMaterialRepository = $this->createMock(ServiceMaterialRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);

        $this->materialManager = new MaterialManager(
            $this->materialRepository,
            $this->unitRepository,
            $this->serviceMaterialRepository,
            $this->entityManager
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
}
