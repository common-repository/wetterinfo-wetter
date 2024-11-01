/**
* @var array aConfig Konfigurations Array
*/

var aConfig = new Array();
/**
* @var Standard Region
*/
var Region = 'Germany';
var GlobalURL = '';
var City = '';
var DayNight = '';

var Picker = '';

/**
* Initialisieren des Konfigurationsarrays
*
*/
function mvInitApp() {
	this.aConfig["sSERVER_PATH"] 			= "http://wiga.t-online.de/wetter/";
	this.aConfig["sSERVER_FILE_LOCATION"] 	= "searchweatherlocationjsonp.php?location=";
}

jQuery(function($) {
	mvInitApp();
	showPicker();
});

/**
* Per Ajax eine Anfrage an den Server stellen
*
* @param string sLocationName Übergabe des gesuchten Ort
* @param string sRegion Übergabe ob in Deutschland oder Weltweit gesucht wird
*/
function mvGetCity(sLocationName, sRegion) {
	//alert("sLocationName: " + sLocationName);
	//alert("encodeURI(sLocationName): " + encodeURI(sLocationName));
		
	Region = sRegion;
	var sURLRegion = "";
	
	if(sRegion == 'worldwide')
		{
			sURLRegion = '&region=worldwide';
		}
	
	jQuery(function($) {
		$.ajax({
			type: "GET",
			url: aConfig["sSERVER_PATH"] + aConfig["sSERVER_FILE_LOCATION"] + encodeURI(sLocationName) + sURLRegion + "&utf8=true&PixelPathWiga=wordpress",
			dataType: 'jsonp',
			success: function(data) {
				mvShowResult(data);
			},
			onFailure: function(){ alert('Something went wrong...') },
		});
	});	


}

/**
* Ergebnis anzeigen
* 
* @param string sContent Übergabe des Contents
*/
function mvShowResult(data) {

	jQuery(function($) {
		var sText = '';
		var iCounter = 0;
		if(data != null){
				
			$(".wi-result-header").html("Es wurden folgende Stationen gefunden:");
			$.each(data, function(index, value) {
				
				if (iCounter == 0) {
					sText += '<label><input type="radio" checked name="ResultLocation" id="ResultLocation" value="' + value.code_uni + '" /><input name="' + value.code_uni + '" id="' + value.code_uni + '" type="hidden" value="'+value.location+'" />';
				} else {
					sText += '<label><input type="radio" name="ResultLocation" id="ResultLocation" value="' + value.code_uni + '" /><input name="' + value.code_uni + '" id="' + value.code_uni + '" type="hidden" value="'+value.location+'" />';
				}
				//null des Zip-Codes Abfangen
				if(value.zip != null) { sText += value.zip; } 
				sText += " " + value.location;
				sText += '</label><br />';
				iCounter++;
			});
		} else {
			$(".wi-result-header").html("Es wurde kein Ergebnis gefunden");
		}
		
		$(".wi-loc-results").html(sText);
		$('#wi-loc-results-container').css('display', 'block');
		$(".wi-picker").css('display', 'none');
	});
} 

function showPicker() {
	jQuery(function($){

		$(".wi-picker-color").click(function(){
			//$('#wi-loc-results-container').css('display', 'none');
			$(".wi-picker").css("display", "block");
			Picker = $(this).attr('id');
		});
		
		$("#wi-picker-btn-accept").click(function()
				{
			
			$(".wi-picker").css('display', 'none');
			//$('#wi-loc-results-container').css('display', 'block');
			$("input[name="+Picker+"]").val($("#hexBox").val());
			$("#"+Picker).css("color",$("#hexBox").val());
			$("#"+Picker).css("background",$("#hexBox").val());
		});
		
		$("#wi-picker-btn-cancel").click(function(){
			$(".wi-picker").css('display', 'none');
			$('#wi-loc-results-container').css('display', 'block');
		});

		
	});
}

