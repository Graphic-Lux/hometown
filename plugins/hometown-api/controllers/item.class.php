<?
class item
{
	/**
	 * A place we will store the table associated with this item
	 */
	var $table;
	
	function save()
	{
		$objectType = "tf_" . str_replace(REST_DATABASE, "", $this->table);
		$object = new $objectType();
		$updateData = (array)$this;
		unset($updateData['table']);
		return $object->update($updateData);
	}
	
	function delete()
	{
		$objectType = "tf_" . str_replace(REST_DATABASE, "", $this->table);
		$object = new $objectType();
		$deleteData = (array)$this;
		unset($deleteData['table']);
		return $object->delete($deleteData);		
	}
}