<?php
$PROJECT_CONFIG = array(
    PROJECT_GN => array(
            'Name'      => 'Гражданин наблюдатель',
			//'ProtoLink' => '',
            'ProtoLink' => 'http://gnhq.info/export/protocols.xml',
			'ProtoLink'  => 'https://gnhq.info/comm/protocols.xml',
            'ViolLink'  => '',
        ),
    PROJECT_GL => array(
            'Name'      => 'Гражданин наблюдатель чистые',
            // 'ProtoLink' => 'C:\git\gnhq.info\gnhq\gnhq.info\php\ParserIKData\Data\proto1.xml',
            'ProtoLink' => '',
            'ViolLink'  => '',
            //'ViolLink'  => 'C:\git\gnhq.info\gnhq\gnhq.info\php\ParserIKData\Data\viol1.xml',
    ),
	PROJECT_GOLOS => array(
            'Name'      => 'Голос',
			'ProtoLink' => '',
            'ProtoLink' => 'http://sms.golos.org/export.xml',
			'ViolLink'  => 'http://m.kartanarusheniy.org/messages/date/2012-06-10/56.0141053,92.8643491/56.0141053,92.8643491/geo',
            //'ViolLink'  => 'C:\git\gnhq.info\gnhq\gnhq.info\php\ParserIKData\Data\viol1.xml',
    ),
);