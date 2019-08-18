//$(function() {
//    $('input[name=Number]').on('keyup change blur',function() {
//        Calculate( this.form );
//    });
//    Calculate( $('form')[0] );
//});

function CargarEventoNumeros(divnombre){
    NumeroPalabrasLeyenda(divnombre);
     $(function () {
        $('#'+divnombre).on('keyup change blur', function () {
        NumeroPalabrasLeyenda(divnombre);
        });        
    });
}



function NumeroPalabrasLeyenda(divnombre) {
        if($("#" + divnombre).val()=="")return;
        var texto = NumerosPalabras($("#" + divnombre).val());
        if ($('#' + divnombre + '_texto').length) {
            $('#' + divnombre + '_texto').html(texto);
        }
        else {
            $("#" + divnombre).parent().append('<div id="' + divnombre + '_texto" class="campoleyendanumero">' + texto + '</div><br />');
        }
    }

function FixSpaces(s){
//returns a string with no double spaces inside
	var t='';
	for(var i=0; i<s.length; i++){
		if (i>0){
			if (!((s.substring(i-1,i)==' ')&(s.substring(i,i+1)==' ')))
				t=t+s.substring(i,i+1);
		}else
			t=t+s.substring(i,i+1);
	}
	return t;
}

function Calculate(form)  {
//fill forms with text
	var Ner = form.Number.value; 
	form.Spanish.value = NumerosPalabras(Ner);
 
}

function REPLICATE(n, c){
//returns n times char c
	var t='';
	for(var i=1; i<=n; i++)
		t=t+c;
	return t;
}

