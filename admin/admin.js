records = [];
driverSortDimensions = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
routeIndex = 3;
window.onload = function()
{
    temp = data.getElementsByTagName('tr');
    for(let i = 2; i < temp.length; ++i){
		records.push(temp[i]);
    }
}
function EditPerson(button)
{
    inputId.value = button.parentElement.parentElement.childNodes[0].textContent;
    inputName.value = button.parentElement.parentElement.childNodes[1].textContent;
    inputPhone.value = button.parentElement.parentElement.childNodes[2].textContent;
    inputAddress.value = button.parentElement.parentElement.childNodes[3].textContent;
    editPanel.style.display='flex';
    inputName.focus();
}
function DeletePerson(button)
{
    deleteId.value = button.parentElement.parentElement.childNodes[0].textContent;
    deleteName.innerText = button.parentElement.parentElement.childNodes[1].textContent;
    deletePhone.innerText = button.parentElement.parentElement.childNodes[2].textContent;
    deleteAddress.innerText = button.parentElement.parentElement.childNodes[3].textContent;
    deletePanel.style.display='flex';
}
function PersonSearch()
{
    searchs = [idSearch, nameSearch, phoneSearch, addressSearch]
    records.forEach(record => {
        record.hidden = (record.childNodes[0].innerText.toLowerCase().indexOf(searchs[0].value.toLowerCase()) < 0
        || record.childNodes[1].innerText.toLowerCase().indexOf(searchs[1].value.toLowerCase()) < 0
        || record.childNodes[2].innerText.toLowerCase().indexOf(searchs[2].value.toLowerCase()) < 0
        || record.childNodes[3].innerText.toLowerCase().indexOf(searchs[3].value.toLowerCase()) < 0 ? true : false);
    });
}
function CashiersSearch()
{
    searchs = [idSearch, nameSearch, phoneSearch, addressSearch, positionSearch, lowerMoneySearch, upperMoneySearch, loginSearch, passwordSearch]
    records.forEach(record => {
        record.hidden = (record.childNodes[0].innerText.toLowerCase().indexOf(searchs[0].value.toLowerCase()) < 0
        || record.childNodes[1].innerText.toLowerCase().indexOf(searchs[1].value.toLowerCase()) < 0
        || record.childNodes[2].innerText.toLowerCase().indexOf(searchs[2].value.toLowerCase()) < 0
        || record.childNodes[3].innerText.toLowerCase().indexOf(searchs[3].value.toLowerCase()) < 0
        || record.childNodes[4].innerText.toLowerCase().indexOf(searchs[4].value.toLowerCase()) < 0
        || Number(record.childNodes[5].innerText) < Number(searchs[5].value)
        || (Number(record.childNodes[5].innerText) > Number(searchs[6].value) && Number(searchs[6].value) != 0)
        || record.childNodes[6].innerText.toLowerCase().indexOf(searchs[7].value.toLowerCase()) < 0
        || record.childNodes[7].innerText.toLowerCase().indexOf(searchs[8].value.toLowerCase()) < 0 ? true : false);
    });
}
function PrivilegeSearch()
{
    searchs = [idSearch, nameSearch]
    records.forEach(record => {
        record.hidden = (record.childNodes[0].innerText.toLowerCase().indexOf(searchs[0].value.toLowerCase()) < 0
        || record.childNodes[1].innerText.toLowerCase().indexOf(searchs[1].value.toLowerCase()) < 0 ? true : false);
    });
}
function PointSearch()
{
    searchs = [idSearch, routeNameSearch, stationNameSearch, lowerPriceSearch, upperPriceSearch, lowerTimeSearch, upperTimeSearch];
    records.forEach(record => {
        record.hidden = (record.childNodes[0].innerText.toLowerCase().indexOf(searchs[0].value.toLowerCase()) < 0
        || record.childNodes[1].innerText.toLowerCase().indexOf(searchs[1].value.toLowerCase()) < 0
        || record.childNodes[2].innerText.toLowerCase().indexOf(searchs[2].value.toLowerCase()) < 0
        || Number(record.childNodes[3].innerText) < Number(searchs[3].value)
        || (Number(record.childNodes[3].innerText) > Number(searchs[4].value) && Number(searchs[4].value) != 0)
        || Date.parse("1.1.1 " + record.childNodes[4].innerText) < Date.parse("1.1.1 " + searchs[5].value)
        || (Date.parse("1.1.1 " + record.childNodes[4].innerText) > Date.parse("1.1.1 " + searchs[6].value)) ? true : false);
    });
}
function EditPoint(button)
{
    inputId.value = button.parentElement.parentElement.childNodes[0].textContent;
    inputRouteName.value = button.parentElement.parentElement.childNodes[1].textContent;
    inputStationName.value = button.parentElement.parentElement.childNodes[2].textContent;
    inputPrice.value = button.parentElement.parentElement.childNodes[3].textContent;
    inputTime.value = button.parentElement.parentElement.childNodes[4].textContent;
    editPanel.style.display='flex';
    inputPrice.focus();
}
function DeletePoint(button)
{
    deleteId.value = button.parentElement.parentElement.childNodes[0].textContent;
    deleteRouteName.innerText = button.parentElement.parentElement.childNodes[1].textContent;
    deleteStationName.innerText = button.parentElement.parentElement.childNodes[2].textContent;
    deletePrice.innerText = button.parentElement.parentElement.childNodes[3].textContent;
    deleteTime.innerText = button.parentElement.parentElement.childNodes[4].textContent;
    deletePanel.style.display='flex';
}
function SortStrings(index)
{
    driverSortDimensions[index] = !driverSortDimensions[index];
    function sorter(a, b)
    {
        return (a.childNodes[index].innerText < b.childNodes[index].innerText ? -1 : 1);
    }
    records.sort(sorter);
    while(data.childElementCount > 2){
		data.removeChild(data.lastChild);
    }
    if(driverSortDimensions[index])
    {
        records.forEach(record => {
            data.appendChild(record);
        });
    }
    else
    {
        for (let index = 0; index < records.length; index++) {
            const record = records[records.length - index - 1];
            data.appendChild(record);
        }
    }
}
function SortNumbers(index)
{
    driverSortDimensions[index] = !driverSortDimensions[index];
    function sorter(a, b)
    {
        return a.childNodes[index].innerText - b.childNodes[index].innerText;
    }
    records.sort(sorter);
    while(data.childElementCount > 2){
		data.removeChild(data.lastChild);
    }
    if(driverSortDimensions[index])
    {
        records.forEach(record => {
            data.appendChild(record);
        });
    }
    else
    {
        for (let index = 0; index < records.length; index++) {
            const record = records[records.length - index - 1];
            data.appendChild(record);
        }
    }
}
function EditPrivilege(button)
{
    inputId.value = button.parentElement.parentElement.childNodes[0].textContent;
    inputName.value = button.parentElement.parentElement.childNodes[1].textContent;
    inputDiscount.value = button.parentElement.parentElement.childNodes[2].textContent;
    editPanel.style.display='flex';
    inputName.focus();
}
function DeletePrivilege(button)
{
    deleteId.value = button.parentElement.parentElement.childNodes[0].textContent;
    deleteName.innerText = button.parentElement.parentElement.childNodes[1].textContent;
    deleteDiscount.innerText = button.parentElement.parentElement.childNodes[2].textContent;
    deletePanel.style.display='flex';
}
function EditStation(button)
{
    inputId.value = button.parentElement.parentElement.childNodes[0].textContent;
    inputName.value = button.parentElement.parentElement.childNodes[1].textContent;
    editPanel.style.display='flex';
    inputName.focus();
}
function DeleteStation(button)
{
    deleteId.value = button.parentElement.parentElement.childNodes[0].textContent;
    deleteName.innerText = button.parentElement.parentElement.childNodes[1].textContent;
    deletePanel.style.display='flex';
}

