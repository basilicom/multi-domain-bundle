<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/bundles/pimcoreadmin/css/object_versions.css"/>
</head>

<body>


<?php

use Pimcore\Model\DataObject;

$fields = $this->object->getClass()->getFieldDefinitions();

?>

<table class="preview" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <th>Name</th>
        <th>Key</th>
        <th>Value</th>
    </tr>
    <tr class="system">
        <td>Date</td>
        <td>o_modificationDate</td>
        <td><?= date('Y-m-d H:i:s', $this->object->getModificationDate()); ?></td>
    </tr>
    <tr class="system">
        <td>Path</td>
        <td>o_path</td>
        <td><?= $this->object->getRealFullPath(); ?></td>
    </tr>
    <tr class="system">
        <td>Published</td>
        <td>o_published</td>
        <td><?= json_encode($this->object->getPublished()); ?></td>
    </tr>

    <tr class="">
        <td colspan="3">&nbsp;</td>
    </tr>

    <?php $c = 0; ?>
    <?php foreach ($fields as $fieldName => $definition) {
        $method = 'get'.ucfirst($fieldName);
        ?>
        <tr<?php if ($c % 2) { ?> class="odd"<?php } ?>>
            <td><?= $definition->getTitle() ?></td>
            <td><?= $definition->getName() ?></td>
            <td><?= $this->object->$method() ?></td>
        </tr>
        <?php $c++;
    } ?>
</table>


</body>
</html>
