<?php
require __DIR__.'/vendor/autoload.php';
use App\Kernel;
\ = new Kernel('dev', true);
\->boot();
\ = \->getContainer()->get('doctrine')->getManager();
\ = \->getConnection();
\ = " SELECT ms.id m.name l.name as loc ms.quantity ms.batch_id FROM material_stock ms JOIN material m ON ms.material_id = m.id LEFT JOIN location l ON ms.location_id = l.id WHERE m.name LIKE %Guedel% \;
\
