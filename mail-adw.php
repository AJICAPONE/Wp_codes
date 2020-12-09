<?php

function cron_ajica_action_hook_dd165524() {

    global $post, $wpdb;

//
//(CURDATE()-2) AND period < CURDATE()

//DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) за прошедшие 28 дней

//LAST_DAY (NOW() - INTERVAL 1 MONTH) Последний день прошлого месяца

// Вывод количества кликов по соцсетям


    $slush = ("SELECT slush_post_id, SUM(sc.slush_summa) AS summa_group 
					FROM wp_slush_clicks sc 
					WHERE sc.click_date_time > (NOW()-INTERVAL 28 DAY)
					AND sc.message_type = 0 	
					GROUP BY sc.slush_post_id");


    $slush_show = $wpdb->get_results($slush);



    $slush_mess = ("SELECT slush_post_id, SUM(sc.slush_summa) AS summa_group_mess 
					FROM wp_slush_clicks sc 
					WHERE sc.message_type = 1
					AND sc.click_date_time > (NOW()-INTERVAL 28 DAY)
					GROUP BY sc.slush_post_id");


    $slush_mess_res = $wpdb->get_results($slush_mess);


    $slush_mess_form= ("SELECT slush_post_id, SUM(sc.slush_summa) AS summa_group_mess 
					FROM wp_slush_clicks sc 
					WHERE sc.message_type = 2
					AND sc.click_date_time > (NOW()-INTERVAL 28 DAY)
					GROUP BY sc.slush_post_id");


    $slush_mess_res_form = $wpdb->get_results($slush_mess_form);






//Предыдущий месяц

//Соцсети
    $slush_old = ("SELECT slush_post_id, SUM(sc.slush_summa) AS summa_group 
					FROM wp_slush_clicks sc 
					WHERE sc.click_date_time > (NOW()-INTERVAL 56 DAY) 
					AND sc.click_date_time < (NOW()-INTERVAL 28 DAY)
					AND sc.message_type = 0 
					GROUP BY sc.slush_post_id");


    $slush_show_old = $wpdb->get_results($slush_old);



//Мессенджеры
    $slush_mess_old = ("SELECT slush_post_id, SUM(sc.slush_summa) AS summa_group_mess 
					FROM wp_slush_clicks sc 
					WHERE sc.message_type = 1 
					AND	sc.click_date_time > (NOW()-INTERVAL 56 DAY) 
					AND sc.click_date_time < (NOW()-INTERVAL 28 DAY)
					
					GROUP BY sc.slush_post_id");


    $slush_mess_res_old = $wpdb->get_results($slush_mess_old);


//Форма
    $slush_mess_form_old = ("SELECT slush_post_id, SUM(sc.slush_summa) AS summa_group_mess 
					FROM wp_slush_clicks sc 
					WHERE sc.message_type = 2
					AND	sc.click_date_time > (NOW()-INTERVAL 56 DAY) 
					AND sc.click_date_time < (NOW()-INTERVAL 28 DAY) 
					GROUP BY sc.slush_post_id");


    $slush_mess_res_form_old = $wpdb->get_results($slush_mess_form_old);









//print_r ($slush_show);

//print_r ($slush_mess_res);

//exit;

// Вывод общего количества просмотров за все время
    $new_visit_kolya2 = ("SELECT id,SUM(count) As all_count FROM wp_post_views WHERE type=4 GROUP BY id");
    $new_row_arr2 =  $wpdb->get_results($new_visit_kolya2);

// Вывод количества просмотров за текущий месяц
    $new_visit_kolya = (

    "SELECT id, SUM(count) As summa 
						FROM wp_post_views 
						WHERE type=0 AND  
						period >= DATE_SUB(CURRENT_DATE, INTERVAL 28 DAY) 
						GROUP BY id"


    );
    $new_row_arr =  $wpdb->get_results($new_visit_kolya);
// Вывод количества просмотров за прошлый месяц
    $insert_summ = ("SELECT id, SUM(count) As summas FROM wp_post_views WHERE type=0 AND  period < ( CURDATE() - 28 )  GROUP BY id");
    $insert_get = $wpdb->get_results($insert_summ);

// Вывод общего количества посетителей
    $hosts = ("SELECT business_id, SUM(hosts_bd) As hosts_summa FROM visits WHERE date_bd >=DATE_SUB(CURRENT_DATE, INTERVAL 28 DAY) GROUP BY business_id");
    $hosts_get = $wpdb->get_results($hosts);

// Вывод общего подсчета всего
    $stats = ("select * from createcompany") ;
    $row = $wpdb->get_results($stats);

    $pic_out = ("SELECT * FROM wp_postmeta WHERE meta_key='listing_logo' GROUP BY post_id");
    $pic_show = $wpdb->get_results($pic_out);



// Тема письма
    $subject = 'סטטיסטיקה';




    foreach ($row as $results){




        $id_company = $results->id_company;

        //$thumb  = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full');
        //$thumb  = wp_get_attachment_image_url( $post->ID, 'small',true );
        //$thumb = wp_get_attachment_image( $post->ID, 'small', true);
        //$thumb = get_post_meta( $post->ID, 'listing_logo', true );



//        $data = get_post_meta( $post->ID, 'listing_banner_image', true );




        //Комментарий
        $qry_comment = ("SELECT COUNT(*) AS count_comment 
						FROM wp_comments 
						WHERE comment_post_ID = '$id_company'
						AND comment_date > (NOW()-INTERVAL 28 DAY)");

        $qry_comment_old = ("SELECT COUNT(*) AS count_comment 
						FROM wp_comments 
						WHERE comment_post_ID = '$id_company'
						AND comment_date > (NOW()-INTERVAL 56 DAY)
						AND comment_date < (NOW()-INTERVAL 28 DAY)");

        $q1 = $wpdb->get_results($qry_comment)[0]->count_comment;
        $q2 = $wpdb->get_results($qry_comment_old)[0]->count_comment;




        if ($q1 > $q2){
            $pr_q = round((($q1 - $summa_slush_old) / $q1) * 100);
            $summa_q = ('<td class="in-flex" style="color: #04d81c;">(+'.$pr_q.'%)</td>');
        } else {
            if ($q2 == 0 or $q1 == $q2) {
                $summa_q = ('<td class="in-flex" style="color: #949494">(0%)</td>');
            } else {
                $pr_q = round((( $q2 - $q1) / $q2) * 100);
                $summa_q = ('<td class="in-flex" style="color: #d80001;">(-'.$pr_q.'%)</td>');
            }
        }







        $summa_slush_mess_old = 0;
        $summa_slush_mess = 0;

        $count_views= 0;
        //$summa_slush_mess_form = '';

        //$summa_slush_mess_form_old = '';

        // Картинки
        foreach($new_row_arr2 as $item) {

            if ($item->id == $id_company) {

                $count_views = $item->all_count;
                break;



            }


        }


        // Всего просмотров за все время
        foreach($pic_show as $out_pic) {

            if ($out_pic->post_id == $id_company) {

                $pic_url = $out_pic->meta_value;
                break;



            }


        }

        $count_viewers_hosts = 0;

        //Количество посетителей
        foreach($hosts_get as $host_view) {

            if ($host_view->business_id == $id_company) {

                $count_viewers_hosts = $host_view->hosts_summa;
                break;

            }


        }



        $summas = 0;
        //Сумма просмотров за ПРОШЛЫЙ месяц
        foreach($insert_get as $item2) {

            if ($item2->id == $id_company) {

                $summas = $item2->summas;


            }


        }


        $summa = 0;

        //Сумма просмотров за ТЕКУЩИЙ месяц
        foreach($new_row_arr as $item) {

            if ($item->id == $id_company) {

                $summa = $item->summa;


            }


        }

        if ($summa > $summas){
            $pr_pr1 = round((($summa - $summas) / $summa) * 100);
            $summa_all_views = ('<td class="in-flex" style="color: #04d81c;">(+'.$pr_pr1.'%)</td>');
        } else {
            if ($summas  == 0 or $summas == $summa) {
                $summa_all_views = ('<td class="in-flex" style="color: #949494">(0%)</td>');
            } else {
                $pr_pr1 = round((( - $summa + $summas) / $summas) * 100);
                $summa_all_views = ('<td class="in-flex" style="color: #d80001;">(-'.$pr_pr1.'%)</td>');
            }
        }



        $summa_slush_old = 0;

        //Количество кликов по соц сетям ПРОШЛЫЙ месяц
        foreach($slush_show_old as $slush_result) {

            if ($slush_result->slush_post_id == $id_company) {

                $summa_slush_old = $slush_result->summa_group;
                break;
            }

        }


        $summa_slush = 0;
        //Количество кликов по соц сетям ТЕКУЩИЙ месяц
        foreach($slush_show as $slush_result) {

            if ($slush_result->slush_post_id == $id_company) {

                $summa_slush = $slush_result->summa_group;


                break;

            }

        }


        if ($summa_slush > $summa_slush_old){
            $pr_show = round((($summa_slush - $summa_slush_old) / $summa_slush) * 100);
            $summa_slush_plus = ('<td class="in-flex" style="color: #04d81c;">(+'.$pr_show.'%)</td>');
        } else {
            if ($summa_slush_old  == 0 or $summa_slush_old == $summa_slush) {
                $summa_slush_plus = ('<td class="in-flex" style="color: #949494">(0%)</td>');
            } else {
                $pr_show = round((( - $summa_slush + $summa_slush_old) / $summa_slush_old) * 100);
                $summa_slush_plus = ('<td class="in-flex" style="color: #d80001;">(-'.$pr_show.'%)</td>');
            }
        }





        // Звонки за ПРОШЛЫЙ месяц
        foreach($slush_mess_res_old as $slush_result) {

            if ($slush_result->slush_post_id == $id_company) {

                $summa_slush_mess_old = $slush_result->summa_group_mess;
                break;

            }


        }

        // Звонки за ТЕКУЩИЙ месяц
        foreach($slush_mess_res as $slush_result) {

            if ($slush_result->slush_post_id == $id_company) {

                $summa_slush_mess = $slush_result->summa_group_mess;

            }


        }


        if ($summa_slush_mess > $summa_slush_mess_old){
            $pr_mess = round((($summa_slush_mess - $summa_slush_mess_old) / $summa_slush_mess) * 100);
            $summa_slush_plus_mess = ('<td class="in-flex" style="color: #04d81c;">(+'.$pr_mess.'%)</td>');
        } else {

            if ($summa_slush_mess_old  == 0 or $summa_slush_mess_old == $summa_slush_mess) {
                $summa_slush_plus_mess = ('<td class="in-flex" style="color: #949494;">(0%)</td>');
            } else {
                $pr_mess = round((( - $summa_slush_mess + $summa_slush_mess_old) / $summa_slush_mess_old) * 100);
                $summa_slush_plus_mess = ('<td class="in-flex" style="color: #d80001;">(-'.$pr_mess.'%)</td>');
            }


        }


        $summa_slush_mess_form_old = 0;
        //Обратная свзязь ПРОШЛЫЙ месяц
        foreach($slush_mess_res_form_old as $slush_result) {

            if ($slush_result->slush_post_id == $id_company) {

                $summa_slush_mess_form_old = $slush_result->summa_group_mess;

                break;
            }



        }


        $summa_slush_mess_form = 0;
        //Обратная свзязь ТЕКУЩИЙ месяц
        foreach($slush_mess_res_form as $slush_result) {

            if ($slush_result->slush_post_id == $id_company) {

                $summa_slush_mess_form = $slush_result->summa_group_mess;


                break;

            }


        }






        if ($summa_slush_mess_form > $summa_slush_mess_form_old){
            $pr_mess_form = round((($summa_slush_mess_form - $summa_slush_mess_form_old) / $summa_slush_mess_form) * 100);
            $summa_slush_plus_mess_form = ('<td class="in-flex" style="color: #04d81c;">(+'.$pr_mess_form.'%)</td>');
        } else {

            if ($summa_slush_mess_form_old  == 0 or $summa_slush_mess_form_old == $summa_slush_mess_form) {
                $summa_slush_plus_mess_form = ('<td class="in-flex" style="color: #949494;">(0%)</td>');

            } else {
                $pr_mess_form = round((( - $summa_slush_mess_form + $summa_slush_mess_form_old) / $summa_slush_mess_form_old) * 100);
                $summa_slush_plus_mess_form = ('<td class="in-flex" style="color: #d80001;">(-'.$pr_mess_form.'%)</td>');
            }

        }


        $meta_data_email = get_post_meta($results->id_company)['listing_email'][0];
//        $meta_data_email = 'ajicas6@gmail.com';
        //header("Content-Type: image/png");
        $message = '.
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
						"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
					<title>Отчет:Статистика за прошедший месяц</title>
					<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
					<style>
					@import url("https://fonts.googleapis.com/css?family=Fira+Sans:500|Roboto+Mono:500&display=swap");

					.background-mail {
						background: #3477CD;
						width: 100%;
						height: 100vh;
						height: 1000px;
						display: flex;
						justify-content: center;
						align-items: center;
						margin: 0 auto;
					}

					.wrap-block-adw {
						position: relative;
						display: block;
						border-radius: 20px;
						box-shadow: 0px 0px 15px 3px #0000008c;
						margin: auto auto;
						height: max-content;
						padding: 15% 0;
					}

					.main-block-adw {
						width: 600px;
						border: 1px solid #c0c0c061;
						margin: auto;
						border-radius: 10px;
						padding: 20px;
						background-color: #fff;
					}

					.main-stats-adw {
						display: flex;
						justify-content: space-around;
						text-align: center;
						border-bottom: 1px solid #c0c0c061;
					}

					.g-title {
						color: #c8c8c8;
						font-family: "Fira Sans", sans-serif;
						font-weight: 500;
						margin: 0;
						text-align: center;
					}

					.g-count {
						font-family: "Roboto Mono", monospace;
						font-weight: 500;
						font-size: 45px;
						margin: 10px 0 15px;
						text-align: center;
						width: 33%;
					}

					.table-main {
						width: 100%;
						margin-top: 5px;
						font-family: "Fira Sans", sans-serif;
					}

					.name-table {
						width: 50%;
					}

					.table-main td {
						padding: 15px 0;
						color: #3f3f3f;
						font-size: 18px;
					}

					.text-centerz{
						text-align: center;
					}


					.table-main tr {
						border-bottom: 1px solid #c0c0c061;
					}

					tr.border_bottom td {
						border-bottom: 1px solid #c0c0c061;
					}
					.table-td{
						width: 100%;
					}
					.table-td .indis,.table-td .in-flex{
						border: 0;
					}
					.indis{
						width: 55%;
						text-align: right;
						padding: 0 !important;
					}
					.in-flex{
						padding: 0 !important;
					}
				
					.pic_logo{
					    background-size: contain;
					    background-repeat: no-repeat;
                        width: 256px;
                        height: 55px;
                        display: block;
                        margin: 0px auto 30px;
					}
					.firm_logo{
					    width: 130px;
					    height: 120px;
					    padding: 7px;
					}
					.logo-and-firm{
					    background-color: #fff;
                        border-radius: 10px;
                        margin: 0px auto 10px;
                        width: 640px;
					}
					.title_classz{
					    padding: 20px 10px;
                        display: block;
                        height: 100px;
                        font-size: 24px;
                        text-align: center;
                        font-family: "Roboto Mono",monospace;
					}

				</style>
				</head>
				<body style="margin: 0; padding: 0;">
				<div class="wrapper">
					<div class="background-mail">
					    
						<div class="wrap-block-adw">
						    <table width="100%">
						        <tr class="pic_logo_wrap">
						            <td class="pic_logo" style="background-image: url(https://cdn1.savepice.ru/uploads/2019/9/2/faaf6619753d3eadb43a847180efa88d-full.png)"></td>
                                </tr>
                            </table>
						    <table width="100%" class="logo-and-firm">
						        <tr>
						            <td><img class="firm_logo" src="'.$pic_url.'" alt=""></td><td class="title_classz">'.$results->title_company.'</td>
                                </tr>
                            </table>
							<div class="main-block-adw">
								<table width="100%">
							
									<tr>
										<td class="g-title">צפיות</td>
										<td class="g-title">מבקרים</td>
									</tr>
									<tr>
										<td class="g-count"> '.$count_views.'</td>
										<td class="g-count">'.$count_viewers_hosts.'</td>
									</tr>
								</table>
								<div class="more-stats-adw">
									<table class="table-main">
										<tr>
											<th></th>
											<th></th>
											<th>חודש שעבר</th>
											<th>חודש נוכחי</th>
										</tr>
										<tr class="border_bottom">
											<td class="name-table">צפיות באתר</td>
											<td></td>
											<td class="text-centerz">'. $summas .'</td>
											<td class="text-centerz">
												<table class="table-td">
													<tr>
														<td class="indis">'.$summa.$summa_all_views.'</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr class="border_bottom">
											<td>מספר שיחות</td>
											<td></td>
											<td class="text-centerz">'.$summa_slush_mess_old.'</td>
											<td class="text-centerz">
											    <table class="table-td">
													<tr>
											            <td class="indis">'.$summa_slush_mess.$summa_slush_plus_mess.'</td>
											        </tr>
											    </table>
											</td>
										</tr>
										<tr class="border_bottom">
											<td class="name-table">מספר הקלקות</td>
											<td></td>
											<td class="text-centerz">'.$summa_slush_old.'</td>
											<td class="text-centerz">
												<table class="table-td">
													<tr>
														<td class="indis">'.$summa_slush.$summa_slush_plus.'</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr class="border_bottom">
											<td class="name-table">יצירת קשר</td>
											<td></td>
											<td class="text-centerz">
											    '.$summa_slush_mess_form_old.'
											</td>
											<td class="text-centerz">
											    <table class="table-td">
											        <tr>
											            <td class="indis">'.$summa_slush_mess_form.$summa_slush_plus_mess_form.'</td>
											        </tr>
											    </table>
											</td>
										</tr>
										<tr>
											<td class="name-table">תגובות</td>
											<td></td>
											<td class="text-centerz">'.$q2.'</td>
											<td class="text-centerz">
											    <table class="table-td">
											        <tr>
											            <td class="indis">'. $q1 .$summa_q.'</td>
											        </tr>
											    </table>   
											</td> 
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				</body>
				</html>
			';




        add_filter( 'wp_mail_content_type', 'set_html_content_type' );

        //		wp_mail( $multiple_to_recipients, 'The subject', "$row" );

        // Сбросим content-type, чтобы избежать возможного конфликта
        remove_filter( 'wp_mail_content_type', 'set_html_content_type' );

        // Отправляем письмо
        $sent_message = wp_mail($meta_data_email, $subject, $message );



        if ( $sent_message ) {
            // Если сообщение успешно отправилось
            echo 'Отправлено';
        } else {
            // Ошибки при отправке
            echo 'Не отправлено делай еще';
        }



        echo $message;

        //foreach ($row2 as $results2){


    }
    return 'text/html';

}
// вот он хук и мы вешаем на него произвольную функцию
// можно повесить и несколько функций на один хук!

add_action( 'ajica_action_hook', 'cron_ajica_action_hook_dd165524', 10, 0 );
 //просто банально поменяю емайл администратора на сайте, на мой взгляд проще всего протестировать




