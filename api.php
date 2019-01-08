<?php

require_once dirname(__FILE__).'/../../config/config.inc.php';
include_once dirname(__FILE__).'/smogbuster.php';

$pdo = Db::getInstance()->getLink();
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$stmt = $pdo->query('SELECT * FROM `'._DB_PREFIX_.'smogbuster`');
$stations = $stmt->fetchAll(PDO::FETCH_ASSOC);

header("Content-Type: application/json");
echo json_encode($stations);

die;
