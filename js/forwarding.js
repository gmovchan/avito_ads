  var second_span = document.getElementById('second');
  var second = 5;
  var countdown = setInterval(function () {
    second = second - 1;
    second_span.textContent = second;
    if (second === 0) {
      clearInterval(countdown);
      window.location.href = "./form_add_ad.html";
    }
  }, 1000)
//window.location.href = "../form_add_ad.html";
