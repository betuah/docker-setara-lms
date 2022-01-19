<?php

class Sekolah
{
    public function __construct() {
        try {
            global $db;
            global $dbLog;
            $tableName = 'sekolah_induk';
            $this->db = $db;
            $this->dbLog   = $dbLog;
            $this->table = $this->db->$tableName;
        } catch(Exception $e) {
            echo "Database Not Connection";
            exit();
        }
    }

	public function GetData($sekolah)
    {
        $criteria   = array('_id' => new MongoId(''.$sekolah.''));
  		$getkelas = $this -> table -> findOne($criteria);

  		return $getkelas;
	}

    // public function GetAllUser($user)
    public function GetAll($skip, $limit, $criteria, $order)
    {
        $columns = array(
            0   => 'npsn' ,
            1   => 'nama_sekolah_induk',
            2   => 'program',
            3   => 'kab_kota'
        );

        foreach ($order as $value) {
            $by         = $value['dir'] == 'asc' ? 1 : -1;
            $sorting[]  = array($columns[$value['column']] => $by);
        }

        $getSekolah = empty($criteria) ?
                                $this->table->find()->skip($skip)->limit($limit)->sort($sorting) :
                                $this->table->find($criteria)->skip(intval($skip))->limit(intval($limit))->sort($sorting);
        // $getSekolah = $this->table->find()->sort($sorting);
        $total      = $this->table->find(array('program'=>'Pendidikan Kesetaraan'))->count();
        $filter     = $getSekolah->count();

        if (empty($filter)) {
            $seluruh  = array();
        }else{
            // $a = 0;
            foreach ($getSekolah as $value) {
                $query  = $this->db->kelas->find(array('sekolah'=>"$value[_id]"));
                $jumlah = $query->count();

                // if ($jumlah > 0) {
                    $data   = array();
                    $data[] = $value['npsn'];
                    $data[] = $value['nama_sekolah_induk'];
                    $data[] = $value['program'];
                    $data[] = $value['kab_kot'];
                    $data[] = $jumlah;
                    $data[] = "$value[_id]";

                    $seluruh[] = $data;

                    // $a++;
                // }
            }
        }

        $feedback   = ['filter'=>$filter, 'total'=>$total, 'data'=>$seluruh];
        // $feedback   = ['filter'=>$a, 'total'=>$total, 'data'=>$seluruh];

        return $feedback;
    }

    public function GetSome($criteria)
    {
        $c  = explode(',', $criteria);
        foreach ($c as $value) {
            $val[] = array('_id'=> new MongoId($value));
        }
        $lol   = array(
                        '$or' => $val
                    );
        $getSekolah = $this->table->find($lol);
        $total      = $this->table->find()->count();
        $filter     = $getSekolah->count();

        foreach ($getSekolah as $value) {
            $query  = $this->db->kelas->find(array('sekolah'=>"$value[_id]"));
            $jumlah = $query->count();
            $data   = array();
            $data[] = $value['npsn'];
            $data[] = $value['nama_sekolah_induk'];
            $data[] = $value['program'];
            $data[] = $value['kab_kot'];
            $data[] = $jumlah;
            $data[] = "$value[_id]";

            $seluruh[] = $data;
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

    public function CountGuru()
    {
        $query =  $this -> table -> find(array("status"=>"guru"));
        $count = $query->count();

        return $count;
    }

    public function CountSiswa()
    {
        $query =  $this -> table -> find(array("status"=>"siswa"));
        $count = $query->count();

        return $count;
    }

}


?>
