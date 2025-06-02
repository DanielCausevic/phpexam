<?php
try {
    $pdo = new PDO(
        "mysql:host=trolley.proxy.rlwy.net;port=21721;dbname=railway",
        "root",
        "GBxVbkmAGpfEcuFSYiOWJxOuBQKeiWxm"
    );
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
