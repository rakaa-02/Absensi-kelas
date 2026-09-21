<?php

class AbsensiModel {
    private $db;

    public function __construct($dbConn) {
        $this->db = $dbConn;
    }

    public function getAllMurid() {
        $query = "SELECT * FROM murid ORDER BY nama_murid ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllGuru() {
        $query = "SELECT * FROM guru ORDER BY nama_guru ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllMapel() {
        $query = "SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllKelas() {
        $query = "SELECT * FROM kelas ORDER BY nama_kelas ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function simpanAtauUpdateAbsensi($idMurid, $idMapel, $idGuru, $tanggal, $status) {
        $checkQuery = "SELECT id_absensi FROM absensi WHERE id_murid = :id_murid AND id_mapel = :id_mapel AND tanggal = :tanggal";
        $stmtCheck = $this->db->prepare($checkQuery);
        $stmtCheck->execute([
            ':id_murid' => $idMurid,
            ':id_mapel' => $idMapel,
            ':tanggal'  => $tanggal
        ]);
        $existing = $stmtCheck->fetch();

        if ($existing) {
            $updateQuery = "UPDATE absensi SET status = :status, id_guru = :id_guru WHERE id_absensi = :id_absensi";
            $stmtUpdate = $this->db->prepare($updateQuery);
            return $stmtUpdate->execute([
                ':status'     => $status,
                ':id_guru'    => $idGuru,
                ':id_absensi' => $existing['id_absensi']
            ]);
        } else {
            $insertQuery = "INSERT INTO absensi (id_murid, id_mapel, id_guru, tanggal, status) VALUES (:id_murid, :id_mapel, :id_guru, :tanggal, :status)";
            $stmtInsert = $this->db->prepare($insertQuery);
            return $stmtInsert->execute([
                ':id_murid' => $idMurid,
                ':id_mapel' => $idMapel,
                ':id_guru'  => $idGuru,
                ':tanggal'  => $tanggal,
                ':status'   => $status
            ]);
        }
    }

    // Method untuk mengambil data absensi yang sudah tersimpan di database
    public function getAbsensiBulan($idMapel, $idKelas, $tahunBulan) {
        $query = "SELECT a.id_murid, DAY(a.tanggal) as tgl, a.status 
                  FROM absensi a 
                  JOIN murid m ON a.id_murid = m.id_murid 
                  WHERE a.id_mapel = :id_mapel AND m.id_kelas = :id_kelas AND DATE_FORMAT(a.tanggal, '%Y-%m') = :tahunBulan";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':id_mapel'    => $idMapel,
            ':id_kelas'    => $idKelas,
            ':tahunBulan'  => $tahunBulan
        ]);
        
        $result = [];
        while ($row = $stmt->fetch()) {
            $result[$row['id_murid']][$row['tgl']] = $row['status'];
        }
        return $result;
    }
}


