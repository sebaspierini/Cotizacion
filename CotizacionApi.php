<?php
class CotizacionApi
{
    
    /**
     * Obtiene las cotizaciones del sitio web https://api.fastforex.io/
     * La api de fastforex permite ver los valores en relación a 1 USD
     * Permite 1000 consultas mensuales
     */
    public function obtener_cotizaciones() {
        try{
            // se invoca a curl para conectarse al sitio web
            $curl = curl_init();

            curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.fastforex.io/fetch-all?from=USD&api_key=3f3d6583a7-c4bbb13b87-qqnnmx",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json"
            ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
                
            $result = null;
            if(!$err){
                if(!empty($response)){
                    // Armo una respuesta para devolver el valor dolar y el valor euro de la api
                    $response = json_decode($response, true);
                    $dolar = floatval($response['results']['ARS']);
                    $euro = floatval($response['results']['EUR']);
                    $result = array('dolar'=>$dolar,'euro'=>$euro,'exito'=>true);                                     
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
     */
    public function calcular_usd($valor,$cambio){
        try{
            
            $cotizaciones = self::obtener_cotizaciones();
            if($cotizaciones){
                $valor = floatval($valor);            
                $calcular = 0;
                // Chequeo la existencia de los valores del sitio web
                if (!$cotizaciones['dolar'] || !$cotizaciones['euro']) {
                    echo "No se encontraron resultados para la cotización\n";
                    return 0;
                }
                // Realizo el cálculo correspondiente convirtiendo a dolar la moneda que le paso como argumento.
                switch ($cambio) {
                    case 'ARS':                    
                        $calcular = (1 / $cotizaciones['dolar']) * $valor ;
                        break;
                    case 'EUR':
                        $calcular = (1 / $cotizaciones['euro']) * $valor;
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
                        $calcular = 1 / $cotizaciones['euro'];
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