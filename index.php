<?php

function build_calendar($month, $year) {

    include "php/config/config.php";
//    $stmt = $mysqli->prepare("select * from customers where MONTH(date) = ? AND YEAR(date)=?");
//    $stmt->bind_param('ss', $month, $year);
//    $bookings = array();
//    if($stmt->execute()){
//        $result = $stmt->get_result();
//        if($result->num_rows>0){
//            while($row = $result->fetch_assoc()){
//                $bookings[] = $row['date'];
//            }
//            $stmt->close();
//        }
//    }

    // Create array containing abbreviations of days of week.
    $daysOfWeek = array('Sunday', 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');

    // What is the first day of the month in question?
    $firstDayOfMonth = mktime(0,0,0,$month,1,$year);

    // How many days does this month contain?
    $numberDays = date('t',$firstDayOfMonth);

    // Retrieve some information about the first day of the
    // month in question.
    $dateComponents = getdate($firstDayOfMonth);

    // What is the name of the month in question?
    $monthName = $dateComponents['month'];

    // What is the index value (0-6) of the first day of the
    // month in question.
    $dayOfWeek = $dateComponents['wday'];

    // Create the table tag opener and day headers

    $datetoday = date('Y-m-d');
    $calendar = "<table class='table table-bordered'>";
    $calendar .= "<center><h2>$monthName $year</h2>";
    $calendar.= "<a class='btn btn-xs btn-primary' href='?month=".date('m', mktime(0, 0, 0, $month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month-1, 1, $year))."'>Previous Month</a> ";

    $calendar.= " <a href='index.php' class='btn btn-xs btn-primary' data-month='".date('m')."' data-year='".date('Y')."'>Current Month</a> ";

    $calendar.= "<a href='?month=".date('m', mktime(0, 0, 0, $month+1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month+1, 1, $year))."' class='btn btn-xs btn-primary'>Next Month</a></center><br>";

    $calendar .= "<tr>";

    // Create the calendar headers
    foreach($daysOfWeek as $day) {
        $calendar .= "<th  class='header'>$day</th>";
    }

    // Create the rest of the calendar
    // Initiate the day counter, starting with the 1st.
    $currentDay = 1;
    $calendar .= "</tr><tr>";

    // The variable $dayOfWeek is used to
    // ensure that the calendar
    // display consists of exactly 7 columns.

    if($dayOfWeek > 0) {
        for($k=0;$k<$dayOfWeek;$k++){
            $calendar .= "<td  class='empty'></td>";
        }
    }


    $month = str_pad($month, 2, "0", STR_PAD_LEFT);

    while ($currentDay <= $numberDays) {
        //Seventh column (Saturday) reached. Start a new row.
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            $calendar .= "</tr><tr>";
        }

        $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
        $date = "$year-$month-$currentDayRel";
        $dayname = strtolower(date('l', strtotime($date)));
        $eventNum = 0;
        $today = $date==date('Y-m-d')? "today" : "";
        if($date<date('Y-m-d')){
            $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'>N/A</button>";
        }else{
            $calendar.="<td class='$today'><h4>$currentDay</h4> <a href='php/form/reserverenForm.php?date=".$date."' class='btn btn-success btn-xs'>Book</a>";
        }


        $calendar .="</td>";
        //Increment counters
        $currentDay++;
        $dayOfWeek++;
    }

    //Complete the row of the last week in month, if necessary
    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for($l=0;$l<$remainingDays;$l++){
            $calendar .= "<td class='empty'></td>";
        }
    }

    $calendar .= "</tr>";
    $calendar .= "</table>";
    return $calendar;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Home Page</title>
    <link rel="stylesheet" href="assets/css/show.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
        table{
            table-layout: fixed;
        }

        td{
            width: 33%;
        }

        .today{
            background: yellow;
        }
    </style>
</head>
<body>

<div class="topnav">
    <a href="index.php">Home</a>
    <a href="show.php">Booking</a>
    <a href="#">Contact</a>
    <a href="#" style="float:right">Login</a>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="calendar">
                <?php
                    $dateComponents = getdate();
//                    echo '<pre>';
//                    var_dump($dateComponents);
//                    echo '</pre>';
                    $month = $dateComponents['mon'];
                    $year = $dateComponents['year'];

//                    if(isset($_GET['month'])) {
//                        $v = $_GET['month'];
//                    } else {
//                        $v = $month;
//                    }
                    echo build_calendar($_GET['month'] ?? $month, $_GET['year'] ?? $year);
                ?>
            </div>
        </div>
    </div>
</div>
