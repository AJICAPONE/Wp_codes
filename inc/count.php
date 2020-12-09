<?php

global $user_ID, $post, $wpdb;


// Получаем IP-адрес посетителя и сохраняем текущую дату
$visitor_ip = $_SERVER['REMOTE_ADDR'];
$refer_link = $_SERVER["HTTP_REFERER"];
$date = date("Y-m-d");
$user_time = $_POST['intMin'];
$pageId = $post->ID;
$sentToID = $_SESSION['userID'];
//print_r($countz);


// Узнаем, были ли посещения за сегодня
$sqli = ("SELECT visit_id FROM visits WHERE date_bd='$date'") or die ("Проблема при подключении к БД");
$res = $wpdb->get_results($sqli);


    // Если сегодня еще не было посещений
    if ($wpdb->get_var($sqli) == false)
    {
        
        // Заносим в базу дату посещения, ip посетителя и устанавливаем кол-во просмотров и уник. посещений в значение 1
        $insert_sql = "INSERT INTO visits
            SET date_bd='$date',
            business_id = '$pageId',
            
            time_bd=NOW(),
            user_online='$user_time',
            hosts_bd=1,
            views_bd=1,
            ip_address_vis='$visitor_ip',
            refer_url='$refer_link'
            ";

        $res_count = $wpdb->query($insert_sql);
    }

    // Если посещения сегодня уже были
    else
    {
        // Проверяем, есть ли уже в базе IP-адрес, с которого происходит обращение
        $current_ip = ("SELECT visit_id FROM visits WHERE date_bd='$date' AND ip_address_vis='$visitor_ip'");
        $have_ip = $wpdb->get_results($current_ip);

        // Если такой IP-адрес уже сегодня был (т.е. это не уникальный посетитель)
        if ($wpdb->get_var($current_ip) == true)
        {
        // Добавляем для текущей даты и ip адреса +1 просмотр, (хит)
        $wpdb->query( "UPDATE visits SET views_bd=views_bd+1 WHERE date_bd='$date' and ip_address_vis='$visitor_ip'");


    }

// Если сегодня такого IP-адреса еще не было (т.е. это уникальный посетитель)
    else
    {

        // Добавляем в базу +1 уникального посетителя (хост),  +1 просмотр (хит), ip посетителя
        $wpdb->query( "INSERT INTO visits
            SET hosts_bd=hosts_bd+1,
            business_id = '$pageId',
            views_bd=views_bd+1,
            
            date_bd='$date',
            time_bd=NOW(),
            user_online='$user_time',
            ip_address_vis='$visitor_ip',
            refer_url='$refer_link'"

        );

        // Добавляем в базу +1 уникального посетителя (хост) и +1 просмотр (хит)
        $wpdb->query( "UPDATE visits SET views_bd=views_bd+1 date_bd='$date',time_bd=NOW(),user_online='$user_time',ip_address_vis='$visitor_ip',refer_url='$refer_link' WHERE date_bd='$date' AND ip_address_vis='$visitor_ip'");
    }

}

$wpdb->query("INSERT INTO wp_posts SET views_count=1 WHERE ID='$pageId'");

$wpdb->query("UPDATE wp_posts SET views_count=views_count+1 WHERE ID='$pageId'");

$gres = ("SELECT views_count FROM wp_posts WHERE ID='$pageId'");

$resu = $wpdb->get_results($gres);

$wpdb->query("DELETE FROM createcompany");

$wpdb->query ("INSERT INTO createcompany (id_company,title_company,date_company,views_company,people_count,comments_count) SELECT ID,post_title,post_date,views_count,all_people,comment_count FROM wp_posts WHERE post_type='business'");

$wpdb->query( "UPDATE createcompany SET id_company,title_company,date_company,views_company,people_count");

