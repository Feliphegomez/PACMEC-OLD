<?php
/**
 *
 * @author     FelipheGomez <feliphegomez@gmail.com>
 * @package    FG
 * @category   Api
 * @copyright  2020-2021 Manager Technology CO
 * @license    license.txt
 * @version    Release: @package_version@
 * @link       http://github.com/ManagerTechnologyCO/PACMEC
 * @version    1.0.1
 */
?>
<!DOCTYPE html>
<html>
  <head>
    <title>PACMEC - API - ReDoc</title>
    <!-- needed for adaptive design -->
    <meta charset="utf-8"/> <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700|Roboto:300,400,700" rel="stylesheet">
    <!-- ReDoc doesn't change outer page styles -->
    <style>
      body { margin: 0; padding: 0; }
    </style>
  </head>
  <body>
    <redoc spec-url='<?= infosite('siteurl'); ?>/pacmec-api/openapi'></redoc>
    <script src="https://cdn.jsdelivr.net/npm/redoc@next/bundles/redoc.standalone.js"> </script>
  </body>
</html>
