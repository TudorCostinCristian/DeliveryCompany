$(document).ready(function(){
	$('input#redeemcode').on('click', function(){
		var friendcode = $('input#friendcode').val();
		if($.trim(friendcode) != ''){
			$.post('ajax/promotii.php',{friendcode: friendcode}, function(data){
				$('input#friendcode').val('');
				if(data == 1) 
				{
					swal("Eroare!", "Codul introdus nu a fost gasit in baza de date!", "error");
				}
				else if(data == 2)
				{
					swal("Eroare!", "Nu poti sa iti folosesti propriul cod de afiliat!", "error");
				}
				else if(data == 3)
				{
					swal("Eroare!", "Ai folosit deja un cod de afiliat!", "error");
				}
				else if(data == -1)
				{
					swal("Eroare!", "Nu esti logat!", "error");
				}
				else{
					swal("Felicitari!", "Ai primit 15 RON discount la urmatoarea livrare!", "success");
				}
			});
		}
		else{
			swal("Eroare!", "Codul introdus nu a fost gasit in baza de date!", "error");
		}
	});
	
	$('input#setcode').on('click', function(){
		var codetoset = $('input#codetoset').val();
		if($.trim(codetoset) != ''){
			if(codetoset.length > 15)
			{
				swal("Eroare!", "Codul de afiliat nu trebuie sa fie mai lung de 15 caractere!", "error");
			}
			else if(codetoset.length < 4)
			{
				swal("Eroare!", "Codul de afiliat trebuie sa aiba o lungime de cel putin 4 caractere!", "error");
			}
			else{
			$.post('ajax/seteazacod.php',{codetoset: codetoset}, function(data){
				if(data == -1)
				{
					swal("Eroare!", "Nu esti logat!", "error");
				}
				else if(data == 4) 
				{
					swal("Eroare!", "Acest cod este deja folosit!", "error");
				}
				else if(data == 1) 
				{
					swal("Eroare!", "Codul de afiliat trebuie sa aiba o lungime de cel putin 4 caractere!", "error");
				}
				else if(data == 2) 
				{
					swal("Eroare!", "Codul de afiliat nu trebuie sa fie mai lung de 15 caractere!", "error");
				}
				else if(data == 3)
				{
					swal("Eroare!", "Codul de afiliat trebuie sa contina doar litere si numere!", "error");
				}
				else
				{
					swal("Felicitari!", "Ti-ai setat codul de afiliat cu succes!", "success");
				}
			});
			}
		}
		else{
			swal("Eroare!", "Codul de afiliat trebuie sa aiba o lungime de cel putin 4 caractere!", "error");
		}
	});	
	
	
});