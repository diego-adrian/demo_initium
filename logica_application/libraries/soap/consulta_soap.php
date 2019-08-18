<?php
class Consulta_soap {
   
    public function __construct() {
        
    }
    
    public function GetOpcionesSoap(){
        $options = array('cache_wsdl' => WSDL_CACHE_NONE, 'encoding' => 'utf-8', 'soap_version' => SOAP_1_1, 'exceptions' => true, 'trace' => true);
        return $options;
    }
    
    public function ConvertirObjeto_Array($result) {
        $array = array();
        foreach ($result as $key => $value) {
            if (is_object($value)) {
                $array[$key] = $this->ConvertirObjeto_Array($value);
            } elseif (is_array($value)) {
                $array[$key] = $this->ConvertirObjeto_Array($value);
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    } 
}
?>