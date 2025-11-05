<?php
include 'views/header.php';

// Gunakan variabel konsisten
$is_edit = isset($employee) && $employee;
$data = $is_edit ? $employee : [];

// Tentukan judul dan action form
$form_title = $is_edit ? 'Edit Data Karyawan' : 'Tambah Karyawan Baru';
$button_text = $is_edit ? 'Update Data' : 'Simpan Data';
$form_action = $is_edit ? "index.php?action=edit&id={$data['id']}" : "index.php?action=create";
?>

<h2><?php echo $form_title; ?></h2>

<?php if (isset($error)): ?>
    <div class="alert alert-error">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?php echo $form_action; ?>">

    <?php if ($is_edit): ?>
        <input type="hidden" name="employee_id" value="<?php echo $data['id']; ?>">
    <?php endif; ?>

    <div class="form-group">
        <label for="first_name" class="form-label">Nama Depan *</label>
        <input type="text" id="first_name" name="first_name" class="form-input"
               value="<?php echo $is_edit ? htmlspecialchars($data['first_name']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="last_name" class="form-label">Nama Belakang *</label>
        <input type="text" id="last_name" name="last_name" class="form-input"
               value="<?php echo $is_edit ? htmlspecialchars($data['last_name']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Email *</label>
        <input type="email" id="email" name="email" class="form-input"
               value="<?php echo $is_edit ? htmlspecialchars($data['email']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="department" class="form-label">Departemen *</label>
        <select name="department" class="form-input" required>
            <option value="">-- Pilih Departemen --</option>
            <?php
            $departments = ['IT', 'HR', 'Finance', 'Marketing', 'Operations'];
            foreach ($departments as $dep) {
                $selected = ($is_edit && $data['department'] == $dep) ? 'selected' : '';
                echo "<option value='$dep' $selected>$dep</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="position" class="form-label">Posisi *</label>
        <input type="text" id="position" name="position" class="form-input"
               value="<?php echo $is_edit ? htmlspecialchars($data['position']) : ''; ?>" required>
    </div>

    <div class="form-group">
        <label for="salary" class="form-label">Gaji (Rp) *</label>
        <input type="text" id="salary" name="salary" class="form-input"
               value="<?php echo $is_edit ? number_format($data['salary'], 0, ',', '.') : ''; ?>"
               required placeholder="Contoh: 5.000.000">
        <small style="color: #666;">Gunakan format angka (otomatis akan diberi titik)</small>
    </div>

    <div class="form-group">
        <label for="hire_date" class="form-label">Tanggal Mulai Bekerja *</label>
        <input type="date" id="hire_date" name="hire_date" class="form-input"
               value="<?php echo $is_edit ? $data['hire_date'] : ''; ?>" required>
    </div>

    <div class="form-group" style="display: flex; gap: 1rem; margin-top: 2rem;">
        <button type="submit" class="btn btn-primary"><?php echo $button_text; ?></button>
        <a href="index.php" class="btn" style="background: #6c757d; color: white;">Kembali ke Daftar</a>
    </div>

    <?php if ($is_edit): ?>
        <p style="margin-top: 1rem;">
            <a href="index.php?action=delete&id=<?php echo $data['id']; ?>"
               class="btn btn-delete"
               onclick="return confirm('Yakin ingin menghapus karyawan <?php echo htmlspecialchars($data['first_name'] . ' ' . $data['last_name']); ?>?');">
               Hapus Karyawan
            </a>
        </p>
    <?php endif; ?>
</form>

<script>
// Format angka otomatis saat input
const salaryInput = document.getElementById('salary');

salaryInput.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, ''); // hapus semua non-digit
    if (value) {
        e.target.value = parseInt(value).toLocaleString('id-ID'); // kasih titik ribuan
    } else {
        e.target.value = '';
    }
});

// Saat edit, pastikan value tetap terformat (kalau belum)
window.addEventListener('DOMContentLoaded', () => {
    let val = salaryInput.value.replace(/\D/g, '');
    if (val) salaryInput.value = parseInt(val).toLocaleString('id-ID');
});

// Validasi dan ubah jadi angka murni sebelum submit
document.querySelector('form').addEventListener('submit', function(e) {
    const hireDate = document.getElementById('hire_date').value;
    const today = new Date().toISOString().split('T')[0];
    const salaryRaw = salaryInput.value.replace(/\D/g, '');

    if (hireDate > today) {
        alert('Tanggal mulai bekerja tidak boleh lebih dari hari ini!');
        e.preventDefault();
        return false;
    }

    if (parseInt(salaryRaw) < 1000000) {
        alert('Gaji minimal Rp 1.000.000!');
        e.preventDefault();
        return false;
    }

    // Ubah value jadi angka murni sebelum dikirim
    salaryInput.value = salaryRaw;
});
</script>

<?php include 'views/footer.php'; ?>
