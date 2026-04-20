<?php
// query.php
echo shell_exec("php bin/console dbal:run-sql 'SELECT ms.id, m.name, l.name as loc, ms.quantity, ms.batch_id FROM material_stock ms JOIN material m ON ms.material_id = m.id LEFT JOIN location l ON ms.location_id = l.id WHERE m.name LIKE \"%Guedel 1%\"'");
