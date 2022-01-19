<?php

class Mapel
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'mata_pelajaran';
            $this->db = $db;
            $this->dbLog   = $dbLog;
            $this->table = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

	public function GetData($user)
    {
        $criteria   = array('_id' => new MongoId(''.$user.''));
  		$getMapel = $this -> table -> findOne($criteria);

  		return $getMapel;
	}

    public function GetAll($skip, $limit, $criteria, $order)
    {
        $columns = array(
            0   => 'id_kelas' ,
            1   => 'nama',
            2   => 'date_created'
        );

        $getMapel = empty($criteria) ? 
                                $this->table->find()->skip(intval($skip))->limit(intval($limit)) :
                                $this->table->find($criteria)->skip(intval($skip))->limit(intval($limit));

         if ( isset($order)) {

            foreach ($order as $value) {
                $by         = $value['dir'] == 'asc' ? 1 : -1;
                $sorting[]    = array($columns[$value['column']] => $by);
            }

            $getMapel->sort($sorting);
        }

        $total      = $this->table->find()->count();
        $filter     = $getMapel->count();

        if (empty($filter)) {
            $seluruh  = array();
        }else{
            foreach ($getMapel as $value) {
                if (!empty($value['id_kelas'])) {
                    $id     = new MongoId($value['id_kelas']);
                    $query  = $this->db->kelas->findOne(array('_id'=>$id));
                    $hasil  = $query['nama'];

                    if ($hasil) {
                        $data   = array();
                        $data[] =  $hasil;
                        $data[] =  $value['nama'];
                        $data[] =  date_format(date_create($value['date_created']), "d-M-Y H:i:s");
                        $data[] = "$value[_id]";

                        $seluruh[] = $data;
                    }
                }else{
                    $hasil  = '';
                }
            }
        }

        $feedback   = ['filter'=>$filter, 'total'=>$total, 'data'=>$seluruh];
        // $feedback   = ['filter'=>'', 'total'=>'', 'data'=>$getMapel];
        
        return $feedback;
    }

    public function RemoveData($iddata)
    {
        $filter     = array('_id' => new MongoId(''.$iddata.''));
        $remove     = $this->table->remove($filter);

        return $remove;
    }


}


?>
