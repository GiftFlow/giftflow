<?php

/**
*
*	ABOUT THIS EXTENSION
*
*	The purpose of this extension is to alter the order in which DataMapper
*	deletes objects. Originally, the object was deleted and then its relationships.
*	This caused problems with the foreign key constraints of our database, and
*	thus this extention reverses the process: the relationships are deleted first,
*	followed by the object itself.
*/

class DataMapperExtension extends DataMapper {
	function __construct($id = NULL) 
	{
		parent::__construct($id);
	}
    
    
	// --------------------------------------------------------------------

	/**
	 * Delete
	 *
	 * Deletes the current record.
	 * If object is supplied, deletes relations between this object and the supplied object(s).
	 *
	 * @param	mixed $object If specified, delete the relationship to the object or array of objects.
	 * @param	string $related_field Can be used to specify which relationship to delete.
	 * @return	bool Success or Failure of the delete.
	 */
	public function delete($object = '', $related_field = '')
	{
		if (empty($object) && ! is_array($object))
		{
			if ( ! empty($this->id))
			{
				// Begin auto transaction
				$this->_auto_trans_begin();

				// Delete all "has many" and "has one" relations for this object
				foreach (array('has_many', 'has_one') as $type) {
					foreach ($this->{$type} as $model => $properties)
					{
						// Prepare model
						$class = $properties['class'];
						$object = new $class();
						
						$this_model = $properties['join_self_as'];
						$other_model = $properties['join_other_as'];
	
						// Determine relationship table name
						$relationship_table = $this->_get_relationship_table($object, $model);
						
						// We have to just set NULL for in-table foreign keys that
						// are pointing at this object 
						if($relationship_table == $object->table  && // ITFK
								 // NOT ITFKs that point at the other object
								 ! ($object->table == $this->table && // self-referencing has_one join
								 	in_array($other_model . '_id', $this->fields)) // where the ITFK is for the other object
								)
						{
							$data = array($this_model . '_id' => NULL);
							
							// Update table to remove relationships
							$this->db->where($this_model . '_id', $this->id);
							$this->db->update($object->table, $data);
						}
						else if ($relationship_table != $this->table)
						{
	
							$data = array($this_model . '_id' => $this->id);
		
							// Delete relation
							$this->db->delete($relationship_table, $data);
						}
						// Else, no reason to delete the relationships on this table
					}
				}
				
				// Delete this object
				$this->db->where('id', $this->id);
				$this->db->delete($this->table);
				
				// Complete auto transaction
				$this->_auto_trans_complete('delete');

				// Clear this object
				$this->clear();

				return TRUE;
			}
		}
		else if (is_array($object))
		{
			// Begin auto transaction
			$this->_auto_trans_begin();

			// Temporarily store the success/failure
			$result = array();

			foreach ($object as $rel_field => $obj)
			{
				if (is_int($rel_field))
				{
					$rel_field = $related_field;
				}
				if (is_array($obj))
				{
					foreach ($obj as $r_f => $o)
					{
						if (is_int($r_f))
						{
							$r_f = $rel_field;
						}
						$result[] = $this->_delete_relation($o, $r_f);
					}
				}
				else
				{
					$result[] = $this->_delete_relation($obj, $rel_field);
				}
			}

			// Complete auto transaction
			$this->_auto_trans_complete('delete (relationship)');

			// If no failure was recorded, return TRUE
			if ( ! in_array(FALSE, $result))
			{
				return TRUE;
			}
		}
		else
		{
			// Begin auto transaction
			$this->_auto_trans_begin();

			// Temporarily store the success/failure
			$result = $this->_delete_relation($object, $related_field);

			// Complete auto transaction
			$this->_auto_trans_complete('delete (relationship)');

			return $result;
		}

		return FALSE;
	}
}
