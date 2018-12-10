let dialog = null;
let compLogCallbackQueue = null;
let latestHl7Data = null;

function isCompLogConnectedOnWS()
{
    var status = false;
    $.ajax({
        type: 'GET',
        url: "http://localhost:"+OE_COMPLog_port+"/info",
        dataType: 'json',
        async: false,
        contentType: 'application/json; charset=utf-8',
        crossDomain: true,
        success: function(response) {
            status = true;
        }
    });
    return status;
}


function COMPLogPresetTest()
{
    addMessageToFadeContent("Waiting for COMPLog results...");
    var requestData = {"Message": "MSH|^~\\&|COMPLOG|COMPLOG||COMPLOG|20130510105428.912+0300||ZPT^ZTP^ZPT_ZTP|MSG100|P|2.4\nEVN|ZTP|20050110045502|||||\nPID|||"+OE_patient_hosnum+"||"+OE_patient_firstname+"^"+OE_patient_lastname+"||"+OE_patient_dob+"|"+OE_patient_gender+"-||2106-3|"+OE_patient_address+"|GL||||S||PATID12345001^2^M10|"+OE_patient_id+"|9-87654^NC\nZTP|COMPlogThresholding"};
    $.ajax({
        url: "http://localhost:"+OE_COMPLog_port+"/hl7",
        data: JSON.stringify(requestData),
        dataType: "json",
        contentType: "application/json; charset=UTF-8",
        type: "POST",
        crossDomain: true,
        async: false,
        success: function (data) {
        },
        error: function (x, y, z) {
            //alert(x.responseText +"  " +x.status);
        }
    });

}

function updateLatestPolledHl7(newLatest){
	latestHl7Data = newLatest;
	console.log('The latestHl7Data has been updated to' + latestHl7Data.Message);
}

function COMPLogCheckTestResults()
{
    var requestData =  {"Message": "MSH|^~\\&|COMPLOG|COMPLOG||COMPLOG|20130510105428.912+0300||QRY^ZTS|MSG100|P|2.4\nQRD|201311111016|R|I|Q1000|||10^RD|0150311798|RES|ALL||\nPID|||"+OE_patient_hosnum+"||"+OE_patient_firstname+"^"+OE_patient_lastname+"||"+OE_patient_dob+"|"+OE_patient_gender+"-||2106-3|"+OE_patient_address+"|GL||||S||PATID12345001^2^M10|"+OE_patient_id+"|9-87654^NC"};
    $.ajax({
        url: "http://localhost:"+OE_COMPLog_port+"/hl7",
        data: JSON.stringify(requestData),
        dataType: "json",
        contentType: "application/json; charset=UTF-8",
        type: "POST",
        crossDomain: true,
        async: false,
        success: function (data) {
        	let hl7 = data.Message;
        	console.log('hl7');
        },
        error: function (x, y, z) {
            //alert(x.responseText +"  " +x.status);
        }
    });

}

function COMPLogDischargePatient() {
    var requestData = {"Message": "MSH|^~\&|ADT1|COMPLOG|COMPLOG|COMPLOG|198808181126|SECURITY|ADT^A03|MSG00001|P|2.4\nEVN|A01-|198808181123\nPID|||"+OE_patient_hosnum+"||"+OE_patient_firstname+"^"+OE_patient_lastname+"||"+OE_patient_dob+"|"+OE_patient_gender+"-||2106-3|"+OE_patient_address+"|GL||||S||PATID12345001^2^M10|"+OE_patient_id+"|9-87654^NC"};
    $.ajax({
        url: "http://localhost:"+OE_COMPLog_port+"/hl7",
        data: JSON.stringify(requestData),
        dataType: "json",
        contentType: "application/json; charset=UTF-8",
        type: "POST",
        crossDomain: true,
        async: false,
        success: function (data) {
        },
        error: function (x, y, z) {
            //alert(x.responseText +"  " +x.status);
        }
    });
}

