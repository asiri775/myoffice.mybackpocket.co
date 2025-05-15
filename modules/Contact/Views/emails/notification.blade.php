@php 
$variableArray = array(
    'name' => $contact->name,
    'email' => $contact->email,
    'message' => $contact->message,
);

$templateHTML = $template['content'];

foreach ($variableArray as $key => $value) {
    $templateHTML = str_replace("{".$key."}", $value, $templateHTML);
}

@endphp

{!! $templateHTML !!}
