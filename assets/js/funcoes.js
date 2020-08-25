$(document).ready(function(){
	$(document).on('click','#btn-new-contact',function(){
		$('#modal_new_contact').modal('show');
	});

	$(document).on('click','#save-new-contact',function(){
		var nome = $('#nome').val();
		var data_nascimento = $('#data_nascimento').val();
		var cpf = $('#cpf').val();
		var identidade = $('#identidade').val();
		var endereco = $('#endereco').val();
		var telefone = $('#telefone').val();
		var celular = $('#celular').val();

		$.ajax({
			url: CI_ROOT + 'contacts/create',
			data: {
				nome: nome,
				data_nascimento: data_nascimento,
				cpf: cpf,
				identidade: identidade,
				endereco: endereco,
				telefone: telefone,
				celular: celular,
			},
			dataType: 'json',
			type: 'post',
			success: function(dados){
				if(dados.return == true){
					location.reload();
				}else{
					alert('Error request by Ajax');
				}
			}
		});
	});

	/**
     * Confirmação de exclusao de registros
     */
     $(document).on('click', '.deletar', function(){
        $('#modal_exclusao').modal('show');
        href = $(this).attr('href');

        return false;
     });

     $('.confirmExclusao').click(function(){
        var confirmacao = $(this).attr('value'); 

        if(confirmacao == 'true'){
            location.href = href;
        
        }else{
            $('#loading').modal('hide');
        }

        $('#modal_exclusao').modal('hide');
    });

	/*
	 * MASCARA NOS CAMPOS - jQuery Mask <https://igorescobar.github.io/jQuery-Mask-Plugin/>
	 */

	var maskBehavior = function (val) {
		return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
	options = {onKeyPress: function(val, e, field, options) {
			field.mask(maskBehavior.apply({}, arguments), options);
		}
	};

	$('.telefone').mask(maskBehavior, options);
	$('.data').mask('00/00/0000');
	$('.cpf').mask('000.000.000-00');
    $('.data').datepicker({
    	language: 'pt-BR',
    	format: 'dd/mm/yyyy',
    	orientation: 'bottom'
    });    
    $('#datepicker').datepicker({
    	language: 'pt-BR',
    	format: 'dd/mm/yyyy',
    	orientation: 'top'
    });

    var mask = function (val) {
		return val.replace(/\D/g, '').length <= 11 ? '000.000.000-009' : '00.000.000/0000-00';
	},
	keyPressOptions = {onKeyPress: function(val, e, field, keyPressOptions) {
			field.mask(mask.apply({}, arguments), keyPressOptions);
		}
	};
	$('.cpf_cnpj').mask(mask, keyPressOptions);

});