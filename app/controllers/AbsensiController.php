<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/AbsensiModel.php';

class AbsensiController {
    private $model;

    public function __construct() {
        $database = new Database();
        $dbConn = $database->connect();
        $this->model = new AbsensiModel($dbConn);
    }

    public function index() {
        $pesanSukses = "";

        // proses simpan
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'simpan') {
            $mapel = $_POST['mapel'] ?? '';
            $guru  = $_POST['guru'] ?? '';
            $bulan = $_POST['bulan'] ?? date('m');
            $tahun = date('Y');
            $absenData = $_POST['absen'] ?? [];

            if (!empty($mapel) && !empty($guru) && !empty($absenData)) {
                foreach ($absenData as $idMurid => $tanggalList) {
                    foreach ($tanggalList as $tgl => $status) {
                        if (!empty($status)) {
                            $tglFormatted = sprintf("%04d-%02d-%02d", $tahun, $bulan, $tgl);
                            $this->model->simpanAtauUpdateAbsensi($idMurid, $mapel, $guru, $tglFormatted, $status);
                        }
                    }
                }
                $pesanSukses = "Data absensi berhasil disimpan ke database!";
            }
        }
        //ambil data
        $dataMurid = $this->model->getAllMurid();
        $dataGuru  = $this->model->getAllGuru();
        $dataMapel = $this->model->getAllMapel();
        $dataKelas = $this->model->getAllKelas();

        // menangkap filter post/get
        $selectedMapel = $_REQUEST['mapel'] ?? ($dataMapel[0]['id_mapel'] ?? '');
        $selectedKelas = $_REQUEST['kelas'] ?? ($dataKelas[0]['id_kelas'] ?? '');
        $selectedGuru  = $_REQUEST['guru'] ?? '';
        $selectedBulan = $_REQUEST['bulan'] ?? date('m');

        // Format Tahun-Bulan 
        $tahunBulan = date('Y') . '-' . sprintf("%02d", $selectedBulan);

        // mengambil data tergantung filter
        $savedAbsensi = [];
        if (!empty($selectedMapel) && !empty($selectedKelas)) {
            $savedAbsensi = $this->model->getAbsensiBulan($selectedMapel, $selectedKelas, $tahunBulan);
        }

        require_once __DIR__ . '/../views/absensi/index.php';
    }
}