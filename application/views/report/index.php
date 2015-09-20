<h2><?php echo $title; ?></h2>

<table>
	<tr>
		<td>ID</td>
		<td>First Name</td>
		<td>Last Name</td>
	</tr>
<?php foreach ($loadedReportItem as $reportItem): ?>
	<tr>
		<td><?php echo $reportItem['ID']; ?></td>
		<td><?php echo $reportItem['first_name']; ?></td>
		<td><?php echo $reportItem['last_name']; ?></td>
	</tr>

<?php endforeach; ?>

</table>
