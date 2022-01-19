<?php

class Modul
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'modul';
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
  		$getkelas = $this -> table -> findOne($criteria);

  		return $getkelas;
	}

    // public function GetAllUser($user)
    public function GetAll($skip, $limit, $criteria, $order)
    {
        $columns = array(
            0   => 'id_mapel' ,
            1   => 'nama',
            2   => 'date_created'
        );

        foreach ($order as $value) {
            $by         = $value['dir'] == 'asc' ? 1 : -1;
            $sorting[]  = array($columns[$value['column']] => $by);
        }

        $getSekolah = empty($criteria) ? 
                                $this->table->find()->skip($skip)->limit($limit)->sort($sorting) :
                                $this->table->find($criteria)->skip(intval($skip))->limit(intval($limit))->sort($sorting);
        $total      = $this->table->find()->count();
        $filter     = $getSekolah->count();

        if (empty($filter)) {
            $seluruh  = array();
        }else{
            foreach ($getSekolah as $value) {
                if (!empty($value['id_mapel'])) {
                    $idmapel = new MongoId($value['id_mapel']);
                    $mapel   = $this->db->mata_pelajaran->findOne(array('_id'=>$idmapel));
                    $dmapel  = $mapel['nama'];
                }else{
                    $dmapel  = '';
                }
                $data   = array();
                $data[] = $dmapel;
                $data[] = $value['nama'];
                $data[] = date_format(date_create($value['date_created']), "d-M-Y H:i:s");
                $data[] = "$value[_id]";

                $seluruh[] = $data;
            }
        }

        $feedback   = ['filter'=>$filter, 'total'=>$total, 'data'=>$seluruh];
        // $feedback   = ['filter'=>'', 'total'=>'', 'data'=>$getSekolah];
        
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
