<?php
class Material {
    private $id, $name, $barcode, $serialNumber, $networkId;
    public function __construct($id, $name, $barcode, $serialNumber = null, $networkId = null) {
        $this->id = $id; $this->name = $name; $this->barcode = $barcode; $this->serialNumber = $serialNumber; $this->networkId = $networkId;
    }
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getBarcode() { return $this->barcode; }
    public function getSerialNumber() { return $this->serialNumber; }
    public function getNetworkId() { return $this->networkId; }
}

$materialCache = [
    new Material(1, 'Motorola DP1400', 'SN-DP1400-01', null, '123456'),
    new Material(2, 'Paracetamol 500mg', '8470006521458', null, null)
];

// simulate Row 4 Pulse Oximeter
$name = "Pulse Oximeter MD300C63";
$barcode = "609224102107"; // or "6,09224E+11"
$serialNumber = "";
$networkId = "";

function findExistingMaterial($name, $barcode, $serialNumber, $networkId, $materialCache) {
    echo "Finding $name, $barcode, $serialNumber, $networkId\n";
    foreach ($materialCache as $m) {
        if ($barcode && $barcode !== 'S/N' && $m->getBarcode() === $barcode) {
            echo "Matched by barcode to " . $m->getName() . "\n";
            return $m;
        }
        if ($serialNumber && $serialNumber !== 'S/N' && $m->getSerialNumber() === $serialNumber) {
            echo "Matched by sn to " . $m->getName() . "\n";
            return $m;
        }
        if ($networkId && $networkId !== 'S/N' && $m->getNetworkId() === $networkId) {
            echo "Matched by networkId to " . $m->getName() . "\n";
            return $m;
        }
    }

    $hasUniqueId = ($barcode && $barcode !== 'S/N') || ($serialNumber && $serialNumber !== 'S/N') || ($networkId && $networkId !== 'S/N');
    if (!$hasUniqueId && $name) {
        foreach ($materialCache as $m) {
            if ($m->getName() === $name) {
                echo "Matched by name (cache) to " . $m->getName() . "\n";
                return $m;
            }
        }
    }
    
    echo "NO MATCH FOUND \n";
    return null;
}

$res = findExistingMaterial($name, $barcode, $serialNumber, $networkId, $materialCache);
if ($res) echo "Result: " . $res->getName() . "\n";
else echo "Result: NULL\n";

// simulate Row 6 Canula de Guedel (with barcode "S/N" and NO OTHER IDENTIFIERS)
$res2 = findExistingMaterial("Canula de Guedel", "S/N", "", "", $materialCache);
if ($res2) echo "Result2: " . $res2->getName() . "\n";
else echo "Result2: NULL\n";
