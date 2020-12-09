<?php


add_action('wp_ajax_ajax_quick_view', 'my_ajax_form');
add_action('wp_ajax_nopriv_ajax_quick_view', 'my_ajax_form');
function my_ajax_form(){

    if (!wp_verify_nonce($_POST['nonce'], 'quick-nonce')){
        wp_die('Данные отправлены с левого адреса');
    }

    $meta_data_email2 = 'ajicas6@gmail.com';

    $message2 = 'dsafsafa';

    $subject2 = '433tsfd';


    add_filter( 'wp_mail_content_type', 'set_html_content_type2' );
    // Отправляем письмо
    $sent_message2 = wp_mail($meta_data_email2, $subject2, $message2 );


    if ( $sent_message2 ) {
        // Если сообщение успешно отправилось
        echo 'Отправлено';
    } else {
        // Ошибки при отправке
        echo 'Не отправлено делай еще';
    }

    echo $message2;

    wp_die();
}
