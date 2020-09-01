"use strict";

(function () {
  window.addEventListener("load", function () {
    var deployButton = document.querySelector(
      "#wp-admin-bar-vercelHookReqBtn > a"
    );

    if (deployButton) {
      deployButton.addEventListener("click", function (event) {
        event.preventDefault(); // To make the POST Request will use
        // the gud-ol jQuery that comes with WordPress

        jQuery
          .ajax({
            url: event.target.href,
            // Our Deploy Hook URL here,
            method: "POST",
          })
          .fail(function () {
            alert("Vercel에 업데이트 요청을 실패하였습니다.");
          })
          .then(function (result, status, responseObj) {
            console.log("vercel web hook response status: ", status);
            console.log("vercel web hook response responseObj: ", responseObj);
            alert("Vercel에 업데이트 요청하였습니다.");
          });
      });
    }
  });
})();
