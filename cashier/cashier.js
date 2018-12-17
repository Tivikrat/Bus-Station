function searchNameChanged(input, list, verify, need)
{
    while(list.firstChild)
    {
        list.removeChild(list.childNodes[0]);
    }
    var text = input.value.trim();
    if(text.length == 0)
    {
        input.className = 'emptyInput';
        verify();
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/requests/' + need + '.php?input=' + text, true);
    xhr.onload = function () {
        var results = xhr.responseText.split('\n', 10);
        for (let index = 0; index < results.length - 1; index++) {
            if(input.value.toLowerCase() == results[index].toLowerCase())
            {
                input.className = 'correctInput';
                verify();
                if(results.length == 2)
                {
                    input.style.borderBottomStyle = "solid";
                    input.style.borderBottomWidth = "2px";
                    //CheckRouteSearch();
                    return;
                }
            }
            var li = document.createElement("LI");
            li.innerText = results[index];
            li.style.cursor = "pointer";
            li.onclick = function(){
                input.value = this.innerText;
                input.className = 'correctInput';
                verify();
                list.hidden = true;
                input.style.borderBottomStyle = "solid";
                input.style.borderBottomWidth = "2px";
                //CheckRouteSearch();
            }
            list.appendChild(li);
        }
        if(list.childNodes.length)
        {
            input.className = 'inputElement';
            verify();
            input.style.borderBottomStyle = "dashed";
            input.style.borderBottomWidth = "1px";
        }
        else
        {
            input.className = 'incorrectInput';
            verify();
        }
        list.hidden = false;
        list.style.width = input.offsetWidth + "px";
        //CheckRouteSearch();
    };
    xhr.send(null);
}
function VerifyAddTicket()
{
    if(addRouteName.className == 'correctInput' && addPrivilegeName.className == 'correctInput' && addDeparture.className == 'correctInput' && addArrival.className == 'correctInput')
    {
        addButton.type='submit';
    }
    else
    {
        addButton.type='button';
    }
}
function RouteSearch()
{
    searchs = [idSearch, routeNameSearch, carrierNameSearch, lowerTimeSearch, upperTimeSearch, lowerPlacesSearch, upperPlacesSearch, D1Search, D2Search, D3Search, D4Search, D5Search, D6Search, D7Search];
    records.forEach(record => {
        record.hidden = (record.childNodes[0].innerText.toLowerCase().indexOf(searchs[0].value.toLowerCase()) < 0
        || record.childNodes[1].innerText.toLowerCase().indexOf(searchs[1].value.toLowerCase()) < 0
        || record.childNodes[2].innerText.toLowerCase().indexOf(searchs[2].value.toLowerCase()) < 0
        || (Date.parse("1.1.1 " + record.childNodes[3].innerText) < Date.parse("1.1.1 " + searchs[3].value) && searchs[3].value != '')
        || (Date.parse("1.1.1 " + record.childNodes[3].innerText) > Date.parse("1.1.1 " + searchs[4].value) && searchs[4].value != '')
        || Number(record.childNodes[4].innerText) < Number(searchs[5].value)
        || (Number(record.childNodes[4].innerText) > Number(searchs[6].value) && searchs[6].value != '') ? true : false);
    });
}
function Form(button)
{
    var win = window.open('/cashier/transport.php?id=' + button.parentElement.parentElement.childNodes[0].textContent, '_blank');
    win.focus();
}
function VerifyAddPoint()
{
    if(p1Input.className == 'correctInput' && p2Input.className == 'correctInput')
    {
        addButton.type='submit';
    }
    else
    {
        addButton.type='button';
    }
}