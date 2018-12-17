window.onload = function()
{
    CheckRouteSearch();
    /*var from = getCookie("from");
    if(from)
    {
        departurePlace.value = from;
    }
    DeparturePlaceChanged();
    var to = getCookie("to");
    if(to)
    {
        arrivalPlace.value = to;
    }
    ArrivalPlaceChanged();
    var date = getCookie("date");
    if(date)
    {
        departureDate.value = date;
    }
    DepartureDateChanged();
    var stage = getCookie("stage");*/
}
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}
function GetStationsSearchResults(input, secondInput, list)
{
    while(list.firstChild)
    {
        list.removeChild(list.childNodes[0]);
    }
    input.style.borderBottomStyle = "solid";
    input.style.borderBottomWidth = "2px";
    var text = input.value.trim();
    if(text.length == 0)
    {
        input.className = 'emptyInput';
        return;
    }
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/stationSearch.php?input=' + text, true);
    xhr.onload = function () {
        var results = xhr.responseText.split('₴', 6);
        for (let index = 0; index < results.length - 1; index++) {
            if(input.value.toLowerCase() == results[index].toLowerCase())
            {
                input.className = 'correctInput';
                document.cookie = (input == departurePlace ? "from=" : "to=") + input.value;
                if(results.length == 2)
                {
                    input.style.borderBottomStyle = "solid";
                    input.style.borderBottomWidth = "2px";
                    CheckRouteSearch();
                    return;
                }
            }
            var li = document.createElement("LI");
            li.innerText = results[index];
            li.style.cursor = "pointer";
            li.onclick = function(){
                input.value = this.innerText;
                document.cookie = (input == departurePlace ? "from=" : "to=") + input.value;
                input.className = 'correctInput';
                list.hidden = true;
                input.style.borderBottomStyle = "solid";
                input.style.borderBottomWidth = "2px";
                CheckRouteSearch();
            }
            list.appendChild(li);
        }
        if(list.childNodes.length)
        {
            input.className = 'inputElement';
            input.style.borderBottomStyle = "dashed";
            input.style.borderBottomWidth = "1px";
        }
        else
        {
            input.className = 'incorrectInput';
        }
        list.hidden = false;
        list.style.width = input.offsetWidth + "px";
        CheckRouteSearch();
    };
    xhr.send(null);
}
function DeparturePlaceChanged()
{
    GetStationsSearchResults(departurePlace, arrivalPlace, departurePlaces);
}
function ArrivalPlaceChanged()
{
    GetStationsSearchResults(arrivalPlace, departurePlace, arrivalPlaces);
}
function DepartureDateChanged()
{
    if(departureDate.value.length)
    {
        departureDate.className = "correctInput";
        document.cookie = "date=" + departureDate.value;
    }
    else
    {
        departureDate.className = "inputElement";
    }
    CheckRouteSearch();
}
function CheckRouteSearch()
{
    if (departureDate.className == "correctInput") {
        notifyDepartureDate.innerText = "";
        if(departurePlace.className == "correctInput") {
            notifyDeparturePlace.innerText = "";
            if(arrivalPlace.className == "correctInput") {
                notifyArrivalPlace.innerText = "";
                if(departurePlace.value != arrivalPlace.value) {
                    routesSearch.className = "complete";
                    routesSearch.disabled = false;
                    return true;
                }
                else
                {
                    routeSearchNote.innerText = "Назви станцій співпадають!";
                    notifyArrivalPlace.innerText = "Назва співпала зі станцією відправлення!";
                    if((document.activeElement != departureDate || departureDate.className == "correctInput") && (document.activeElement != departurePlace || departurePlace.className == "correctInput") && (document.activeElement != arrivalPlace || arrivalPlace.className == "correctInput"))
                    {
                        departurePlace.focus();
                    }
                }
            }
            else
            {
                routeSearchNote.innerText = "Не вказана станція прибуття!";
                notifyArrivalPlace.innerText = "← вкажіть станцію прибуття";
                if((document.activeElement != departureDate || departureDate.className == "correctInput") && (document.activeElement != departurePlace || departurePlace.className == "correctInput") && (document.activeElement != arrivalPlace || arrivalPlace.className == "correctInput"))
                {
                    arrivalPlace.focus();
                }
            }
        }
        else
        {
            routeSearchNote.innerText = "Не вказана станція відправлення!"
            notifyArrivalPlace.innerText = "← вкажіть станцію відправлення";
            if((document.activeElement != departureDate || departureDate.className == "correctInput") && (document.activeElement != departurePlace || departurePlace.className == "correctInput") && (document.activeElement != arrivalPlace || arrivalPlace.className == "correctInput"))
            {
                departurePlace.focus();
            }
        }
    }
    else
    {
        routeSearchNote.innerText = "Не вказана дата відправлення!"
        notifyArrivalPlace.innerText = "← вкажіть дату відправлення";
        if((document.activeElement != departureDate || departureDate.className == "correctInput") && (document.activeElement != departurePlace || departurePlace.className == "correctInput") && (document.activeElement != arrivalPlace || arrivalPlace.className == "correctInput"))
        {
            departureDate.focus();
        }
    }
    routesSearch.className = "incomplete";
    routesSearch.disabled = true;
    routesSearch.focus();
    return false;
}
function FindRoutes(trust = false)
{
    if(trust || CheckRouteSearch()){
        while(routes.firstChild)
        {
            routes.removeChild(routes.childNodes[0]);
        }
        var from = departurePlace.value.trim();
        var to = arrivalPlace.value.trim();
        var date = departureDate.value;
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/routeSearch.php?from=' + from + "&to=" + to + "&date=" + date, true);
        xhr.onload = function () {
            var results = xhr.responseText.split('@');
            if(results.length > 1)
            {
                routes.innerHTML = "<tr><th>№</th><th>Маршрут</th><th>Час руху</th><th>Тривалість</th><th>Кількість місць</th><th>Вибрати</th></tr>";
                for (let index = 0; index < results.length - 1; index++) {
                    var info = results[index].split("^");
                    var tr = document.createElement("tr");
                    var N = document.createElement("td");
                    N.innerText = info[0];
                    var R = document.createElement("td");
                    R.innerText = info[1];
                    var TT = document.createElement("td");
                    TT.innerHTML = "Відправлення: " + info[2] + "<br>Прибуття: " + info[3];
                    var T = document.createElement("td");
                    T.innerText = info[4];
                    var P = document.createElement("td");
                    P.innerHTML = (info[7] == "0" ? info[8] + " плацкарт" : (info[8] == "0" ? info[7] + " купе" : info[7] + " купе" + "<br>" + info[8] + " плацкарт"));
                    var S = document.createElement("td");
                    S.innerHTML = (info[7] == "0" || info[8] == "0" ? "<button onclick='SelectTrain(\"" + info[0] + "\", \"" + info[1] + "\", \"" + info[2] + " + " + info[4] + " = " + info[3] + "\"," + info[5] + "," + info[6] + "," + info[9] + ")'>Вибрати</button>" : "<button onclick='SelectTrain(" + info[0] + "," + info[1] + "," + info[2] + " + " + info[4] + " = " + info[3] + "," + info[5] + "," + info[6] + "," + info[9] + ", true)'>Вибрати</button onclick='SelectTrain(" + info[0] + "," + info[1] + "," + info[2] + " + " + info[4] + " = " + info[3] + "," + info[5] + "," + info[6] + "," + info[9] + ")'><br><button>Вибрати</button>");
                    tr.appendChild(N);
                    tr.appendChild(R);
                    tr.appendChild(TT);
                    tr.appendChild(T);
                    tr.appendChild(T);
                    tr.appendChild(P);
                    tr.appendChild(S);
                    routes.appendChild(tr);
                }
                trainsExisting.className = "trainsExist";
                routesData.innerText = departureDate.value + ": " + departurePlace.value + " → " + arrivalPlace.value;
            }
            else
            {
                trainsExisting.className = "trainsDontExist";
            }
            if(!trust) document.cookie = "stage=1";
            routesFinder.className = "infoMode";
            trainFinder.className = "focusMode";
        };
        xhr.send(null);
    }
}
function SwapRoute()
{
    [departurePlace.value, arrivalPlace.value] = [arrivalPlace.value, departurePlace.value];
    [departurePlace.className, arrivalPlace.className] = [arrivalPlace.className, departurePlace.className];
}
function ChangeRoutes()
{
    document.cookie = "stage=0";
    routesFinder.className = "focusMode";
    trainFinder.className = "namerMode";
    placeFinder.className = "namerMode";
}
var carriages = [];
var selected = [];
var PK, PP, allPrice = 0;
function SelectTrain(num, trainName, time, priceK, priceP, pathNum, compartment = false)
{
    carriages = [];
    selected = [];
    while(trainCarriages.firstChild)
    {
        trainCarriages.removeChild(trainCarriages.childNodes[0]);
    }
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/getPathPlaces.php?path=' + pathNum, true);
    xhr.onload = function () {
        var results = xhr.responseText.split('@');
        carriages.length = 0;
        for (let i = 0; i < results.length - 1; i++) {
            var element = results[i].split(':');
            carriages.push([element[0], element[1].split(',').slice(0, -1)]);
            selected.push([]);
            for (let index = 0; index < carriages[i][1].length; index++) {
                selected[i][index] = false;
            }
            var carriage = document.createElement("button");
            carriage.innerHTML = element[0] + ':' + carriages[i][1].length + '<span id="routeSearchNote" class="tooltiptext">Купе, ' + carriages[i][1].length + ' вільних місць</span>';
            carriage.className = "carriage";
            carriage.onclick = function()
            {
                SelectCarriage(i);
            }
            trainCarriages.appendChild(carriage);
        }
        trainRoute.innerText = "№ " + num + " \""+ trainRoute + "\"";
        trainTime.innerText = time;
        routesFinder.className = "infoMode";
        trainFinder.className = "infoMode";
        placeFinder.className = "focusMode";
        PK = priceK;
        PP = priceP;
        document.cookie = "num=" + num;
        document.cookie = "name=" + trainName;
        document.cookie = "time=" + time;
        document.cookie = "PriceK" + priceK;
        document.cookie = "PriceP" + priceP;
        document.cookie = "pathNum" + pathNum;
        document.cookie = "stage=2";
        SelectCarriage(0);
    };
    xhr.send(null);
}
function SelectCarriage(index)
{
    while(carriagePlaces.firstChild)
    {
        carriagePlaces.removeChild(carriagePlaces.childNodes[0]);
    }
    for (let i = 0; i < trainCarriages.childNodes.length; i++) {
        const element = trainCarriages.childNodes[i];
        element.className = "carriage";
    }
    trainCarriages.childNodes[index].className = "carriageSelected";
    var i = 0;
    if(carriages[index][0][0] == "К"){
        var firstRaw = document.createElement("tr");
        var secondRaw = document.createElement("tr");
        var thirdRaw = document.createElement("tr");
        var twoRows = [firstRaw, secondRaw];
        carriagePlaces.appendChild(firstRaw);
        carriagePlaces.appendChild(secondRaw);
        carriagePlaces.appendChild(thirdRaw);
        for (let j = 1; j <= 40; j++) {
            var place = document.createElement("td");
            place.innerText = j;
            if(carriages[index][1][i] == j)
            {
                if(selected[index][j])
                {
                    place.className = "placeSelected";
                    place.onclick = function()
                    {
                        SelectPlace(index, j);
                    }
                }
                else
                {
                    place.className = "placeEnabled";
                    place.onclick = function()
                    {
                        SelectPlace(index, j);
                    }
                }
                i++;
            }
            else
            {
                place.className = "placeDisabled";
            }
            if((j - 1) % 4 > 1)
            {
                place.style.borderRight = "3px solid #00006f";
            }
            else
            {
                place.style.borderLeft = "3px solid #00006f";
            }
            twoRows[j % 2].appendChild(place);
        }
        for (let j = 0; j < 20; j++) {
            var place = document.createElement("td");
            place.className = "noPlace";
            place.innerText = '  ';
            thirdRaw.appendChild(place);
        }
    }
}
function SelectPlace(carriage, place)
{
    selected[carriage][place] = !selected[carriage][place];
    allPrice += (selected[carriage][place] * 2 - 1) * (carriages[carriage][0].indexOf("К") < 0 ? PP : PK);
    price.innerText = "Вибрано на суму " + allPrice.toFixed(2) + "₴";
    setSettings.disabled = allPrice < 0.01;
    setSettings.className = (allPrice < 0.01 ? 'incomplete' : 'complete');
    SelectCarriage(carriage);
}
function SetSettings()
{
    routesFinder.className = "infoMode";
    trainFinder.className = "infoMode";
    placeFinder.className = "infoMode";
    booker.className = "focusMode";
    while(carriageTickets.firstChild)
    {
        carriageTickets.removeChild(carriageTickets.firstChild);
    }
    var CarriageRow = document.createElement("tr");
    var PlacesRow = document.createElement("tr");
    var td = document.createElement("td");
    td.innerText = "Вагони №";
    CarriageRow.appendChild(td);
    var td = document.createElement("td");
    td.innerText = "Кількість місць";
    PlacesRow.appendChild(td);
    var td = document.createElement("td");
    for (let index = 0; index < selected.length; index++) {
        var count = 0;
        for (let j = 0; j < selected[index].length; j++) {
            if(selected[index][j])
            {
                count++;
            }
        }
        if(count)
        {
            var td = document.createElement("td");
            td.innerText = index;
            CarriageRow.appendChild(td);
            var td = document.createElement("td");
            td.innerText = count;
            PlacesRow.appendChild(td);
        }
    }
    carriageTickets.appendChild(CarriageRow);
    carriageTickets.appendChild(PlacesRow);


    while(tickets.firstChild)
    {
        tickets.removeChild(tickets.firstChild);
    }
    var tr = document.createElement("tr");
    var th = document.createElement("th");
    th.innerText = "№ вагона";
    tr.appendChild(th);
    var th = document.createElement("th");
    th.innerText = "№ місця";
    tr.appendChild(th);
    var th = document.createElement("th");
    th.innerText = "Прізвище";
    tr.appendChild(th);
    var th = document.createElement("th");
    th.innerText = "Ім'я";
    tr.appendChild(th);
    tickets.appendChild(tr);
    for (let index = 0; index < selected.length; index++) {
        var count = 0;
        for (let j = 0; j < selected[index].length; j++) {
            if(selected[index][j])
            {
                var tr = document.createElement("tr");
                var td = document.createElement("td");
                td.innerText = index;
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerText = j;
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = '<input class="emptyInput" oninput="this.className=(this.value ? \'correctInput\' : \'incorrectInput\' " placeholder="→ Введіть прізвище ←">'
                tr.appendChild(td);
                var td = document.createElement("td");
                td.innerHTML = '<input class="emptyInput" oninput="this.className=(this.value ? \'correctInput\' : \'incorrectInput\' " placeholder="→ Введіть ім\'я ←">'
                tr.appendChild(td);
                tickets.appendChild(tr);
            }
        }
    }
}
function ChangeTrain()
{
    routesFinder.className = "infoMode";
    trainFinder.className = "focusMode";
    placeFinder.className = "namerMode";
    booker.className = "namerMode";
    document.cookie = "stage=1";
}
function SetTickets()
{

}