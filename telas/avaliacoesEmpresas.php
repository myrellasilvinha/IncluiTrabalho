<?php
require_once __DIR__ . '/../includes/config.php';
$id = (int) get('id');
redirect(absolute_url('empresa.php') . ($id > 0 ? '?id=' . $id : ''));
