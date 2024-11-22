function calcula_metrado(){
	m1 = document.getElementById("precio_metrado").value;
	m2 = document.getElementById("metrado").value;
	r = m1*m2;
	document.getElementById("importe_dolar").value = r;
	}

function calcula_soles(){
	x1 = document.getElementById("importe_dolar").value;
	x2 = document.getElementById("tc").value;
	w = x1*x2;
	document.getElementById("importe_soles").value = w;
	}

function calcula_garantia_soles(){
	n1 = document.getElementById("importe_soles").value;
	n2 = document.getElementById("meses_garantia").value;
	a = n1*n2;
	document.getElementById("importe_garantia_soles").value = a;
	}	
	