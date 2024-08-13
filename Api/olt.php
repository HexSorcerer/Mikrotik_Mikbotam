<?php

function getInfoOnt() {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://vivi.gifofficial.my.id/api/vivi-vivinet-olt?req=getONT',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Cookie: XSRF-TOKEN=eyJpdiI6IlliQ1hhUUQyK3JydHI1amZWVWl6dUE9PSIsInZhbHVlIjoiOFRcL3hqWHFxNTdBY09xUmpiaCtFekNHb2VZcDJzWDNJUUpzOVpWV0FHeGVOZ0xVRWZSNllGWCtQNVlLZ0laTmlEV3JpYUFDTnFIeVpaVnFVOHRJeVVBPT0iLCJtYWMiOiIyNTVjZmE3MTE1YTBlMTlkNzc4MTFiMjEyNDdkOWE3NmMyYjAyMzUwMzBmZTMzMjI3NGNmMThlOTI4YmYzZTRhIn0%3D; laravel_session=eyJpdiI6IkpFWmFQQmZENHMwbGlRWEhMMWtQc2c9PSIsInZhbHVlIjoiUUdOQ3J3Tmw3UDZMNkhUWWpFTk96RVRzZ3Y2OHNuTlNuTDBxWVRuQWhpVWxFMWFMb1JDZU1WVmZzWDd5TGg3SlM0SldjS1N4Z25CaU9malM5T0plNFE9PSIsIm1hYyI6IjBmYmU5NjE2YTk3ZGZlZjZjN2NiMjg1NWJhNDc3NzRlNjFlY2EzMGM3MmU1MTAxNzIwOGU5MGE4ODJhNWM5N2EifQ%3D%3D'
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

?>