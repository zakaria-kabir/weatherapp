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
                    echo '<span class="text-muted">Longitude: ' . $newweather->lon . '&deg N&emsp; Latitude: ' . $newweather->lon . '&deg E </span>';
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
                        <p class="">
                            <?php
                            echo "Max temp: " . $newweather->temp_max . "&deg; C<br>" . "Min Temp: " . $newweather->temp_min . "&deg; C";
                            ?>
                        </p>
                    </div>

                </div>
                <div class="pb-1">
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
                <p>
                    <?php
                    echo '
                    <i class="fa-solid fa-temperature-quarter"> </i> Feels like: ' . $newweather->feels_like . '&deg; C &emsp;<i class="fas fa-compress-arrows-alt"></i> Pressure: ' . $newweather->pressure . 'hPa &emsp;<i class="fas fa-tint"></i> Humidity: ' . $newweather->humidity . '% &emsp;<i class="fa-solid fa-eye"></i> Visibility: ' . ($newweather->visibility / 1000) . 'Km';
                    ?>
                </p>
            </section>
        </div>
        <!-- eof heading -->
        <section class="mx-3">
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
                            $count = -1;
                        }
                    }
                    $count++;
                }
            }
            $dayArr = array();
            getDayHrwisedata($dayArr, $dc);
            $tabmenu = '';
            $tabcontent = '';
            function daily3hr($dayArr, $day)
            {
                global $tabcontent;
                foreach ($dayArr as $arr) {
                    $tabsubcontent = '<div class="card-group">';
                    foreach ($arr as $hr) {
                        if ($hr->day == $day) {
                            $tabsubcontent .= '
                            <div class="card">
                                <i><img src="http://openweathermap.org/img/wn/' . $hr->weather_icon . '@2x.png" class=" " alt=""></i>
                                <div class="card-body">
                                    <h5 class="card-title">' . $hr->temp . '&deg; C</h5>
                                    <p class="card-text">' . $hr->type . ' <span class="text-muted">( ' . $hr->typedescription . ' )</p>
                                    <p class="card-text">' . $hr->humidity . '</p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">' . $hr->time . '</small>
                                </div>
                            </div>
                        ';
                        }
                    }
                    $tabsubcontent .= '</div>';
                    $tabcontent .= $tabsubcontent;
                    $tabsubcontent = '';
                }
            }
            function day5($dayArr)
            {
                $count = 0;
                global $tabmenu;
                global $tabcontent;
                $daylist = array();
                foreach ($dayArr as $arr) {
                    $flag = false;
                    foreach ($arr as $hr) {
                        $day = $hr->day;
                        array_push($daylist, $day);
                        if ($count == 0 && $flag == false) {
                            $tabmenu .= '
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="' . $day . '-tab" data-bs-toggle="tab" data-bs-target="#' . $day . '"type="button" role="tab" aria-controls="' . $day . '" aria-selected="true"><p>' . $day . ", " . $hr->date . '</p><i><img src="http://openweathermap.org/img/wn/' . $hr->weather_icon . '.png" alt=""></i><h4>' . $hr->temp . '&deg; C</h4><p>' . $hr->type . '</p> </button>
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
                        } elseif ($day != $daylist[$count - 1] && $flag == false) {
                            $tabmenu .= '
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="' . $day . '-tab" data-bs-toggle="tab" data-bs-target="#' . $day . '"type="button" role="tab" aria-controls="' . $day . '" aria-selected="false"><p>' . $day . ", " . $hr->date . '</p><i><img src="http://openweathermap.org/img/wn/' . $hr->weather_icon . '.png" alt=""></i><h3>' . $hr->temp . '&deg; C</h3><p>' . $hr->type . '</p> </button>
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
                        }
                        $count++;
                    }
                }
            }
            day5($dayArr);
            ?>
            <div>
                <h3>
                    5 Day Forecast
                </h3>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php
                    echo $tabmenu;
                    ?>
                </ul>
                <h3>
                    3 Hour Forecast
                </h3>
                <div class="tab-content" id="myTabContent">
                    <?php
                    echo $tabcontent;
                    ?>
                </div>
            </div>
        </section>
    <?php
}
    ?>
    <!-- bootstrap js -->
    <script src=" ./assets/js/bootstrap.min.js">
    </script>

    </body>

</html>