function NumerosPalabras(s)  {
//Spanish words for numbers
var strSeparadorDecimal = ',';
var strSeparadorMiles = '.';
    s = s.replace(strSeparadorMiles,'');
	var a,b,c,j,orlen,result=' ',
        cents = s.split(strSeparadorDecimal)[1] || '00',
        s = s.split(strSeparadorDecimal)[0] || s;
	if (s=='0') {return ('CERO');}
		orlen=s.length;
	if ((s.length % 3)>0)
		s=' '+s;
	if ((s.length % 3)>0)
		s=' '+s;
	for (var i = 0; i < s.length; i=i+3) {
		j=s.length-i-1;
		a=s.substring(j, j+1);
		b=s.substring(j-1, j);
		c=s.substring(j-2, j-1);
		if (a!=' '){
			if ((i==3)&(c+b+a!=strSeparadorMiles+'000') ) {result='MIL '+result;}
			else if (((i==6)&(c+b+a!=strSeparadorMiles+'000') ) &(orlen==7)&(a=='1')) {result='MILLÓN '+result;}
			else if ((i==6)&(c+b+a!=strSeparadorMiles+'000') ) {result='MILLONES '+result;}
			else if ((i==9)&(c+b+a!=strSeparadorMiles+'000') ) {result='MIL MILLONES '+result;}
		}
		if ((b!=1) | (b==' ')){
			if (a==1){result='UN '+result;}
			else if (a==2){result='DOS '+result;}
			else if (a==3){result='TRES '+result;}
			else if (a==4){result='CUATRO '+result;}
			else if (a==5){result='CINCO '+result;}
			else if (a==6){result='SEIS '+result;}
			else if (a==7){result='SIETE '+result;}
			else if (a==8){result='OCHO '+result;}
			else if (a==9){result='NUEVE '+result;}
		}
		if ((b!=' ')&(b!=strSeparadorMiles+'0')){
			if ((b==1) | (b==2)){
				if (b+a==10){result='DIEZ '+result;}
				else if (b+a==11){result='ONCE '+result;}
				else if (b+a==12){result='DOCE '+result;}
				else if (b+a==13){result='TRECE '+result;}
				else if (b+a==14){result='CATORCE '+result;}
				else if (b+a==15){result='QUINCE '+result;}
				else if (b+a==16){result='DIECISÉIS '+result;}
				else if (b+a==17){result='DIECISIETE '+result;}
				else if (b+a==18){result='DIECIOCHO '+result;}
    			else if (b+a==19){result='DIECINUEVE '+result;}
				else if (b+a==20){result='VEINTE '+result;}
				else if (b+a==21){result='VEINTI'+result;}
				else if (b+a==22){result='VEINTI'+result;}
				else if (b+a==23){result='VEINTI'+result;}
				else if (b+a==24){result='VEINTI'+result;}
				else if (b+a==25){result='VEINTI'+result;}
				else if (b+a==26){result='VEINTI'+result;}
				else if (b+a==27){result='VEINTI'+result;}
				else if (b+a==28){result='VEINTI'+result;}
				else if (b+a==29){result='VEINTI'+result;}
			}
			else{
				var temp=''
				if (a!=0){temp='Y ';}
				if (b==3){result='TREINTA '+temp+result;}
				else if (b==4){result='CUARENTA '+temp+result;}
				else if (b==5){result='CINCUENTA '+temp+result;}
				else if (b==6){result='SESENTA '+temp+result;}
				else if (b==7){result='SETENTA '+temp+result;}
				else if (b==8){result='OCHENTA '+temp+result;}
				else if (b==9){result='NOVENTA '+temp+result;}
				
				}
		}
		if ((c!=' ')&(c!=strSeparadorMiles+'0')){
			if ((a==strSeparadorMiles+'0') & (b==strSeparadorMiles+'0')){
				if (c==1){result='CIEN '+result;}
				else if (c==2){result='DOSCIENTOS '+result;}
				else if (c==3){result='TRESCIENTOS '+result;}
				else if (c==4){result='CUATROCIENTOS '+result;}
				else if (c==5){result='QUINIENTOS '+result;}
				else if (c==6){result='SEISCIENTOS '+result;}
				else if (c==7){result='SETECIENTOS '+result;}
				else if (c==8){result='OCHOCIENTOS '+result;}
				else if (c==9){result='NOVECIENTOS '+result;}
			}
			else{
				if (c==1){result='CIENTO '+result;}
				else if (c==2){result='DOSCIENTOS '+result;}
				else if (c==3){result='TRESCIENTOS '+result;}
				else if (c==4){result='CUATROCIENTOS '+result;}
				else if (c==5){result='QUINIENTOS '+result;}
				else if (c==6){result='SEISCIENTOS '+result;}
				else if (c==7){result='SETECIENTOS '+result;}
				else if (c==8){result='OCHOCIENTOS '+result;}
				else if (c==9){result='NOVECIENTOS '+result;}
			}
		}
	}
	result=FixSpaces(result);
	result=Trim(result);
	if (result.substring(0, 7)=='MIL ') result=result.substring(3,result.length);
	if (result.substring(result.length-3, result.length)==' UN') result=result+'o';
	if (result=='UN ') result='uno';
	if (result.substring(result.length-2, result.length)=='Y ') 
		result=result.substring(0,result.length-2);
    
if (InStr(result, 'MILLONES')!=RInStr(result, 'MILLONES'))
	{var z=InStr(result, 'MILLONES')
	result=result.substring(0,z-1)+result.substring(z+7,result.length);}
	result=FixSpaces(result);
	//return (result+" CON "+ cents +"/100 BOLIVIANOS");
        return (result+" "+ cents +"/100 BOLIVIANOS");
}
  
function InStr(n, s1, s2){
 	var numargs=InStr.arguments.length;
	
	if(numargs<3)
		return n.indexOf(s1)+1;
	else
		return s1.indexOf(s2, n)+1;
}

function RInStr(n, s1, s2){
	var numargs=RInStr.arguments.length;
	
	if(numargs<3)
		return n.lastIndexOf(s1)+1;
	else
		return s1.lastIndexOf(s2, n)+1;
}

function LTrim(s){
	// Devuelve una cadena sin los espacios del principio
	var i=0;
	var j=0;
	
	// Busca el primer caracter <> de un espacio
	for(i=0; i<=s.length-1; i++)
		if(s.substring(i,i+1) != ' '){
			j=i;
			break;
		}
	return s.substring(j, s.length);
}

function RTrim(s){
	// Quita los espacios en blanco del final de la cadena
	var j=0;
	
	// Busca el último caracter <> de un espacio
	for(var i=s.length-1; i>-1; i--)
		if(s.substring(i,i+1) != ' '){
			j=i;
			break;
		}
	return s.substring(0, j+1);
}

function Trim(s){
	// Quita los espacios del principio y del final
	return LTrim(RTrim(s));
} 


