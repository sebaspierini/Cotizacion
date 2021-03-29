<?php
class Cotizacion
{
    
    /**
     * Obtiene las cotizaciones del euro y dolar del sitio web https://www.dolarsi.com
     */
    public function obtener_cotizaciones() {
        try{
            // se invoca a curl para conectarse al sitio web
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "https://www.dolarsi.com/api/api.php?type=cotizador",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",            
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json"
            ],           
            ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $result = null;
            if(!$err){
                if(!empty($response)){
                    // Armo una respuesta para devolver el valor dolar y el valor euro de la api
                    $response = json_decode($response, true); 
                      
                    $dolar = floatval(str_replace(',','.',$response[0]['casa']['venta']));
                    $euro = floatval(str_replace(',','.',$response[1]['casa']['venta']));
                    $result = array('dolar'=>$dolar,'euro'=>$euro);
                }                          
            }else{
                echo "CURL Error #:" . $err; 
            }       
                                       
            return $result;
        }catch (Exception $e) {                       
            echo $e->getMessage();            
        }
    }

    /**
     * Realiza el cálculo según el tipo de cambio (ARS, EUR) a dolar
     * valor: es el monto
     * cambio: es el tipo de cambio (ARS,EUR)
     */
    public function calcular_usd($valor,$cambio){
        try{            
            $cotizaciones = self::obtener_cotizaciones();
            if($cotizaciones){
                $calcular = 0;
                $valor = floatval($valor);
                // Chequeo la existencia de los valores del sitio web
                if (!$cotizaciones['dolar'] || !$cotizaciones['euro']) {
                    echo "No se encontraron resultados para la cotización\n";
                    return 0;
                }
                // Realizo el cálculo correspondiente convirtiendo a dolar la moneda que le paso como argumento.
                switch ($cambio) {
                    case 'ARS':
                        $calcular = $valor / $cotizaciones['dolar'] ;
                        break;
                    case 'EUR':
                        $calcular = ($cotizaciones['euro'] / $cotizaciones['dolar']) * $valor;
                        break;
                }            
                return number_format($calcular,2);
            }else{                             
                return 0;
            }
            
        }catch (Exception $e) {            
            echo $e->getMessage();            
        }
    }

    /**
     * Devuelve el valor del dolar equivalente a 1
     * cambio: es el tipo de cambio (ARS,EUR)
     */
    public function obtener_valor_usd($cambio){
        try{            
            $cotizaciones = self::obtener_cotizaciones();
            if($cotizaciones){
                // Chequeo la existencia de los valores del sitio web
                if (!$cotizaciones['dolar'] || !$cotizaciones['euro']) {
                    echo "No se encontraron resultados para la cotización\n";
                    return 0;
                }
                // Realizo el cálculo correspondiente según el cambio.
                switch ($cambio) {
                    case 'ARS':
                        $calcular = $cotizaciones['dolar'];
                        break;
                    case 'EUR':
                        $calcular = $cotizaciones['euro'] / $cotizaciones['dolar'];
                        break;
                }            
                return number_format($calcular,2);                    
            }else{                             
                return 0;
            }
            
        }catch (Exception $e) {            
            echo $e->getMessage();            
        }
    }
}

?>