$(document).ready(function () {
    $.ajax({
        url: '../backend/searches_backend',
        type: 'GET',
        timeout: 5000,
        contentType: "application/json",
        dataType: "json",
        success: function (result) {
            let i = 0;
            while (i < result.length && "destCnt" in result[i]) {
                let ul = document.getElementById("destinations");
                let li = document.createElement("li");
                let span = document.createElement("span");
                let sub = document.createElement("sub");
                let a = document.createElement("a");
                let button = document.createElement("button");
                button.style.cssFloat = "right";
                button.style.marginTop = "9px";
                button.classList.add("deleteDest");
                button.classList.add("btn-close");
                button.classList.add("btn-sm");
                button.ariaLabel = "Close";
                button.setAttribute('data-id', result[i].id);
                button.setAttribute('value', result[i].destination);
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
                button.addEventListener('click', function () {
                    no(event);
                    let element = this;
                    $.ajax({
                        url: '../backend/delete_search',
                        type: 'DELETE',
                        timeout: 5000,
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify({
                            "type": "destination",
                            "id": this.getAttribute('data-id')
                        }),
                        success: function (result) {
                            if (result.message == "Destination Deleted") {
                                $(element).closest('li').css('background', 'gray');
                                $(element).closest('li').fadeOut(800, function () {
                                    $(this).remove();
                                });
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
                });
                li.addEventListener('click', function () {
                    redirectTo("dt?destVal=" + li.firstChild.innerHTML);
                });
                li.appendChild(button);
                ul.appendChild(li);
                i++;
            }
            while (i < result.length && "keyCnt" in result[i]) {
                let ul = document.getElementById("keywords");
                let li = document.createElement("li");
                let span = document.createElement("span");
                let sub = document.createElement("sub");
                let a = document.createElement("a");
                let button = document.createElement("button");
                button.style.cssFloat = "right";
                button.style.marginTop = "9px";
                button.classList.add("deleteKey");
                button.classList.add("btn-close");
                button.classList.add("btn-sm");
                button.ariaLabel = "Close";
                button.setAttribute('data-id', result[i].id);
                button.setAttribute('value', result[i].keyword);
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
                li.appendChild(button);
                button.addEventListener('click', function () {
                    no(event);
                    let element = this;
                    $.ajax({
                        url: '../backend/delete_search',
                        type: 'DELETE',
                        timeout: 5000,
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify({
                            "type": "keyword",
                            "id": this.getAttribute('data-id')
                        }),
                        success: function (result) {
                            if (result.message === "Keyword Deleted") {
                                $(element).closest('li').css('background', 'gray');
                                $(element).closest('li').fadeOut(800, function () {
                                    $(this).remove();
                                });
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
                });
                li.addEventListener('click', function () {
                    redirectTo("dt?keyVal=" + li.firstChild.innerHTML);
                });
                ul.appendChild(li);
                ++i;
            }
            const d = new Date();
            if (localStorage.getItem("dark_mode") === "false" || (d.getHours() >= 6 && d.getHours() <= 18 && localStorage.getItem("dark_mode") === null)) {
                lightStyle();
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
});