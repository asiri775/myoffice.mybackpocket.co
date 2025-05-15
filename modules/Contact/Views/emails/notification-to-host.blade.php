@php

$variableArray = array(
    'name'=>ucfirst($name ?? 'Guest'),
    'email'=>$email ?? 'N/A',
    'phone'=>$phone ?? 'N/A',
    'messageText'=>$messageText ?? '',
);

$templateHTML = $template['content'];

foreach ($variableArray as $key => $value) {
    $templateHTML = str_replace("{".$key."}", $value, $templateHTML);
}

@endphp

{!! $templateHTML !!}