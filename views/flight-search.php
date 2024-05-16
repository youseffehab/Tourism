<?php

require_once("../includes/dbh.php");
require_once("../includes/config.php");

$db = new DBh();
$conn = $db->connect();


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT code, name FROM airports";
$result = $db->query($sql);


$airports = array();
if ($result->num_rows > 0) {
    while ($row = $db->fetchRow($result)) {
        $airports[$row['code']] = $row['name'];
    }
}

?>
<!DOCTYPE html>
<html>
<title>Flight Search</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="../public/js/flight-search.js"></script>
<script>
   
function showAirportMatches(input, autocompleteId) {
    var airportList = <?php echo json_encode($airports); ?>;

    var autocompleteContainer = document.getElementById(autocompleteId);
    autocompleteContainer.innerHTML = '';

    if (input.length === 0) {
        autocompleteContainer.style.display = 'none';
            return;
        } else {
            autocompleteContainer.style.display = 'block';
        }
    for (var code in airportList) {
        if (airportList.hasOwnProperty(code)) {
            if (code.toLowerCase().startsWith(input.toLowerCase()) || airportList[code].toLowerCase().startsWith(input.toLowerCase())) {
                var airportItem = document.createElement('div');
                airportItem.innerHTML = "<strong>" + code + "</strong> - " + airportList[code].substr(0, input.length);
                airportItem.innerHTML += "<input type='hidden' value='" + airportList[code] + "'>";
                airportItem.addEventListener('click', function() {
                    document.getElementById(autocompleteId).previousElementSibling.value = this.getElementsByTagName('input')[0].value;
                    closeAirportMatches(autocompleteId);
                });
                airportItem.addEventListener('keydown', function(event) {
                        handleKeydown(event, autocompleteId);
                    });
                autocompleteContainer.appendChild(airportItem);
            }
        }
    }   
    
}

function closeAirportMatches(autocompleteId) {
    document.getElementById(autocompleteId).innerHTML = '';
}

</script>

        
 <style>
    <?php include "../public/css/flight-search.css"; ?>
 </style>
        
   

<body>
    <div class="container">
        <div class="card custom-bg w-75 p-4 d-flex">
            <div class="row">
                <div class="pb-3 h3 text-left">Flight Search &#128747;</div>
            </div>
            <form id="flight-form" onsubmit="return validateForm()">
                <div class="row">
                    <div class="form-group col-md align-items-start flex-column">
                        <label for="origin" class="d-inline-flex">From</label>
                        <input type="text" placeholder="City or Airport" class="form-control" id="origin" name="origin" required
    onkeyup="showAirportMatches(this.value, 'origin-autocomplete')">
<div id="origin-autocomplete" class="autocomplete-items"></div>
                    </div>
                    <div class="form-group col-md align-items-start flex-column">
                        <label for="depart" class="d-inline-flex">To</label>
                        <input type="text" placeholder="City or Airport" class="form-control" id="depart" name="depart" required
    onkeyup="showAirportMatches(this.value, 'depart-autocomplete')">
<div id="depart-autocomplete" class="autocomplete-items"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md align-items-start flex-column">
                        <label for="departure-date" class=" d-inline-flex">Depart</label>
                        <input type="date" class="form-control" id="departure-date" name="departure-date"
                            onkeydown="return false" required>
                    </div>
                    <div class="form-group col-md align-items-start flex-column">
                        <label for="return-date" class="d-inline-flex">Return</label>
                        <input type="date" placeholder="One way" value=""
                            onChange="this.setAttribute('value', this.value)" class="form-control" id="return-date"
                            name="return-date">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3 align-items-start flex-column">
                        <label for="adults" class="d-inline-flex col-auto">Adults <span class="sublabel"> 12+
                            </span></label>
                        <select class="form-select" id="adults"
                            onchange="javascript: dynamicDropDown(this.options[this.selectedIndex].value);">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3 align-items-start flex-column">
                        <label for="children" class="d-inline-flex col-auto">Children <span class="sublabel"> 2-11
                            </span></label>
                        <select class="form-select" id="children">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-3 align-items-start flex-column">
                        <label for="infants" class="d-inline-flex col-auto">Infants <span class="sublabel"> less than
                                2</span></label>
                        <select class="form-select" id="infants">
                            <option value="0">0</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6 align-items-start flex-column">
                        <label for="cabin" class="d-inline-flex">Cabin</label>
                        <select class="form-select" id="cabin">
                            <option value="ECONOMY">Economy</option>
                            <option value="BUSINESS">Business</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-6 align-items-start flex-column pt-lg-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input align-self-center" type="checkbox" id="directFlights">
                            <label class="form-check-label d-inline-flex align-self-center" for="directFlights">Direct
                                flights</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="text-left col-auto">
                        <button type="submit" class="btn btn-primary">Search flights</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
