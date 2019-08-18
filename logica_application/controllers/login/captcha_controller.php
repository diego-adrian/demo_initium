<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Captcha_ci
 *
 * @author 
 */
class captcha_controller  extends CI_Controller {
  private $_charNum = 6;
   
  public function __construct()
  {
    parent::__construct();
    session_start();
    //$this->load->library('session');
    //$this->rand = random_string('alnum', 6);	
    //$this->load->driver('session');    
    //$this->load->helper('captcha');
  }
  
    public function captcha() {
        //configuramos el captcha
        $text = $this->_randomText($this->_charNum);
        $this->load->helper('captcha');
        $conf_captcha = array(
            'word' => $text,
            //'word' => 'ABCDEFGHIJ',
            'img_path' => './captcha/',
            'img_url' => 'captcha/',
            'font_path' => './css/comic.ttf',            
            'img_width' => '150',
            'img_height' => '40',            
            'expiration' => 100
        );
        
        $cap = create_captcha($conf_captcha);
        //print_r ($conf_captcha['img_url'].' - '.$cap['image'].' - '.$conf_captcha['word'] );exit;
        $_SESSION["session_informacion"]=array("captcha_img"=>$text);
        //print_r ($cap['image'].' '.$conf_captcha['word'] );exit;                
        
        $this->load->view('login/view_captcha', $cap);
        return $cap;
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


