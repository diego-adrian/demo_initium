<?php
/*
Ejemplo de uso:
 * $this->load->library('LibreriasPersonalizadas/Construccion_captcha');
   $strTextoCaptcha = "";
   $imgTagHtml =  $this->construccion_captcha->GetCaptcha($strTextoCaptcha);
   $data["imgTagHtml"]= $imgTagHtml; //imagen
   echo "---------->".$strTextoCaptcha; //texto
 *  */
class Construccion_captcha   {
  private $_charNum = 4;
   
  public function __construct()
  {
    $CI = & get_instance();
    $CI->load->helper('captcha');
    //session_start();
  }
  
    public function GetCaptcha(&$strTexto) {
        //configuramos el captcha
        $text = $this->_randomText($this->_charNum);
        echo $text;
        $conf_captcha = array(
            'word' => $text,
            'img_path' => 'html_public/captcha/',
            'img_url' => 'html_public/captcha/',
            'font_path' => 'html_public/css/comic.ttf',            
            'img_width' => '150',
            'img_height' => '40',            
            'expiration' => 100
        );
        $cap = create_captcha($conf_captcha);
        $strTexto = $text;
        return $cap['image'];
    }
    
    public function GetCaptchaExterno(&$strTexto) {
        //configuramos el captcha
        $text = $this->_randomText($this->_charNum);
        $conf_captcha = array(
            'word' => $text,
            'img_path' => 'html_public/captcha/',
            'img_url' => '../../html_public/captcha/',
            'font_path' => 'html_public/css/comic.ttf',            
            'img_width' => '150',
            'img_height' => '40',            
            'expiration' => 100
        );
        $cap = create_captcha($conf_captcha);
        $strTexto = $text;
        return $cap['image'];
    }
    
    private function _randomText($charNum)
    {
        $text = '';
        $chars = "1234567890abcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $charNum; $i++) {
            $text .= $chars[rand(0, 35)];
        }
        return $text;
    }
               
}
?>