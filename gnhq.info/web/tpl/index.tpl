<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/statScreen.css" rel="stylesheet">
<title>Гражданин Наблюдатель - Аналитика</title>
</head>
<body>
	<div class="container" style="margin-top: 25px;">
	<h3>Результаты выборов в Государственную Думу РФ 4 декабря 2011 года</h3>
	<div class="partSeparator"> </div>
	<!-- SEARCH FORM -->
		<form id="filterForm" name="filterForm">
		<div class="span8">
			<div class="row">
				<div class="span2">
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
					</select>
				</div>
			</div>
			<div class="row" id="selectionRow">
				<div class="span2">
					<label class="form-horizontal"><input type="radio" selected="selected" id="<?php echo SELECTION_TYPE_DEFAULT?>"
						value="<?php echo SELECTION_TYPE_DEFAULT?>" name="selectionType"/> Все участки</label>
				</div>
				<div class="span3">
					<label class="form-horizontal"><input type="radio" id="<?php echo SELECTION_TYPE_PROTOCOL?>"
						value="<?php echo SELECTION_TYPE_PROTOCOL?>" name="selectionType"/> Только с протоколами</label>
				</div>
				<div class="span3">
					<label class="form-horizontal"><input type="radio" id="<?php echo SELECTION_TYPE_CLEAN?>" value="<?php echo SELECTION_TYPE_CLEAN?>"
						name="selectionType"/> Без серьезных нарушений</label>
				</div>
			</div>
		</div>

		<!-- STAT BLOCK -->
		<div class="span3">
			<div id="currentStats" style="display: none; text-align: left;">
				<div>Охвачено участков: <strong id="uikCount"></strong></div>
				<div>Получено протоколов: <strong id="protocolCount"></strong></div>
				<div id="hasDiscrepancy" style="display: none;">На <strong id="discrepancyCount"></strong > участк<span id="discrepancyOne">е</span><span id="discrepancyMany">ах</span> данные ЦИК расходятся с протоколами</div>
				<div id="noDiscrepancy" style="display: none;">На всех участках данные ЦИК совпадают с протоколами</div>
			</div>
			<div id="uikMenu" style="display:none; text-align: left;">
				<ul>
					<!-- <li><a href="">Сведения об участке</a> -->
					<li id="hasProtocol" style="display: none;">Протокол получен</li>
					<li id="noProtocol" style="display: none;">Протокол не получен</li>
					<!-- <li><a href="">Копия протокола</a></li>-->
					<li id="report" style="display: none;"><a href="" target="_blank">Отчет наблюдателя</a></li>
					<li id="noReport" style="display: none;">Отчет наблюдателя отсутствует</li>

				</ul>
			</div>
		</div>
		<!-- /STAT BLOCK -->
		</form>
		<!-- /SEARCH FORM -->




		<div class="partSeparator" style="border-bottom: 1px grey solid;"> </div>


		<!-- RESULTS -->
		<table class="table table-striped" id="results">
			<thead>
				<tr>
					<th width="20%"></th>
					<th width="40%" colspan="2">Выборка</th>
					<th width="40%" colspan="2">ЦИК</th>
				</tr>
			</thead>
			<tbody>
				<?php
				    foreach($view->diagRows as $line) {
				        include('protocolline.tpl');
				    }
				    unset($line);
				 ?>
			</tbody>
		</table>
		<!-- /RESULTS -->
	</div>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/statScreen.js"></script>
</body>
</html>
