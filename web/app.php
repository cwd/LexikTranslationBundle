<?php

/**
+ * @var Composer\Autoload\ClassLoader
+ */
$loader = require __DIR__.'/../app/autoload.php';
include_once __DIR__.'/../var/bootstrap.php.cache';

$apcLoader = new Symfony\Component\ClassLoader\ApcClassLoader('zitateeu'.sha1(__FILE__), $loader);
$loader->unregister();
$apcLoader->register(true);

$kernel = new AppKernel('prod', false);
$kernel->loadClassCache();
//$kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
