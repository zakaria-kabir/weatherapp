<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- mdb -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.11.0/mdb.min.css" rel="stylesheet" />
    <!-- bootstrap css -->
    <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
    <!-- font awsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- custom css -->
    <link rel="stylesheet" href="./assets/css/customcss.css">
    <title>Weather App</title>
</head>
<!-- nav -->
<nav class="navbar navbar-expand-sm navbar-sm navbar-light bg-light fixed-top shadow-sm bg-transparent">
    <div class="container-fluid input-group">
        <h2 class="m-0 text-white text-center">Weather App</h2>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
            <form id="inputform" action="" method="POST" class="ms-auto d-flex form-outline">
                <input class="form-control me-2" id="city" type="search" placeholder="" name="city" required />
                <label id="form-label" class="form-label text-white" for="inputform">Enter your City Name</label>
                <button class="btn btn-sm btn-primary" type="submit" id="submit" name="submitted"><i class="fas fa-search"></i></button>
            </form>
        </div>
    </div>
</nav>
<!-- eof nav -->
<?php
$status = '';
if (!isset($_POST["city"])) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=dhaka&appid=d705900452bd53d61fae15651c6fed92&units=metric";
} else {
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . $_POST["city"] . "&appid=d705900452bd53d61fae15651c6fed92&units=metric";
}
$content = @file_get_contents($url);
if (!$content) {
    $status = "City not found...";
    echo '<div class="d-flex justify-content-center vh-100">
                <div class="alert alert-danger align-self-center" role="alert"><h1>'
        . $status .
        '</h1></div>
        </div>';
} else {
    $dc = json_decode($content);
    include "./include/timezone.php";
    class weather
    {
        public $name;
        public $country;
        public $time;
        public $ctime;
        public $date;
        public $day;
        public $datetime;
        public $weather_icon;
        public $temp;
        public $temp_max;
        public $temp_min;
        public $feels_like;
        public $pressure;
        public $humidity;
        public $wind_speed;
        public $wind_degree;
        public $visibility;
        public $lon;
        public $lat;
        public $type;
        public $typedescription;
        public $cloud;
        public $sunrise;
        public $sunset;
        public $unix;
    }
    $a = $names[$dc->sys->country];
    function getCurrentData($dc, $newweather)
    {
        $newweather->name = $dc->name;
        $newweather->country = $GLOBALS['a'];
        $newweather->time = date('g:i a, F j, Y; l', ($dc->dt + $dc->timezone));
        $newweather->ctime = date('g:i a', ($dc->dt + $dc->timezone));
        $newweather->temp = $dc->main->temp;
        $newweather->temp_max = $dc->main->temp_max;
        $newweather->temp_min = $dc->main->temp_min;
        $newweather->feels_like = $dc->main->feels_like;
        $newweather->pressure = $dc->main->pressure;
        $newweather->humidity = $dc->main->humidity;
        $newweather->wind_speed = $dc->wind->speed;
        $newweather->wind_degree = $dc->wind->deg;
        $newweather->lon = $dc->coord->lon;
        $newweather->lat = $dc->coord->lat;
        $newweather->type = $dc->weather[0]->main;
        $newweather->typedescription = $dc->weather[0]->description;
        $newweather->weather_icon = $dc->weather[0]->icon;
        $newweather->visibility = $dc->visibility;
        $newweather->cloud = $dc->clouds->all;
        $newweather->unix = $dc->dt;
    }
    $newweather = new weather();
    getCurrentData($dc, $newweather);

?>

    <body class='<?php echo $newweather->type ?>'>
        <!-- heading -->
        <div class='wrapper container'>
            <section class="text-center mt-4 pt-5" id="heading">
                <h2 class="m-0">
                    <?php
                    echo $newweather->name . ', ' . $newweather->country;
                    ?>
                </h2>
                <span class="text-muted">
                    <?php
                    echo '<span class="text-muted">Longitude: ' . $newweather->lon . '&deg N&emsp; Latitude: ' . $newweather->lat . '&deg E </span>';
                    ?>
                </span>
                <div class="row">
                    <div class="col-md-7 text-md-end text-center d-flex flex-column justify-content-center">
                        <h1 class="m-0">
                            <i><img src="<?php
                                            echo " http://openweathermap.org/img/wn/" . $newweather->weather_icon . "@2x.png";
                                            ?>" alt=""></i>
                            <?php
                            echo $newweather->temp . "&deg C";
                            ?>
                        </h1>
                    </div>
                    <div class="col-md-4 text-md-start text-center d-flex flex-column justify-content-center">
                        <p class="" style="color: rgb(225, 225, 225);">
                            <?php
                            echo "Max temp: " . $newweather->temp_max . "&deg; C<br>" . "Min Temp: " . $newweather->temp_min . "&deg; C";
                            ?>
                        </p>
                    </div>

                </div>
                <div class=" pb-1 mt-md-n4">
                    <h3 class="d-inline">
                        <?php
                        echo $newweather->type;
                        ?>
                    </h3>
                    <span class="text-muted">
                        <?php
                        echo $newweather->typedescription;
                        ?>
                    </span>
                </div>
                <p class="mb-0">
                    <?php
                    echo '<i class="fa-regular fa-clock"></i> Updated as of: ' . $newweather->time;
                    ?>
                </p>
                <p class="mb-0">
                    <?php
                    echo '
                    <i class="fa-solid fa-temperature-quarter"> </i> Feels like: ' . $newweather->feels_like . '&deg; C &emsp;<i class="fas fa-compress-arrows-alt"></i> Pressure: ' . $newweather->pressure . 'hPa &emsp;<i class="fas fa-tint"></i> Humidity: ' . $newweather->humidity . '% &emsp;<i class="fa-solid fa-eye"></i> Visibility: ' . ($newweather->visibility / 1000) . 'Km';
                    ?>
                </p>
                <p>
                    <?php
                    echo '
                    <i class="fa-solid fa-wind"> </i> Wind Speed: ' . $newweather->wind_speed . 'm/s &emsp;<i class="fa-solid fa-angles-up"></i> Wind Degree: ' . $newweather->wind_degree . '&deg &emsp;<i class="fa-solid fa-cloud"></i> Cloud: ' . $newweather->cloud . '%';
                    ?>
                </p>
            </section>
        </div>
        <!-- eof heading -->
        <section class="mx-3 mb-4">
            <?php
            if (!isset($_POST["city"])) {
                $url = "https://api.openweathermap.org/data/2.5/forecast?q=dhaka&appid=d705900452bd53d61fae15651c6fed92&units=metric";
            } else {
                $url = "https://api.openweathermap.org/data/2.5/forecast?q=" . $_POST["city"] . "&appid=d705900452bd53d61fae15651c6fed92&units=metric";
            }
            $content = file_get_contents($url);
            $dc = json_decode($content);
            include "./include/timezone.php";
            function getDayHrwisedata(&$dayArr, $dc)
            {
                $count = 0;
                $newarr = array();
                foreach ($dc->list as $hr3) {
                    $newweatherhr = new weather();
                    $newweatherhr->time = date('g:i a', ($hr3->dt + $dc->city->timezone));
                    $newweatherhr->date = date('F j, Y', ($hr3->dt + $dc->city->timezone));
                    $newweatherhr->datetime = date('g:i a, l, F j, Y;', ($hr3->dt + $dc->city->timezone));
                    $newweatherhr->day = date('D', ($hr3->dt + $dc->city->timezone));
                    $newweatherhr->temp = $hr3->main->temp;
                    $newweatherhr->temp_max = $hr3->main->temp_max;
                    $newweatherhr->temp_min = $hr3->main->temp_min;
                    $newweatherhr->feels_like = $hr3->main->feels_like;
                    $newweatherhr->pressure = $hr3->main->pressure;
                    $newweatherhr->humidity = $hr3->main->humidity;
                    $newweatherhr->wind_speed = $hr3->wind->speed;
                    $newweatherhr->wind_degree = $hr3->wind->deg;
                    $newweatherhr->type = $hr3->weather[0]->main;
                    $newweatherhr->typedescription = $hr3->weather[0]->description;
                    $newweatherhr->weather_icon = $hr3->weather[0]->icon;
                    $newweatherhr->visibility = $hr3->visibility;
                    $newweatherhr->cloud = $hr3->clouds->all;
                    $newweatherhr->unix = $hr3->dt;


                    if (empty($newarr)) {
                        array_push($newarr, $newweatherhr);
                    } else {
                        if ($newarr[$count - 1]->date == $newweatherhr->date) {
                            array_push($newarr, $newweatherhr);
                        } else {
                            array_push($dayArr, $newarr);
                            $newarr = array();
                            array_push($newarr, $newweatherhr);
                            $count = 0;
                        }
                    }
                    $count++;
                }
                array_push($dayArr, $newarr);
            }
            $dayArr = array();
            getDayHrwisedata($dayArr, $dc);
            $tabmenu = '';
            $tabcontent = '';
            $tabsubsubcontent = '<div class="tab-content details-tabcontent">';
            // details of an hour
            function daydetails($hr, $timeid)
            {
                global $tabsubsubcontent;
                $tabsubsubcontent .= '
                <div class="tab-pane fade" id="' . $timeid . '" role="tabpanel" aria-labelledby="profile-tab"><h4 class="text-decoration-underline"> Details of this hour</h4><p class="text-muted d-inline"><i class="fa-solid fa-temperature-full text-white"></i> Max temp: <span class="text-white">'
                    . $hr->temp_max . '&deg C</span></p><p class="text-muted d-inline">&emsp;<i class="fa-solid fa-temperature-empty  text-white"></i> Min temp: <span class="text-white">' .
                    $hr->temp_min . '&deg C</span></p><p class="text-muted d-inline">&emsp;<i class="fa-solid fa-temperature-half  text-white"></i> Feels Like: <span class="text-white">' .
                    $hr->feels_like . '&deg C</span></p><div></div><p class="text-muted d-inline"><i class="fas fa-compress-arrows-alt  text-white"></i> Pressure: <span class="text-white">' .
                    $hr->pressure . 'hPa</span></p><p class="text-muted d-inline">&emsp;<i class="fas fa-tint  text-white"></i> Humidity: <span class="text-white">' .
                    $hr->humidity . '%</span></p><p class="text-muted d-inline">&emsp;<i class="fa-solid fa-eye text-white"></i> Visibility: <span class="text-white">' .
                    ($hr->visibility) / 1000 . 'Km</span></p><div></div><p class="text-muted d-inline"><i class="fa-solid fa-wind text-white"></i> Wind Speed: <span class="text-white">' .
                    $hr->wind_speed . 'm/s</span></p><p class="text-muted d-inline">&emsp;<i class="fa-solid fa-angles-up text-white"></i> Wind Degree: <span class="text-white">' .
                    $hr->wind_degree . '&deg</span></p><p class="text-muted d-inline">&emsp;<i class="fa-solid fa-cloud text-white"></i> Cloud: <span class="text-white">' .
                    $hr->cloud . '%</span></p>
                </div>';
            }
            // daily 3hr interval forcast
            $temparr = array();
            $labelarr = array();
            $countchart = 0;
            $label = '';
            $temp = '';
            ?>
            <script>
                function chartshow(l, d, count, date, icon) {
                    let chartStatus = Chart.getChart("myChart");
                    if (chartStatus != undefined) {
                        chartStatus.destroy();
                    }
                    var img = [];
                    for (let i = 0; i < icon.length; i++) {
                        img[i] = new Image(50, 50);
                        img[i].src = icon[i];
                    }
                    var ctx = document.getElementById("myChart").getContext("2d");
                    Chart.defaults.font.size = 16;
                    Chart.defaults.color = "#fff";
                    var myChart = new Chart(ctx, {
                        type: "line",
                        data: {
                            labels: l,
                            datasets: [{
                                data: d,
                                label: 'Temparature °C',
                                borderWidth: 2,
                                borderColor: "#767676",
                                lineTension: 0.6,
                                pointStyle: img,
                            }, ]
                        },
                        options: {
                            plugins: {
                                title: {
                                    display: true,
                                    text: date,
                                    font: {
                                        size: 24
                                    }
                                },

                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    },
                                    offset: true,
                                    title: {
                                        display: true,
                                        text: 'Time Frame'
                                    }
                                },
                                y: {
                                    offset: true,
                                    title: {
                                        display: true,
                                        text: 'Temparature ℃'
                                    }
                                }
                            },
                            responsive: true,
                            maintainAspectRatio: false,
                        }
                    })
                }

                function chart(day, count) {
                    var x = document.getElementById("chart-container");
                    if (x.style.display == "none") {
                        x.style.display = "block";
                    }
                    var temp = [];
                    var label = [];
                    var icon = [];
                    var dayArr = <?php echo (json_encode($dayArr)); ?>;
                    var get = dayArr[count];
                    var date = get[0].date;
                    for (let i = 0; i < get.length; i++) {
                        temp.push(get[i].temp);
                        label.push(get[i].time);
                        icon.push("http://openweathermap.org/img/wn/" + get[i].weather_icon + ".png");
                    }
                    chartshow(label, temp, count, date, icon);
                }
            </script>
            <?php
            function daily3hr($dayArr, $day)
            {
                global $tabcontent;
                global $tabsubsubcontent;
                foreach ($dayArr as $arr) {
                    $tabsubcontent = '<div class="card-group">';
                    $tabsubcontent .= '<ul class="nav nav-tabs" id="myTab" role="tablist">';
                    foreach ($arr as $hr) {
                        if ($hr->day == $day) {
                            $stime = date_create_from_format('g:i a', $hr->time);
                            $timeid = $day . date_format($stime, 'gia');
                            $tabsubcontent .= '
                            <li class="nav-item" role="presentation">
                            <button class="nav-link p-0" id="' . $timeid . '-tab" data-bs-toggle="tab" data-bs-target="#' . $timeid . '" type="button" role="tab" aria-controls="home" aria-selected="false">
                            <div class="card">
                                <i><img src="http://openweathermap.org/img/wn/' . $hr->weather_icon . '@2x.png" class=" " alt=""></i>
                                 
                                <div class="card-body">
                                    <h5 class="card-title">' . $hr->temp . '&deg; C</h5>
                                    <p class="card-text">' . $hr->type . ' <span class="text-muted"> (' . $hr->typedescription . ')</p>                     
                                </div>
                               
                                <div class="card-footer">
                                    <small class="text-muted">' . $hr->time . '</small>
                                </div>
                            </div>
                             </button>
                             </li>
                        ';
                            daydetails($hr, $timeid);
                        }
                    }
                    $tabsubcontent .= '</ul></div>' . $tabsubsubcontent . '</div>';
                    $tabcontent .= $tabsubcontent;
                    $tabsubcontent = '';
                }
            }
            // 5 days forcast
            function day5($dayArr)
            {
                $count = 0;
                $c = 0;
                global $tabmenu;
                global $tabcontent;
                $daylist = array();
                foreach ($dayArr as $arr) {
                    $flag = false;
                    foreach ($arr as $hr) {
                        $day = $hr->day;
                        $d = "'$day'";
                        array_push($daylist, $day);
                        if ($count == 0 && $flag == false) {
                            $tabmenu .= '
                        <li class="nav-item" role="presentation ">
                            <button class="nav-link active" id="' . $day . '-tab" data-bs-toggle="tab" data-bs-target="#' . $day . '"type="button" role="tab" aria-controls="' . $day . '" aria-selected="true" onclick="chart(' . $d . ',' . json_encode($c) . ')"><p>' . $day . ", " . $hr->date . '</p><i><img src="http://openweathermap.org/img/wn/' . $hr->weather_icon . '.png" alt=""></i><h4>' . $hr->temp . '&deg; C</h4><p>' . $hr->type . '</p> </button>
                        </li>
                        ';
                            $tabcontent .= '
                        <div class="tab-pane fade show active" id="' . $day . '" role="tab' . $day . '" aria-labelledby="' . $day . '-tab">
                        ';
                            daily3hr($dayArr, $day);
                            $tabcontent .= '
                        </div>
                        ';
                            $flag = true;
                            $c++;
                        } elseif ($day != $daylist[$count - 1] && $flag == false) {
                            $tabmenu .= '
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="' . $day . '-tab" data-bs-toggle="tab" data-bs-target="#' . $day . '"type="button" role="tab" aria-controls="' . $day . '" aria-selected="false" onclick="chart(' . $d . ',' . json_encode($c) . ')"><p>' . $day . ", " . $hr->date . '</p><i><img src="http://openweathermap.org/img/wn/' . $hr->weather_icon . '.png" alt=""></i><h3>' . $hr->temp . '&deg; C</h3><p>' . $hr->type . '</p> </button>
                        </li>
                        ';
                            $tabcontent .= '
                        <div class="tab-pane fade" id="' . $day . '" role="tab' . $day . '" aria-labelledby="' . $day . '-tab">
                        ';
                            daily3hr($dayArr, $day);
                            $tabcontent .= '
                        </div>
                        ';
                            $flag = true;
                            $c++;
                        }
                        $count++;
                    }
                }
            }
            day5($dayArr);
            ?>
            <div class="text-center d-flex flex-column justify-content-center align-items-center">
                <h3 class="text-decoration-underline">
                    5 Day Forecast
                </h3>
                <ul class="nav nav-tabs item" id="myTab" role="tablist">
                    <?php
                    echo $tabmenu;
                    ?>
                </ul>
                <h3 class="text-decoration-underline mt-2">
                    3 Hour Forecast
                </h3>
                <div id="chart-container" class="chart-wrap mb-4" style=" height:40vh; width:80vw; display: none;">
                    <h5>Summery</h5>
                    <canvas id="myChart"></canvas>
                </div>
                <div class="tab-content mt-3" id="myTabContent">
                    <h5>Details</h5>
                    <?php
                    echo  $tabcontent;
                    ?>
                </div>
            </div>
        </section>
    <?php
}
    ?>
    <!-- bootstrap js -->
    <script src=" ./assets/js/bootstrap.min.js"></script>
    <!-- chart js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>

    </body>

</html>