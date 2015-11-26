<?php
require_once './include/config.php';

use fumbol\common\resources\ResourceFactory;

$app = new \Slim\Slim();
$app->add(new \SlimJson\Middleware([
    'json.status' => true,
    'json.debug' => false
]));

$app->get('/api/hello/:name', function ($name) use ($app) {
    $app->render(200,['name'=>$name]);
});

$resourceFactory = new ResourceFactory($app);

//-- Users
$app->post('/api/users/?',              [$resourceFactory->getUserResource(), 'addUser']);
$app->post('/api/users/login/?',        [$resourceFactory->getUserResource(), 'loginUser']);
$app->post('/api/users/token/?',        [$resourceFactory->getUserResource(), 'tokenTest']);


//-- Groups
$app->get('/api/groups/:groupId?',      [$resourceFactory->getGroupResource(), 'getGroup']);
$app->post('/api/groups/?',             [$resourceFactory->getGroupResource(), 'addGroup']);
$app->post('/api/groups/:groupId?',     [$resourceFactory->getGroupResource(), 'updateGroup']);


//-- Matches
$app->get('/api/match/check?',          [$resourceFactory->getMatchResource(), 'check']);
$app->get('/api/match/getCurrentMatch?',          [$resourceFactory->getMatchResource(), 'getCurrentMatch']);
$app->post('/api/match/signup?',        [$resourceFactory->getMatchResource(), 'signup']);
$app->get('/api/match/confirm/:token/:match_id?',        [$resourceFactory->getMatchResource(), 'confirm']);
$app->get('/api/match/shuffle/?',        [$resourceFactory->getMatchResource(), 'shuffle']);


//-- ScoreBoard
$app->post('/api/scoreboard/?',          [$resourceFactory->getScoreBoardResource(), 'update']);

$app->run();
