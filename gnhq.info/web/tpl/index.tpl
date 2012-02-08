<html>
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/statScreen.css" rel="stylesheet">
<title>Гражданин Наблюдатель - Аналитика</title>
</head>
<body>
	<div class="container" style="margin-top: 25px;">
	<!-- SEARCH FORM -->
		<form id="filterForm" name="filterForm">
		<div class="span9">
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
					</select>
				</div>
			</div>
			<div class="row">
				<div class="span3">
					<label class="form-inline"><input type="radio" selected="selected" id="<?php echo SELECTION_TYPE_DEFAULT?>"
						value="<?php echo SELECTION_TYPE_DEFAULT?>" name="selectionType"/> Все участки</label>
				</div>
				<div class="span3">
					<label class="form-inline"><input type="radio" id="<?php echo SELECTION_TYPE_PROTOCOL?>"
						value="<?php echo SELECTION_TYPE_PROTOCOL?>" name="selectionType"/> Только с протоколами</label>
				</div>
				<div class="span3">
					<label class="form-inline"><input type="radio" id="<?php echo SELECTION_TYPE_CLEAN?>" value="<?php echo SELECTION_TYPE_CLEAN?>"
						name="selectionType"/> Без серьезных нарушений</label>
				</div>
			</div>
		</div>
		</form>
		<!-- /SEARCH FORM -->

		<!-- STAT BLOCK -->
		<div class="span3" id="currentStats" style="text-align: right!important; vertical-align: top!important; width: 200px;">
			<div><strong id="uikCount"><?php echo $view->totalUikCount;?></strong> участков</div>
			<div>На <strong id="discrepancyCount"><?php echo $view->discrepancyUikCount?></strong > участках данные ЦИК расходятся с протоколами</div>
		</div>
		<div class="span3" id="uikMenu" style="display:none;">
			<ul>
				<li><a href="">Сведения об участке</a>
				<li><a href="">Копия протокола</a>
				<li><a href="">Отчет наблюдателя</a>
			</ul>
		</div>
		<!-- /STAT BLOCK -->


		<div id="partSeparator"> </div>


		<!-- RESULTS -->
		<table class="table table-striped" id="results">
			<thead>
				<tr>
					<th></th>
					<th>Выборка</th>
					<th>ЦИК</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>СР</td>
					<td id="S_GN"></td>
					<td id="S_OF"></td>
				</tr>
				<tr>
					<td>ЛДПР</td>
					<td id="L_GN"></td>
					<td id="L_OF"></td>
				</tr>
				<tr>
					<td>ПР</td>
					<td id="PR_GN"></td>
					<td id="PR_OF"></td>
				</tr>
				<tr>
					<td>КПРФ</td>
					<td id="K_GN"></td>
					<td id="K_OF"></td>
				</tr>
				<tr>
					<td>Яблоко</td>
					<td id="Y_GN"></td>
					<td id="Y_OF"></td>
				</tr>
				<tr>
					<td>ЕР</td>
					<td id="E_GN"></td>
					<td id="E_OF"></td>
				</tr>
				<tr>
					<td>ПД</td>
					<td id="PD_GN"></td>
					<td id="PD_OF"></td>
				</tr>
				<tr>
					<td>Явка</td>
					<td id="AT_GN"></td>
					<td id="AT_OF"></td>
				</tr>
				<tr>
					<td>Недействительные</td>
					<td id="SP_GN"></td>
					<td id="SP_OF"></td>
				</tr>
			</tbody>
		</table>
		<!-- /RESULTS -->
	</div>

	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/statScreen.js"></script>
</body>
</html>
