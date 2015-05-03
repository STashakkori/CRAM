
function makeServiceCall(service, argObject, callback) {
	var jsonEncodedObject = json_encode(argObject);
	var sendObj = {
		f : service,
		d : jsonEncodedObject
	}
	console.log("Calling "+service+" with jsonobj ("+jsonEncodedObject+")");
	$.post('cram.php', sendObj, function(d) {
		var obj = {};
		// if (typeof DEV !== "undefined") {
			 console.log("Call to " + service + " complete, RETURN: " + $.trim(d));
		// }
		try {
			obj = jQuery.parseJSON(d);
		} catch (err) {
			//console.log(d);
		}
		if (obj.error != null) {
			console.log("Service Failure "+obj.error);
			return;
		}

		callback(obj);
	});
}
