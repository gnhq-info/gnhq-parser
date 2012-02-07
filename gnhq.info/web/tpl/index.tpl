<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="css/bootstrap.min.css" rel="stylesheet">
<title>Гражданин Наблюдатель - Аналитика</title>
</head>
<body>
	<div class="container" style="margin-top: 25px;">
		<form id="filterForm" name="filterForm">
			<div class="row">
				<div class="span3">
					<div>Город:</div>
					<select style="width: 130px"><option>Москва</option></select>

				</div>
				<div class="span3">
					<div>Округ:</div>
					<select id="okrug" name="okrug" style="width: 130px;">
						<option value="">--- не выбран ---</option>
						<?php foreach ($view->okrugs as $okrug) { ?>
						<option value="<?php echo $okrug->getAbbr();?>">
						<?php echo $okrug->getAbbr();?>
						</option>
						<?php } ?>
					</select>
				</div>
				<div class="span3">
					<div>УИК:</div>
					<select id="uik" name="uik" style="width: 130px;">
						<option value="">--- не выбран ---</option>
						<?php foreach ($view->watches as $watch) { ?>
						<option value="<?php echo $watch->getUniqueId();?>">
						<?php echo 'УИК ', $watch->getUniqueId();?>
						</option>
						<?php } ?>
					</select>
				</div>
				<div class="span3" id="currentStats" style="text-align: right!important; vertical-align: top!important; width: 200px;">
					<div><strong id="uikCount"><?php echo $view->totalUikCount;?></strong> участков</div>
					<div>На <strong id="varyIkCount">0</strong > участках данные ЦИК расходятся с протоколами</div>
				</div>
				<div class="span3" id="uikMenu" style="display:none;">
					<ul>
						<li><a href="">Сведения об участке</a>
						<li><a href="">Копия протокола</a>
						<li><a href="">Отчет наблюдателя</a>
					</ul>
				</div>
			</div>
		</form>
	</div>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
</body>
</html>
