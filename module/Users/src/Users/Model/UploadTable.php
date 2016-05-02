<?php
namespace Users\Model;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class UploadTable {
	protected $tableGateway;
	protected $uploadSharingTableGateway;
	public function __construct(TableGateway $tableGateway, TableGateway $uploadSharingTableGateway)
	{
		$this->tableGateway = $tableGateway;
		$this->uploadSharingTableGateway =$uploadSharingTableGateway;
	}
	public function saveUpload(Upload $upload) {
		
		$data = array (
				'filename' => $upload->filename,
				//'label' => $upload->label,
				//'password' => $upload->password 
		);
		$id = ( int ) $upload->id;
		
		if ($id == 0) {
			$this->tableGateway->insert ( $data );
		} else {
			if ($this->getUpload( $id )) {
				$this->tableGateway->update( $data, array ('id' => $id) );
			} else {
				throw new \Exception ( 'User ID does not exist' );
			}
		}
	}
	
	public function getUploadsByUserId($userId)
	{
		$userId = (int) $userId;
		$rowset = $this->tableGateway->select(
				array('user_id' => $userId));
		return $rowset;
	}
	public function getUpload($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $id");
		}
		return $row;
	}
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	public function getUserByEmail($userEmail)
	{
		$rowset = $this->tableGateway->select(array('email' =>$userEmail));
		$row = $rowset->current();
		if (!$row) {
			throw new \Exception("Could not find row $ userEmail");
		}
		return $row;
	}
	public function deleteUser($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}