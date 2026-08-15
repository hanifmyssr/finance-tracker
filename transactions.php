<?php
require_once 'config/db.php';

// Filter
$month = $_GET['month'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "SELECT t.*, c.name as category_name FROM transactions t JOIN categories c ON t.category_id = c.id WHERE 1=1";
$params = [];
$types = "";

if ($month !== '') {
    $sql .= " AND DATE_FORMAT(t.transaction_date, '%Y-%m') = ?";
    $params[] = $month;
    $types .= "s";
}
if ($category !== '') {
    $sql .= " AND t.category_id = ?";
    $params[] = $category;
    $types .= "i";
}
$sql .= " ORDER BY t.transaction_date DESC, t.id DESC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$categories = $conn->query("SELECT * FROM categories ORDER BY type, name");

include 'includes/header.php';
?>

<h2 class="mb-4">Daftar Transaksi</h2>

<form method="GET" class="row g-2 mb-4">
    <div class="col-auto">
        <input type="month" name="month" class="form-control" value="<?php echo htmlspecialchars($month); ?>">
    </div>
    <div class="col-auto">
        <select name="category" class="form-select">
            <option value="">Semua Kategori</option>
            <?php
            $categories->data_seek(0);
            while ($cat = $categories->fetch_assoc()):
            ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo ($category == $cat['id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-secondary">Filter</button>
        <a href="transactions.php" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-hover align-middle bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Catatan</th>
                <th class="text-end">Jumlah</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows === 0): ?>
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>
            <?php endif; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('d M Y', strtotime($row['transaction_date'])); ?></td>
                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                    <td>
                        <?php if ($row['type'] === 'income'): ?>
                            <span class="badge bg-success">Pemasukan</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Pengeluaran</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['note']); ?></td>
                    <td class="text-end">Rp <?php echo number_format($row['amount'], 0, ',', '.'); ?></td>
                    <td class="text-end">
                        <a href="edit_transaction.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <a href="delete_transaction.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus transaksi ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
