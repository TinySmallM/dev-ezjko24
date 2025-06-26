<?
$this->title = 'Заказы';
?>
<h1 class="title">Заказы</h1>

<div class="card">
	<div class="card-body">
		
		<table class="table table-stripped table-hover">
			<thead>
				<tr>
					<th>ID</th>
					<th>Покупатель</th>
					<th>Телефон</th>
					<th>Сумма</th>
					<th>Статус</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?foreach($items as $d){?>
				<tr>
					<td><a href="/master/order/<?=$d['id']?>"><?=$d['id']?></a></td>
					<td><?=$d['name']?></td>
					<td><?=$d['phone']?></td>
					<td><?=$d['amount']?>₽</td>
					<td><?=Yii::$app->params['orderStatus'][$d['status']]['badge']?></td>
				</tr>
				<?}?>
			</tbody>
		</table>		
		
	</div>
</div>


