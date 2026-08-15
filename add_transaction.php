<?php
require_once 'config/db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category_id']);
    $type = $_POST['type'];
    $amount = floatval($_POST['amount']);
    $date = $_POST['transaction_date'];
    $note = trim($_POST['note']);

    $stmt = $conn->prepare("INSERT INTO transactions (category_id, type, amount, transaction_date, note) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $category_id, $type, $amount, $date, $note);

    if ($stmt->execute()) {
        $message = "success";
    } else {
        $message = "error";
    }
    $stmt->close();
}

$categories = $conn->query("SELECT * FROM categories ORDER BY type, name");

include 'includes/header.php';
?>

<h2 class="mb-4">Tambah Transaksi</h2>

<?php if ($message === "success"): ?>
    <div class="alert alert-success">Transaksi berhasil ditambahkan!</div>
<?php elseif ($message === "error"): ?>
    <div class="alert alert-danger">Gagal menambahkan transaksi.</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Jenis Transaksi</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="income">Pemasukan</option>
                    <option value="expense">Pengeluaran</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>" data-type="<?php echo $cat['type']; ?>">
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="amount" class="form-control" min="0" step="0.01" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="transaction_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan (opsional)</label>
                <input type="text" name="note" class="form-control" placeholder="Contoh: Makan siang di kantin">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
            <a href="transactions.php" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
