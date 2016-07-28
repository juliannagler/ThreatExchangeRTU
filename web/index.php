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

// verification
$app->get('/get_update.php', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) {
  return $request->query->get('hub_challenge');
});

// receive webhooks update

$ent_info = null;

/**
* @param string $filename <p>file name including folder.
* example :: /path/to/file/filename.ext or filename.ext</p>
* @param string $data <p> The data to write.
* </p>
* @param int $flags same flags used for file_put_contents.
* more info: http://php.net/manual/en/function.file-put-contents.php
* @return bool <b>TRUE</b> file created succesfully <br> <b>FALSE</b> failed to create file.
*/
function file_force_contents($filename, $data, $flags = 0){
    if(!is_dir(dirname($filename)))
        mkdir(dirname($filename).'/', 0777, TRUE);
    return file_put_contents($filename, $data, $flags);
}

$app->post('/get_update.php', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) {
  // $response = new JsonResponse();
  // $response->setContent(json_decode($request->getContent()));
  $ent_info = json_decode($request->getContent(), true);
  error_log("\n%%%".print_r(json_decode($request->getContent(), true), true)."!!!!\n");
  $file_name = __DIR__ . '/tx_info.txt';
  error_log("\n".$file_name);
  file_force_contents($file_name, print_r($ent_info));
  file_force_contents($file_name, 'TEST!!!');
  return 'ok';
});

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  return $app['twig']->render('index.twig');
});

$app->run();
