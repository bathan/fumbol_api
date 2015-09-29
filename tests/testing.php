<?php
require_once '../include/config.php';

R::setup( 'mysql:host='._DB_HOST.';dbname='._DB_NAME,_DB_USER, _DB_PASS); //for both mysql or mariaDB

$book = R::dispense( 'book' );
$book->title = 'Learn to Program';
$book->rating = 10;

$book['price'] = 29.99; //you can use array notation as well
$id = R::store( $book );