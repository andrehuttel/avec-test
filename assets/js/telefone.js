var countries = {'af': 'Afeganistão', 'al': 'Albânia', 'dz': 'Argélia', 'as': 'Samoa Americana', 'ad': 'Andorra', 'ao': 'Angola', 'ai': 'Anguila', 'aq': 'Antártica'  , 'ag': 'Antigua and Barbuda' , 'ar': 'Argentina' , 'am': 'Armênia' , 'aw': 'Aruba' , 'au': 'Austrália' , 'at': 'Áustria' , 'az': 'Azerbaijão'  , 'bh': 'Bahrein' , 'bd': 'Bangladesh', 'bb': 'Barbados'  , 'by': 'Bielorrússia(Belarus)', 'be': 'Bélgica', 'bz': 'Belize', 'bj': 'Benin', 'bm': 'Bermuda', 'bt': 'Butão', 'bo': 'Bolívia', 'ba': 'Bósnia Herzegóvina', 'bw': 'Botsuana', 'bv': 'Ilha Bouvet', 'br': 'Brasil', 'io': 'Território Britânico do Oceano Índico', 'vg': 'Ilhas Virgens Britânicas', 'bn': 'Brunei', 'bg': 'Bulgária', 'bf': 'Burkina-Fasso', 'bi': 'Burundi', 'ci': 'Costa do Marfim', 'kh': 'Camboja', 'cm': 'Camarões', 'ca': 'Canadá', 'cv': 'Cabo Verde', 'ky': 'Ilhas Cayman', 'cf': 'Rep. Centro-Africana', 'td': 'Chade', 'cl': 'Chile', 'cn': 'China', 'cx': 'Ilha Christmas', 'cc': 'Ilhas Cocos(Keeling)', 'co': 'Colômbia', 'km': 'Comores', 'cg': 'Congo', 'ck': 'Ilhas Cook', 'cr': 'Costa Rica', 'hr': 'Croácia', 'cu': 'Cuba', 'cy': 'Chipre', 'cz': 'República Tcheca', 'cd': 'República Democrática do Congo ou Zaire', 'dk': 'Dinamarca', 'dj': 'Djibuti', 'dm': 'Dominica', 'do': 'Rep. Dominicana', 'tp': 'East Timor', 'ec': 'Equador', 'eg': 'Egito', 'sv': 'El Salvador', 'gq': 'Guiné Equatorial', 'er': 'Eritréia', 'ee': 'Estônia', 'et': 'Etiópia', 'fo': 'Ilhas Feroe', 'fk': 'Ilhas Malvinas', 'fj': 'Fiji', 'fi': 'Finlândia', 'mk': 'Macedônia', 'fr': 'França', 'fx': 'França Metropolitana', 'gf': 'Guiana Francesa', 'pf': 'Polinésia Francesa', 'tf': 'Terras Austrais e Antárticas Francesas', 'ga': 'Gabão', 'ge': 'Geórgia', 'de': 'Alemanha', 'gh': 'Gana', 'gi': 'Gibraltar', 'gr': 'Grécia', 'gl': 'Groenlândia(Dinamarca)', 'gd': 'Granada', 'gp': 'Guadalupe', 'gu': 'Guam', 'gt': 'Guatemala', 'gn': 'Guiné', 'gw': 'Guiné Bissau', 'gy': 'Guiana', 'ht': 'Haiti', 'hm': 'Ilha Heard e Ilhas McDonald', 'hn': 'Honduras', 'hk': 'Hong Kong', 'hu': 'Hungria', 'is': 'Islândia', 'in': 'Índia', 'id': 'Indonésia', 'ir': 'Irã', 'iq': 'Iraque', 'ie': 'Irlanda', 'il': 'Israel', 'it': 'Itália', 'jm': 'Jamaica', 'jp': 'Japão', 'jo': 'Jordânia', 'kz': 'Cazaquistão', 'ke': 'Quênia', 'ki': 'Kiribati', 'kw': 'Kuweit', 'kg': 'Quirguistão', 'la': 'Laos', 'lv': 'Letônia', 'lb': 'Líbano', 'ls': 'Lesoto', 'lr': 'Libéria', 'ly': 'Líbia', 'li': 'Liechtenstein', 'lt': 'Lituânia', 'lu': 'Luxemburgo', 'mo': 'Macau', 'mg': 'Madagascar', 'mw': 'Malauí', 'my': 'Malásia', 'mv': 'Maldivas', 'ml': 'Mali', 'mt': 'Malta', 'mh': 'Ilhas Marshall', 'mq': 'Martinica', 'mr': 'Mauritânia', 'mu': 'Maurício', 'yt': 'Mayotte', 'mx': 'México', 'fm': 'Micronésia', 'md': 'Moldávia', 'mc': 'Mônaco', 'mn': 'Mongólia', 'me': 'Montenegro', 'ms': 'Montserrat', 'ma': 'Marrocos', 'mz': 'Moçambique', 'mm': 'Mianmar', 'na': 'Namíbia', 'nr': 'Nauru', 'np': 'Nepal', 'nl': 'Holanda', 'an': 'Antilhas Neerlandesas', 'nc': 'Nova Caledónia', 'nz': 'Nova Zelândia', 'ni': 'Nicarágua', 'ne': 'Níger', 'ng': 'Nigéria', 'nu': 'Niue', 'nf': 'Ilha Norfolk', 'kp': 'Coréia do Norte', 'mp': 'Marianas Setentrionais', 'no': 'Noruega', 'om': 'Omã', 'pk': 'Paquistão', 'pw': 'Palau', 'ps': 'Palestina', 'pa': 'Panamá', 'pg': 'Papua Nova Guiné', 'py': 'Paraguai', 'pe': 'Peru', 'ph': 'Filipinas', 'pn': 'Ilhas Pitcairn', 'pl': 'Polônia', 'pt': 'Portugal', 'pr': 'Porto Rico', 'qa': 'Catar', 're': 'Reunion', 'ro': 'Romênia', 'ru': 'Rússia', 'rw': 'Ruanda', 'st': 'São Tomé e Príncipe', 'sh': 'Santa Helena', 'pm': 'Saint-Pierre e Miquelon', 'kn': 'São Cristóvão e Névis', 'lc': 'Santa Lúcia', 'vc': 'São Vicente e Granadinas', 'ws': 'Samoa', 'sm': 'San Marino', 'sa': 'Arábia Saudita', 'sn': 'Senegal', 'rs': 'Sérvia', 'sc': 'Seicheles', 'sl': 'Serra Leoa', 'sg': 'Singapura', 'sk': 'Eslováquia', 'si': 'Eslovênia', 'sb': 'Ilhas Salomão', 'so': 'Somália', 'za': 'África do Sul', 'gs': 'Ilhas Geórgia do Sul e Sandwich do Sul', 'kr': 'Coréia do Sul', 'es': 'Espanha', 'lk': 'Sri Lanka', 'sd': 'Sudão', 'sr': 'Suriname', 'sj': 'Svalbard e Jan Mayen', 'sz': 'Suazilândia', 'se': 'Suécia', 'ch': 'Suíça', 'sy': 'Síria', 'tw': 'Taiwan', 'tj': 'Tadjiquistão', 'tz': 'Tanzânia', 'th': 'Tailândia', 'bs': 'Bahamas', 'gm': 'Gâmbia', 'tg': 'Togo', 'tk': 'Tokelau', 'to': 'Tonga', 'tt': 'Trinidad e Tobago', 'tn': 'Tunísia', 'tr': 'Turquia', 'tm': 'Turcomenistão', 'tc': 'Turks e Caicos', 'tv': 'Tuvalu', 'vi': 'Ilhas Virgens Americanas', 'ug': 'Uganda', 'ua': 'Ucrânia', 'ae': 'Emirados Árabes Unidos', 'gb': 'Reino Unido', 'us': 'Estados Unidos', 'um': 'Ilhas Menores Distantes dos Estados Unidos', 'uy': 'Uruguai', 'uz': 'Uzbequistão', 'vu': 'Vanuatu', 'va': 'Vaticano', 've': 'Venezuela', 'vn': 'Vietnã', 'wf': 'Wallis e Futuna', 'eh': 'Saara Ocidental', 'ye': 'Iêmen', 'zm': 'Zâmbia', 'zw': 'Zimbábue'}

