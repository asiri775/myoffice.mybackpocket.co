@php 
$variableArray = array(
    'first_name'    => $user->first_name,
    'last_name'     => $user->last_name,
    'name'          => $user->name,
    'email'         => $user->email,
    'url' => $url
);
$templateHTML = $template['content'];

foreach ($variableArray as $key => $value) {
    $templateHTML = str_replace("{".$key."}", $value, $templateHTML);
    $templateHTML = str_replace("[".$key."]", $value, $templateHTML);
}

$templateHTML = str_replace("[button_verify]", '<a href="'.$variableArray['url'].'">ACTIVATE MY ACCOUNT</a>', $templateHTML);

@endphp

{!! $templateHTML !!}