<?php

use Carbon\Carbon;

function formatTanggalIndonesia($tanggal)
{
    return Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d F Y');
}

function formatWaktuIndonesia($tanggal)
{
    return Carbon::parse($tanggal)->locale('id')->translatedFormat('l, d M Y - H:i:s');
}