@php 
$variableArray = array(
    'first_name' => ucfirst($first_name),
    'dashboard_url' => $link,
);

$templateHTML = $template['content'];

foreach ($variableArray as $key => $value) {
    $templateHTML = str_replace("{".$key."}", $value, $templateHTML);
}

@endphp

{!! $templateHTML !!}