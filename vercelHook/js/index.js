(function () {
  window.addEventListener("load", () => {
    const deployButton = document.querySelector(
      "#wp-admin-bar-vercelHookReqBtn > a"
    );
    if (deployButton) {
      deployButton.addEventListener("click", (event) => {
        event.preventDefault();
        // To make the POST Request will use
        // the gud-ol jQuery that comes with WordPress
        jQuery
          .ajax({
            url: event.target.href, // Our Deploy Hook URL here,
            method: "POST",
          })
          .done(() => {
            alert(
              "Your website will be updated shortly. Check in a couple of minutes"
            );
          });
      });
    }
  });
})();
