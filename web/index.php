<?php

require('../vendor/autoload.php');

$app = new Silex\Application();
//$app['debug'] = true;

// Register the monolog logging service
$app->register(new Silex\Provider\MonologServiceProvider(), array(
  'monolog.logfile' => 'php://stderr',
));

// Register view rendering
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

// https://developers.facebook.com/docs/graph-api/webhooks#setup
//
// try {
//   $app->mkdir('/Users/carmentang/Desktop/TX_Info', 0700);
// } catch(IOExeceptionInterface $e) {
//   echo "An error occurred while creating your directors at ".$e->getPath();
// }

// $app->touch('tx_tag.txt');

// verification
$app->get('/get_update.php', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) {
  return $request->query->get('hub_challenge');
});

// receive webhooks update
$app->post('/get_update.php', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) {
  error_log("\n".print_r(json_decode($request->getContent(), true), true));
  // $app->dumpFile('tx_tag.txt', $request->getContent());
  return 'ok';
});

// Our web handlers
$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render(
    'index.twig',
    array('ent_info' => json_decode($request->getContent())
  ));
});

$app->run();
