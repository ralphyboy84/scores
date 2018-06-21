$(document).ready(function() {
  $("body").on("click", "#submitForm", function() {
    var formData = $("#scores").serialize();

    $.ajax({
      type: "POST",
      url: "ajax/dbSavePredictions.php",
      data: formData
    }).done(function(html) {
      $("#debug").html(html);
    });
  });

  $("body").on("click", "#login", function() {
    var formData = $("#loginForm").serialize();

    $.ajax({
      type: "POST",
      url: "ajax/login.php",
      data: formData
    }).done(function(html) {
      if (html === "OK") {
        document.location.href = "index.php";
      } else {
        $("#debuglogin").html(html + "Whoops something wrong happened");
      }
    });
  });

  $("body").on("click", ".rowClass", function() {
    $("#modalHeader").text("Match Predictions");
    document.getElementById("id02").style.display = "block";

    $.ajax({
      type: "POST",
      url: "ajax/matchInfo.php",
      data: { id: this.id }
    }).done(function(html) {
      $("#matchInfoDiv").html(html);
    });
  });

  $("body").on("click", ".groupClass", function() {
    $("#modalHeader").text("Group Table");
    document.getElementById("id02").style.display = "block";

    $.ajax({
      type: "POST",
      url: "/scores/ajax/groupInfo.php",
      dataType: "json",
      data: { group: $(this).attr("data-group") },
      error: function() {
        $("#matchInfoDiv").html("Error 1");
      }
    }).done(function(json) {
      if (json) {
        $("#matchInfoDiv").html(
          '<table class="table table-striped table-hover dataTable no-footer" id="usertable"><thead><tr><th></th><th></th><th></th><th></th></tr></thead></table>'
        );

        arr = [];
        dataSet = [];

        count = 0;

        $.each(json, function(key, vals) {
          arr.push(key);
          arr.push(vals.points);
          arr.push(vals.scored);
          arr.push(vals.conceded);

          dataSet.push(arr);
          arr = [];
          count++;
        });

        $("#usertable").DataTable({
          data: dataSet,
          paging: false,
          lengthChange: false,
          searching: false,
          info: false,
          order: [[1, "desc"]],
          columns: [
            { title: "Team" },
            { title: "Points" },
            { title: "Scored" },
            { title: "Conceded" }
          ]
        });
      } else {
        $("#matchInfoDiv").html("Error 2");
      }
    });
  });

  $("body").on("click", ".userRow", function() {
    $("#modalHeader").text("User Scores");
    document.getElementById("id02").style.display = "block";

    $.ajax({
      type: "POST",
      url: "ajax/userScores.php",
      data: { userid: this.id }
    }).done(function(html) {
      $("#matchInfoDiv").html(html);
    });
  });

  $.getJSON(
    "/scores/ajax/leaderhistory.php",
    function(json) {
      var chartLabels = Array();
      var chartData = Array();


      if (json) {
        $.each(json.games, function(key, vals) {
          chartLabels.push(vals);
        });

        var dataset = Array();

        $.each(json.users, function(key, vals) {
          dataset.push({
            data: vals,
            label: key,
            fill: false,
            lineTension: 0.1,
            backgroundColor: "rgba(75,192,192,0.4)",
            borderColor: getRandomColor(),
            borderCapStyle: "butt",
            borderDash: [],
            borderDashOffset: 0.0,
            borderJoinStyle: "miter",
            pointBorderColor: "rgba(75,192,192,1)",
            pointBackgroundColor: "#fff",
            pointBorderWidth: 1,
            pointHoverRadius: 5,
            pointHoverBackgroundColor: "rgba(75,192,192,1)",
            pointHoverBorderColor: "rgba(220,220,220,1)",
            pointHoverBorderWidth: 2,
            pointRadius: 1,
            pointHitRadius: 10
          })
        });
      }

      var config = {
        type: "line",
        data: {
          labels: chartLabels,
          datasets: dataset
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                stepSize: 1,
                max:21,
                min: 1,
                reverse: true,
                start: 21
              }
            }]
          },
          legend: {
            display: true
          }
        }
      };

      var ctx = document.getElementById("leaderhistory").getContext("2d");
      var myChart = new Chart(ctx, config);
    }
  );
});

// Change style of navbar on scroll
window.onscroll = function() {
  myFunction();
};

function myFunction() {
  var navbar = document.getElementById("myNavbar");
  if (
    document.body.scrollTop > 100 ||
    document.documentElement.scrollTop > 100
  ) {
    navbar.className =
      "w3-navbar" + " w3-card-2" + " w3-animate-top" + " w3-white";
  } else {
    navbar.className = navbar.className.replace(
      " w3-card-2 w3-animate-top w3-white",
      ""
    );
  }
}

function getRandomColor() {
  var letters = '0123456789ABCDEF';
  var color = '#';
  for (var i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}