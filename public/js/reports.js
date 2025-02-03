function generateMonthlyReport(report_type)
{
  var month = document.getElementById("month").value;
  var year = document.getElementById("year").value;

  window.location.href="/reports/" + report_type + "/" + month + "/" + year;
}

function generateQuarterlyReport(report_type)
{

  var quarter = document.getElementById("quarter").value;
  var year = document.getElementById("year").value;
  if (!(quarter>=1 && quarter<=4))
  {
    alert("Invalid input for quarter");
  }
  else
  {
    window.location.href="/reports/" + report_type + "/Q" + quarter + "/" + year;
  }
}

function generateYearlyReport(report_type)
{
  var quarter = document.getElementById("quarter").value;
  var year = document.getElementById("year").value;

  window.location.href="/reports/" + report_type + "/year/" + year;
}