var maskBehavior = function(val){
	return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
},
options = {onKeyPress: function(val, e, field, options){
		field.mask(maskBehavior.apply({}, arguments), options);
	}
};

var telefone1 = document.querySelector("#telefone1");
var telefone2 = document.querySelector("#telefone2");
var telefone3 = document.querySelector("#telefone3");
var telefone4 = document.querySelector("#telefone4");
var telefone_filtro = document.querySelector("#telefone_filtro");

var defaultCountry1 = $('#pais_codigo1').val();
var defaultCountry2 = $('#pais_codigo2').val();
var defaultCountry3 = $('#pais_codigo3').val();
var defaultCountry4 = $('#pais_codigo4').val();

if(defaultCountry1 == 'br'){
	$('#telefone1').mask(maskBehavior, options);

}else if(defaultCountry1 == 'py'){
	$('#telefone1').mask('0000 000000');

}else{
	$('#telefone1').mask('000000000000000');
}

if(defaultCountry2 == 'br'){
	$('#telefone2').mask(maskBehavior, options);

}else if(defaultCountry2 == 'py'){
	$('#telefone2').mask('0000 000000');

}else{
	$('#telefone2').mask('000000000000000');
}

if(defaultCountry3 == 'br'){
	$('#telefone3').mask(maskBehavior, options);

}else if(defaultCountry3 == 'py'){
	$('#telefone3').mask('0000 000000');

}else{
	$('#telefone3').mask('000000000000000');
}

