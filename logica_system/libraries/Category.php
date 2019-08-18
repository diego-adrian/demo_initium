<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category{

	 var $obarray, $list;
		 
	 function buildTree($catArray)
	 {
	 	 global $obarray, $list;
	 
	 	 $list = "<ul style=\"list-style-type: none;\">";
		 if (!is_array($catArray)) return '';
		 $obarray = $catArray;
		 
		 foreach($obarray as $item){
		 	 if($item['parent'] == 0){
		 	 	 $mainlist = $this->_buildElements($item, 0);
		 	 }
		 }
		 $list .= "</ul>";
		 return $list;
	 }
	 
	 private function _buildElements($parent, $append)
	 {
	 	 global $obarray, $list;
	 	 
	 	 
		 if($this->_hasChild($parent['id'])){
                     $list .= '<li style="text-decoration: none;"><div id="divElemento'.$parent['id'].'" padre="'.$parent['parent'].'"  class="TextoDescriptivo Contraido" style="">'
                         . '<a id="link'.$parent['id'].'"  onclick="Elementos_MostrarDivTree('.$parent['id'].','.$parent['parent'].');">' . $parent['name'] . '</a><hr noshade="noshade"/></div></li>';
                
                     $list .= "<div style='display:none' id='Hijo".$parent['id']."' padre='".$parent['parent']."'>";
		 	 $append++;
		 	 $list .= "<ul style=\"list-style-type: none;\">";
		 	 $child = $this->_buildArray($parent['id']);

			 foreach($child as $item){
				 $list .= $this->_buildElements($item, $append);
			 }
			 $list .= "</ul>";
                     $list .= "</div>";        
                 }else{
                    $list .= '<li style="text-decoration: none;"><div  id="divElemento'.$parent['id'].'" padre="'.$parent['parent'].'" class="TextoDescriptivo Expuesto ClasificacionOpcion">'
                         . '<a class="">' . $parent['name'] . '</a>';
                    $list.='<span style="display: none;"><span style="display:none;letter-spacing: 0.20em;color:#d0d0d0">----------';
                    //$list.='&nbsp;&nbsp;&nbsp;<img src="imagenes/arrow-right.png"/>&nbsp;&nbsp;&nbsp;';
                    $list.='</span>';
                    $list .='<a class="BotonMinimo" onclick="Ajax_MostrarClasificacionSeleccionar(true,\''.$parent['name'].'\' ,\''.$parent['id'].'\' , \''.$parent['clave'].'\')"  class="">SELECCIONAR</a></span>';
                    $list.='<hr noshade="noshade" /></div></li>';
                 
                 }
                 
	 }
	 
	 private function _hasChild($parent)
	 {
	 	 global $obarray;
		 $counter = 0;
		 foreach($obarray as $item){
			 if($item['parent'] == $parent){
				 ++$counter;
			 }
		 }
		 return $counter;
	 }
	 
	 private function _buildArray($parent)
	 {
	 	 global $obarray;
		 $bArray = array();
		 
		 foreach($obarray as $item){
			 if($item['parent'] == $parent){
				 array_push($bArray, $item);
			 }
		 }
		 
		 return $bArray;
	 }

 }