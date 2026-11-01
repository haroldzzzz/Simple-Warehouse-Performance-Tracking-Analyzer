<?php
// Espayos, Charles Lenard
// Calderon, Adrian
// Barela, Mark Harold

session_start();

// Reset Button
if (isset($_POST["newweek"])) {
    session_destroy();
    session_start();
}

if (!isset($_SESSION['laptopStock'])) {
    $_SESSION['laptopStock'] = 85;
    $_SESSION['keyboardStock'] = 75;
    $_SESSION['babyOilStock'] = 45;
    $_SESSION['vixInhalerStock'] = 50;
    $_SESSION['kangkongChipsStock'] = 40;
}

// The ORIGINAL stock numbers, only used for the Weekly Report average
$originalLaptopStock = 85;
$originalKeyboardStock = 75;
$originalBabyOilStock = 45;
$originalVixInhalerStock = 50;
$originalKangkongChipsStock = 40;

// Turns 1 -> "monday", 2 -> "tuesday", etc.
// Ginagawa nitong word yung number para gumawa ng session names. Ex: "mondayLaptopSold"
$dayWord = [1 => "monday", 2 => "tuesday", 3 => "wednesday", 4 => "thursday",
            5 => "friday", 6 => "saturday", 7 => "sunday"];
// Pang Display
$dayLabel = [1 => "Monday", 2 => "Tuesday", 3 => "Wednesday", 4 => "Thursday",
             5 => "Friday", 6 => "Saturday", 7 => "Sunday", 8 => "Weekly Report"];

$choice = $_POST["day"] ?? 1;
if ($choice < 1 || $choice > 8) { $choice = 1; }

// Current stock (goes down every time a day is saved)
$laptopStock = $_SESSION['laptopStock'];
$keyboardStock = $_SESSION['keyboardStock'];
$babyOilStock = $_SESSION['babyOilStock'];
$vixInhalerStock = $_SESSION['vixInhalerStock'];
$kangkongChipsStock = $_SESSION['kangkongChipsStock'];

// Items sold this day (0 until saved)
$laptopSold = 0; $keyboardSold = 0; $babyOilSold = 0; $vixInhalerSold = 0; $kangkongChipsSold = 0;

// Status Daily
$item1StatusDaily = "---"; $item2StatusDaily = "---"; $item3StatusDaily = "---"; $item4StatusDaily = "---"; $item5StatusDaily = "---";
// Status Weekly
$item1Status = "---"; $item2Status = "---"; $item3Status = "---"; $item4Status = "---"; $item5Status = "---";
// Average Weekly
$laptopAvg = 0; $keyboardAvg = 0; $babyOilAvg = 0; $vixInhalerAvg = 0; $kangkongChipsAvg = 0;
// Total sold items
$laptopTotalSold = 0; $keyboardTotalSold = 0; $babyOilTotalSold = 0; $vixInhalerTotalSold = 0; $kangkongChipsTotalSold = 0;

$day = "";
$isDaily = ($choice >= 1 && $choice <= 7);
$daySaved = false;

