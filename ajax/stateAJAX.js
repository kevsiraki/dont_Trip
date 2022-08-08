document.addEventListener("DOMContentLoaded", function () {
    $.ajax({
        url: '../backend/state_backend',
        type: 'GET',
        timeout: 5000,
        contentType: "application/json",
        dataType: "json",
        async: false,
        success: function (result) {
            let i = 0;
            while (i < result.length && "destCnt" in result[i]) {
                let ul = document.getElementById("destinations");
                let li = document.createElement("li");
                let span = document.createElement("span");
                let sub = document.createElement("sub");
                let a = document.createElement("a");
                li.classList.add("links");
                a.classList.add("link");
                a.href = "dt?destVal=" + result[i].destination;
                a.textContent = result[i].destination;
                span.setAttribute("id", "bubble");
                span.classList.add("bubble");
                span.appendChild(document.createTextNode(result[i].destCnt > 1 ? result[i].destCnt + " Searches" : result[i].destCnt + " Search"));
                sub.appendChild(span);
                li.appendChild(a);
                li.appendChild(document.createElement("br"));
                li.appendChild(sub);
                ul.appendChild(li);
                i++;
            }
            while (i < result.length && "keyCnt" in result[i]) {
                let ul = document.getElementById("keywords");
                let li = document.createElement("li");
                let span = document.createElement("span");
                let sub = document.createElement("sub");
                let a = document.createElement("a");
                li.classList.add("links");
                a.classList.add("link");
                a.href = "dt?keyVal=" + result[i].keyword;
                a.textContent = result[i].keyword;
                span.setAttribute("id", "bubble");
                span.classList.add("bubble");
                span.appendChild(document.createTextNode(result[i].keyCnt > 1 ? result[i].keyCnt + " Searches" : result[i].keyCnt + " Search"));
                sub.appendChild(span);
                li.appendChild(a);
                li.appendChild(document.createElement("br"));
                li.appendChild(sub);
                ul.appendChild(li);
                i++;
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            var text = 'Ajax Request Error: ' + 'XMLHTTPRequestObject status: (' + xhr.status + ', ' + xhr.statusText + '), ' +
                'text status: (' + textStatus + '), error thrown: (' + errorThrown + ')';
            console.log('The AJAX request failed with the error: ' + text);
            console.log(xhr.responseText);
            console.log(xhr.getAllResponseHeaders());
        }
    });
    const lis = document.querySelectorAll('.links');
    lis.forEach(li => {
        if (li.parentNode.id == "destinations") {
            li.addEventListener('click', function () {
                redirectTo("dt?destVal=" + li.firstChild.innerHTML);
            });
        } else if (li.parentNode.id == "keywords") {
            li.addEventListener('click', function () {
                redirectTo("dt?keyVal=" + li.firstChild.innerHTML);
            });
        }
    });
});