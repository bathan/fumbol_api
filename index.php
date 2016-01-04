<?php
require_once './include/config.php';

use fumbol\common\resources\ResourceFactory;

$app = new \Slim\Slim();
$app->add(new \SlimJson\Middleware([
    'json.status' => true,
    'json.debug' => false
]));

//-- TODO:: Create variables with resources and send those variables instead of instancing every time?
$resourceFactory = new ResourceFactory($app);

//-- Groups IGNORE
$app->get('/groups/:groupId?',      [$resourceFactory->getGroupResource(), 'getGroup']);
$app->post('/groups/?',             [$resourceFactory->getGroupResource(), 'addGroup']);
$app->post('/groups/:groupId?',     [$resourceFactory->getGroupResource(), 'updateGroup']);

//-- Users
$app->post('/users/?',              [$resourceFactory->getUserResource(), 'addUser']);
$app->post('/users/login/?',        [$resourceFactory->getUserResource(), 'loginUser']);
$app->post('/users/token/?',        [$resourceFactory->getUserResource(), 'tokenTest']);

//-- Matches
$app->get('/match/check?',                              [$resourceFactory->getMatchResource(), 'check']);

$app->get('/match/getCurrentMatch?',                    [$resourceFactory->getMatchResource(), 'getCurrentMatch']);
$app->post('/match/signup?',                            [$resourceFactory->getMatchResource(), 'signup']);
$app->get('/match/confirm/:token/:match_id?',           [$resourceFactory->getMatchResource(), 'confirm']);
$app->get('/match/shuffle/?',                           [$resourceFactory->getMatchResource(), 'shuffle']);
$app->get('/match/mariconear/:token/:match_id?',        [$resourceFactory->getMatchResource(), 'mariconear']);


//-- ScoreBoard
$app->post('/scoreboard/?',          [$resourceFactory->getScoreBoardResource(), 'update']);

$app->run();
