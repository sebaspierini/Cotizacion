<?php
    require('Cotizacion.php');
    require('CotizacionApi.php');
    require('Validacion.php');    
    try{
        $monto = 0;
        $exit = false;
        while (!$exit){
                echo "Elegir la opción que desea:\n";
                echo "1 -> Cotización\n";
                echo "2 -> Salir\n";
                fscanf(STDIN, "%s\n", $menu);            
                $es_entero =  Validacion::es_entero($menu,'item');                                       
                if(!$es_entero['exito']){
                    echo $es_entero['mensaje'];                    
                    $menu = 0;
                }
                $menu = intval($menu);
                if($menu === 1){
                    $monto = 0;
                    while ($monto <= 0){
                        echo "Introducir monto. Ej: 20 o 1.23\n ";
                        fscanf(STDIN, "%s\n", $monto);        
                        $es_float =  Validacion::es_decimal($monto,'monto');                                       
                        if(!$es_float['exito']){
                            echo $es_float['mensaje'];                            
                            $monto = 0;
                        }
                    } 
                    $moneda = 0;                   
                    while($moneda <=0){
                        echo "Elegir la opción de moneda que desea convertir a dólar:\n";
                        echo "1 -> Peso Argentino\n";
                        echo "2 -> Euro\n";
                        fscanf(STDIN, "%s\n", $moneda);                                    
                        $es_entero =  Validacion::es_entero($moneda,'item');                                       
                        if(!$es_entero['exito']){
                            echo $es_entero['mensaje']; 
                            $moneda = 0;
                        }
                        $moneda = intval($moneda);
                        
                        switch ($moneda) {
                            case 1:                    
                                $val = 'ARS'; 
                                break;
                            case 2:
                                $val = 'EUR';
                                break;
                        }  
                    }
                    echo "------------------------\n";
                    echo "Obteniendo resultados...\n";
                    echo "------------------------\n";
                    $monto = floatval($monto);  
                    
                    // Obtengo los resultados                  
                    $resultado_cotizacion = Cotizacion::obtener_valor_usd($val);                                                 
                    $resultado_calculo = Cotizacion::calcular_usd($monto,$val);    
                    // Si el sitio falla busco por otro  
                    if(empty($resultado_cotizacion ) or empty($resultado_calculo)){
                        $resultado_cotizacion = CotizacionApi::obtener_valor_usd($val);                                        
                        $resultado_calculo = CotizacionApi::calcular_usd($monto,$val);
                    }    
                    if(empty($resultado_cotizacion ) or empty($resultado_calculo)){
                        echo "No se pudo obtener la cotización\n";
                    }             
                    echo "Cotizacion del dolar: ".$resultado_cotizacion ."\n";
                    echo "El resultado de calcular: ". $monto. " $val es: ".$resultado_calculo." USD \n"; 
                    echo "------------------------------------------------\n";
                    echo "Presione enter para continuar\n";
                    fscanf(STDIN, "%s\n", $enter);    
                }
                if($menu === 2){                                                     
                    $exit = true;    
                }
        }
        echo "Saludos!!";
        
    }
    catch (Exception $e) {     
        echo $e->getMessage();
    }

?>