function requestHl7TestResults(successCallback=$.noop, errorCallback=$.noop){
	let requestData =  {"Message": "MSH|^~\\&|COMPLOG|COMPLOG||COMPLOG|20130510105428.912+0300||QRY^ZTR|MSG100|P|2.4\nQRD|201311111016|R|I|Q1000|||10^RD|100437363|RES|ALL||\nPID|||"+OE_patient_hosnum+"||"+OE_patient_firstname+"^"+OE_patient_lastname+"||"+OE_patient_dob+"|"+OE_patient_gender+"-||2106-3|"+OE_patient_address+"|GL||||S||PATID12345001^2^M10|"+OE_patient_id+"|9-87654^NC"};
	$.ajax({
		url: "http://localhost:" + OE_COMPLog_port + "/hl7",
		data: JSON.stringify(requestData),
		dataType: "json",
		contentType: "application/json; charset=UTF-8",
		type: "POST",
		crossDomain: true,
		async: false,
		success: data => successCallback(data),
		error: (jqXHR, textStatus, errorThrown) => errorCallback(jqXHR, textStatus, errorThrown)
	});
}

function convertHl7ToArray(hl7Data){
	var hl7 = hl7Data.Message;
	console.log('convertHl7ToArray' + hl7);
	var parse = function (str) {
		var segments = str.split('\n');
		return _.map(segments, function (segment) {
			var fields = segment.split('|');
			return _.map(fields, function (field) {
				return _.includes(field,'^') ? field.split('^') : field;
			});
		});
	};

	var measurements = parse(hl7);
	var results = [];

	var today = new Date().toJSON().slice(0,10).replace(/-/g,'');

	for(i=0;i<measurements[0].length;i++){
		if(measurements[0][i] == "\rZR1") {
			if(measurements[0][i+2].substring(0,8) == today){
				measurement = {side: "", method: "", logmar: "", snellen: "", base: ""};
				measurement.side = measurements[0][i+4].toLowerCase();
				measurement.method = measurements[0][i+5].replace("Usual ","").replace("Lenses", "lens").replace("Best Corrected", "Glasses");
				measurement.base = measurements[0][i+7];
				measurement.logmar = measurements[0][i+8].substring(0, measurements[0][i+8].length - 1).replace("(","").replace(")","");
				measurement.snellen = measurements[0][i+11].replace(".0","").replace("(","").replace(")","");
				results.push(measurement);
			}
		}
	}
	return results;
}

function pollForTestResults(){
	requestHl7TestResults(updateLatestPolledHl7, $.noop);
	if(dialog){
		setTimeout(pollForTestResults, 1000);
	}
}

function importCompLogResults() {
	//addMessageToFadeContent("Getting test results from COMPLog...");
	requestHl7TestResults(updateLatestPolledHl7);
	saveResultsToOE(convertHl7ToArray(latestHl7Data));
            //var hl7parser = require("hl7parser");
            //$('.visualAcuityReading ').append(data.Message);
}

function OphCiExamination_VisualAcuity_getClosestValue(mvalue)
{
    var lastdiff = 10000;
    var previousvalue = {};

    //$('#OEModule_OphCiExamination_models_Element_OphCiExamination_VisualAcuity_'+side+'_readings_'+key+'_value option').each(function() {
    $('ul[data-id="reading_val"]').each(function() {
        $(this).find('li').each(function()
        {
            diff = Math.abs($(this).data("id") - mvalue);
            if(diff < lastdiff)
            {
                lastdiff = diff;
                previousvalue.id = $(this).data("id");
                previousvalue.label = $(this).data("label");
                previousvalue.tooltip = $(this).data("tooltip");
            }
        });

    });
    return previousvalue;

}

function OphCiExamination_VisualAcuity_getMethodData(methodName)
{
    var method_data = {};

    $('ul[data-id="method"]').each(function() {
        $(this).find('li').each(function(){
            if($(this).data("label") == methodName){
                method_data.id = $(this).data("id");
                method_data.label = $(this).data("label");
                return method_data;
            }
        });
    });
    return method_data;
}

