<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Hadir Murid</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="container">
        
        <?php if (!empty($pesanSukses)): ?>
            <div style="padding: 10px; background-color: #dcfce7; color: #15803d; border: 1px solid #86efac; border-radius: 6px; margin-bottom: 15px;">
                <?= $pesanSukses; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" id="formAbsensi">
            <input type="hidden" name="action" value="simpan">

            <div class="header-title">
                <h2>Daftar Hadir Murid</h2>
                <button type="submit" class="btn-simpan"> Simpan Absensi</button>
            </div>

            <div class="card-form">
                <div class="form-grid">
                    
                    
                    <div class="form-group">
                        <label for="mapel">Mata Pelajaran</label>
                        <select name="mapel" id="mapel" onchange="filterData()" required>
                            <option value=""> Pilih Mata Pelajaran </option>
                            <?php foreach ($dataMapel as $mapel): ?>
                                <option value="<?= $mapel['id_mapel']; ?>" <?= ($selectedMapel == $mapel['id_mapel']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($mapel['nama_mapel']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="kelas">Kelas</label>
                        <select name="kelas" id="kelas" onchange="filterData()">
                            <option value="">-- Pilih Kelas --</option>
                            <?php foreach ($dataKelas as $kelas): ?>
                                <option value="<?= $kelas['id_kelas']; ?>" <?= ($selectedKelas == $kelas['id_kelas']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($kelas['nama_kelas']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="guru">Nama Guru</label>
                        <select name="guru" id="guru" required>
                            <option value="">-- Pilih Guru --</option>
                            <?php foreach ($dataGuru as $guru): ?>
                                <option value="<?= $guru['id_guru']; ?>" <?= ($selectedGuru == $guru['id_guru']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($guru['nama_guru']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <?php 
                            $listBulan = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', 
                                '04' => 'April', '05' => 'Mei', '06' => 'Juni', 
                                '07' => 'Juli', '08' => 'Agustus', '09' => 'September', 
                                '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                        ?>
                        <select name="bulan" id="bulan" onchange="filterData()">
                            <?php foreach ($listBulan as $key => $val): ?>
                                <option value="<?= $key; ?>" <?= ($selectedBulan == $key) ? 'selected' : ''; ?>>
                                    <?= $val; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
            </div>

            <div class="table-wrapper">
                <table class="table-absensi">
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-nama">Nama Murid</th>
                            <?php for ($i = 1; $i <= 30; $i++): ?>
                                <th><?= $i; ?></th>
                            <?php endfor; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($dataMurid)): ?>
                            <?php $no = 1; ?>
                            <?php foreach ($dataMurid as $murid): ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-left font-bold"><?= htmlspecialchars($murid['nama_murid']); ?></td>
                                    
                                    <?php for ($tgl = 1; $tgl <= 30; $tgl++): ?>
                                        <?php 
                                            $idM = $murid['id_murid'];
                                            $valAbsen = $savedAbsensi[$idM][$tgl] ?? '';
                                        ?>
                                        <td>
                                            <select name="absen[<?= $idM; ?>][<?= $tgl; ?>]" class="select-absensi">
                                                <option value="" <?= ($valAbsen == '') ? 'selected' : ''; ?>></option>
                                                <option value="Hadir" <?= ($valAbsen == 'Hadir') ? 'selected' : ''; ?>>H</option>
                                                <option value="Izin"  <?= ($valAbsen == 'Izin')  ? 'selected' : ''; ?>>I</option>
                                                <option value="Sakit" <?= ($valAbsen == 'Sakit') ? 'selected' : ''; ?>>S</option>
                                                <option value="Alpha" <?= ($valAbsen == 'Alpha') ? 'selected' : ''; ?>>A</option>
                                            </select>
                                        </td>
                                    <?php endfor; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="32" class="text-center">Data murid tidak ditemukan.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </form>
    </div>

    <script>
        function filterData() {
            var form = document.getElementById('formAbsensi');
            document.querySelector('input[name="action"]').value = 'filter';
            form.method = 'GET';
            form.submit();
        }
    </script>
</body>
</html>