if ($isDaily) {

    $day = $dayLabel[$choice];
    $prefix = $dayWord[$choice]; // Ex. "monday"

    if (isset($_SESSION[$prefix . "Saved"])) {
        $daySaved = true;

        $laptopStock = $_SESSION[$prefix . "LaptopStock"];
        $keyboardStock = $_SESSION[$prefix . "KeyboardStock"];
        $babyOilStock = $_SESSION[$prefix . "BabyOilStock"];
        $vixInhalerStock = $_SESSION[$prefix . "VixInhalerStock"];
        $kangkongChipsStock = $_SESSION[$prefix . "KangkongChipsStock"];

        $laptopSold = $_SESSION[$prefix . "LaptopSold"];
        $keyboardSold = $_SESSION[$prefix . "KeyboardSold"];
        $babyOilSold = $_SESSION[$prefix . "BabyOilSold"];
        $vixInhalerSold = $_SESSION[$prefix . "VixInhalerSold"];
        $kangkongChipsSold = $_SESSION[$prefix . "KangkongChipsSold"];

        $item1StatusDaily = $_SESSION[$prefix . "LaptopStatus"];
        $item2StatusDaily = $_SESSION[$prefix . "KeyboardStatus"];
        $item3StatusDaily = $_SESSION[$prefix . "BabyOilStatus"];
        $item4StatusDaily = $_SESSION[$prefix . "VixInhalerStatus"];
        $item5StatusDaily = $_SESSION[$prefix . "KangkongChipsStatus"];
    }
    else if (isset($_POST["save"])) {
        $laptopSold = (int)($_POST['laptopSold'] ?? 0);
        $keyboardSold = (int)($_POST['keyboardSold'] ?? 0);
        $babyOilSold = (int)($_POST['babyOilSold'] ?? 0);
        $vixInhalerSold = (int)($_POST['vixInhalerSold'] ?? 0);
        $kangkongChipsSold = (int)($_POST['kangkongChipsSold'] ?? 0);

        if ($laptopSold > $laptopStock) { $laptopSold = $laptopStock; }
        if ($keyboardSold > $keyboardStock) { $keyboardSold = $keyboardStock; }
        if ($babyOilSold > $babyOilStock) { $babyOilSold = $babyOilStock; }
        if ($vixInhalerSold > $vixInhalerStock) { $vixInhalerSold = $vixInhalerStock; }
        if ($kangkongChipsSold > $kangkongChipsStock) { $kangkongChipsSold = $kangkongChipsStock; }

        $laptopPercent = ($laptopStock > 0) ? ($laptopSold / $laptopStock) * 100 : 0;
        if ($laptopPercent >= 70) { $item1StatusDaily = "High Performing Item"; }
        else if ($laptopPercent >= 40) { $item1StatusDaily = "Moderate Performing Item"; }
        else { $item1StatusDaily = "Low Performing Item"; }

        $keyboardPercent = ($keyboardStock > 0) ? ($keyboardSold / $keyboardStock) * 100 : 0;
        if ($keyboardPercent >= 70) { $item2StatusDaily = "High Performing Item"; }
        else if ($keyboardPercent >= 40) { $item2StatusDaily = "Moderate Performing Item"; }
        else { $item2StatusDaily = "Low Performing Item"; }

        $babyOilPercent = ($babyOilStock > 0) ? ($babyOilSold / $babyOilStock) * 100 : 0;
        if ($babyOilPercent >= 70) { $item3StatusDaily = "High Performing Item"; }
        else if ($babyOilPercent >= 40) { $item3StatusDaily = "Moderate Performing Item"; }
        else { $item3StatusDaily = "Low Performing Item"; }

        $vixInhalerPercent = ($vixInhalerStock > 0) ? ($vixInhalerSold / $vixInhalerStock) * 100 : 0;
        if ($vixInhalerPercent >= 70) { $item4StatusDaily = "High Performing Item"; }
        else if ($vixInhalerPercent >= 40) { $item4StatusDaily = "Moderate Performing Item"; }
        else { $item4StatusDaily = "Low Performing Item"; }

        $kangkongChipsPercent = ($kangkongChipsStock > 0) ? ($kangkongChipsSold / $kangkongChipsStock) * 100 : 0;
        if ($kangkongChipsPercent >= 70) { $item5StatusDaily = "High Performing Item"; }
        else if ($kangkongChipsPercent >= 40) { $item5StatusDaily = "Moderate Performing Item"; }
        else { $item5StatusDaily = "Low Performing Item"; }

        // Saving the stock
        $_SESSION[$prefix . "LaptopStock"] = $laptopStock;
        $_SESSION[$prefix . "KeyboardStock"] = $keyboardStock;
        $_SESSION[$prefix . "BabyOilStock"] = $babyOilStock;
        $_SESSION[$prefix . "VixInhalerStock"] = $vixInhalerStock;
        $_SESSION[$prefix . "KangkongChipsStock"] = $kangkongChipsStock;

        $_SESSION[$prefix . "LaptopSold"] = $laptopSold;
        $_SESSION[$prefix . "KeyboardSold"] = $keyboardSold;
        $_SESSION[$prefix . "BabyOilSold"] = $babyOilSold;
        $_SESSION[$prefix . "VixInhalerSold"] = $vixInhalerSold;
        $_SESSION[$prefix . "KangkongChipsSold"] = $kangkongChipsSold;

        $_SESSION[$prefix . "LaptopStatus"] = $item1StatusDaily;
        $_SESSION[$prefix . "KeyboardStatus"] = $item2StatusDaily;
        $_SESSION[$prefix . "BabyOilStatus"] = $item3StatusDaily;
        $_SESSION[$prefix . "VixInhalerStatus"] = $item4StatusDaily;
        $_SESSION[$prefix . "KangkongChipsStatus"] = $item5StatusDaily;

        // Decrease the stock for the next day
        $_SESSION['laptopStock'] = $laptopStock - $laptopSold;
        $_SESSION['keyboardStock'] = $keyboardStock - $keyboardSold;
        $_SESSION['babyOilStock'] = $babyOilStock - $babyOilSold;
        $_SESSION['vixInhalerStock'] = $vixInhalerStock - $vixInhalerSold;
        $_SESSION['kangkongChipsStock'] = $kangkongChipsStock - $kangkongChipsSold;

        $_SESSION[$prefix . "Saved"] = true;
        $daySaved = true;
    }

} else {
    // WEEKLY REPORT (choice == 8)
    $days = ["monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday"];

    foreach ($days as $d) {
        $laptopTotalSold += $_SESSION[$d . "LaptopSold"] ?? 0;
        $keyboardTotalSold += $_SESSION[$d . "KeyboardSold"] ?? 0;
        $babyOilTotalSold += $_SESSION[$d . "BabyOilSold"] ?? 0;
        $vixInhalerTotalSold += $_SESSION[$d . "VixInhalerSold"] ?? 0;
        $kangkongChipsTotalSold += $_SESSION[$d . "KangkongChipsSold"] ?? 0;
    }

    $laptopAvg = ($laptopTotalSold / $originalLaptopStock) * 100;
    $keyboardAvg = ($keyboardTotalSold / $originalKeyboardStock) * 100;
    $babyOilAvg = ($babyOilTotalSold / $originalBabyOilStock) * 100;
    $vixInhalerAvg = ($vixInhalerTotalSold / $originalVixInhalerStock) * 100;
    $kangkongChipsAvg = ($kangkongChipsTotalSold / $originalKangkongChipsStock) * 100;

    $laptopStock = $originalLaptopStock;
    $keyboardStock = $originalKeyboardStock;
    $babyOilStock = $originalBabyOilStock;
    $vixInhalerStock = $originalVixInhalerStock;
    $kangkongChipsStock = $originalKangkongChipsStock;

    $day = "Weekly Report";
}

