<?php
/**
 * Kwara State Polytechnic - School Fees System
 * Main Application (Single Page)
 */
require_once __DIR__ . '/config.php';

session_start();

// Simple auth check
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Kwara State Polytechnic - School Fees System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #030; color: #e2e8f0; min-height: 100vh; }
    .bg-primary { background-color: #030; }
    .bg-accent { background-color: #339933; }
    .text-accent { color: #339933; }
    .border-accent { border-color: #339933; }
    .bg-card { background-color: rgba(255,255,255,0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.1); }
    .btn-accent { background: #339933; color: #fff; transition: all 0.3s ease; }
    .btn-accent:hover { background: #196819; transform: translateY(-1px); box-shadow: 0 4px 20px rgba(51,153,51,0.4); }
    .btn-outline { border: 1px solid rgba(255,255,255,0.2); color: #e2e8f0; transition: all 0.3s ease; }
    .btn-outline:hover { border-color: #339933; color: #339933; }
    .nav-btn { transition: all 0.3s ease; cursor: pointer; border-bottom: 2px solid transparent; }
    .nav-btn.active { color: #339933; border-bottom-color: #339933; }
    .nav-btn:not(.active):hover { color: #94a3b8; border-bottom-color: #94a3b8; }
    .view { display: none; animation: fadeIn 0.4s ease; }
    .view.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes popIn { 0% { transform: scale(0); } 60% { transform: scale(1.15); } 100% { transform: scale(1); } }
    .reveal { animation: fadeIn 0.5s ease forwards; opacity: 0; }
    .reveal-delay-1 { animation-delay: 0.1s; }
    .reveal-delay-2 { animation-delay: 0.2s; }
    .reveal-delay-3 { animation-delay: 0.3s; }
    .reveal-delay-4 { animation-delay: 0.4s; }
    .stat-card { transition: all 0.3s ease; }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(51,153,51,0.2); }
    input, select, textarea { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #e2e8f0; border-radius: 0.5rem; padding: 0.625rem 1rem; width: 100%; outline: none; transition: all 0.3s ease; }
    input:focus, select:focus, textarea:focus { border-color: #339933; box-shadow: 0 0 0 3px rgba(51,153,51,0.2); }
    select option { background: #030; color: #e2e8f0; }
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 0.75rem 1rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; border-bottom: 1px solid rgba(255,255,255,0.1); }
    td { padding: 0.75rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); }
    tr:hover td { background: rgba(255,255,255,0.03); }
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); display: none; align-items: center; justify-content: center; z-index: 50; animation: fadeIn 0.3s ease; }
    .modal-overlay.active { display: flex; }
    .modal-content { background: #0a1a0a; border: 1px solid rgba(255,255,255,0.1); border-radius: 1rem; padding: 2rem; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto; }
    .toast { position: fixed; bottom: 2rem; right: 2rem; padding: 1rem 1.5rem; border-radius: 0.75rem; color: #fff; font-weight: 500; transform: translateY(100px); opacity: 0; transition: all 0.4s ease; z-index: 100; max-width: 400px; }
    .toast.show { transform: translateY(0); opacity: 1; }
    .toast.success { background: #16a34a; }
    .toast.error { background: #dc2626; }
    .toast.info { background: #2563eb; }
    .badge-paid { background: rgba(22,163,74,0.15); color: #22c55e; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; }
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: #030; }
    ::-webkit-scrollbar-thumb { background: #339933; border-radius: 3px; }
    .logo-img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.15); }
    @media (max-width: 768px) {
      .nav-btn span { display: none; }
      .stat-card { padding: 1rem; }
      .modal-content { padding: 1.5rem; }
    }
  </style>
</head>
<body>

<div id="app" class="min-h-screen flex flex-col">

  <!-- HEADER -->
  <header class="bg-card border-b border-white/10 px-4 py-3 flex items-center justify-between sticky top-0 z-40 backdrop-blur-xl">
    <div class="flex items-center gap-3">
      <img src="assets/kwarapoly-logo.png" alt="Kwara Poly Logo" class="logo-img" onerror="this.style.display='none'">
      <div>
        <h1 class="text-lg font-bold text-white leading-tight">Kwara State Polytechnic</h1>
        <p class="text-xs text-gray-400">School Fees Recording System</p>
      </div>
    </div>
    <div class="flex items-center gap-3 text-sm">
      <span id="lastUpdateBadge" class="hidden md:flex items-center gap-1.5 text-gray-500">
        <i class="fas fa-clock text-xs"></i> <span id="lastUpdateText">just now</span>
      </span>
      <span id="onlineStatus" class="flex items-center gap-1.5 text-gray-400">
        <i class="fas fa-circle text-xs text-green-500"></i> Online
      </span>
    </div>
  </header>

  <!-- NAV -->
  <nav class="bg-card border-b border-white/10 px-2 py-1 flex overflow-x-auto gap-1 sticky top-[73px] z-30 backdrop-blur-xl">
    <button class="nav-btn active px-4 py-3 text-sm font-medium flex items-center gap-2 whitespace-nowrap" data-view="dashboard">
      <i class="fas fa-chart-pie"></i><span>Dashboard</span>
    </button>
    <button class="nav-btn px-4 py-3 text-sm font-medium flex items-center gap-2 whitespace-nowrap" data-view="students">
      <i class="fas fa-users"></i><span>Students</span>
    </button>
    <button class="nav-btn px-4 py-3 text-sm font-medium flex items-center gap-2 whitespace-nowrap" data-view="payments">
      <i class="fas fa-money-bill-wave"></i><span>Record Payment</span>
    </button>
    <button class="nav-btn px-4 py-3 text-sm font-medium flex items-center gap-2 whitespace-nowrap" data-view="verify">
      <i class="fas fa-qrcode"></i><span>Verify</span>
    </button>
    <button class="nav-btn px-4 py-3 text-sm font-medium flex items-center gap-2 whitespace-nowrap" data-view="search">
      <i class="fas fa-search"></i><span>Search</span>
    </button>
  </nav>

  <!-- MAIN CONTENT -->
  <main class="flex-1 p-4 md:p-6 max-w-7xl mx-auto w-full">

    <!-- ===== DASHBOARD ===== -->
    <section id="view-dashboard" class="view active">
      <h2 class="text-2xl font-bold mb-6 reveal"><i class="fas fa-chart-pie text-accent mr-3"></i>Dashboard</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-card rounded-xl p-5 reveal reveal-delay-1">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-400 text-sm">Total Students</span>
            <i class="fas fa-user-graduate text-accent"></i>
          </div>
          <p class="text-3xl font-bold" id="dashTotalStudents">—</p>
        </div>
        <div class="stat-card bg-card rounded-xl p-5 reveal reveal-delay-2">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-400 text-sm">Total Collections</span>
            <i class="fas fa-coins text-accent"></i>
          </div>
          <p class="text-3xl font-bold" id="dashTotalCollections">&#8358;0</p>
        </div>
        <div class="stat-card bg-card rounded-xl p-5 reveal reveal-delay-3">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-400 text-sm">Payments This Term</span>
            <i class="fas fa-check-circle text-green-500"></i>
          </div>
          <p class="text-3xl font-bold" id="dashTermPayments">—</p>
        </div>
        <div class="stat-card bg-card rounded-xl p-5 reveal reveal-delay-4">
          <div class="flex items-center justify-between mb-2">
            <span class="text-gray-400 text-sm">Pending Payments</span>
            <i class="fas fa-clock text-yellow-500"></i>
          </div>
          <p class="text-3xl font-bold" id="dashPendingPayments">—</p>
        </div>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-card rounded-xl p-5 reveal reveal-delay-2">
          <h3 class="font-semibold mb-3"><i class="fas fa-clock text-yellow-500 mr-2"></i>Recent Payments</h3>
          <div id="dashRecentPayments" class="text-sm text-gray-400">Loading...</div>
        </div>
        <div class="bg-card rounded-xl p-5 reveal reveal-delay-3">
          <h3 class="font-semibold mb-3"><i class="fas fa-users text-accent mr-2"></i>Student Overview</h3>
          <div id="dashStudentOverview" class="text-sm text-gray-400">Loading...</div>
        </div>
      </div>
    </section>

    <!-- ===== STUDENTS ===== -->
    <section id="view-students" class="view">
      <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h2 class="text-2xl font-bold reveal"><i class="fas fa-users text-accent mr-3"></i>Students</h2>
        <button onclick="openStudentModal()" class="btn-accent px-5 py-2.5 rounded-lg text-sm font-semibold flex items-center gap-2 reveal reveal-delay-1">
          <i class="fas fa-plus"></i> Add Student
        </button>
      </div>
      <div class="bg-card rounded-xl overflow-hidden reveal reveal-delay-2">
        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th>Matric No</th>
                <th>Name</th>
                <th>Department</th>
                <th>Session</th>
                <th>Parent</th>
                <th class="text-right">Actions</th>
              </tr>
            </thead>
            <tbody id="studentsBody">
              <tr><td colspan="6" class="text-center text-gray-500 py-8">Loading...</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- ===== RECORD PAYMENT ===== -->
    <section id="view-payments" class="view">
      <h2 class="text-2xl font-bold mb-6 reveal"><i class="fas fa-money-bill-wave text-accent mr-3"></i>Record Payment</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-card rounded-xl p-6 reveal reveal-delay-1">
          <form id="paymentForm" onsubmit="return recordPayment(event)">
            <div class="mb-4">
              <label class="block text-gray-400 text-sm mb-1">Select Student <span class="text-red-400">*</span></label>
              <select id="payStudentId" required onchange="onStudentSelected(this)">
                <option value="">-- Select a student --</option>
              </select>
            </div>
            <div id="selectedStudentInfo" class="bg-white/5 rounded-lg p-3 mb-4 text-sm hidden">
              <div id="ssiName" class="font-semibold text-white"></div>
              <div id="ssiDept" class="text-gray-400"></div>
            </div>
            <div class="mb-4">
              <label class="block text-gray-400 text-sm mb-1">Amount (₦) <span class="text-red-400">*</span></label>
              <input type="number" id="payAmount" placeholder="Enter amount" min="1" required>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
              <div>
                <label class="block text-gray-400 text-sm mb-1">Session <span class="text-red-400">*</span></label>
                <select id="paySession" required>
                  <option value="2025/2026">2025/2026</option>
                  <option value="2024/2025">2024/2025</option>
                  <option value="2023/2024">2023/2024</option>
                </select>
              </div>
              <div>
                <label class="block text-gray-400 text-sm mb-1">Term <span class="text-red-400">*</span></label>
                <select id="payTerm" required>
                  <option value="First">First Term</option>
                  <option value="Second">Second Term</option>
                  <option value="Third">Third Term</option>
                </select>
              </div>
            </div>
            <div class="mb-5">
              <label class="block text-gray-400 text-sm mb-1">Payment Date</label>
              <input type="date" id="payDate">
            </div>
            <button type="submit" class="btn-accent w-full py-3 rounded-lg font-semibold">
              <i class="fas fa-save mr-2"></i> Record Payment
            </button>
          </form>
        </div>
        <div class="bg-card rounded-xl p-6 reveal reveal-delay-2">
          <h3 class="font-semibold mb-3"><i class="fas fa-history text-accent mr-2"></i>Recent Payments</h3>
          <div id="recentPaymentsList" class="space-y-2 text-sm"></div>
        </div>
      </div>
    </section>

    <!-- ===== VERIFY ===== -->
    <section id="view-verify" class="view">
      <h2 class="text-2xl font-bold mb-6 reveal"><i class="fas fa-qrcode text-accent mr-3"></i>Verify Payment</h2>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-card rounded-xl p-6 reveal reveal-delay-1">
          <h3 class="font-semibold mb-3"><i class="fas fa-camera text-accent mr-2"></i>Manual Verification</h3>
          <p class="text-gray-400 text-sm mb-4">Enter the receipt number to verify a payment.</p>
          <div class="flex gap-2">
            <input type="text" id="verifyReceiptNo" placeholder="Receipt number e.g. KPF-2025-00001">
            <button onclick="verifyByReceipt()" class="btn-accent px-5 py-2 rounded-lg whitespace-nowrap">
              <i class="fas fa-search"></i>
            </button>
          </div>
          <div class="mt-4 flex gap-2">
            <button onclick="verifyByMatric()" class="btn-outline px-4 py-2 rounded-lg text-sm">
              <i class="fas fa-user mr-1"></i> By Matric No
            </button>
            <button onclick="verifyByName()" class="btn-outline px-4 py-2 rounded-lg text-sm">
              <i class="fas fa-id-card mr-1"></i> By Name
            </button>
          </div>
        </div>
        <div class="bg-card rounded-xl p-6 reveal reveal-delay-2">
          <h3 class="font-semibold mb-3"><i class="fas fa-check-circle text-green-500 mr-2"></i>Verification Result</h3>
          <div id="verifyResult" class="text-sm text-gray-400">Enter a receipt number and click search to verify.</div>
        </div>
      </div>
    </section>

    <!-- ===== SEARCH ===== -->
    <section id="view-search" class="view">
      <h2 class="text-2xl font-bold mb-6 reveal"><i class="fas fa-search text-accent mr-3"></i>Search Payments</h2>
      <div class="bg-card rounded-xl p-6 reveal reveal-delay-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
          <div>
            <label class="block text-gray-400 text-sm mb-1">Student Name</label>
            <input type="text" id="searchName" placeholder="Search by name...">
          </div>
          <div>
            <label class="block text-gray-400 text-sm mb-1">Matric No</label>
            <input type="text" id="searchMatric" placeholder="Matric number...">
          </div>
          <div>
            <label class="block text-gray-400 text-sm mb-1">From Date</label>
            <input type="date" id="searchDateFrom">
          </div>
          <div>
            <label class="block text-gray-400 text-sm mb-1">To Date</label>
            <input type="date" id="searchDateTo">
          </div>
        </div>
        <button onclick="runSearch()" class="btn-accent px-5 py-2 rounded-lg font-semibold">
          <i class="fas fa-search mr-1"></i> Search
        </button>
      </div>
      <div class="bg-card rounded-xl overflow-hidden mt-6 reveal reveal-delay-2">
        <div class="overflow-x-auto">
          <table>
            <thead>
              <tr>
                <th>Receipt No</th>
                <th>Student Name</th>
                <th>Matric No</th>
                <th>Amount</th>
                <th>Term</th>
                <th>Session</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody id="searchResults">
              <tr><td colspan="7" class="text-center text-gray-500 py-8">Run a search to see results.</td></tr>
            </tbody>
          </table>
        </div>
        <div id="searchSummary" class="p-3 text-sm text-gray-500 border-t border-white/5"></div>
      </div>
    </section>

  </main>
</div>

<!-- MODAL: Add/Edit Student -->
<div id="studentModal" class="modal-overlay">
  <div class="modal-content">
    <div class="flex items-center justify-between mb-5">
      <h3 class="text-xl font-bold"><i class="fas fa-user-graduate text-accent mr-2"></i><span id="studentModalTitle">Add Student</span></h3>
      <button onclick="closeStudentModal()" class="text-gray-400 hover:text-white text-xl"><i class="fas fa-times"></i></button>
    </div>
    <form id="studentForm" onsubmit="return saveStudent(event)">
      <div class="mb-3">
        <label class="block text-gray-400 text-sm mb-1">Matriculation Number <span class="text-red-400">*</span></label>
        <input type="text" id="studentMatric" required placeholder="e.g. KW/2025/00123">
      </div>
      <div class="mb-3">
        <label class="block text-gray-400 text-sm mb-1">Full Name <span class="text-red-400">*</span></label>
        <input type="text" id="studentName" required placeholder="Student's full name">
      </div>
      <div class="mb-3">
        <label class="block text-gray-400 text-sm mb-1">Department <span class="text-red-400">*</span></label>
        <input type="text" id="studentDept" required placeholder="e.g. Computer Science">
      </div>
      <div class="mb-3">
        <label class="block text-gray-400 text-sm mb-1">Session <span class="text-red-400">*</span></label>
        <select id="studentSession" required>
          <option value="2025/2026">2025/2026</option>
          <option value="2024/2025">2024/2025</option>
          <option value="2023/2024">2023/2024</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <div>
          <label class="block text-gray-400 text-sm mb-1">Parent Name</label>
          <input type="text" id="studentParent" placeholder="Parent/Guardian name">
        </div>
        <div>
          <label class="block text-gray-400 text-sm mb-1">Parent Phone</label>
          <input type="text" id="studentPhone" placeholder="080xxxxxxxx">
        </div>
      </div>
      <button type="submit" class="btn-accent w-full py-3 rounded-lg font-semibold mt-2">
        <i class="fas fa-save mr-1"></i> Save Student
      </button>
    </form>
  </div>
</div>

<!-- MODAL: Receipt -->
<div id="receiptModal" class="modal-overlay">
  <div class="modal-content" style="max-width:420px">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-bold"><i class="fas fa-receipt text-accent mr-2"></i>Payment Receipt</h3>
      <button onclick="closeReceiptModal()" class="text-gray-400 hover:text-white text-xl"><i class="fas fa-times"></i></button>
    </div>
    <div id="receiptContent"></div>
    <div class="flex gap-3 mt-4">
      <button onclick="printReceipt()" class="btn-accent flex-1 py-2.5 rounded-lg font-semibold text-sm">
        <i class="fas fa-print mr-1"></i> Print
      </button>
      <button onclick="downloadReceipt()" class="btn-outline flex-1 py-2.5 rounded-lg font-semibold text-sm">
        <i class="fas fa-download mr-1"></i> Download
      </button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast" class="toast success"></div>

<script>
// ===== API Helpers =====
const API = {
  async get(url) {
    const r = await fetch(url);
    if (!r.ok) throw new Error(await r.text());
    return r.json();
  },
  async post(url, data) {
    const r = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(data),
    });
    if (!r.ok) throw new Error(await r.text());
    return r.json();
  }
};

function escHtml(str) {
  if (!str) return '';
  const d = document.createElement('div');
  d.textContent = str;
  return d.innerHTML;
}

function showToast(msg, type = 'success') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'toast ' + type + ' show';
  setTimeout(() => t.classList.remove('show'), 3500);
}

// ===== Navigation =====
document.querySelectorAll('.nav-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
    document.getElementById('view-' + btn.dataset.view).classList.add('active');
    if (btn.dataset.view === 'dashboard') loadDashboard();
    if (btn.dataset.view === 'students') loadStudents();
    if (btn.dataset.view === 'payments') { loadPaymentForm(); loadRecentPayments(); }
    if (btn.dataset.view === 'search') { /* on demand */ }
  });
});

// ===== Dashboard =====
async function loadDashboard() {
  try {
    const stats = await API.get('api/dashboard.php');
    document.getElementById('dashTotalStudents').textContent = stats.total_students ?? 0;
    document.getElementById('dashTotalCollections').textContent = '\u20A6' + (Number(stats.total_collections) || 0).toLocaleString();
    document.getElementById('dashTermPayments').textContent = stats.term_payments ?? 0;
    document.getElementById('dashPendingPayments').textContent = stats.pending_payments ?? 0;

    const rp = document.getElementById('dashRecentPayments');
    if (stats.recent_payments && stats.recent_payments.length) {
      rp.innerHTML = '<div class="space-y-2">' + stats.recent_payments.map(p =>
        '<div class="flex items-center justify-between py-2 border-b border-white/5">' +
          '<div><span class="font-medium text-white">' + escHtml(p.student_name) + '</span><br><span class="text-xs text-gray-500">' + escHtml(p.receipt_no) + '</span></div>' +
          '<div class="text-right"><span class="font-semibold text-green-400">\u20A6' + Number(p.amount).toLocaleString() + '</span><br><span class="text-xs text-gray-500">' + p.payment_date + '</span></div>' +
        '</div>'
      ).join('') + '</div>';
    } else {
      rp.innerHTML = '<p class="text-gray-400">No payments recorded yet.</p>';
    }

    const so = document.getElementById('dashStudentOverview');
    if (stats.departments) {
      so.innerHTML = '<div class="space-y-1">' + Object.entries(stats.departments).map(([dept, count]) =>
        '<div class="flex justify-between"><span class="text-gray-400">' + escHtml(dept) + '</span><span class="font-semibold">' + count + '</span></div>'
      ).join('') + '</div>';
    }
  } catch (e) {
    showToast('Failed to load dashboard', 'error');
  }
}

// ===== Students =====
async function loadStudents() {
  try {
    const data = await API.get('api/students.php');
    const students = data.students || [];
    const tbody = document.getElementById('studentsBody');
    if (students.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" class="text-center text-gray-500 py-8">No students found.</td></tr>';
      return;
    }
    tbody.innerHTML = students.map(s =>
      '<tr>' +
        '<td class="font-mono text-sm">' + escHtml(s.matric_no) + '</td>' +
        '<td>' + escHtml(s.full_name) + '</td>' +
        '<td>' + escHtml(s.department) + '</td>' +
        '<td>' + escHtml(s.session) + '</td>' +
        '<td class="text-sm text-gray-400">' + (s.parent_name || '—') + '</td>' +
        '<td class="text-right">' +
          '<button onclick="editStudent(\'' + s.matric_no + '\')" class="text-blue-400 hover:text-blue-300 mr-3 text-sm"><i class="fas fa-edit"></i></button>' +
          '<button onclick="deleteStudent(\'' + s.matric_no + '\')" class="text-red-400 hover:text-red-300 text-sm"><i class="fas fa-trash"></i></button>' +
        '</td>' +
      '</tr>'
    ).join('');
  } catch (e) {
    document.getElementById('studentsBody').innerHTML = '<tr><td colspan="6" class="text-center text-red-400 py-8">Error loading students.</td></tr>';
    showToast('Error loading students', 'error');
  }
}

function openStudentModal(student = null) {
  document.getElementById('studentMatric').value = student ? student.matric_no : '';
  document.getElementById('studentMatric').readOnly = !!student;
  document.getElementById('studentName').value = student ? student.full_name : '';
  document.getElementById('studentDept').value = student ? student.department : '';
  document.getElementById('studentSession').value = student ? student.session : '2025/2026';
  document.getElementById('studentParent').value = student ? (student.parent_name || '') : '';
  document.getElementById('studentPhone').value = student ? (student.parent_phone || '') : '';
  document.getElementById('studentModalTitle').textContent = student ? 'Edit Student' : 'Add Student';
  document.getElementById('studentModal').classList.add('active');
}

function closeStudentModal() {
  document.getElementById('studentModal').classList.remove('active');
}

async function editStudent(matric) {
  try {
    const data = await API.get('api/students.php?matric_no=' + encodeURIComponent(matric));
    if (data.student) openStudentModal(data.student);
  } catch (e) {
    showToast('Error loading student', 'error');
  }
}

async function saveStudent(e) {
  e.preventDefault();
  const payload = {
    matric_no: document.getElementById('studentMatric').value.trim(),
    full_name: document.getElementById('studentName').value.trim(),
    department: document.getElementById('studentDept').value.trim(),
    session: document.getElementById('studentSession').value,
    parent_name: document.getElementById('studentParent').value.trim(),
    parent_phone: document.getElementById('studentPhone').value.trim(),
  };
  try {
    const res = await API.post('api/students.php', payload);
    showToast(res.message || 'Student saved!');
    closeStudentModal();
    loadStudents();
  } catch (err) {
    showToast(err.message || 'Error saving student', 'error');
  }
  return false;
}

async function deleteStudent(matric) {
  if (!confirm('Delete student ' + matric + '?')) return;
  try {
    const res = await API.post('api/students.php', { delete_matric: matric });
    showToast(res.message || 'Student deleted');
    loadStudents();
  } catch (e) {
    showToast('Error deleting student', 'error');
  }
}

// ===== Record Payment =====
async function loadPaymentForm() {
  try {
    const data = await API.get('api/students.php');
    const select = document.getElementById('payStudentId');
    select.innerHTML = '<option value="">-- Select a student --</option>' +
      (data.students || []).map(s =>
        '<option value="' + escHtml(s.matric_no) + '">' + escHtml(s.matric_no) + ' — ' + escHtml(s.full_name) + '</option>'
      ).join('');
  } catch (e) {
    showToast('Error loading students', 'error');
  }
}

async function onStudentSelected(sel) {
  const info = document.getElementById('selectedStudentInfo');
  if (!sel.value) { info.classList.add('hidden'); return; }
  const s = (await API.get('api/students.php?matric_no=' + encodeURIComponent(sel.value))).student;
  if (s) {
    document.getElementById('ssiName').textContent = s.full_name;
    document.getElementById('ssiDept').textContent = s.department + ' — ' + s.session;
    info.classList.remove('hidden');
  } else {
    info.classList.add('hidden');
  }
}

async function loadRecentPayments() {
  try {
    const data = await API.get('api/dashboard.php');
    const list = document.getElementById('recentPaymentsList');
    if (!data.recent_payments || data.recent_payments.length === 0) {
      list.innerHTML = '<p class="text-gray-500">No payments recorded.</p>';
      return;
    }
    list.innerHTML = data.recent_payments.map(p =>
      '<div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">' +
        '<div><div class="font-medium text-white text-sm">' + escHtml(p.student_name) + '</div><div class="text-xs text-gray-500">' + escHtml(p.receipt_no) + ' · ' + p.term + ' Term</div></div>' +
        '<div class="text-right"><div class="text-green-400 font-semibold">\u20A6' + Number(p.amount).toLocaleString() + '</div><div class="text-xs text-gray-500">' + p.payment_date + '</div></div>' +
      '</div>'
    ).join('');
  } catch (e) {}
}

async function recordPayment(e) {
  e.preventDefault();
  const studentMatric = document.getElementById('payStudentId').value;
  if (!studentMatric) { showToast('Select a student', 'error'); return false; }
  const payload = {
    student_matric: studentMatric,
    amount: document.getElementById('payAmount').value,
    session: document.getElementById('paySession').value,
    term: document.getElementById('payTerm').value,
    payment_date: document.getElementById('payDate').value || null,
  };
  try {
    const res = await API.post('api/payments.php', payload);
    showReceipt(res.payment);
    document.getElementById('paymentForm').reset();
    document.getElementById('selectedStudentInfo').classList.add('hidden');
    loadRecentPayments();
    loadDashboard();
  } catch (err) {
    showToast(err.message || 'Error recording payment', 'error');
  }
  return false;
}

function showReceipt(payment) {
  if (!payment) return;
  const qrData = JSON.stringify({
    type: 'fee_receipt',
    receipt_no: payment.receipt_no,
    student_name: payment.student_name,
    amount: payment.amount,
    term: payment.term,
    session: payment.session
  });
  document.getElementById('receiptContent').innerHTML = `
    <div class="bg-white text-black rounded-lg p-4 font-mono text-sm" id="receiptPrint">
      <div class="text-center mb-3">
        <div class="font-bold text-green-700">KWARA STATE POLYTECHNIC</div>
        <div class="text-xs text-gray-500">Technology, Innovation and Service</div>
      </div>
      <div class="border-t border-b border-dashed border-gray-300 py-2 mb-3 text-center font-bold text-green-700">
        OFFICIAL RECEIPT
      </div>
      <div class="space-y-1 text-xs">
        <div class="flex justify-between"><span class="text-gray-500">Receipt No:</span><span class="font-bold">${escHtml(payment.receipt_no)}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Date:</span><span>${payment.payment_date}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Matric No:</span><span>${escHtml(payment.matric_no)}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Student:</span><span class="font-semibold">${escHtml(payment.student_name)}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Session:</span><span>${escHtml(payment.session)}</span></div>
        <div class="flex justify-between"><span class="text-gray-500">Term:</span><span>${escHtml(payment.term)} Term</span></div>
      </div>
      <div class="border-t border-dashed border-gray-300 my-2 pt-2 text-center">
        <div class="text-xs text-gray-500">Amount Paid</div>
        <div class="text-2xl font-bold text-green-700">&#8358;${Number(payment.amount).toLocaleString()}</div>
      </div>
      <div class="text-center mt-3" id="receiptQRCode"></div>
      <div class="text-center text-xs text-gray-400 mt-2">System-generated receipt</div>
    </div>
  `;
  document.getElementById('receiptQRCode').innerHTML = '';
  new QRCode(document.getElementById('receiptQRCode'), {
    text: qrData,
    width: 120,
    height: 120,
    colorDark: '#000000',
    colorLight: '#ffffff',
    correctLevel: QRCode.CorrectLevel.H
  });
  document.getElementById('receiptModal').classList.add('active');
}

function closeReceiptModal() {
  document.getElementById('receiptModal').classList.remove('active');
}

function printReceipt() {
  const content = document.getElementById('receiptPrint').outerHTML;
  const win = window.open('', '', 'width=500,height=700');
  win.document.write('<html><head><title>Receipt - ' + document.querySelector('#receiptContent .font-bold.text-green-700')?.textContent + '</title>' +
    '<style>body{font-family:"Courier New",monospace;padding:20px}*{box-sizing:border-box}#receiptPrint{background:#fff}</style></head><body>' + content + '</body></html>');
  win.document.close();
  win.onload = () => { win.print(); };
}

function downloadReceipt() {
  const el = document.createElement('a');
  const receiptNo = document.querySelector('#receiptContent .font-bold')?.textContent || 'receipt';
  el.href = 'data:text/html;charset=utf-8,' + encodeURIComponent(document.getElementById('receiptPrint').outerHTML);
  el.download = receiptNo + '.html';
  el.click();
}

// ===== Verify =====
async function verifyByReceipt() {
  const receiptNo = document.getElementById('verifyReceiptNo').value.trim();
  if (!receiptNo) { showToast('Enter a receipt number', 'error'); return; }
  try {
    const data = await API.get('api/verify.php?receipt_no=' + encodeURIComponent(receiptNo));
    showVerifyResult(data);
  } catch (e) {
    showVerifyResult({ found: false, message: 'Receipt not found.' });
  }
}

async function verifyByMatric() {
  const matric = prompt('Enter Matric Number:');
  if (!matric) return;
  try {
    const data = await API.get('api/verify.php?matric_no=' + encodeURIComponent(matric.trim()));
    showVerifyResult(data);
  } catch (e) {
    showVerifyResult({ found: false, message: 'No payments found for this matric number.' });
  }
}

async function verifyByName() {
  const name = prompt('Enter Student Name:');
  if (!name) return;
  try {
    const data = await API.get('api/verify.php?name=' + encodeURIComponent(name.trim()));
    showVerifyResult(data);
  } catch (e) {
    showVerifyResult({ found: false, message: 'No payments found.' });
  }
}

function showVerifyResult(data) {
  const div = document.getElementById('verifyResult');
  if (data.found && data.payments) {
    const p = data.payments[0];
    div.innerHTML = `
      <div class="text-center mb-4">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500/20 mb-3" style="animation:popIn 0.4s ease">
          <i class="fas fa-check-circle text-green-400 text-3xl"></i>
        </div>
        <h4 class="text-green-400 font-bold text-lg">PAYMENT VERIFIED</h4>
      </div>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between bg-white/5 p-2 rounded"><span class="text-gray-400">Receipt No</span><span class="font-medium text-white">${escHtml(data.payments[0].receipt_no)}</span></div>
        <div class="flex justify-between bg-white/5 p-2 rounded"><span class="text-gray-400">Student</span><span class="text-white">${escHtml(data.payments[0].student_name)}</span></div>
        <div class="flex justify-between bg-white/5 p-2 rounded"><span class="text-gray-400">Matric No</span><span class="text-white">${escHtml(data.payments[0].matric_no)}</span></div>
        <div class="flex justify-between bg-white/5 p-2 rounded"><span class="text-gray-400">Amount</span><span class="font-semibold text-green-400">&#8358;${Number(data.payments[0].amount).toLocaleString()}</span></div>
        <div class="flex justify-between bg-white/5 p-2 rounded"><span class="text-gray-400">Term</span><span class="text-white">${escHtml(data.payments[0].term)}</span></div>
        <div class="flex justify-between bg-white/5 p-2 rounded"><span class="text-gray-400">Session</span><span class="text-white">${escHtml(data.payments[0].session)}</span></div>
        <div class="flex justify-between bg-white/5 p-2 rounded"><span class="text-gray-400">Date</span><span class="text-white">${data.payments[0].payment_date}</span></div>
      </div>
      ${data.count > 1 ? '<p class="text-xs text-gray-500 mt-3">Total ' + data.count + ' payments found for this student.</p>' : ''}
    `;
  } else {
    div.innerHTML = `
      <div class="text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-red-500/20 mb-3">
          <i class="fas fa-times-circle text-red-400 text-3xl"></i>
        </div>
        <h4 class="text-red-400 font-bold text-lg mb-2">NOT FOUND</h4>
        <p class="text-gray-400 text-sm">${escHtml(data.message || 'No matching payment records.')}</p>
      </div>
    `;
  }
}

// ===== Search =====
async function runSearch() {
  const params = new URLSearchParams({
    name: document.getElementById('searchName').value.trim(),
    matric_no: document.getElementById('searchMatric').value.trim(),
    date_from: document.getElementById('searchDateFrom').value,
    date_to: document.getElementById('searchDateTo').value,
  });
  try {
    const data = await API.get('api/search.php?' + params.toString());
    const tbody = document.getElementById('searchResults');
    const summary = document.getElementById('searchSummary');
    if (!data.results || data.results.length === 0) {
      tbody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-500 py-8">No matching payments found.</td></tr>';
      summary.textContent = '';
      return;
    }
    const total = data.results.reduce((s, p) => s + Number(p.amount), 0);
    tbody.innerHTML = data.results.map(p =>
      '<tr>' +
        '<td class="font-mono text-xs text-green-400">' + escHtml(p.receipt_no) + '</td>' +
        '<td>' + escHtml(p.student_name) + '</td>' +
        '<td class="font-mono text-xs">' + escHtml(p.matric_no) + '</td>' +
        '<td class="font-semibold text-green-400">&#8358;' + Number(p.amount).toLocaleString() + '</td>' +
        '<td>' + escHtml(p.term) + '</td>' +
        '<td>' + escHtml(p.session) + '</td>' +
        '<td>' + p.payment_date + '</td>' +
      '</tr>'
    ).join('');
    summary.textContent = 'Found ' + data.results.length + ' payment(s) — Total: \u20A6' + total.toLocaleString();
  } catch (e) {
    showToast('Search error', 'error');
  }
}

// ===== Init =====
(async function init() {
  document.getElementById('payDate').value = new Date().toISOString().split('T')[0];
  loadDashboard();
})();
</script>
</body>
</html>