<?php
$details = $details ?? '';
$address = $address ?? '';
$event = $event ?? '';
$class = $class ?? '';
$from = $from ?? date('Y-m-d H:i:s');
$to = $to ?? date('Y-m-d H:i:s');

$from = date('Ymd', strtotime($from)) . 'T' . date('His', strtotime($from));
$to = date('Ymd', strtotime($to)) . 'T' . date('His', strtotime($to));

$time = $from . '/' . $to;

$link = 'http://www.google.com/calendar/event?action=TEMPLATE&text=' . urlencode($event) . '&dates=' . urlencode($time) . '&details=' . $details . '&location=' . $address . '&sprop=website:';

$template = $template ?? '';

?>

<?php
if($template!=null){
    $template = str_replace("--calendarLink--", $link, $template);
    echo $template;
}else{
   ?>
<a style="text-decoration: none" class="{{$class}}" href="{{ $link }}" target="_blank"><span class="material-icons">event</span>
    <h4 class="mt-2">Add to Calendar</h4>
</a>
<?php
}
?>
