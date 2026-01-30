<?php
include_once __DIR__ . '/Core/app.php';
include_once __DIR__ . '/Core/controller.php';
include_once __DIR__ . '/Core/connectDB.php';
include_once __DIR__ . '/../Public/Classes/PHPExcel.php';
include_once __DIR__ . '/../Public/Classes/PHPExcel/IOFactory.php';
include_once __DIR__ . '/../Public/Classes/TimezoneHelper.php';
include_once __DIR__ . '/../Public/Classes/UrlHelper.php';

TimezoneHelper::setDefaultTimezone();
