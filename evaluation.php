<section id="evaluation-section">
  <h1 class="text-3xl font-semibold text-gray-800 mb-6">Evaluation & Monitoring</h1>

  <!-- Controls -->
  <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center mb-4 gap-4">
    <input type="text" id="evalSearch" placeholder="Search evaluations..." class="px-4 py-2 border rounded-lg w-full md:w-1/3 focus:outline-none focus:ring-2 focus:ring-red-500">
    <button id="addEvalBtn" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
      <i class="ri-add-line"></i> Add Evaluation
    </button>
  </div>

  <!-- Evaluation Table -->
  <div class="overflow-x-auto bg-white rounded-2xl shadow">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title / Form Name</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Program / Project</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Findings / Recommendations</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Progress Notes</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody id="evalTable" class="bg-white divide-y divide-gray-200"></tbody>
    </table>
  </div>
</section>

<!-- Modal for Add/Edit Evaluation -->
<div id="evalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-xl p-6 w-full max-w-lg overflow-y-auto max-h-[90vh]">
    <h2 class="text-xl font-semibold mb-4" id="evalModalTitle">Add Evaluation</h2>
    <form id="evalForm" class="space-y-3">
      <input type="text" id="evalTitle" placeholder="Evaluation Title / Form Name" class="w-full px-4 py-2 border rounded-lg">
      <input type="text" id="evalProgram" placeholder="Program / Project" class="w-full px-4 py-2 border rounded-lg">
      <select id="evalType" class="w-full px-4 py-2 border rounded-lg">
        <option value="">Select Type</option>
        <option value="Needs Assessment">Needs Assessment</option>
        <option value="Pre/Post Survey">Pre/Post Survey</option>
        <option value="Feedback">Feedback</option>
      </select>
      <textarea id="evalFindings" placeholder="Findings / Recommendations" class="w-full px-4 py-2 border rounded-lg"></textarea>
      <textarea id="evalProgress" placeholder="Progress Notes" class="w-full px-4 py-2 border rounded-lg"></textarea>
      <div class="flex justify-end gap-3 mt-4">
        <button type="button" id="cancelEvalBtn" class="px-4 py-2 rounded-lg border hover:bg-gray-100">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded-lg bg-red-500 text-white hover:bg-red-600">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
// ==================== Evaluation & Monitoring Logic ====================
let evaluations = [];
let editEvalIndex = null;

const evalTable = document.getElementById('evalTable');
const evalModal = document.getElementById('evalModal');
const evalForm = document.getElementById('evalForm');
const evalModalTitle = document.getElementById('evalModalTitle');
const evalSearch = document.getElementById('evalSearch');

function renderEvalTable(filter = '') {
  evalTable.innerHTML = '';
  evaluations
    .filter(e => e.title.toLowerCase().includes(filter.toLowerCase()))
    .forEach((e, index) => {
      const row = document.createElement('tr');
      row.classList.add('hover:bg-gray-50');
      row.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">${e.title}</td>
        <td class="px-6 py-4 whitespace-nowrap">${e.program}</td>
        <td class="px-6 py-4 whitespace-nowrap">${e.type}</td>
        <td class="px-6 py-4 whitespace-nowrap">${e.findings}</td>
        <td class="px-6 py-4 whitespace-nowrap">${e.progress}</td>
        <td class="px-6 py-4 whitespace-nowrap flex gap-2">
          <button class="text-blue-500 hover:text-blue-700" onclick="editEval(${index})"><i class="ri-edit-line"></i></button>
          <button class="text-red-500 hover:text-red-700" onclick="deleteEval(${index})"><i class="ri-delete-bin-line"></i></button>
        </td>
      `;
      evalTable.appendChild(row);
    });
}

function openEvalModal(edit = false) {
  evalModal.classList.remove('hidden');
  evalModalTitle.textContent = edit ? 'Edit Evaluation' : 'Add Evaluation';
}

function closeEvalModal() {
  evalModal.classList.add('hidden');
  evalForm.reset();
  editEvalIndex = null;
}

function editEval(index) {
  editEvalIndex = index;
  const e = evaluations[index];
  document.getElementById('evalTitle').value = e.title;
  document.getElementById('evalProgram').value = e.program;
  document.getElementById('evalType').value = e.type;
  document.getElementById('evalFindings').value = e.findings;
  document.getElementById('evalProgress').value = e.progress;
  openEvalModal(true);
}

function deleteEval(index) {
  if(confirm('Are you sure you want to delete this evaluation?')) {
    evaluations.splice(index, 1);
    renderEvalTable(evalSearch.value);
  }
}

document.getElementById('addEvalBtn').addEventListener('click', () => openEvalModal());
document.getElementById('cancelEvalBtn').addEventListener('click', () => closeEvalModal());

evalForm.addEventListener('submit', e => {
  e.preventDefault();
  const title = document.getElementById('evalTitle').value;
  const program = document.getElementById('evalProgram').value;
  const type = document.getElementById('evalType').value;
  const findings = document.getElementById('evalFindings').value;
  const progress = document.getElementById('evalProgress').value;

  if(editEvalIndex !== null) {
    evaluations[editEvalIndex] = {title, program, type, findings, progress};
  } else {
    evaluations.push({title, program, type, findings, progress});
  }

  renderEvalTable(evalSearch.value);
  closeEvalModal();
});

evalSearch.addEventListener('input', e => renderEvalTable(e.target.value));

// Initial dummy data
evaluations.push({title:'Needs Assessment 2025', program:'Program A', type:'Needs Assessment', findings:'Identify gaps in resources', progress:'50% completed'});
evaluations.push({title:'Feedback Survey Project X', program:'Project X', type:'Feedback', findings:'Positive feedback, need more volunteers', progress:'Completed'});
renderEvalTable();
</script>