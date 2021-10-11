function toggle_ipub_filter_pannel() {
  var x = document.getElementById("publications_filter_pannel");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}


function toggle_div_by_id(i) {
  var x = document.getElementById(i);
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}