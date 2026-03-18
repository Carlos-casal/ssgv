<?php
class Material {
    public $id, $name, $barcode, $nature, $sn, $nid;
    public function getName() { return $this->name; }
    public function setName($n) { $this->name = $n; }
    public function getBarcode() { return $this->barcode; }
    public function setBarcode($b) { $this->barcode = $b; }
    public function getSerialNumber() { return $this->sn; }
    public function getNetworkId() { return $this->nid; }
    public function setNature($n) { $this->nature = $n; }
}

$rows = [
    ['Motorola DP1400', 'SN-DP1400-01', '', ''],
    ['Paracetamol 500mg', '8470006521458', '', ''],
    ['Pulse Oximeter MD300C63', '609224102107', '', ''],
    ['Electronic Blood Pressure', '695245139203', '', ''],
    ['Canula de Guedel', '6972091022124', '', ''],
    ['Canula de Guedel', 'S/N', '', ''],
    ['Canula de Guedel', '6972091022155', '', ''],
    ['Canula de Guedel', '15019315071423', '', ''],
    ['Canula de Guedel', '6972091022179', '', ''],
    ['Canula de Guedel', '15019315071447', '', '']
];

$cache = [];
$counted = [];

function isAlreadyCounted($m, &$counted) {
    if (in_array($m, $counted, true)) return true;
    $counted[] = $m;
    return false;
}

$created = 0; $updated = 0;

foreach ($rows as $row) {
    $name = $row[0]; $barcode = $row[1]; $sn = $row[2]; $nid = $row[3];
    
    $found = null;
    foreach ($cache as $m) {
        if ($barcode && $barcode !== 'S/N' && $m->getBarcode() === $barcode) { $found = $m; break; }
        if ($sn && $sn !== 'S/N' && $m->getSerialNumber() === $sn) { $found = $m; break; }
        if ($nid && $nid !== 'S/N' && $m->getNetworkId() === $nid) { $found = $m; break; }
    }
    
    if (!$found) {
        $hasUniqueId = ($barcode && $barcode !== 'S/N') || ($sn && $sn !== 'S/N') || ($nid && $nid !== 'S/N');
        if (!$hasUniqueId && $name) {
            foreach ($cache as $m) {
                if ($m->getName() === $name) { $found = $m; break; }
            }
        }
    }
    
    $material = $found;
    
    if (!$material) {
        $material = new Material();
        $material->setName($name);
        $created++;
        $cache[] = $material;
    } else {
        if (!isAlreadyCounted($material, $counted)) {
            $updated++;
        }
    }
    
    if ($barcode && $barcode !== 'S/N') $material->setBarcode($barcode);
}

echo "Created: $created\n";
echo "Updated: $updated\n";
foreach ($cache as $m) {
    echo "Material in cache: " . $m->getName() . " / " . $m->getBarcode() . "\n";
}
