<?php
require_once 'config/db.php';

$id = intval($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = intval($_POST['category_id']);
    $type = $_POST['type'];
    $amount = floatval($_POST['amount']);
    $date = $_POST['transaction_date'];
    $note = trim($_POST['note']);

    $stmt = $conn->prepare("UPDATE transactions SET category_id=?, type=?, amount=?, transaction_date=?, note=? WHERE id=?");
    $stmt->bind_param("isdssi", $category_id, $type, $amount, $date, $note, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: transactions.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$transaction = $stmt->get_result()->fetch_assoc();

if (!$transaction) {
    header("Location: transactions.php");
    exit;
}

$categories = $conn->query("SELECT * FROM categories ORDER BY type, name");

include 'includes/header.php';
?>

<h2 class="mb-4">Edit Transaksi</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Jenis Transaksi</label>
                <select name="type" class="form-select" required>
                    <option value="income" <?php echo $transaction['type'] === 'income' ? 'selected' : ''; ?>>Pemasukan</option>
                    <option value="expense" <?php echo $transaction['type'] === 'expense' ? 'selected' : ''; ?>>Pengeluaran</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="category_id" class="form-select" required>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo ($cat['id'] == $transaction['category_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah (Rp)</label>
                <input type="number" name="amount" class="form-control" min="0" step="0.01" value="<?php echo $transaction['amount']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="transaction_date" class="form-control" value="<?php echo $transaction['transaction_date']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan (opsional)</label>
                <input type="text" name="note" class="form-control" value="<?php echo htmlspecialchars($transaction['note']); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Update Transaksi</button>
            <a href="transactions.php" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
