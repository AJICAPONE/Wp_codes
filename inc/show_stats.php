<?php

global $post,$wpdb;

$date = date("Y-m-d");
$views_timer= $_POST['intMin'];

// Извлекаем статистику по текущей дате (переменная date попадает сюда из файла count.php, который, в свою очередь, подключается в каждом из 4 обычных файлов)
$res = ( "SELECT *,SUM(views_bd) AS result_value, COUNT(visit_id) AS result_hosts FROM visits WHERE date_bd='$date'");
$row3 = $wpdb->get_results($res);

//
//// Извлекаем статистику по текущей дате (переменная date попадает сюда из файла count.php, который, в свою очередь, подключается в каждом из 4 обычных файлов)
//$res = ( "SELECT *,SUM(views_last_mouth) AS result_view, FROM createcompany WHERE date_company=NOW() GROUP BY id_company");
//$row2 = $wpdb->get_results($res);

foreach ($row3  as $resul) {

    ?>

    <p>Уникальных посетителей: <?php echo $resul->result_hosts; ?> <br/>
        Просмотров: <?php echo $resul->result_value; ?></p>
    <span id="timer-counter" style='color:red;font-size:150%;font-weight:bold;'></span>


    <?php

}



