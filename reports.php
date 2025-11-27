<section id="reports-section">
  <h1 class="text-3xl font-semibold text-gray-800 mb-6">Reports & Analytics</h1>

  <!-- Controls -->
  <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
    <input type="text" id="reportSearch" placeholder="Search reports..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
    <button id="addReportBtn" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
      <i class="ri-add-line"></i> Add Report
    </button>
  </div>

  <!-- Reports Table -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Report Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description / Metrics</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody id="reportTable" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>
</section>

<!-- Modal for Add/Edit Report -->
<div id="reportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-semibold mb-4" id="reportModalTitle">Add Report</h2>
    <form id="reportForm" class="space-y-3">
      <input type="text" id="reportName" placeholder="Report Name" class="w-full px-4 py-2 border rounded-lg">
      <select id="reportCategory" class="w-full px-4 py-2 border rounded-lg">
        <option value="">Select Category</option>
        <option value="Beneficiary Statistics">Beneficiary Statistics</option>
        <option value="Volunteer Statistics">Volunteer Statistics</option>
        <option value="Program/Project Performance">Program/Project Performance</option>
        <option value="Resource Utilization">Resource Utilization</option>
        <option value="Partner & Donor Contributions">Partner & Donor Contributions</option>
        <option value="Evaluation Results">Evaluation Results</option>
      </select>
      <textarea id="reportDesc" placeholder="Description / Metrics" class="w-full px-4 py-2 border rounded-lg"></textarea>
      <div class="flex justify-end gap-3 mt-4">
        <button type="button" id="cancelReportBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-500 text-white hover:bg-indigo-600">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
// ==================== Reports & Analytics Logic ====================
let reports = [];
let editReportIndex = null;

const reportTable = document.getElementById('reportTable');
const reportModal = document.getElementById('reportModal');
const reportForm = document.getElementById('reportForm');
const reportModalTitle = document.getElementById('reportModalTitle');
const reportSearch = document.getElementById('reportSearch');

function renderReportTable(filter = '') {
  reportTable.innerHTML = '';
  reports
    .filter(r => r.name.toLowerCase().includes(filter.toLowerCase()))
    .forEach((r, index) => {
      const row = document.createElement('tr');
      row.classList.add('hover:bg-gray-50');
      row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">${r.name}</td>
        <td class="px-6 py-4 whitespace-nowrap">${r.category}</td>
        <td class="px-6 py-4 whitespace-nowrap">${r.desc}</td>
        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
          <button class="text-blue-500 hover:text-blue-700" onclick="editReport(${index})"><i class="ri-edit-line"></i></button>
          <button class="text-red-500 hover:text-red-700" onclick="deleteReport(${index})"><i class="ri-delete-bin-line"></i></button>
        </td>
      `;
      reportTable.appendChild(row);
    });
}

function openReportModal(edit = false) {
  reportModal.classList.remove('hidden');
  reportModalTitle.textContent = edit ? 'Edit Report' : 'Add Report';
}

function closeReportModal() {
  reportModal.classList.add('hidden');
  reportForm.reset();
  editReportIndex = null;
}

function editReport(index) {
  editReportIndex = index;
  const r = reports[index];
  document.getElementById('reportName').value = r.name;
  document.getElementById('reportCategory').value = r.category;
  document.getElementById('reportDesc').value = r.desc;
  openReportModal(true);
}

function deleteReport(index) {
  if(confirm('Are you sure you want to delete this report?')) {
    reports.splice(index, 1);
    renderReportTable(reportSearch.value);
  }
}

document.getElementById('addReportBtn').addEventListener('click', () => openReportModal());
document.getElementById('cancelReportBtn').addEventListener('click', () => closeReportModal());

reportForm.addEventListener('submit', e => {
  e.preventDefault();
  const name = document.getElementById('reportName').value;
  const category = document.getElementById('reportCategory').value;
  const desc = document.getElementById('reportDesc').value;

  if(editReportIndex !== null) {
    reports[editReportIndex] = {name, category, desc};
  } else {
    reports.push({name, category, desc});
  }

  renderReportTable(reportSearch.value);
  closeReportModal();
});

reportSearch.addEventListener('input', e => renderReportTable(e.target.value));

// Initial dummy data
reports.push({name:'Beneficiary Demographics 2025', category:'Beneficiary Statistics', desc:'Age, gender, location'});
reports.push({name:'Volunteer Participation Rate', category:'Volunteer Statistics', desc:'Total hours and events attended'});
reports.push({name:'Program Performance Q1', category:'Program/Project Performance', desc:'Completion %, budget vs actual'});
renderReportTable();
</script>