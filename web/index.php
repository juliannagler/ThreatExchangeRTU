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
    return file_put_contents($filename, $data,$flags);
}

$app->post('/get_update.php', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request) {
  $ent_info = json_decode($request->getContent(), true);
  error_log("\n".print_r(json_decode($request->getContent(), true), true));
  $file_name = __DIR__ . '/tx_info.txt';
  error_log("\n".$file_name);
  $fp = fopen($file_name, 'w');
  fwrite($fp, '12345test');
  fclose($fp);
  // $app->dumpFile('tx_tag.txt', $request->getContent());
  return 'ok';
});

// Our web handlers

$app->get('/', function() use($app) {
  $app['monolog']->addDebug('logging output.');
  // error_log("\n"."@@@@@@@@@@@@@@@@@");
  // error_log("\n\n".print_r($ent_info, true));
  // error_log("\n"."$$$$$$$$$$$$$$$$$$");
  return $app['twig']->render('index.twig', array('ent_info' => $ent_info,)
  );
});

$app->run();