function NameChanged(input)
{
    if(input.value.length == 0)
    {
        input.className = "emptyInput";
    }
    else
    {
        input.className = "filledInput";
    }
}
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
function VerifyAddRoute()
{
    if(addCarrierName.className == 'correctInput')
    {
        addButton.type='submit';
    }
    else
    {
        addButton.type='button';
    }
}
function VerifyEditRoute()
{
    if(editCarrierName.className == 'correctInput')
    {
        editButton.type='submit';
    }
    else
    {
        editButton.type='button';
    }
}
function VerifyAddPoint()
{
    if(addRouteName.className == 'correctInput' && addStationName.className == 'correctInput')
    {
        addButton.type='submit';
    }
    else
    {
        addButton.type='button';
    }
}
function VerifyAddPosition()
{
    if(addStationName.className == 'correctInput')
    {
        addButton.type='submit';
    }
    else
    {
        addButton.type='button';
    }
}
function VerifyEditPosition()
{
    if(editStationName.className == 'correctInput')
    {
        editButton.type='submit';
    }
    else
    {
        editButton.type='button';
    }
}
function EditRoute(button)
{
    values = button.parentElement.parentElement.childNodes;
    editId.value = values[0].textContent;
    editRouteName.value = values[1].textContent;
    editCarrierName.value = values[2].textContent;
    editTime.value = values[3].textContent;
    editPlaces.value = values[4].textContent;
    editD1.checked = values[5].firstChild.checked;
    editD2.checked = values[6].firstChild.checked;
    editD3.checked = values[7].firstChild.checked;
    editD4.checked = values[8].firstChild.checked;
    editD5.checked = values[9].firstChild.checked;
    editD6.checked = values[10].firstChild.checked;
    editD7.checked = values[11].firstChild.checked;
    editPanel.style.display='flex';
    editRouteName.focus();
}
function DeleteRoute(button)
{
    values = button.parentElement.parentElement.childNodes;
    deleteId.value = values[0].textContent;
    deleteRouteName.innerText = values[1].textContent;
    deleteCarrierName.innerText = values[2].textContent;
    deleteTime.innerText = values[3].textContent;
    deletePlaces.innerText = values[4].textContent;
    deleteD1.checked = values[5].firstChild.checked;
    deleteD2.checked = values[6].firstChild.checked;
    deleteD3.checked = values[7].firstChild.checked;
    deleteD4.checked = values[8].firstChild.checked;
    deleteD5.checked = values[9].firstChild.checked;
    deleteD6.checked = values[10].firstChild.checked;
    deleteD7.checked = values[11].firstChild.checked;
    deletePanel.style.display='flex';
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
        || (Number(record.childNodes[4].innerText) > Number(searchs[6].value) && searchs[6].value != '')
        || (!record.childNodes[5].firstChild.checked && searchs[7].checked)
        || (!record.childNodes[6].firstChild.checked && searchs[8].checked)
        || (!record.childNodes[7].firstChild.checked && searchs[9].checked)
        || (!record.childNodes[8].firstChild.checked && searchs[10].checked)
        || (!record.childNodes[9].firstChild.checked && searchs[11].checked)
        || (!record.childNodes[10].firstChild.checked && searchs[12].checked)
        || (!record.childNodes[11].firstChild.checked && searchs[13].checked) ? true : false);
    });
}
function AddAddPoint(item)
{
    var tr = item.parentElement.parentElement.parentElement.parentElement.insertRow(item.parentElement.parentElement.parentElement.childElementCount - 2);
    var td = document.createElement("TD");
    var input = document.createElement("input");
    input.type = "text";
    input.name = "point" + routeIndex;
    input.id = "point" + routeIndex;
    input.placeholder = "Назва станції";
    td.appendChild(input);
    tr.appendChild(td);
    var td = document.createElement("TD");
    var input = document.createElement("input");
    input.type = "date";
    input.name = "day" + routeIndex;
    input.id = "day" + routeIndex;
    td.appendChild(input);
    var input = document.createElement("input");
    input.type = "time";
    input.name = "time" + routeIndex;
    input.id = "time" + routeIndex;
    td.appendChild(input);
    tr.appendChild(td);
}
