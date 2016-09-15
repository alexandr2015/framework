hello
<table border="1">
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $value): ?>
        <tr>
            <td>
                <?=$value['id'];?>
            </td>
            <td>
                <?=$value['name'];?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>