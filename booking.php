<?php
session_start();
include("header.php");
?>
		<div id="propertiesSelector">
            <h1>Замовлення проїзних документів</h1>
            <div class="area">
                <div class="focusMode" id="routesFinder">
                    <div class="focusArea">
                        <h2>↓ Виберіть маршрут ↓</h2>
                        <table id="routeParameters">
                            <tr>
                                <td class="propertyName" tabindex="1">Дата відправлення:</td><td><input id="departureDate" type="date" placeholder ="→ Введіть дату ←"
                            min='<?php echo date("Y-m-d");?>'
                            max='<?php echo ((int)date("m") > 10
                                ? ((int)date("Y") + 1)."-".(((int)date("m") + 1) % 12 + 1)."-".date("d")
                                : (int)date("Y")."-".((int)date("m") + 1)."-".date("d"));?>' name="date" class="emptyInput" oninput="DepartureDateChanged()"></td>
                                <td class="notify" id="notifyDepartureDate">← вкажіть дату відправлення</td>
                            </tr>
                            <tr>
                                <td class="propertyName">Станція відправлення:</td><td><input id="departurePlace" class="emptyInput" oninput="DeparturePlaceChanged()" placeholder="→ Введіть станцію ←">
                            <ul class="StationList" id="departurePlaces">
                            </ul></td><td class="notify" id="notifyDeparturePlace">← вкажіть станцію відправлення</td>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align: center;"><button class="complete" onclick="SwapRoute()">↑↓</button></td>
                            </tr>
                            <tr>
                                <td class="propertyName">Станція прибуття:</td><td><input id="arrivalPlace" class="emptyInput" oninput="ArrivalPlaceChanged()" placeholder ="→ Введіть станцію ←">
                            <ul class="StationList" id="arrivalPlaces"><td class="notify" id="notifyArrivalPlace">← вкажіть станцію прибуття</td>
                            </ul></td>
                            </tr>
                        </table>
                        <br>
                        <button id="routesSearch" class="incomplete" disabled onclick="FindRoutes()">Пошук маршрутів<span id="routeSearchNote" class="tooltiptext">Tooltip text</span></button>
                    </div>
                    <div class="infoArea">
                        <h2 class="info">Маршрут: </h2>
                        <h2 id="routesData"></h2>
                        <button id="routesChange" class="complete" onclick="ChangeRoutes()">Змінити маршрут</button>
                    </div>
                </div>
            </div>
            <div class="area">
                <div class="namerMode" id="trainFinder">
                    <div class="namerArea">
                        <h2>↑ Виберіть маршрут для вибору потягу ↑</h2>
                    </div>
                    <div class="focusArea">
                        <div id="trainsExisting" class="trainsExist">
                            <table id="tableRoutes" class="focusTable">
                                <thead><h2 id="tableRoutesHeader">↓ Виберіть потяг ↓</h2></thead>
                                <tbody id="routes">
                                    <tr>
                                        <th>№</th><th>Маршрут</th><th>Час руху</th><th>Тривалість</th><th>Кількість місць</th><th>Вибрати</th>
                                    </tr>
                                </tbody>
                            </table>
                            <div id="noTrains">
                                <h2>Потяги відсутні!</h2>
                                <div>Введіть електронну адресу, щоб дізнатись першим про наявність: <input type="email" name="email" id="email"><button class="complete">Підтвердити</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="infoArea">
                        <h2 class="info">Потяг: </h2><h2 id="trainRoute"></h2><h2 class="info">Час руху: </h2><h2 id="trainTime"></h2>
                        <button id="routesChange" class="complete" onclick="ChangeTrain()">Змінити потяг</button>
                    </div>
                </div>
            </div>
            <div class="area">
                <div class="namerMode" id="placeFinder">
                    <div class="namerArea">
                        <h2>↑ Виберіть потяг для вибору місць ↑</h2>
                    </div>
                    <div class="focusArea">
                        <h2>↓ Виберіть вагони та місця в них ↓</h2>
                        <div id="trainCarriages">
                            
                        </div>
                        <div id="placeSelector">
                            <table id="carriagePlaces">
                                
                            </table>
                        </div>
                        <h2 class="info" id="price">Вибрано на вартість: 0₴</h2>
                        <button id="setSettings" class="incomplete" disabled onclick="SetSettings()">Встановити місця<span id="routeSearchNote" class="tooltiptext">Виберіть місця!</span></button>
                    </div>
                    <div class="infoArea">
                        <h2 class="info">Кількість місць за вагонами: </h2>
                        <table class="focusTable" id="carriageTickets">
                            
                        </table>
                    </div>
                </div>
            </div>
            <div class="area">
                <div class="namerMode" id="booker">
                    <div class="namerArea">
                        <h2>↑ Виберіть місця для встановлення власника ↑</h2>
                    </div>
                    <div class="focusArea">
                        <h2>↓ Встановіть власників ↓</h2>
                        <table class="focusTable" id="tickets">
                            <tr>
                                <th>№ вагона</th><th>№ місця</th><th>Прізвище</th><th>Ім'я</th>
                            </tr>
                        </table>
                        <button id="routesSearch" class="complete" onclick="ToPayment()">Перейти до оплати<span id="routeSearchNote" class="tooltiptext">Tooltip text</span></button>
                    </div>
                </div>
            </div>
		</div>
	</body>
</html>
