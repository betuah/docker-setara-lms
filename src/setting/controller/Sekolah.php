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

    public function getInfoSekolah($idSekolah){

        try {
            $ID = new MongoId($idSekolah);
        } catch (MongoException $ex) {
            $ID = new MongoId();
        }
        $query  = $this->table->findOne(array("_id" => $ID));

        // print_r($query);
        return $query;
    }

    public function getListSekolah(){
        $query =  $this->table->find()->sort(array('nama_sekolah_induk'=> 1));;
        $dataSekolah    = [];
        foreach ($query as $data) {
            $dataSekolah[] = ['id'=>"$data[_id]", 'text'=>$data['nama_sekolah_induk']];
        }

        return $dataSekolah;
    }

    public function getListSekolahByProgram($program){
        $query =  $this->table->find(array("program" => ($program == "" ? array('$ne' => ""): $program)))->sort(array('nama_sekolah_induk'=> 1));;
        $dataSekolah    = [];
        foreach ($query as $data) {
            $dataSekolah[] = ['id'=>"$data[_id]", 'text'=>$data['nama_sekolah_induk']];
        }

        return $dataSekolah;
    }

    public function getListSekolahbyNama($sekolah, $tipe = null){
        $regex      = new MongoRegex('/'.$sekolah.'/i');
        $query =  $this->table->find(array("nama_sekolah_induk" => $regex))->limit(100)->sort(array('nama_sekolah_induk'=> 1));
        $dataSekolah    = [];
        if ($tipe == 'select') {
            foreach ($query as $data) {
                $dataSekolah[] = ['id'=>"$data[_id]", 'text'=>$data['nama_sekolah_induk'], 'npsn'=>$data['npsn']];
            }
        }else{
            foreach ($query as $data) {
                $dataSekolah[] = $data['nama_sekolah_induk'];
            }
        }

        $response = array("query"=>"Sekolah", "suggestion"=>$dataSekolah);

        // return $response;
        return $dataSekolah;
    }

    public function getListSekolahbyNamaByProgram($program, $sekolah, $tipe = null){
        $regex      = new MongoRegex('/'.$sekolah.'/i');
        $query =  $this->table->find(array("program" => ($program == "" ? array('$ne' => ""): $program), "nama_sekolah_induk" => $regex))->limit(100)->sort(array('nama_sekolah_induk'=> 1));
        $dataSekolah    = [];
        if ($tipe == 'select') {
            foreach ($query as $data) {
                $dataSekolah[] = ['id'=>"$data[_id]", 'text'=>$data['nama_sekolah_induk'], 'npsn'=>$data['npsn'], 'provinsi'=>$data['provinsi'], 'kab_kot'=>$data['kab_kot']];
            }
        }else{
            foreach ($query as $data) {
                $dataSekolah[] = $data['npsn'].": ".$data['nama_sekolah_induk'];
            }
        }

        $response = array("query"=>"Sekolah", "suggestion"=>$dataSekolah);

        // return $response;
        return $dataSekolah;
    }

    public function CountSekolah(){
        $query = $this->table->find(array("program"=>"Pendidikan Kesetaraan"));
        $count = $query->count();
        return $count;
    }
}