function saveResultsToOE(resultsArray)
{
    unit = $("#visualacuity_unit_change option:selected").html();

    resultsArray.forEach(function(element)
    {
        var selected_data = {};
        closestValue = OphCiExamination_VisualAcuity_getClosestValue(element.base);

        selected_data.reading_value = closestValue.id;
        selected_data.reading_display = closestValue.label;
        selected_data.tooltip =  closestValue.tooltip;

        method_data = OphCiExamination_VisualAcuity_getMethodData(element.method);

        selected_data.method_id = method_data.id;
        selected_data.method_display = method_data.label;

        OphCiExamination_VisualAcuity_addReading(element.side, selected_data);

    });
    COMPLogDischargePatient();
}


function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds){
            break;
        }
    }
}

var Queue = (function(){

    function Queue() {}

    Queue.prototype.running = false;

    Queue.prototype.queue = [];

    Queue.prototype.add_function = function(callback) {
        var _this = this;
        //add callback to the queue
        this.queue.push(function(){
            var finished = callback();
            if(typeof finished === "undefined" || finished) {
                //  if callback returns `false`, then you have to
                //  call `next` somewhere in the callback
                _this.next();
            }
        });

        if(!this.running) {
            // if nothing is running, then start the engines!
            this.next();
        }

        return this; // for chaining fun!
    };

    Queue.prototype.next = function(){
        this.running = false;
        //get the first element off the queue
        var shift = this.queue.shift();
        if(shift) {
            this.running = true;
            shift();
        }
    };

    return Queue;

})();

function openInIframe(url){
    $('body').append('<iframe width="0" height="0" vspace="0" hspace="0" id="complog_iframe" src="'+url+'"></iframe>');
}

function openInNewTab(url) {
    var win = window.open(url, '_blank');
    win.close();
}

function addMessageToFadeContent(msg)
{
    $(".fadeContent").append(msg+"<br>");
}

function establishConnection(){
	compLogCallbackQueue = new Queue;
	if(!isCompLogConnectedOnWS()){
		compLogCallbackQueue.add_function(function(){
			// having to use open in new tab, as oelauncher() method doesn't work where
			// Will mean pop-ups must be enabled on site
			//openInNewTab("oeLauncher:complog");
			openInIframe("oeLauncher:complog");
			sleep(100);
		});
	}

	compLogCallbackQueue.add_function(function(){
		setTimeout(function() {
			var maxRetry = 30;
			var retry = 0;
			while((!isCompLogConnectedOnWS()) && (retry < maxRetry)) {
				sleep(1000);
				retry++;
			}
			sleep(5000);
			COMPLogDischargePatient(); //makes sure the there is no lingering results
			// data from a previous early close of COMPLog
			compLogCallbackQueue.add_function(COMPLogPresetTest);
		}, 10);
	});
}

function initialiseCompLogDialog(){
	dialog = new OpenEyes.UI.Dialog.Confirm({
		title: 'Loading COMPLog test - Please wait...',
		okButton: 'Pull COMPLog Results',
		templateSelector: '#dialog-complog-template'});
	dialog.on('cancel', function(){
		$("#complog_iframe").remove();
		dialog.destroy();
		dialog = null;
	});
	dialog.open();
}

$(document).on("click", "#et_complog", function(event){
    event.preventDefault();
    initialiseCompLogDialog();
    // try to connect to the WS
    establishConnection();
    //need to add a statement to  make sure there is no lingering data in COMPLog from previous early closing
    pollForTestResults();
    compLogCallbackQueue.add_function(function(){
			dialog.setTitle('COMPLog test in progress');
			dialog.on('ok', function(){
				importCompLogResults();
				$("#complog_iframe").remove(); // need to add this for on cancel too
				dialog.destroy();
				dialog = null;
			});
			$('.ok').show();
    });
});
