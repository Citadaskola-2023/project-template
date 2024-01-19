<?php

require_once  dirname(__DIR__) . '/vendor/autoload.php';

$db = new \App\Database();
$posts = $db->query('SELECT * FROM blog_posts WHERE id = 6')
    ->fetchAll();

foreach ($posts as $post) {
    echo "<li>" . $post['title'] . "</li>";
}

dd('stop');

view('index');
