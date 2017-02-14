<?php
// protected/components/helpers.php

function bulanToRomawi($bulan) {
    $arr = array('','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII');

    return $arr[$bulan];
}

function convertTanggal($date) {
    $waktu = '';
    $time_now = date('d-m-Y', strtotime('now'));
    $time_date = date('d-m-Y', strtotime($date));
    if($time_now == $time_date) {
        $waktu = date('H:i:s', strtotime($date));
    } else {
        $waktu = date('d-m-Y H:i:s', strtotime($date));
    }

    return $waktu;
}

function getStatus($id)
{
    return '<span class="label label-primary label-mini">'.TluStatus::model()->findByPk($id)->nama.'</span>';
}
