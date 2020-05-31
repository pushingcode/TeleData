<?php

declare(strict_types = 1);

namespace App;

use App\AppLogServices;
use Exception;
use JsonException;

class AppCoreServices 
{

    private const FILE_CONF = __DIR__ . '/../config/env.json';

    private $data_DB = array();

    public static function makeEntityManager()
    {
        
        require_once(__DIR__ .'/../bootstrap.php');
        AppLogServices::logEvent(__FUNCTION__,'Se ha creado un EntityManager', [], 100);
        return $em;
    }

    public static function getApp()
    {

        try {
            if (file_exists(self::FILE_CONF)) {
                $get_data = file_get_contents(self::FILE_CONF);
            } else {
                throw new Exception("El archivo no existe", 600);
                
            }
            $data = json_decode($get_data, true);
        } catch (\Exception $e) {
            //throw $th;
            $data = [];

            AppLogServices::logEvent(__FUNCTION__,$e->getMessage(), ["El archivo no existe: "=> self::FILE_CONF], $e->getCode());

        } catch (JsonException $j){
            $data = [];

            AppLogServices::logEvent(__FUNCTION__,$j->getMessage(), ["JSON Error: "=> "Parsing Error"], $j->getCode());
        }

        return $data;
    }

    public function dbData()
    {
        $data = self::getApp();
        $this->data_DB = $data['db'];
        return $this->data_DB;
    }

    public static function pathsData( string $path = null )
    {
        $data_paths = false;

        foreach (self::getApp() as $key => $value) {
            if ($key == 'paths') {
                //$this->data_paths = $value;
                foreach ($value as $k_path => $v_path) {
                    if ($k_path == $path) {
                        $data_paths = $v_path;
                    }
                }
            }
        }

        try {
            if ($data_paths == false) {
                throw new Exception("Error al crear una ruta");
            }
        } catch (\Exception $e) {
            $data_paths = "";
            AppLogServices::logEvent(__FUNCTION__,$e->getMessage(), ["ruta solicitada: "=>$path], 400);
        }

        return $data_paths;
    }
}