if(defaultCountry4 == 'br'){
	$('#telefone4').mask(maskBehavior, options);

}else if(defaultCountry4 == 'py'){
	$('#telefone4').mask('0000 000000');

}else{
	$('#telefone4').mask('000000000000000');
}

$('#telefone_filtro').mask(maskBehavior, options);

if(telefone1){
    var util1 = window.intlTelInput(telefone1, {
		initialCountry: defaultCountry1,
		localizedCountries: countries,
		preferredCountries: ['br', 'py'],
		utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
    });
}

if(telefone2){
    var util2 = window.intlTelInput(telefone2, {
		initialCountry: defaultCountry2,
		localizedCountries: countries,
		preferredCountries: ['br', 'py'],
		utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
    });
}

if(telefone3){
    var util3 = window.intlTelInput(telefone3, {
		initialCountry: defaultCountry3,
		localizedCountries: countries,
		preferredCountries: ['br', 'py'],
		utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
    });
}

if(telefone4){
    var util4 = window.intlTelInput(telefone4, {
		initialCountry: defaultCountry4,
		localizedCountries: countries,
		preferredCountries: ['br', 'py'],
		utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
    });
}

if(telefone_filtro){
    var util_filtro = window.intlTelInput(telefone_filtro, {
		initialCountry: 'br',
		localizedCountries: countries,
		preferredCountries: ['br', 'py'],
		utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
    });
}

/**
 * Evento alteração de pais do telefone 1
 */
if(telefone1){
	telefone1.addEventListener('countrychange', function(e){
		var pais_cod = util1.getSelectedCountryData().iso2;
		var ddi = util1.getSelectedCountryData().dialCode;

		$('#pais_codigo1').val(pais_cod);
		$('#ddi1').val(ddi);

		if(pais_cod == 'py'){
			$('#telefone1').mask('0000 000000');

		}else if(pais_cod == 'br'){
			$('#telefone1').mask(maskBehavior, options);
			
		}else{
			$('#telefone1').mask('000000000000000');
		}
	});
}

//   $('.cod1 [role="option"]').on('click', function(){
//   	var pais_cod = $(this).attr('data-country-code');
//   	var ddi = $(this).attr('data-dial-code');

//   	$('#pais_codigo1').val(pais_cod);
//   	$('#ddi1').val(ddi);

//   	if(pais_cod == 'py'){
//   		$('#telefone1').mask('0000 000000');
//   	}else if(pais_cod == 'br'){
//   		$('#telefone1').mask(maskBehavior, options);
//   	}else{
	// 	$('#telefone1').mask('000000000000000');
	// }
//   });


/**
 * Evento alteração de pais do telefone 2
 */
if(telefone2){
	telefone2.addEventListener('countrychange', function(e){
		var pais_cod = util2.getSelectedCountryData().iso2;
		var ddi = util2.getSelectedCountryData().dialCode;

		$('#pais_codigo2').val(pais_cod);
		$('#ddi2').val(ddi);

		if(pais_cod == 'py'){
			$('#telefone2').mask('0000 000000');

		}else if(pais_cod == 'br'){
			$('#telefone2').mask(maskBehavior, options);
			
		}else{
			$('#telefone2').mask('000000000000000');
		}
	});
}

