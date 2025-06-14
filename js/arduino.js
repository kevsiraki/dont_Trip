document.addEventListener("DOMContentLoaded", function() {
    getState();
});

window.setInterval(function() {
    getState();
}, 30000);

function setState(action, alertClass) {
    fetch('https://www.donttrip.org/donttrip/backend/arduino_backend?action=' + action)
        .then((response) => response.json())
        .then(led => {
            $('#status').html(led.LED_UPDATE);
            $('#status').attr('class', alertClass);
            $('#status').show();
            if (led.Red) {
                $('#states').html('<span style="color:red">Red LED: ' + led.Red + '</span> <br><span style="color:blue">Blue LED: ' + led.Blue + '</span><span style="color:#CA4C31"><br>Orange LED: ' + led.Orange + '</span>' + '</span><span style="color:#AA336A"><br>Tree LED: ' + led.Tree + '</span>');
                $('#states').show();
            }
        });
}

function getState() {
    fetch('https://www.donttrip.org/donttrip/backend/arduino_backend?action=state')
        .then((response) => response.json())
        .then(led => {
            if (led.Red) {
                $('#states').html('<span style="color:red">Red LED: ' + led.Red + '</span> <br><span style="color:blue">Blue LED: ' + led.Blue + '</span><span style="color:#CA4C31"><br>Orange LED: ' + led.Orange + '</span>' + '</span><span style="color:#AA336A"><br>Tree LED: ' + led.Tree + '</span>');
                $('#states').show();
            }
        });
}