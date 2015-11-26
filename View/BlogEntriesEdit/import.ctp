<?php
//echo $this->NetCommonsForm->create(false, ['type' => 'file']);
echo $this->NetCommonsForm->create(null, ['type' => 'file']);
echo $this->NetCommonsForm->input('import_csv', ['type' => 'file']);
echo $this->NetCommonsForm->input('import_photo', ['type' => 'file']);
?>
<input type="file" name="my_file" />
<?php
echo $this->NetCommonsForm->submit();
echo $this->NetCommonsForm->end();
