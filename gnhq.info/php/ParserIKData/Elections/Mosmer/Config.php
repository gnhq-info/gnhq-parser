<?php
		 if (!isset($PROJECT_CONFIG)) {
		$PROJECT_CONFIG =
array (
  'GN' =>
  array (
    'Name' => 'Гражданин наблюдатель',
    'ProtoLink' => '',
    'ViolLink' => 'C:\temp\viol.json', // @testing url
    //'ViolLink' => 'http://rosvybory.org/api/violations.json'
  ),
  'AG' =>
  array (
    'Name' => 'Голос',
    'ProtoLink' => 'http://sms-cik.org/elections/151/export_json.json',
    //'ProtoLink' => 'http://sms-cik.org/elections/111/export_json.json', //@testing url
    'ViolLink' => '',
  ),
);}