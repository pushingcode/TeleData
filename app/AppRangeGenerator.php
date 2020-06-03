<?php

declare(strict_types = 1);

namespace App;
use App\AppLogServices;


/**
 * Genera un Arreglo a partir de una matriz
 * @example AppRangeGenerator::makeRange(array(A,AB));
 * @author  Carlos Guillen <code_dev@zoho.com>
 * @version Revision: 1.1.2
 * @access public
 * @since PHP 7.3.5
 * 
 * @method array makeRange(array)
 * @method array iterador(array)
 * @method array stringToArray(string)
 * @method array setRange(string)
 * @method bool checkRange(array)
 */
class AppRangeGenerator
{
    /**
     * Crea un arreglo con rango alfabetico a partir de un arreglo [AA,XX]
     * @Throwable
     * @test
     * @access public
     * @param array $array
     * @return mixed
     * @since PHP 7.3.5
     */
    public static function makeRange(array $a)
    {
        //generamos atributos las partes del arreglo
        foreach ($a as $attrib) {
            $array[] = self::stringToArray($attrib);
        }
        
        try {
            self::checkRange($array);
            
            $arrayFinal = self::iterador($array);

        } catch (\Exception $e) {
            /*$arrayFinal = [
                'Excepcion: '   => $e->getMessage(),
                'Codigo'        => $e->getCode()
            ];*/
            $arrayFinal = false;
            AppLogServices::logEvent(__FUNCTION__,$e->getMessage(),[],$e->getCode());
        }
        
        return $arrayFinal;
    }


    /**
     * Iterador motor del metodo makeRange
     * @test
     * @access public
     * @param array $array
     * @return mixed
     * @since PHP 7.3.5
     */
    public static function iterador(array $arrayD)
    {
        /*
         * Caso de Uso A-? Return true
         * Caso de Uso ?-?? Return true
         * Caso de Uso ??-?? Return true
        */

        $range = self::setRange('A');

        //caso de uso A-?
        if ($arrayD[0]["count"] == 1 && $arrayD[1]["count"] == 1) {
            $array = range($arrayD[0]["raw"], $arrayD[1]["raw"]);
        }

        //caso de uso ?-??
        if ($arrayD[0]["count"] == 1 && $arrayD[1]["count"] == 2) {
            //creamos el rango de ?-z
            $array_1 = self::setRange($arrayD[0]["raw"]);
            //creamos la primera iteracion de A-$array[1]['split'][0]
            $iterador = range('A', $arrayD[1]["split"][0]);
            //bucle de iteracion
            foreach ($iterador as $value) {
                foreach ($range as $terminal) {
                    if ($value.''.$terminal == $arrayD[1]["raw"]) {
                        $array_2[] = $value.''.$terminal;
                        break;
                    } else {
                        $array_2[] = $value.''.$terminal;
                    }
                }
                
            }
            //salida
            $array = array_merge($array_1, $array_2);
        }

        //caso de uso ??-??
        if ($arrayD[0]["count"] == 2 && $arrayD[1]["count"] == 2) {
            $iterador = range($arrayD[0]["split"][0], $arrayD[1]["split"][0]);
            //bucle de iteracion
            foreach ($iterador as $value) {
                foreach ($range as $terminal) {
                    if ($value.''.$terminal == $arrayD[1]["raw"]) {
                        $arrayT[] = $value.''.$terminal;
                        break;
                    } else {
                        $arrayT[] = $value.''.$terminal;
                    }
                }
            }
            $array = $arrayT;
        }

        return $array;

    }

    /**
     * Toma una cadena y la transforma en un arreglo
     * con propiedades: strength[int], raw[str], split[arr], count[int] 
     * @test
     * @access public
     * @param string $string
     * @return array
     * @since PHP 7.3.5 
     */
    public static function stringToArray(string $string)
    {
        $strength = null;
        $array = str_split($string);
        
        foreach ($array as $value) {
            $strength .= array_search($value, range('A','Z')) + 1;
        }
        
        if (count($array) == 2) {
            $strength = $strength * count($array) + 5;
        }
 
        $array = [
            "strength"      => (int) $strength,
            "raw"           => $string,
            "split"         => $array,
            "count"         => count($array)
        ];

        return $array;
    }

    /**
     * Genera un arreglo del tipo X->Z a partir de la 
     * cadena X expresada en $string
     * @test
     * @access public
     * @param string $string
     * @return mixed
     * @since PHP 7.3.5
     */
    public static function setRange(string $string)
    {

        $patron = "/^[0-9]+$/";

        if (preg_match($patron, $string)) {
            $salida = false;

        }elseif(strlen($string) > 1){
            $salida = false;

        }else{

            $string = strtoupper($string);
            $salida = range($string,'Z');

        }

        

        return $salida;
    }

    /**
     * Verifica que no exita flujo inverso en un arreglo [X,Y]-[Y,Z]
     * Establece una Politica de EDP al negar la lectura de mas de 3 niveles [XYZ]
     * @test
     * @access public
     * @param array $array
     * @return bool
     * @since PHP 7.3.5
     * @Trowable
     */
    public static function checkRange(array $array)
    {
        //
        
        $salida = true;
        if ($array[0]["strength"] >= $array[1]["strength"]) {
           
            throw new \Exception("Error, se genera un flujo inverso", 200);
            $salida = false;

        }

        if ($array[0]["count"] >= 3 or $array[1]["count"] >= 3) {
            
            throw new \Exception("Se alcanzo la Politica Prevencion de Ejecucion de Datos", 300);
            $salida = false;

        }

        return $salida;
    }
}