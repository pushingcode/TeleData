<?php

declare(strict_types = 1);

namespace App;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager; 
use App\AppLogServices;
use Exception;
use JsonException;

/**
 * Servicios de aplicacion
 *
 * @property array $data_DB
 * 
 * @method Doctrine\ORM\EntityManager makeEntityManager()
 * @method string setIdWork()
 * @method array getApp()
 * @method string appData()
 */
class AppCoreServices 
{

    private const FILE_CONF = __DIR__ . '/../config/env.json';

    private $data_DB = array();

    public static function makeEntityManager()
    {
       
        try {
            
            $ruta = [__DIR__ . "" .self::pathsData('Entity')];
            $modo = false;
            $mysql = self::appData('db');

            $configuracion = Setup::createAnnotationMetadataConfiguration($ruta, $modo, null, null, false);
            $em = EntityManager::create($mysql,$configuracion);

            if($em->getConnection()->ping() == false){
                throw new Exception("Fallo al crear el EntityManager ", 600);
            }

            AppLogServices::logEvent(__FUNCTION__,'Se ha creado un EntityManager', ["_ID"=>self::setIdWork()], 250);
            
        } catch (Exception $th) {
            AppLogServices::logEvent(__FUNCTION__, $th->getMessage(), [
                "_ID"                       => self::setIdWork(),
                "TRACE_INTERNAL_MESSAGE"    => $th->getMessage(),
                "TRACE_INTERNAL_CODE"       => $th->getCode()
            ], 400);
        }
        
        return $em;
    }

    public static function setIdWork()
    {
         
        if (!isset($workID)) {
            $mark = (string) time();
            $workID = md5($mark);
        }

        return $workID;
    }

    public static function getApp()
    {

        try {

            if (!file_exists(self::FILE_CONF)) {
                throw new Exception("El archivo no existe", 600);
            }

            $get_data = file_get_contents(self::FILE_CONF);
            $data = json_decode($get_data, true);

        } catch (\Exception $e) {
            //throw $th;
            $data = [];

            AppLogServices::logEvent(__FUNCTION__,$e->getMessage(), ["_ID"=>self::setIdWork(), "El archivo no existe: "=> self::FILE_CONF], $e->getCode());

        }

        return $data;
    }

    /**
     * Extrae los valores de configuracion
     * @param string $conf
     * @return mixed
     */
    public static function appData(string $conf)
    {
        $data = self::getApp();
        $data_conf = $data[$conf];
        return $data_conf;
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
            AppLogServices::logEvent(__FUNCTION__,$e->getMessage(), ["_ID"=>self::setIdWork(), "ruta solicitada: "=>$path], 400);
        }

        return $data_paths;
    }
}