// $('.cod2 [role="option"]').on('click', function(){
// 	var pais_cod = $(this).attr('data-country-code');
// 	var ddi = $(this).attr('data-dial-code');

// 	$('#pais_codigo2').val(pais_cod);
// 	$('#ddi2').val(ddi);

// 	if(pais_cod == 'py'){
// 		$('#telefone2').mask('0000 000000');
// 	}else if(pais_cod == 'br'){
// 		$('#telefone2').mask(maskBehavior, options);
// 	}else{
// 		$('#telefone2').mask('000000000000000');
// 	}
// });


/**
 * Evento alteração de pais do telefone 3
 */
if(telefone3){
	telefone3.addEventListener('countrychange', function(e){
		var pais_cod = util3.getSelectedCountryData().iso2;
		var ddi = util3.getSelectedCountryData().dialCode;

		$('#pais_codigo3').val(pais_cod);
		$('#ddi3').val(ddi);

		if(pais_cod == 'py'){
			$('#telefone3').mask('0000 000000');

		}else if(pais_cod == 'br'){
			$('#telefone3').mask(maskBehavior, options);
			
		}else{
			$('#telefone3').mask('000000000000000');
		}
	});
}

// $('.cod3 [role="option"]').on('click', function(){
// 	var pais_cod = $(this).attr('data-country-code');
// 	var ddi = $(this).attr('data-dial-code');

// 	$('#pais_codigo3').val(pais_cod);
// 	$('#ddi3').val(ddi);

// 	if(pais_cod == 'py'){
// 		$('#telefone3').mask('0000 000000');
// 	}else if(pais_cod == 'br'){
// 		$('#telefone3').mask(maskBehavior, options);
// 	}else{
// 		$('#telefone3').mask('000000000000000');
// 	}
// });

/**
 * Evento alteração de pais do telefone 4
 */
if(telefone4){
	telefone4.addEventListener('countrychange', function(e){
		var pais_cod = util4.getSelectedCountryData().iso2;
		var ddi = util4.getSelectedCountryData().dialCode;

		$('#pais_codigo4').val(pais_cod);
		$('#ddi4').val(ddi);

		if(pais_cod == 'py'){
			$('#telefone4').mask('0000 000000');

		}else if(pais_cod == 'br'){
			$('#telefone4').mask(maskBehavior, options);
			
		}else{
			$('#telefone4').mask('000000000000000');
		}
	});
}

// $('.cod4 [role="option"]').on('click', function(){
// 	var pais_cod = $(this).attr('data-country-code');
// 	var ddi = $(this).attr('data-dial-code');

// 	$('#pais_codigo4').val(pais_cod);
// 	$('#ddi4').val(ddi);

// 	if(pais_cod == 'py'){
// 		$('#telefone4').mask('0000 000000');
// 	}else if(pais_cod == 'br'){
// 		$('#telefone4').mask(maskBehavior, options);
// 	}else{
// 		$('#telefone4').mask('000000000000000');
// 	}
// });

/**
 * Evento alteração de pais do telefone filtro
 */
if(telefone_filtro){
	telefone_filtro.addEventListener('countrychange', function(e){
		var pais_cod = util_filtro.getSelectedCountryData().iso2;
		var ddi = util_filtro.getSelectedCountryData().dialCode;

		if(pais_cod == 'py'){
			$('#telefone_filtro').mask('0000 000000');

		}else if(pais_cod == 'br'){
			$('#telefone_filtro').mask(maskBehavior, options);
			
		}else{
			$('#telefone_filtro').mask('000000000000000');
		}
	});
}

// $('.filtro [role="option"]').on('click', function(){
// 	var pais_cod = $(this).attr('data-country-code');
// 	var ddi = $(this).attr('data-dial-code');

// 	if(pais_cod == 'py'){
// 		$('#telefone_filtro').mask('0000 000000');
// 	}else if(pais_cod == 'br'){
// 		$('#telefone_filtro').mask(maskBehavior, options);
// 	}else{
// 		$('#telefone_filtro').mask('000000000000000');
// 	}
// });