// STATUS CHECKING (Weekly Report)
$averages = [
    $laptopAvg,
    $keyboardAvg,
    $babyOilAvg,
    $vixInhalerAvg,
    $kangkongChipsAvg
];

$statuses = [];
$bgcolors = [];

for ($counter = 0; $counter < 5; $counter++) {

    if ($averages[$counter] >= 70) {
        $statuses[$counter] = "High Performing Item";
        $bgcolors[$counter] = "lightgreen";
    }
    else if ($averages[$counter] >= 40) {
        $statuses[$counter] = "Moderate Performing Item";
        $bgcolors[$counter] = "lightyellow";
    }
    else {
        $statuses[$counter] = "Low Performing Item";
        $bgcolors[$counter] = "pink";
    }
}

$item1Status = $statuses[0]; // Laptop
$item2Status = $statuses[1]; // Keyboard
$item3Status = $statuses[2]; // Baby Oil
$item4Status = $statuses[3]; // Vix Inhaler
$item5Status = $statuses[4]; // Kangkong Chips

?>
<!DOCTYPE html>
<html>
<head>
    <title>Warehouse Performance Tracking Analyzer</title>
</head>

<body bgcolor="lightblue">
        <h1>Warehouse Performance Tracking Analyzer</h1>

        <form method="POST">
            <label><b>Select Day:</b></label>
            <select name="day">
                <option value="1" <?php if ($choice == 1) { echo "selected"; } ?>>Monday</option>
                <option value="2" <?php if ($choice == 2) { echo "selected"; } ?>>Tuesday</option>
                <option value="3" <?php if ($choice == 3) { echo "selected"; } ?>>Wednesday</option>
                <option value="4" <?php if ($choice == 4) { echo "selected"; } ?>>Thursday</option>
                <option value="5" <?php if ($choice == 5) { echo "selected"; } ?>>Friday</option>
                <option value="6" <?php if ($choice == 6) { echo "selected"; } ?>>Saturday</option>
                <option value="7" <?php if ($choice == 7) { echo "selected"; } ?>>Sunday</option>
                <option value="8" <?php if ($choice == 8) { echo "selected"; } ?>>Weekly Report</option>
            </select>
            <input type="submit" value="SELECT">
            <input type="submit" name="newweek" value="RESET">
        <hr>

        <?php
        if ($isDaily) {
        // Displaying the Table
        echo "
            <table border='1' width='500' bgcolor='black'>
            <caption><b><h3>$day</h3></b></caption>
                <tr bgcolor='white'>
                    <th> Item
                    <th> Stock
                    <th> Sold
                    <th> Status
                </tr>
                <tr bgcolor='white' align='center'>
                    <td align='left'> 1. Laptop
                    <td> $laptopStock";

        if ($daySaved) {
            echo "<td> $laptopSold <input type='hidden' name='laptopSold' value='$laptopSold'>";
        } else {
            echo "<td> <input type='range' name='laptopSold' min='0' max='$laptopStock' value='0' oninput='laptopOutput.value = this.value'>
                  <output id='laptopOutput'> 0 </output>";
        }

        echo "
                    <td> $item1StatusDaily
                </tr>
                <tr bgcolor='white' align='center'>
                    <td align='left'> 2. Keyboard
                    <td> $keyboardStock";

        if ($daySaved) {
            echo "<td> $keyboardSold <input type='hidden' name='keyboardSold' value='$keyboardSold'>";
        } else {
            echo "<td> <input type='range' name='keyboardSold' min='0' max='$keyboardStock' value='0' oninput='keyboardOutput.value = this.value'>
                  <output id='keyboardOutput'> 0 </output>";
        }

        echo "
                    <td> $item2StatusDaily
                </tr>
                <tr bgcolor='white' align='center'>
                    <td align='left'> 3. Baby Oil
                    <td> $babyOilStock";

        if ($daySaved) {
            echo "<td> $babyOilSold <input type='hidden' name='babyOilSold' value='$babyOilSold'>";
        } else {
            echo "<td> <input type='range' name='babyOilSold' min='0' max='$babyOilStock' value='0' oninput='babyOilOutput.value = this.value'>
                  <output id='babyOilOutput'> 0 </output>";
        }

        echo "
                    <td> $item3StatusDaily
                </tr>
                <tr bgcolor='white' align='center'>
                    <td align='left'> 4. Vix Inhaler
                    <td> $vixInhalerStock";

        if ($daySaved) {
            echo "<td> $vixInhalerSold <input type='hidden' name='vixInhalerSold' value='$vixInhalerSold'>";
        } else {
            echo "<td> <input type='range' name='vixInhalerSold' min='0' max='$vixInhalerStock' value='0' oninput='vixInhalerOutput.value = this.value'>
                  <output id='vixInhalerOutput'> 0 </output>";
        }

        echo "
                    <td> $item4StatusDaily
                </tr>
                <tr bgcolor='white' align='center'>
                    <td align='left'> 5. Kangkong Chips
                    <td> $kangkongChipsStock";

        if ($daySaved) {
            echo "<td> $kangkongChipsSold <input type='hidden' name='kangkongChipsSold' value='$kangkongChipsSold'>";
        } else {
            echo "<td> <input type='range' name='kangkongChipsSold' min='0' max='$kangkongChipsStock' value='0' oninput='kangkongChipsOutput.value = this.value'>
                  <output id='kangkongChipsOutput'> 0 </output>";
        }

        echo "
                    <td> $item5StatusDaily
                </tr>
            </table>";

        if ($daySaved) {
            echo "<br> <b>$day has been saved.</b>";
        } else {
            echo "<br> <input type='submit' name='save' value='SAVE'>";
        }

        } else {
        // WEEKLY REPORT
        echo "
            <table border='1' width='800' bgcolor='black'>
            <caption><b><h3> Weekly Report </h3></b></caption>
                <tr bgcolor='white'>
                    <th> Item
                    <th> Total Stocks
                    <th> Total Sold Items
                    <th> Average Items Sold
                    <th> Performance Status
                </tr>
                <tr bgcolor='$bgcolors[0]'>
                    <td> Laptop
                    <td> $laptopStock
                    <td> $laptopTotalSold
                    <td> $laptopAvg%
                    <td> $item1Status
                </tr>
                <tr bgcolor='$bgcolors[1]'>
                    <td> Keyboard
                    <td> $keyboardStock
                    <td> $keyboardTotalSold
                    <td> $keyboardAvg%
                    <td> $item2Status
                </tr>
                <tr bgcolor='$bgcolors[2]'>
                    <td> Baby Oil
                    <td> $babyOilStock
                    <td> $babyOilTotalSold
                    <td> $babyOilAvg%
                    <td> $item3Status
                </tr>
                <tr bgcolor='$bgcolors[3]'>
                    <td> Vix Inhaler
                    <td> $vixInhalerStock
                    <td> $vixInhalerTotalSold
                    <td> $vixInhalerAvg%
                    <td> $item4Status
                </tr>
                <tr bgcolor='$bgcolors[4]'>
                    <td> Kangkong Chips
                    <td> $kangkongChipsStock
                    <td> $kangkongChipsTotalSold
                    <td> $kangkongChipsAvg%
                    <td> $item5Status
                </tr>

            </table>
            ";
        }

        ?>

        </form>
</body>
</html>