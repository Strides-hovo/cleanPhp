<?php 


return [
    'id' => 'INT NOT NULL AUTO_INCREMENT PRIMARY KEY',
    'name' =>  'VARCHAR(150) NOT NULL',
    'comment' => 'TEXT',
    'email' => 'VARCHAR(150) NOT NULL UNIQUE',
    'created_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
];