function confirmaContato(){
   	var confirma1 = document.querySelector("#confirma1");
    var confirma2 = document.querySelector("#confirma2");
    var confirma3 = document.querySelector("#confirma3");

    var confirmaCountry1 = $('#confirma_pais_codigo1').val();
    var confirmaCountry2 = $('#confirma_pais_codigo2').val();
    var confirmaCountry3 = $('#confirma_pais_codigo3').val();

    if(confirmaCountry1 == 'br'){
		$('#confirma1').mask(maskBehavior, options);

	}else if(confirmaCountry1 == 'py'){
		$('#confirma1').mask('0000 000000');

	}else{
		$('#confirma1').mask('000000000000000');
	}

	if(confirmaCountry2 == 'br'){
		$('#confirma2').mask(maskBehavior, options);

	}else if(confirmaCountry2 == 'py'){
		$('#confirma2').mask('0000 000000');

	}else{
		$('#confirma2').mask('000000000000000');
	}

	if(confirmaCountry3 == 'br'){
		$('#confirma3').mask(maskBehavior, options);

	}else if(confirmaCountry3 == 'py'){
		$('#confirma3').mask('0000 000000');

	}else{
		$('#confirma3').mask('000000000000000');
	}

	if(confirma1){
	    var util1 = window.intlTelInput(confirma1, {
			initialCountry: confirmaCountry1,
			localizedCountries: countries,
			preferredCountries: ['br', 'py'],
			utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
	    });
	}

	if(confirma2){
	    var util2 = window.intlTelInput(confirma2, {
			initialCountry: confirmaCountry2,
			localizedCountries: countries,
			preferredCountries: ['br', 'py'],
			utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
	    });
	}

    if(confirma3){
	    var util3 = window.intlTelInput(confirma3, {
			initialCountry: confirmaCountry3,
			localizedCountries: countries,
			preferredCountries: ['br', 'py'],
			utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
	    });
	}

	/**
	 * Evento alteração de pais do telefone 1
	 */
	if(confirma1){
		confirma1.addEventListener('countrychange', function(e){
			var pais_cod = util1.getSelectedCountryData().iso2;
			var ddi = util1.getSelectedCountryData().dialCode;

			$('#confirma_pais_codigo1').val(pais_cod);
			$('#confirma_ddi1').val(ddi);

			if(pais_cod == 'py'){
				$('#confirma1').mask('0000 000000');

			}else if(pais_cod == 'br'){
				$('#confirma1').mask(maskBehavior, options);
				
			}else{
				$('#confirma1').mask('000000000000000');
			}
		});
	}

  //   $('.cod1 [role="option"]').on('click', function(){
  //   	var pais_cod = $(this).attr('data-country-code');
  //   	var ddi = $(this).attr('data-dial-code');

  //   	$('#confirma_pais_codigo1').val(pais_cod);
  //   	$('#confirma_ddi1').val(ddi);

  //   	if(pais_cod == 'py'){
  //   		$('#confirma1').mask('0000 000000');
  //   	}else if(pais_cod == 'br'){
  //   		$('#confirma1').mask(maskBehavior, options);
  //   	}else{
		// 	$('#confirma1').mask('000000000000000');
		// }
  //   });

  	/**
	 * Evento alteração de pais do telefone 2
	 */
	if(confirma2){
		confirma2.addEventListener('countrychange', function(e){
			var pais_cod = util2.getSelectedCountryData().iso2;
			var ddi = util2.getSelectedCountryData().dialCode;

			$('#confirma_pais_codigo2').val(pais_cod);
			$('#confirma_ddi2').val(ddi);

			if(pais_cod == 'py'){
				$('#confirma2').mask('0000 000000');

			}else if(pais_cod == 'br'){
				$('#confirma2').mask(maskBehavior, options);
				
			}else{
				$('#confirma2').mask('000000000000000');
			}
		});
	}

  //   $('.cod2 [role="option"]').on('click', function(){
  //   	var pais_cod = $(this).attr('data-country-code');
  //   	var ddi = $(this).attr('data-dial-code');

  //   	$('#confirma_pais_codigo2').val(pais_cod);
  //   	$('#confirma_ddi2').val(ddi);

  //   	if(pais_cod == 'py'){
  //   		$('#confirma2').mask('0000 000000');
  //   	}else if(pais_cod == 'br'){
  //   		$('#confirma2').mask(maskBehavior, options);
  //   	}else{
		// 	$('#confirma2').mask('000000000000000');
		// }
  //   });

	/**
	 * Evento alteração de pais do telefone 3
	 */
	if(confirma3){
		confirma3.addEventListener('countrychange', function(e){
			var pais_cod = util3.getSelectedCountryData().iso2;
			var ddi = util3.getSelectedCountryData().dialCode;

			$('#confirma_pais_codigo3').val(pais_cod);
			$('#confirma_ddi3').val(ddi);

			if(pais_cod == 'py'){
				$('#confirma3').mask('0000 000000');

			}else if(pais_cod == 'br'){
				$('#confirma3').mask(maskBehavior, options);
				
			}else{
				$('#confirma3').mask('000000000000000');
			}
		});
	}

  //   $('.cod3 [role="option"]').on('click', function(){
  //   	var pais_cod = $(this).attr('data-country-code');
  //   	var ddi = $(this).attr('data-dial-code');

  //   	$('#confirma_pais_codigo3').val(pais_cod);
  //   	$('#confirma_ddi3').val(ddi);

  //   	if(pais_cod == 'py'){
  //   		$('#confirma3').mask('0000 000000');
  //   	}else if(pais_cod == 'br'){
  //   		$('#confirma3').mask(maskBehavior, options);
  //   	}else{
		// 	$('#confirma3').mask('000000000000000');
		// }
  //   });
}

