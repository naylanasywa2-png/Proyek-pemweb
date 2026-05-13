<?php
$db = new mysqli('localhost', 'root', '', 'db_memories_book');
if ($db->connect_error) {
    echo 'CONNECT ERROR: ' . $db->connect_error . PHP_EOL;
    exit(1);
}
$res = $db->query('SHOW COLUMNS FROM migrations');
if (!$res) {
    echo 'ERROR: ' . $db->error . PHP_EOL;
    exit(1);
}
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . ' | ' . $row['Type'] . ' | ' . $row['Null'] . ' | ' . var_export($row['Default'], true) . PHP_EOL;
}
