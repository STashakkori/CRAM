$(document).ready(function() {
    $("#Submit").click(function() {
$.ajax({
            type: "POST",
            contentType: "application/json;",
            dataType: "json",
            url: "student.cs.appstate.edu/3430/131/team5/Reserve",


error: function(checkBuilding, status, thrownError)
{
  alert(checkBuilding.status);
  alert(thrownError);
}

error: function(checkTime, status, thrownError)
{
    alert(checkTime.status);
    alert(thrownError);

});
});
    });


//Need to worry about checkBuilding and checkTime