function novoContato(){
	var telefone5 = document.querySelector("#telefone5");
	var defaultCountry5 = $('#pais_codigo5').val();

	if(defaultCountry5 == 'br'){
		$('#telefone5').mask(maskBehavior, options);

	}else if(defaultCountry5 == 'py'){
		$('#telefone5').mask('0000 000000');

	}else{
		$('#telefone5').mask('000000000000000');
	}

	if(telefone5){
	    var util5 = window.intlTelInput(telefone5, {
			initialCountry: defaultCountry5,
			localizedCountries: countries,
			preferredCountries: ['br', 'py'],
			utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
	    });
	
		telefone5.addEventListener('countrychange', function(e){
			var pais_cod = util5.getSelectedCountryData().iso2;
			var ddi = util5.getSelectedCountryData().dialCode;

			$('#pais_codigo5').val(pais_cod);
			$('#ddi5').val(ddi);

			if(pais_cod == 'py'){
				$('#telefone5').mask('0000 000000');

			}else if(pais_cod == 'br'){
				$('#telefone5').mask(maskBehavior, options);
				
			}else{
				$('#telefone5').mask('000000000000000');
			}
		});
	}

	// $('.cod5 [role="option"]').on('click', function(){
 //    	var pais_cod = $(this).attr('data-country-code');
 //    	var ddi = $(this).attr('data-dial-code');

 //    	$('#pais_codigo5').val(pais_cod);
 //    	$('#ddi5').val(ddi);

 //    	if(pais_cod == 'py'){
 //    		$('#telefone5').mask('0000 000000');
 //    	}else if(pais_cod == 'br'){
 //    		$('#telefone5').mask(maskBehavior, options);
 //    	}else{
	// 		$('#telefone5').mask('000000000000000');
	// 	}
 //    });
}

