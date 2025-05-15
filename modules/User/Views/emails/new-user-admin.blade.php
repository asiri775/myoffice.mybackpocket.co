@php 
$variableArray = array(
    'first_name' => ucfirst($user->first_name),
    'last_name' => ucfirst($user->last_name),
);

$templateHTML = $template['content'];

foreach ($variableArray as $key => $value) {
    $templateHTML = str_replace("{".$key."}", $value, $templateHTML);
}

@endphp

{!! $templateHTML !!}