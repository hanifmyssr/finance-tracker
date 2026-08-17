<?php
require_once 'config/db.php';

// Ringkasan total
$totalIncome = $conn->query("SELECT COALESCE(SUM(amount),0) as total FROM transactions WHERE type='income'")->fetch_assoc()['total'];
$totalExpense = $conn->query("SELECT COALESCE(SUM(amount),0) as total FROM transactions WHERE type='expense'")->fetch_assoc()['total'];
$balance = $totalIncome - $totalExpense;

// Data untuk pie chart (pengeluaran per kategori)
$categoryData = $conn->query("
    SELECT c.name, SUM(t.amount) as total
    FROM transactions t
    JOIN categories c ON t.category_id = c.id
    WHERE t.type = 'expense'
    GROUP BY c.name
    ORDER BY total DESC
");
$catLabels = [];
$catValues = [];
while ($row = $categoryData->fetch_assoc()) {
    $catLabels[] = $row['name'];
    $catValues[] = $row['total'];
}

// Data untuk line chart (tren 6 bulan terakhir)
$monthlyData = $conn->query("
    SELECT DATE_FORMAT(transaction_date, '%Y-%m') as ym,
           SUM(CASE WHEN type='income' THEN amount ELSE 0 END) as income,
           SUM(CASE WHEN type='expense' THEN amount ELSE 0 END) as expense
    FROM transactions
    WHERE transaction_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY ym
    ORDER BY ym ASC
");
$months = [];
$monthlyIncome = [];
$monthlyExpense = [];
while ($row = $monthlyData->fetch_assoc()) {
    $months[] = $row['ym'];
    $monthlyIncome[] = $row['income'];
    $monthlyExpense[] = $row['expense'];
}

include 'includes/header.php';
?>

<h2 class="mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow-sm">
            <div class="card-body">
                <h6>Total Pemasukan</h6>
                <h4>Rp <?php echo number_format($totalIncome, 0, ',', '.'); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger shadow-sm">
            <div class="card-body">
                <h6>Total Pengeluaran</h6>
                <h4>Rp <?php echo number_format($totalExpense, 0, ',', '.'); ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow-sm">
            <div class="card-body">
                <h6>Saldo</h6>
                <h4>Rp <?php echo number_format($balance, 0, ',', '.'); ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Pengeluaran per Kategori</h6>
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="card-title">Tren Bulanan (6 Bulan Terakhir)</h6>
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
const catLabels = <?php echo json_encode($catLabels); ?>;
const catValues = <?php echo json_encode($catValues); ?>;
const months = <?php echo json_encode($months); ?>;
const monthlyIncome = <?php echo json_encode($monthlyIncome); ?>;
const monthlyExpense = <?php echo json_encode($monthlyExpense); ?>;

const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
const chartTextColor = isDark ? '#e8e9ea' : '#212529';
const chartGridColor = isDark ? '#33373d' : '#e9ecef';

new Chart(document.getElementById('categoryChart'), {
    type: 'pie',
    data: {
        labels: catLabels,
        datasets: [{
            data: catValues,
            backgroundColor: ['#f94144','#f3722c','#f8961e','#f9c74f','#90be6d','#43aa8b','#577590','#277da1','#9b5de5','#adb5bd']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: chartTextColor } } }
    }
});

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [
            { label: 'Pemasukan', data: monthlyIncome, borderColor: '#43aa8b', backgroundColor: '#43aa8b33', tension: 0.3, fill: true },
            { label: 'Pengeluaran', data: monthlyExpense, borderColor: '#f94144', backgroundColor: '#f9414433', tension: 0.3, fill: true }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: chartTextColor } } },
        scales: {
            x: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } },
            y: { ticks: { color: chartTextColor }, grid: { color: chartGridColor } }
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