function unificarDDI(){
	var telefone1 = document.querySelector("#unificar_telefone1");
    var telefone2 = document.querySelector("#unificar_telefone2");
    var telefone3 = document.querySelector("#unificar_telefone3");

    var defaultCountry1 = $('#pais_codigo1').val();
    var defaultCountry2 = $('#pais_codigo2').val();
    var defaultCountry3 = $('#pais_codigo3').val();

    if(defaultCountry1 == 'br'){
		$('#unificar_telefone1').mask(maskBehavior, options);

	}else if(defaultCountry1 == 'py'){
		$('#unificar_telefone1').mask('0000 000000');

	}else{
		$('#unificar_telefone1').mask('000000000000000');
	}

	if(defaultCountry2 == 'br'){
		$('#unificar_telefone2').mask(maskBehavior, options);

	}else if(defaultCountry2 == 'py'){
		$('#unificar_telefone2').mask('0000 000000');

	}else{
		$('#unificar_telefone2').mask('000000000000000');
	}

	if(defaultCountry3 == 'br'){
		$('#unificar_telefone3').mask(maskBehavior, options);

	}else if(defaultCountry3 == 'py'){
		$('#unificar_telefone3').mask('0000 000000');

	}else{
		$('#unificar_telefone3').mask('000000000000000');
	}

	if(telefone1){
	    var util1 = window.intlTelInput(telefone1, {
			initialCountry: defaultCountry1,
			localizedCountries: countries,
			preferredCountries: ['br', 'py'],
			utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
	    });
	}

	if(telefone2){
	    var util2 = window.intlTelInput(telefone2, {
			initialCountry: defaultCountry2,
			localizedCountries: countries,
			preferredCountries: ['br', 'py'],
			utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
	    });
	}

    if(telefone3){
	    var util3 = window.intlTelInput(telefone3, {
			initialCountry: defaultCountry3,
			localizedCountries: countries,
			preferredCountries: ['br', 'py'],
			utilsScript: CI_ROOT+"assets/js/vendor/country/utils.js",
	    });
	}

	if(telefone1){
		telefone1.addEventListener('countrychange', function(e){
			var pais_cod = util1.getSelectedCountryData().iso2;
			var ddi = util1.getSelectedCountryData().dialCode;

			$('#pais_codigo1').val(pais_cod);
			$('#ddi1').val(ddi);

			if(pais_cod == 'py'){
				$('#unificar_telefone1').mask('0000 000000');

			}else if(pais_cod == 'br'){
				$('#unificar_telefone1').mask(maskBehavior, options);
				
			}else{
				$('#unificar_telefone1').mask('000000000000000');
			}
		});
	}

	// $('.cod1 [role="option"]').on('click', function(){
 //    	var pais_cod = $(this).attr('data-country-code');
 //    	var ddi = $(this).attr('data-dial-code');

 //    	$('#pais_codigo1').val(pais_cod);
 //    	$('#ddi1').val(ddi);

 //    	if(pais_cod == 'py'){
 //    		$('#unificar_telefone1').mask('0000 000000');
 //    	}else if(pais_cod == 'br'){
 //    		$('#unificar_telefone1').mask(maskBehavior, options);
 //    	}else{
	// 		$('#unificar_telefone1').mask('000000000000000');
	// 	}
 //    });

 	if(telefone2){
	 	telefone2.addEventListener('countrychange', function(e){
			var pais_cod = util2.getSelectedCountryData().iso2;
			var ddi = util2.getSelectedCountryData().dialCode;

			$('#pais_codigo2').val(pais_cod);
			$('#ddi2').val(ddi);

			if(pais_cod == 'py'){
				$('#unificar_telefone2').mask('0000 000000');

			}else if(pais_cod == 'br'){
				$('#unificar_telefone2').mask(maskBehavior, options);
				
			}else{
				$('#unificar_telefone2').mask('000000000000000');
			}
		});
	 }

  //   $('.cod2 [role="option"]').on('click', function(){
  //   	var pais_cod = $(this).attr('data-country-code');
  //   	var ddi = $(this).attr('data-dial-code');

  //   	$('#pais_codigo2').val(pais_cod);
  //   	$('#ddi2').val(ddi);

  //   	if(pais_cod == 'py'){
  //   		$('#unificar_telefone2').mask('0000 000000');
  //   	}else if(pais_cod == 'br'){
  //   		$('#unificar_telefone2').mask(maskBehavior, options);
  //   	}else{
		// 	$('#unificar_telefone2').mask('000000000000000');
		// }
  //   });

  	if(telefone3){
	  	telefone3.addEventListener('countrychange', function(e){
			var pais_cod = util3.getSelectedCountryData().iso2;
			var ddi = util3.getSelectedCountryData().dialCode;

			$('#pais_codigo3').val(pais_cod);
			$('#ddi3').val(ddi);

			if(pais_cod == 'py'){
				$('#unificar_telefone3').mask('0000 000000');

			}else if(pais_cod == 'br'){
				$('#unificar_telefone3').mask(maskBehavior, options);
				
			}else{
				$('#unificar_telefone3').mask('000000000000000');
			}
		});
	}

  //   $('.cod3 [role="option"]').on('click', function(){
  //   	var pais_cod = $(this).attr('data-country-code');
  //   	var ddi = $(this).attr('data-dial-code');

  //   	$('#pais_codigo3').val(pais_cod);
  //   	$('#ddi3').val(ddi);

  //   	if(pais_cod == 'py'){
  //   		$('#unificar_telefone3').mask('0000 000000');
  //   	}else if(pais_cod == 'br'){
  //   		$('#unificar_telefone3').mask(maskBehavior, options);
  //   	}else{
		// 	$('#unificar_telefone3').mask('000000000000000');
		// }
  //   });
}
